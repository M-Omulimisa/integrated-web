<?php

namespace App\Models\Users;

use App\Models\Settings\Type;
use Spatie\Permission\Models\Role as BaseRole;
use App\Models\Traits\Relationships\Users\RoleRelationship;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class Role extends BaseRole
{
	use Uuid;
    
    public const ROLE_ADMIN     = "Administrator";
    public const ROLE_FIELD     = "Field Offcer";
    public const ROLE_SUPPORT   = "Support";

    public const ROLE_ORG_ADMIN = "Organisation Administrator";
    public const ROLE_ORG_USER  = "Organisation User";

    public const ROLE_MICROFIN_ADMIN    = "Microfinance Administrator";
    public const ROLE_MICROFIN_USER     = "Microfinance User";

    public const ROLE_EXTN    = "Extension Officer";
    public const ROLE_AGENT    = "Village Agent";
    public const ROLE_FARMER  = "Farmer";

    public const ROLE_PARTN   = "Partner";

    public const ROLE_INSTR  = "Instructor";
    public const ROLE_STDT   = "Student";

    public const ROLE_TRADER   = "Service Provider";
    
    public const ROLE_DISTR_ADMIN   = "Distributor Administrator";
    public const ROLE_BUYER_ADMIN   = "Buyer Administrator";

    /**
     * every time a model is created, we want to automatically assign a UUID to it
     *
     * @var array
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function (Role $model) {
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
}