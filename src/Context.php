<?php

/**
 * @package Guidance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Guidance;

use Brick\Math\BigInteger;
use DateTimeInterface;
use DecodeLabs\Guidance;
use DecodeLabs\Guidance\Factory\Ramsey as RamseyFactory;
use DecodeLabs\Veneer;
use DecodeLabs\Veneer\LazyLoad;

#[LazyLoad]
class Context implements Factory
{
    protected Factory $factory;


    /**
     * Set factory
     *
     * @return $this
     */
    public function setFactory(
        Factory $factory
    ): static {
        $this->factory = $factory;
        return $this;
    }

    /**
     * Get factory
     */
    public function getFactory(): Factory
    {
        if (!isset($this->factory)) {
            $this->factory = new RamseyFactory();
        }

        return $this->factory;
    }

    /**
     * Set default short format
     */
    public function setDefaultShortFormat(Format $format): static
    {
        $this->getFactory()->setDefaultShortFormat($format);
        return $this;
    }

    /**
     * Get default short format
     */
    public function getDefaultShortFormat(): Format
    {
        return $this->getFactory()->getDefaultShortFormat();
    }

    /**
     * Create a void UUID
     */
    public function createVoid(): Uuid
    {
        return $this->getFactory()->createVoid();
    }

    /**
     * Create a V1 UUID
     */
    public function createV1(
        int|string|null $node = null,
        ?int $clockSeq = null
    ): Uuid {
        return $this->getFactory()->createV1($node, $clockSeq);
    }

    /**
     * Create a V3 UUID
     */
    public function createV3(
        string $name,
        ?string $namespace = null
    ): Uuid {
        return $this->getFactory()->createV3($name, $namespace);
    }

    /**
     * Create a V4 UUID
     */
    public function createV4(): Uuid
    {
        return $this->getFactory()->createV4();
    }

    /**
     * Create a COMB UUID
     */
    public function createV4Comb(): Uuid
    {
        return $this->getFactory()->createV4Comb();
    }

    /**
     * Create a V5 UUID
     */
    public function createV5(
        string $name,
        ?string $namespace = null
    ): Uuid {
        return $this->getFactory()->createV5($name, $namespace);
    }

    /**
     * Create V6
     */
    public function createV6(
        int|string|null $node = null,
        ?int $clockSeq = null
    ): Uuid {
        return $this->getFactory()->createV6($node, $clockSeq);
    }

    /**
     * Create V7
     */
    public function createV7(
        ?DateTimeInterface $date = null
    ): Uuid {
        return $this->getFactory()->createV7($date);
    }


    /**
     * Check is valid UUID
     */
    public function isValid(
        string|BigInteger|Uuid|null $uuid
    ): bool {
        return $this->getFactory()->isValid($uuid);
    }

    /**
     * Create a UUID from a string, BigInteger or Uuid
     */
    public function from(
        string|BigInteger|Uuid $uuid
    ): Uuid {
        return $this->getFactory()->from($uuid);
    }

    /**
     * Create a UUID from a string, BigInteger or Uuid
     */
    public function tryFrom(
        string|BigInteger|Uuid|null $uuid
    ): ?Uuid {
        return $this->getFactory()->tryFrom($uuid);
    }

    /**
     * Create a UUID from a byte string
     */
    public function fromBytes(
        string $bytes
    ): Uuid {
        return $this->getFactory()->fromBytes($bytes);
    }

    /**
     * Create a UUID from a string
     */
    public function fromString(
        string $uuid
    ): Uuid {
        return $this->getFactory()->fromString($uuid);
    }

    /**
     * Create a UUID from a short string
     */
    public function fromShortString(
        string $uuid,
        ?Format $format = null
    ): Uuid {
        return $this->getFactory()->fromShortString($uuid, $format);
    }

    /**
     * Create a UUID from a BigInteger
     */
    public function fromBigInteger(
        BigInteger $uuid
    ): Uuid {
        return $this->getFactory()->fromBigInteger($uuid);
    }


    /**
     * Create short string from input
     */
    public function shorten(
        string|BigInteger|Uuid $uuid,
        ?Format $format = null
    ): string {
        return $this->getFactory()->shorten($uuid, $format);
    }
}

// Veneer
Veneer::register(
    Context::class,
    Guidance::class // @phpstan-ignore-line
);
