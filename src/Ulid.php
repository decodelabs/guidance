<?php

/**
 * @package Guidance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Guidance;

use DateTimeInterface;
use DecodeLabs\Glitch\Dumpable;
use DecodeLabs\Guidance\Ulid\Engine;;
use DecodeLabs\Guidance\Codec\Crockford32;

class Ulid implements
    Uid,
    Dumpable
{
    use UidTrait;

    public int $size { get => 16; }

    public string $urn {
        get => 'urn:ulid:' . $this->__toString();
    }

    protected(set) ?DateTimeInterface $dateTime {
        get {
            if(!isset($this->dateTime)) {
                $this->dateTime = Engine::extractDateTime(
                    $this->bytes
                );
            }

            return $this->dateTime;
        }
    }


    public function __toString(): string
    {
        return Crockford32::encode($this->bytes);
    }

    public function glitchDump(): iterable
    {
        yield 'text' => $this->__toString();

        yield 'meta' => [
            'bytes' => $this->bytes,
            'dateTime' => $this->dateTime,
        ];
    }
}
