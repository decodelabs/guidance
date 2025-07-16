<?php

/**
 * @package Guidance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Guidance;

use DecodeLabs\Exceptional;

/**
 * @phpstan-require-implements Uid
 */
trait UidTrait
{
    public protected(set) string $bytes;

    public ?int $timestamp {
        get {
            if (null === ($date = $this->dateTime)) {
                return null;
            }

            return $date->getTimestamp();
        }
    }

    public function __construct(
        string $bytes
    ) {
        if (strlen($bytes) != $this->size) {
            throw Exceptional::InvalidArgument(
                message: 'Uid must be a ' . ($this->size * 8) . ' bit integer'
            );
        }

        $this->bytes = $bytes;
    }

    public function isNil(): bool
    {
        return $this->bytes === str_repeat("\0", $this->size);
    }

    public function jsonSerialize(): string
    {
        return $this->__toString();
    }
}
