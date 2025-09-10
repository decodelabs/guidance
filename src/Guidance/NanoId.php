<?php

/**
 * @package Guidance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Guidance;

use DateTimeInterface;
use DecodeLabs\Exceptional;
use DecodeLabs\Nuance\Dumpable;
use DecodeLabs\Nuance\Entity\NativeObject as NuanceEntity;

class NanoId implements
    Uid,
    Dumpable
{
    use UidTrait;

    public int $size { get => strlen($this->bytes); }

    public string $urn {
        get => 'urn:nanoid:' . $this->__toString();
    }

    public Dictionary $dictionary = Dictionary::NanoId;

    public ?DateTimeInterface $dateTime { get => null; }

    public function __construct(
        string $bytes,
        Dictionary $dictionary = Dictionary::NanoId
    ) {
        $this->bytes = $bytes;
        $this->dictionary = $dictionary;
    }

    public function __toString(): string
    {
        $bytes = array_values((array)unpack('C*', $this->bytes));
        $map = str_split($this->dictionary->value);
        $output = '';

        /** @var int $byte */
        foreach ($bytes as $byte) {
            if (!isset($map[$byte])) {
                throw Exceptional::InvalidArgument(
                    message: 'Invalid byte value for NanoId',
                    data: [
                        'byte' => $byte,
                        'bytes' => $this->bytes,
                    ]
                );
            }

            $output .= $map[$byte];
        }

        return $output;
    }

    public function toNuanceEntity(): NuanceEntity
    {
        $entity = new NuanceEntity($this);
        $entity->text = $this->__toString();
        $entity->meta['bytes'] = $this->bytes;
        return $entity;
    }
}
