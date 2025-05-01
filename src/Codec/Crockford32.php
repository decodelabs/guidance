<?php

/**
 * @package Guidance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Guidance\Codec;

use DecodeLabs\Guidance\Codec;

class Crockford32 implements Codec
{
    const Alphabet = '0123456789abcdefghjkmnpqrstvwxyz';
    const MaxQuintet = 0b11111;

    public static function encode(
        string $bytes
    ): string {
        /** @var array<int,int> */
        $bytes = array_values((array)unpack('C*', $bytes));

        $quintets = [
            ($bytes[0] >> 5),
            ($bytes[0]),

            ($bytes[1] >> 3),
            ($bytes[1] << 2 | $bytes[2] >> 6),
            ($bytes[2] >> 1),
            ($bytes[2] << 4 | $bytes[3] >> 4),
            ($bytes[3] << 1 | $bytes[4] >> 7),
            ($bytes[4] >> 2),
            ($bytes[4] << 3 | $bytes[5] >> 5),
            ($bytes[5]),

            ($bytes[6] >> 3),
            ($bytes[6] << 2 | $bytes[7] >> 6),
            ($bytes[7] >> 1),
            ($bytes[7] << 4 | $bytes[8] >> 4),
            ($bytes[8] << 1 | $bytes[9] >> 7),
            ($bytes[9] >> 2),
            ($bytes[9] << 3 | $bytes[10] >> 5),
            ($bytes[10]),

            ($bytes[11] >> 3),
            ($bytes[11] << 2 | $bytes[12] >> 6),
            ($bytes[12] >> 1),
            ($bytes[12] << 4 | $bytes[13] >> 4),
            ($bytes[13] << 1 | $bytes[14] >> 7),
            ($bytes[14] >> 2),
            ($bytes[14] << 3 | $bytes[15] >> 5),
            ($bytes[15]),
        ];

        $output = '';

        for($idx = 0, $end = count($quintets); $idx < $end; $idx++) {
            $output .= self::Alphabet[$quintets[$idx] & self::MaxQuintet];
        }

        return $output;
    }

    public static function decode(
        string $id
    ): string {
        $map = array_flip(str_split(self::Alphabet));
        $map += [
            'i' => $map['1'],
            'l' => $map['1'],
            'o' => $map['0'],
            'u' => $map['v'],
        ];

        $id = strtolower($id);
        $quintets = $bytes = [];

        for($idx = 0, $end = strlen($id); $idx < $end; $idx++) {
            $quintets[] = $map[$id[$idx]];
        }

        $bytes[0] = ($quintets[0] << 5) | $quintets[1];

        $bytes[1] = ($quintets[2] << 3) | ($quintets[3] >> 2);
        $bytes[2] = ($quintets[3] << 6) | ($quintets[4] << 1) | ($quintets[5] >> 4);
        $bytes[3] = ($quintets[5] << 4) | ($quintets[6] >> 1);
        $bytes[4] = ($quintets[6] << 7) | ($quintets[7] << 2) | ($quintets[8] >> 3);
        $bytes[5] = ($quintets[8] << 5) | $quintets[9];

        $bytes[6] = ($quintets[10] << 3) | ($quintets[11] >> 2);
        $bytes[7] = ($quintets[11] << 6) | ($quintets[12] << 1) | ($quintets[13] >> 4);
        $bytes[8] = ($quintets[13] << 4) | ($quintets[14] >> 1);
        $bytes[9] = ($quintets[14] << 7) | ($quintets[15] << 2) | ($quintets[16] >> 3);
        $bytes[10] = ($quintets[16] << 5) | $quintets[17];

        $bytes[11] = ($quintets[18] << 3) | ($quintets[19] >> 2);
        $bytes[12] = ($quintets[19] << 6) | ($quintets[20] << 1) | ($quintets[21] >> 4);
        $bytes[13] = ($quintets[21] << 4) | ($quintets[22] >> 1);
        $bytes[14] = ($quintets[22] << 7) | ($quintets[23] << 2) | ($quintets[24] >> 3);
        $bytes[15] = ($quintets[24] << 5) | $quintets[25];

        return pack('C*', ...$bytes);
    }
}
