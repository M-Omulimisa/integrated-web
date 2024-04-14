<?php

namespace App\Models;

use App\Models\Market\MarketOutbox;
use App\Models\Market\MarketPackage;
use App\Models\Market\MarketPackageMessage;
use App\Models\Settings\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;

class MarketInfoMessageCampaign extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            try {
                self::prepareMessages($model);
            } catch (\Exception $e) {
                //dd($e);
            }
        });
        static::updated(function ($model) {
            try {
                self::prepareMessages($model);
            } catch (\Exception $e) {
                //dd($e);
            }
        });
    }

    //setter for packages as json
    public function setPackagesAttribute($value)
    {
        if ($value == null || $value == '') {
            $value = '';
        }
        $this->attributes['packages'] = json_encode($value);
    }
    //getter for packages as json
    public function getPackagesAttribute($value)
    {
        if ($value == null) {
            return [];
        }
        return json_decode($value);
    }

    //prepare messages
    public static function prepareMessages($campaign)
    {
        $lanuages = Language::all();
        foreach ($lanuages as $language) {
            foreach ($campaign->packages as $package_id) {
                $package = MarketPackage::find($package_id);
                if ($package == null) {
                    continue;
                }
                $message = MarketPackageMessage::where([
                    'market_info_message_campaign_id' => $campaign->id,
                    'package_id' => $package->id,
                    'language_id' => $language->id,
                ])->first();
                if ($message == null) {
                    $message = new MarketPackageMessage();
                    $message->market_info_message_campaign_id = $campaign->id;
                    $message->package_id = $package->id;
                    $message->language_id = $language->id;
                    $message->menu = 1;
                }
                $message_text = "";
                $error_message = "";
                $has_error = false;
                $isFirst = true;
                foreach ($package->ents as $ent) {
                    $word = TranslatedWord::translate(trim($ent->name), $language->slug);
                    $price = ItemPrice::latest_price($ent->id);
                    if (!$isFirst) {
                        $message_text .= ", ";
                    }
                    $message_text .=  $word . ": " . $price;
                    $isFirst = false;
                }
                $message_text .= ".";
                $message->message = $message_text;
                try {
                    $message->save();
                } catch (\Exception $e) {
                    $error_message = $e->getMessage();
                    $has_error = true;
                }
            }
        }
    }

    //has many messages 
    public function messages()
    {
        return $this->hasMany(MarketPackageMessage::class, 'market_info_message_campaign_id');
    }
    //has many outboxes
    public function outboxes()
    {
        return $this->hasMany(MarketOutbox::class, 'market_info_message_campaign_id');
    }
}
