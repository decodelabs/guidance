<?php

/**
 * @package Guidance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Guidance\Uuid;

use Brick\Math\BigInteger;
use DecodeLabs\Exceptional;

enum Format: string
{
    case Base62 = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    case GmpBase62 = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    case Base64 = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ+/';
    case FlickrBase58 = '123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
    case CookieBase90 = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!#$%&()*+-./:<=>?@[]^_`{|}~';

    /**
     * Convert to format
     */
    public function encode(
        string $bytes
    ): string {
        $bigInt = BigInteger::fromBase(bin2hex($bytes), 16);
        return $bigInt->toArbitraryBase($this->value);
    }

    /**
     * Convert from format
     */
    public function decode(
        string $uuid
    ): string {
        $bigInt = BigInteger::fromArbitraryBase($uuid, $this->value);
        $hex = str_pad($bigInt->toBase(16), 32, '0', \STR_PAD_LEFT);

        if (false === ($bin = hex2bin($hex))) {
            throw Exceptional::InvalidArgument(
                message: 'Unable to decode ' . $uuid . ' from ' . $this->value . ' format'
            );
        }

        return $bin;
    }

    /**
     * Is valid format?
     */
    public function isValid(
        string $uuid
    ): bool {
        $length = strlen($uuid);

        if ($length > 32) {
            return false;
        }

        return preg_match('/^[' . preg_quote($this->value, '/') . ']+$/', $uuid) ? true : false;
    }
}
