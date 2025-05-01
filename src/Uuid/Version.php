<?php

/**
 * @package Guidance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Guidance\Uuid;

enum Version: int
{
    case Unknown = 0x00;
    case V1 = 16;
    case V2 = 32;
    case V3 = 48;
    case V4 = 64;
    case V5 = 80;
    case V6 = 96;
    case V7 = 112;
    case V8 = 128;

    public static function fromBytes(
        string $bytes
    ): Version {
        $version = ord($bytes[6]) >> 4;

        if (
            $version < 1 ||
            $version > 8
        ) {
            return self::Unknown;
        }

        return self::fromValue($version);
    }

    public static function fromValue(
        int $value
    ): Version {
        return match ($value) {
            1 => self::V1,
            2 => self::V2,
            3 => self::V3,
            4 => self::V4,
            5 => self::V5,
            6 => self::V6,
            7 => self::V7,
            8 => self::V8,
            default => self::Unknown
        };
    }
}
