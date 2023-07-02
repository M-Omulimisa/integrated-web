<?php

namespace App\Models\Traits\Relationships\Users;

use App\Models\Auth\PasswordHistory;

use App\Models\Users\UserSession;
use App\Models\Users\Activity;

use App\Models\Setting\Setting;
use App\Models\Auth\OneTimePasswordLog;

use App\Models\Traits\Relationships\Users\CreatedByRelationship;
use App\Models\Organisations\Organisation;
use App\Models\Organisations\OrganisationUserPosition;

use App\Models\Loans\Microfinance;

/**
 * Class UserRelationship.
 */
trait UserRelationship
{
    use CreatedByRelationship;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function passwordHistories()
    {
        return $this->hasMany(PasswordHistory::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function settings()
    {
        return $this->morphMany(
            Setting::class,
            'settingable'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userSessions()
    {
        return $this->hasMany(UserSession::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * lastest resource
     */
    public function userSession()
    {
        return $this->hasOne(UserSession::class)->orderBy('created_at', 'DESC')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function oneTimePasswordLog()
    {
        return $this->hasMany(OneTimePasswordLog::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userActivity()
    {
        return $this->hasMany(Activity::class, 'causer_id');
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    public function position()
    {
        return $this->belongsTo(OrganisationUserPosition::class, 'id', 'user_id');
    }

    public function microfinance()
    {
        return $this->belongsTo(Microfinance::class, 'microfinance_id');
    }
}
