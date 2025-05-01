<?php

/**
 * @package Guidance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Guidance\Uuid\Engine;

use DateTimeInterface;
use DecodeLabs\Guidance\Uuid;
use DecodeLabs\Guidance\Uuid\Engine;
use Ramsey\Uuid\Codec\TimestampFirstCombCodec;
use Ramsey\Uuid\Generator\CombGenerator;
use Ramsey\Uuid\Rfc4122\UuidV1;
use Ramsey\Uuid\Rfc4122\UuidV2;
use Ramsey\Uuid\Rfc4122\UuidV6;
use Ramsey\Uuid\Rfc4122\UuidV7;
use Ramsey\Uuid\Type\Hexadecimal;
use Ramsey\Uuid\Uuid as UuidLib;
use Ramsey\Uuid\UuidFactory;

class Ramsey implements Engine
{
    protected ?UuidFactory $combFactory = null;

    public function createVoid(): Uuid
    {
        return new Uuid(
            str_repeat("\0", 16)
        );
    }

    public function createV1(
        int|string|null $node = null,
        ?int $clockSeq = null
    ): Uuid {
        return new Uuid(
            UuidLib::uuid1($node, $clockSeq)->getBytes()
        );
    }

    public function createV3(
        string $name,
        ?string $namespace = null
    ): Uuid {
        return new Uuid(
            UuidLib::uuid3($namespace ?? UuidLib::NAMESPACE_DNS, $name)->getBytes()
        );
    }

    public function createV4(): Uuid
    {
        return new Uuid(
            UuidLib::uuid4()->getBytes()
        );
    }

    public function createV4Comb(): Uuid
    {
        if (!$this->combFactory) {
            $this->combFactory = new UuidFactory();
            $this->combFactory->setCodec(new TimestampFirstCombCodec($this->combFactory->getUuidBuilder()));

            $this->combFactory->setRandomGenerator(new CombGenerator(
                $this->combFactory->getRandomGenerator(),
                $this->combFactory->getNumberConverter()
            ));
        }

        return new Uuid($this->combFactory->uuid4()->getBytes());
    }

    public function createV5(
        string $name,
        ?string $namespace = null
    ): Uuid {
        return new Uuid(
            UuidLib::uuid5($namespace ?? UuidLib::NAMESPACE_DNS, $name)->getBytes()
        );
    }

    public function createV6(
        int|string|null $node = null,
        ?int $clockSeq = null
    ): Uuid {
        if (is_int($node)) {
            $node = dechex($node);
        }

        if (is_string($node)) {
            $node = new Hexadecimal($node);
        }

        return new Uuid(
            UuidLib::uuid6($node, $clockSeq)->getBytes()
        );
    }

    public function createV7(
        ?DateTimeInterface $date = null
    ): Uuid {
        return new Uuid(
            UuidLib::uuid7($date)->getBytes()
        );
    }

    public function getDateTimeFromBytes(
        string $bytes
    ): ?DateTimeInterface {
        $uuid = UuidLib::getFactory()->fromBytes($bytes);

        if (
            !$uuid instanceof UuidV1 &&
            !$uuid instanceof UuidV2 &&
            !$uuid instanceof UuidV6 &&
            !$uuid instanceof UuidV7
        ) {
            return null;
        }

        return $uuid->getDateTime();
    }
}
