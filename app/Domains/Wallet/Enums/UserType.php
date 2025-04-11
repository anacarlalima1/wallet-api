<?php

namespace Domains\Wallet\Enums;

final class UserType
{
    public const COMMON = 'common';
    public const MERCHANT = 'merchant';

    public static function values(): array
    {
        return [
            self::COMMON,
            self::MERCHANT,
        ];
    }
}
