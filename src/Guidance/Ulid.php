<?php

/**
 * @package Guidance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Guidance;

use DateTimeInterface;
use DecodeLabs\Guidance\Codec\Crockford32;
use DecodeLabs\Guidance\Ulid\Engine;
use DecodeLabs\Nuance\Dumpable;
use DecodeLabs\Nuance\Entity\NativeObject as NuanceEntity;

class Ulid implements
    Uid,
    Dumpable
{
    use UidTrait;

    public int $size { get => 16; }

    public string $urn {
        get => 'urn:ulid:' . $this->__toString();
    }

    public protected(set) ?DateTimeInterface $dateTime {
        get {
            if (!isset($this->dateTime)) {
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


    public function toNuanceEntity(): NuanceEntity
    {
        $entity = new NuanceEntity($this);
        $entity->text = $this->__toString();

        $entity->meta = [
            'bytes' => $this->bytes,
            'dateTime' => $this->dateTime,
        ];

        return $entity;
    }
}
