<?php

/**
 * @package Guidance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Guidance\NanoId;

use DecodeLabs\Exceptional;
use DecodeLabs\Guidance\Dictionary;
use DecodeLabs\Guidance\NanoId;

class Engine
{
    public function createVoid(
        int $size = 21,
        Dictionary $dictionary = Dictionary::NanoId
    ): NanoId {
        return new NanoId(
            str_repeat("\0", $size),
            $dictionary
        );
    }

    public function create(
        int $size = 21,
        Dictionary $dictionary = Dictionary::NanoId
    ): NanoId {
        $length = strlen($dictionary->value);
        $mask = (2 << (int) (log($length - 1) / M_LN2)) - 1;
        /** @var int<1,max> $step */
        $step = (int) ceil(1.6 * $mask * $size / $length);
        $output = [];
        $count = 0;

        while (true) {
            $bytes = array_values((array)unpack('C*', random_bytes($step)));

            /** @var int $byte */
            foreach ($bytes as $i => $byte) {
                $output[] = $byte & $mask;
                $count++;

                if ($count >= $size) {
                    break 2;
                }
            }
        }

        return new NanoId(
            pack('C*', ...$output),
            $dictionary
        );
    }

    public function fromString(
        string $nanoId,
        Dictionary $dictionary = Dictionary::NanoId
    ): NanoId {
        $nanoId = (string)preg_replace('/^urn:nanoid:/is', '', $nanoId);

        if (!preg_match('/^[' . preg_quote($dictionary->value, '/') . ']+$/', $nanoId)) {
            throw Exceptional::InvalidArgument(
                message: 'Invalid NanoId string',
                data: $nanoId
            );
        }

        $map = array_flip(str_split($dictionary->value));
        $bytes = str_split($nanoId);

        foreach ($bytes as $idx => $byte) {
            if (!isset($map[$byte])) {
                throw Exceptional::InvalidArgument(
                    message: 'Invalid NanoId string',
                    data: $nanoId
                );
            }

            $bytes[$idx] = $map[$byte];
        }

        return new NanoId(
            pack('C*', ...$bytes)
        );
    }
}
