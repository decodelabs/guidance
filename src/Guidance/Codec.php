<?php

/**
 * @package Guidance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Guidance;

interface Codec
{
    public static function encode(
        string $bytes
    ): string;

    public static function decode(
        string $id
    ): string;
}
