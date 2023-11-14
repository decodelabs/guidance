<?php

/**
 * @package Guidance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Guidance;

enum Variant: int
{
    case Reserved = 224;
    case Ms = 192;
    case Rfc = 128;
    case Ncs = 0;

    /**
     * Create from bytes
     */
    public static function fromBytes(
        string $bytes
    ): Variant {
        $byte = ord($bytes[8]);

        if ($byte >= Variant::Reserved->value) {
            return Variant::Reserved;
        } elseif ($byte >= Variant::Ms->value) {
            return Variant::Ms;
        } elseif ($byte >= Variant::Rfc->value) {
            return Variant::Rfc;
        } else {
            return Variant::Ncs;
        }
    }
}
