<?php

namespace App\Facades\Support;

use Illuminate\Support\Facades\Facade;

/**
 * @method static DateTime getResolutionByPriority()
 *
 * @see \App\Services\Support\TicketCommentService
 */
class TicketCommentServiceFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'ticket-comment-service';
    }
}
