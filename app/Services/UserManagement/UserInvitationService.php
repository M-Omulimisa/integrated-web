<?php


namespace App\Services\UserManagement;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use DB;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendNewUserInvitationNotification;

class UserInvitationService
{
    public function __construct() { }


}
