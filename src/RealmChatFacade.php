<?php

namespace Laraditz\RealmChat;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Laraditz\RealmChat\Skeleton\SkeletonClass
 */
class RealmChatFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'realm-chat';
    }
}
