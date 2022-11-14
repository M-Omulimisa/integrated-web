<?php

namespace App\Models\Auth;

use Carbon\Carbon;
use App\Models\User;
use App\Models\BaseModel;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Eloquent\Model;
use App\Services\OtpServices\ServiceFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class OneTimePassword extends BaseModel
{
    use Uuid;

    protected $connection = 'mysql';

    protected $fillable = ["user_id", "status"];

    /**
     * every time a model is created, we want to automatically assign a UUID to it
     *
     * @var array
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function (OneTimePassword $model) {
            $model->id = $model->generateUuid();
        });
    }

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    public function oneTimePasswordLogs()
    {
        return $this->hasMany(OneTimePasswordLog::class, "user_id", "user_id");
    }

    public function oneTimePasswordActivities()
    {
        return $this->hasMany(OneTimePasswordActivity::class, "user_id", "user_id");
    }

    public function user()
    {
        return $this->hasOne(User::class, "id", "user_id");
    }

    public function send()
    {
        $ref = $this->ReferenceNumber();

        $otp = $this->createOTP($ref);

        if (!empty($otp)) {
            if (config("otp.otp_service_enabled", false)) {
                return $this->sendOTPWithService($this->user, $otp, $ref);
            }
            return true;
        }

        return null;
    }

    private function sendOTPWithService($user, $otp, $ref)
    {
        $OTPFactory = new ServiceFactory();

        $service = $OTPFactory->getService(config("otp.otp_default_service", null));

        if ($service) {
            // $this->updateOtpActivityTimestamp('initiated_at', $ref);
            return $service->sendOneTimePassword($user, $otp, $ref);
        }

        return false;
    }

    public function createOTP($ref)
    {
        $this->discardOldPasswords();
        $otp = $this->OTPGenerator();

        $otp_code = $otp;

        if (config("otp.encode_password", false)) {
            $otp_code = Hash::make($otp);
        }

        $this->update(["status" => "waiting"]);

        $this->oneTimePasswordLogs()->create([
            'user_id'       => $this->user->id,
            'otp_code'      => $otp_code,
            'refer_number'  => $ref,
            'status'        => 'waiting',
        ]);

        return $otp;
    }

    private function ReferenceNumber()
    {
        $number = strval(rand(100000000, 999999999));
        return substr($number, 0, config("otp.otp_reference_number_length", 4));
    }

    private function OTPGenerator()
    {
        $number = 123456; // strval(rand(100000000, 999999999));
        return substr($number, 0, config("otp.otp_digit_length", 4));
    }

    public function discardOldPasswords($onLogin=null)
    {
        $discarded = $this->update(["status" => "discarded"]);

        // update otp activity if discarded
        if ($discarded && is_null($onLogin)) $this->updateOtpActivityTimestamp('discarded_at');

        return $this->oneTimePasswordLogs()->whereIn("status", ["waiting", "verified"])->update(["status" => "discarded"]);
    }

    public function checkPassword($oneTimePassword)
    {
        $oneTimePasswordLog = $this->oneTimePasswordLogs()
            ->where("status", "waiting")->first();

        if (!empty($oneTimePasswordLog)) {

            if (config("otp.encode_password", false)) {
                return Hash::check($oneTimePassword, $oneTimePasswordLog->otp_code);
            } else {
                return $oneTimePasswordLog->otp_code == $oneTimePassword;
            }
        }

        return false;
    }

    public function acceptEntrance()
    {
        $this->update(["status" => "verified"]);
        $this->updateOtpActivityTimestamp('verified_at');

        $latestDiscardId = null;
        $latestDiscard = OneTimePassword::where(["status" => "discarded", "user_id" => $this->user->id])->get();
        if (count($latestDiscard) > 0) {
            $latestDiscardId = [];
            foreach ($latestDiscard as $discard) {
                $latestDiscardId[] = $discard->id;
            }
        }
        $deleteLogs = $this->oneTimePasswordLogs()->where("status", "discarded")->delete();
        $deleteOtp = OneTimePassword::where(["status" => "discarded", "user_id" => $this->user->id])->delete();
         // update otp activity if discarded
        if($deleteLogs && $deleteOtp) $this->updateOtpActivityTimestamp('deleted_at', $latestDiscardId);

        return $this->oneTimePasswordLogs()->where("user_id", $this->user->id)->where("status", "waiting")->update(["status" => "verified"]);
    }

    public function isExpired()
    {
        return $this->created_at < Carbon::now()->subSeconds(config("otp.otp_timeout"));
    }

    public function createOtpActivity($type)
    {
        $this->oneTimePasswordActivities()->create([
            'user_id'           => $this->user->id,
            'otp_id'            => $this->id,
            'phone'             => $this->user->phone,
            'type'              => $type
        ]);
    }

    public function updateOtpActivityTimestamp($timestamp, $latestDiscardId=null)
    {
        $update = true;
        if (is_null($latestDiscardId)) {

            if ($timestamp == 'discarded_at' && $this->status == 'waiting') $update = false;

            if ($update) {
                $latest = OneTimePasswordActivity::where('otp_id', $this->id)->orderBy('id', 'DESC')->first();
                $this->oneTimePasswordActivities()->where('id', $latest->id)->update([$timestamp => Carbon::now()]);
            }            
        } else {
            // if there are more than 1 to delete
            for ($i=0; $i < count($latestDiscardId) ; $i++) { 
                $latestActivity = OneTimePasswordActivity::where('otp_id', $latestDiscardId[$i])->get();
                foreach ($latestActivity as $latest) {
                    $this->oneTimePasswordActivities()->where('id', $latest->id)->update([$timestamp => Carbon::now()]);
                }
            }
        }
    }
}
