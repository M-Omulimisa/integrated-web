<?php

namespace App\Facades\UserManagement;

use Illuminate\Support\Facades\Facade;

/**
 * @method static 
 *
 * @see \App\Services\Support\DisputeCommentService
 */
class UserInvitationServiceFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'user-invitation-service';
    }
}
