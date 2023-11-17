<?php

/**
 * @package Guidance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Guidance;

use Brick\Math\BigInteger;
use DateTimeInterface;
use DecodeLabs\Exceptional;

trait FactoryTrait
{
    protected Format $defaultShortFormat = Format::Base62;

    /**
     * Set default short format
     *
     * @return static
     */
    public function setDefaultShortFormat(
        Format $format
    ): static {
        $this->defaultShortFormat = $format;
        return $this;
    }

    /**
     * Get default short format
     */
    public function getDefaultShortFormat(): Format
    {
        return $this->defaultShortFormat;
    }


    /**
     * Create void ID
     */
    public function createVoid(): Uuid
    {
        return $this->fromBytes(str_repeat("\0", 16));
    }

    /**
     * Create void ID string
     */
    public function createVoidString(): string
    {
        return (string)$this->createVoid();
    }

    /**
     * Create V1 string
     */
    public function createV1String(
        int|string|null $node = null,
        ?int $clockSeq = null
    ): string {
        return (string)$this->createV1($node, $clockSeq);
    }

    /**
     * Create V3 string
     */
    public function createV3String(
        string $name,
        ?string $namespace = null
    ): string {
        return (string)$this->createV3($name, $namespace);
    }

    /**
     * Create V4 string
     */
    public function createV4String(): string
    {
        return (string)$this->createV4();
    }

    /**
     * Create V4 comb string
     */
    public function createV4CombString(): string
    {
        return (string)$this->createV4Comb();
    }

    /**
     * Create V5 string
     */
    public function createV5String(
        string $name,
        ?string $namespace = null
    ): string {
        return (string)$this->createV5($name, $namespace);
    }

    /**
     * Create V6 string
     */
    public function createV6String(
        int|string|null $node = null,
        ?int $clockSeq = null
    ): string {
        return (string)$this->createV6($node, $clockSeq);
    }

    /**
     * Create V7 string
     */
    public function createV7String(
        ?DateTimeInterface $date = null
    ): string {
        return (string)$this->createV7($date);
    }


    /**
     * Check if a string is a valid UUID
     */
    public function isValid(
        string|BigInteger|Uuid|null $uuid
    ): bool {
        if ($uuid === null) {
            return false;
        }

        if (
            $uuid instanceof Uuid ||
            $uuid instanceof BigInteger
        ) {
            return true;
        }

        if (preg_match('/^[a-f0-9]{32}|[a-f0-9\-]{36}$/i', $uuid)) {
            return true;
        }

        if ($this->defaultShortFormat->isValid($uuid)) {
            return true;
        }

        return false;
    }


    /**
     * Create from string, big integer or Uuid
     */
    public function from(
        string|BigInteger|Uuid $uuid
    ): Uuid {
        if (!$uuid = $this->tryFrom($uuid)) {
            throw Exceptional::InvalidArgument([
                'message' => 'Invalid UUID',
                'data' => $uuid
            ]);
        }

        return $uuid;
    }

    /**
     * Try to create from string, big integer or Uuid
     */
    public function tryFrom(
        string|BigInteger|Uuid|null $uuid
    ): ?Uuid {
        if (
            $uuid === null ||
            $uuid instanceof Uuid
        ) {
            return $uuid;
        }

        if ($uuid instanceof BigInteger) {
            return new Uuid($uuid->toBytes());
        }

        try {
            return $this->fromString($uuid);
        } catch (InvalidArgumentException $e) {
        }

        try {
            return $this->fromShortString($uuid);
        } catch (InvalidArgumentException $e) {
        }

        return null;
    }

    /**
     * Create from bytes
     */
    public function fromBytes(
        string $bytes
    ): Uuid {
        return new Uuid($bytes);
    }

    /**
     * Create from string
     */
    public function fromString(
        string $uuid
    ): Uuid {
        return $this->fromBytes($this->stringToBytes($uuid));
    }

    /**
     * Create from short string
     */
    public function fromShortString(
        string $uuid,
        ?Format $format = null
    ): Uuid {
        if (preg_match('/^[a-f0-9]{32}|[a-f0-9\-]{36}$/i', $uuid)) {
            return $this->fromString($uuid);
        }

        $format ??= $this->defaultShortFormat;
        return $this->fromBytes($format->decode($uuid));
    }

    /**
     * Create from big integer
     */
    public function fromBigInteger(
        BigInteger $uuid
    ): Uuid {
        return $this->fromBytes($uuid->toBytes());
    }

    /**
     * Convert string to bytes
     */
    protected function stringToBytes(
        string $uuid,
        int $length = 16
    ): string {
        $uuid = (string)preg_replace('/^urn:uuid:/is', '', $uuid);
        $uuid = (string)preg_replace('/[^a-f0-9]/is', '', $uuid);

        if (strlen($uuid) != ($length * 2)) {
            throw Exceptional::InvalidArgument(
                'Invalid UUID string: ' . $uuid
            );
        }

        return pack('H*', $uuid);
    }


    /**
     * Shorten UUID
     */
    public function shorten(
        string|BigInteger|Uuid $uuid,
        ?Format $format = null
    ): string {
        $uuid = $this->from($uuid);
        $format ??= $this->defaultShortFormat;

        return $format->encode($uuid->getBytes());
    }
}
