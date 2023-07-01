<?php

namespace App\Models\Organisations;

use App\Models\AdminRoleUser;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\OrganisationRelationship;
use App\Models\User;
use Encore\Admin\Auth\Database\Administrator;
use Exception;
use GuzzleHttp\Promise\Create;

class Organisation extends BaseModel
{
    use Uuid, OrganisationRelationship;

    protected $fillable = [
        'name', 'address', 'services'
    ];

    /**
     * every time a model is created
     * automatically assign a UUID to it
     *
     * @var array
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function (Organisation $model) {
            $model->id = $model->generateUuid();
        });
        self::deleting(function (Organisation $model) {
            throw new Exception("You cannot delete this item.", 1);
        });
        self::created(function ($m) {
            $m->my_update();
        });
        self::updated(function ($m) {
            $m->my_update();
        });
    }

    public function my_update()
    {
        $u = Administrator::find($this->user_id);
        if ($u != null) {
            $u->organisation_id = $this->id;
            AdminRoleUser::create([
                'user_id' => $this->user_id,
                'role_id' => 2,
            ]);
            $u->save();
        }
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
}
