<?php

namespace App\Constants;

class OrderStatus
{
    const PENDING = 'Pending';
    const COMPLETED = 'Completed';
    const IN_PROGRESS = 'In Progress';

    public static function getAllowedStatuses()
    {
        return [
            self::PENDING,
            self::IN_PROGRESS,
            self::COMPLETED,
        ];
    }
}