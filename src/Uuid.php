<?php

/**
 * @package Guidance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Guidance;

use DateTimeInterface;
use DecodeLabs\Exceptional;
use DecodeLabs\Guidance;
use DecodeLabs\Guidance\Uuid\Format;
use DecodeLabs\Guidance\Uuid\Variant;
use DecodeLabs\Guidance\Uuid\Version;
use DecodeLabs\Nuance\Dumpable;
use DecodeLabs\Nuance\Entity\NativeObject as NuanceEntity;

class Uuid implements
    Uid,
    Dumpable
{
    use UidTrait;

    public int $size { get => 16; }

    public string $urn {
        get => 'urn:uuid:' . $this->__toString();
    }

    public Version $version {
        get => Version::fromBytes($this->bytes);
    }

    public Variant $variant {
        get => Variant::fromBytes($this->bytes);
    }

    protected(set) ?DateTimeInterface $dateTime {
        get {
            if(!isset($this->dateTime)) {
                $this->dateTime = Guidance::getUuidDateTime($this);
            }

            return $this->dateTime;
        }
    }

    public function shorten(
        ?Format $format = null
    ): string {
        $format ??= Guidance::getDefaultShortUuidFormat();
        return $format->encode($this->bytes);
    }

    public function __toString(): string
    {
        return
            bin2hex(substr($this->bytes, 0, 4)) . '-' .
            bin2hex(substr($this->bytes, 4, 2)) . '-' .
            bin2hex(substr($this->bytes, 6, 2)) . '-' .
            bin2hex(substr($this->bytes, 8, 2)) . '-' .
            bin2hex(substr($this->bytes, 10, 6));
    }

    public function toNuanceEntity(): NuanceEntity
    {
        $entity = new NuanceEntity($this);
        $entity->text = $this->__toString();

        $entity->meta = [
            'bytes' => bin2hex($this->bytes),
            'version' => $this->version->value,
            'variant' => $this->variant->value,
            'dateTime' => $this->dateTime,
        ];

        return $entity;
    }
}
