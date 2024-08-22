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
use Stringable;

/**
 * @phpstan-require-implements Factory
 */
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
        string|Stringable|BigInteger|Uuid|null $uuid,
        bool $includeShort = false
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

        if ($uuid instanceof Stringable) {
            $uuid = (string)$uuid;
        }

        // Full string
        if (preg_match('/^([0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12})$/i', $uuid)) {
            return true;
        }

        if (!$includeShort) {
            return false;
        }

        // No dashes
        if (preg_match('/^[a-f0-9]{32}$/i', $uuid)) {
            return true;
        }

        // Short format
        if ($this->defaultShortFormat->isValid($uuid)) {
            return true;
        }

        return false;
    }


    /**
     * Create from string, big integer or Uuid
     */
    public function from(
        string|Stringable|BigInteger|Uuid $uuid
    ): Uuid {
        if (!$uuid = $this->tryFrom($uuid)) {
            throw Exceptional::InvalidArgument(
                message: 'Invalid UUID',
                data: $uuid
            );
        }

        return $uuid;
    }

    /**
     * Try to create from string, big integer or Uuid
     */
    public function tryFrom(
        string|Stringable|BigInteger|Uuid|null $uuid
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
        string|Stringable $uuid
    ): Uuid {
        return $this->fromBytes($this->stringToBytes($uuid));
    }

    /**
     * Create from short string
     */
    public function fromShortString(
        string|Stringable $uuid,
        ?Format $format = null
    ): Uuid {
        $uuid = (string)$uuid;

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
        string|Stringable $uuid
    ): string {
        $uuid = (string)$uuid;

        if (strlen($uuid) === 16) {
            return $uuid;
        }

        $uuid = (string)preg_replace('/^urn:uuid:/is', '', $uuid);

        if (preg_match('/[^a-f0-9\-]/is', $uuid)) {
            throw Exceptional::InvalidArgument(
                'Invalid UUID string: ' . $uuid
            );
        }

        $uuid = str_replace('-', '', $uuid);

        if (strlen($uuid) != 32) {
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
        string|Stringable|BigInteger|Uuid $uuid,
        ?Format $format = null
    ): string {
        $uuid = $this->from($uuid);
        $format ??= $this->defaultShortFormat;

        return $format->encode($uuid->getBytes());
    }

    /**
     * Get timestamp from UUID if possible
     */
    public function getDateTime(
        string|Stringable|BigInteger|Uuid $uuid
    ): ?DateTimeInterface {
        if (!$uuid = $this->tryFrom($uuid)) {
            return null;
        }

        return $this->getDateTimeFromBytes($uuid->getBytes());
    }

    /**
     * Extract timestamp from bytes if possible
     */
    protected function getDateTimeFromBytes(
        string $bytes
    ): ?DateTimeInterface {
        return null;
    }
}
