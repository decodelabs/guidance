<?php

/**
 * @package Guidance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Guidance\Factory;

use DateTimeInterface;
use DecodeLabs\Guidance\Factory;
use DecodeLabs\Guidance\FactoryTrait;
use DecodeLabs\Guidance\Uuid;
use Ramsey\Uuid\Codec\TimestampFirstCombCodec;
use Ramsey\Uuid\Generator\CombGenerator;
use Ramsey\Uuid\Rfc4122\UuidV1;
use Ramsey\Uuid\Rfc4122\UuidV2;
use Ramsey\Uuid\Rfc4122\UuidV6;
use Ramsey\Uuid\Rfc4122\UuidV7;
use Ramsey\Uuid\Type\Hexadecimal;
use Ramsey\Uuid\Uuid as UuidLib;
use Ramsey\Uuid\UuidFactory;

class Ramsey implements Factory
{
    use FactoryTrait;

    protected ?UuidFactory $combFactory = null;

    /**
     * Create V1
     */
    public function createV1(
        int|string|null $node = null,
        ?int $clockSeq = null
    ): Uuid {
        return new Uuid(
            UuidLib::uuid1($node, $clockSeq)->getBytes()
        );
    }

    /**
     * Create V3
     */
    public function createV3(
        string $name,
        ?string $namespace = null
    ): Uuid {
        return new Uuid(
            UuidLib::uuid3($namespace ?? UuidLib::NAMESPACE_DNS, $name)->getBytes()
        );
    }

    /**
     * Create V4
     */
    public function createV4(): Uuid
    {
        return new Uuid(
            UuidLib::uuid4()->getBytes()
        );
    }

    /**
     * Create comb
     */
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

    /**
     * Create V5
     */
    public function createV5(
        string $name,
        ?string $namespace = null
    ): Uuid {
        return new Uuid(
            UuidLib::uuid5($namespace ?? UuidLib::NAMESPACE_DNS, $name)->getBytes()
        );
    }


    /**
     * Create V6
     */
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

    /**
     * Create V7
     */
    public function createV7(
        ?DateTimeInterface $date = null
    ): Uuid {
        return new Uuid(
            UuidLib::uuid7($date)->getBytes()
        );
    }


    /**
     * Extract timestamp from bytes if possible
     */
    protected function getDateTimeFromBytes(
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
