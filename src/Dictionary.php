<?php

/**
 * @package Guidance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Guidance;

enum Dictionary: string
{
    case Upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    case Lower = 'abcdefghijklmnopqrstuvwxyz';
    case Digits = '0123456789';
    case AlphaNumeric = self::Upper->value . self::Lower->value . self::Digits->value;

    case HexUpper = self::Digits->value . 'ABCDEF';
    case HexLower = self::Digits->value . 'abcdef';

    case Crockford32Upper = self::Digits->value . 'ABCDEFGHJKMNPQRSTVWXYZ';
    case Crockford32Lower = self::Digits->value . 'abcdefghjkmnpqrstvwxyz';

    // Brotli optimized, from original NanoId implementation
    case NanoId = 'useandom-26T198340PX75pxJACKVERYMINDBUSHWOLF_GQZbfghjklqvwyzrict';

    case Cookie = self::AlphaNumeric->value . '!#$%&\'*+-.^_`|~';
}
