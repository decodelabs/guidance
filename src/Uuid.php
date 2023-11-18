<?php

/**
 * @package Guidance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Guidance;

use DateTime;
use DateTimeInterface;
use DecodeLabs\Exceptional;
use DecodeLabs\Glitch\Dumpable;
use DecodeLabs\Guidance;
use Stringable;

class Uuid implements
    Stringable,
    Dumpable
{
    public const CLEAR_VERSION = 15;
    public const CLEAR_VARIANT = 63;

    protected string $bytes;
    protected DateTimeInterface|false|null $dateTime = false;

    /**
     * Init with byte string
     */
    public function __construct(
        string $bytes
    ) {
        if (strlen($bytes) != 16) {
            throw Exceptional::InvalidArgument(
                'Guid must be a 128 bit integer'
            );
        }

        $this->bytes = $bytes;
    }

    /**
     * Get bytes
     */
    public function getBytes(): string
    {
        return $this->bytes;
    }

    /**
     * Get hex
     */
    public function getHex(): string
    {
        return bin2hex($this->bytes);
    }

    /**
     * Get URN
     */
    public function getUrn(): string
    {
        return 'urn:uuid:' . $this->__toString();
    }

    /**
     * Get version
     */
    public function getVersion(): Version
    {
        return Version::fromBytes($this->bytes);
    }

    /**
     * Get variant
     */
    public function getVariant(): Variant
    {
        return Variant::fromBytes($this->bytes);
    }

    /**
     * Is nil variant
     */
    public function isNil(): bool
    {
        return $this->bytes === str_repeat("\0", 16);
    }

    /**
     * Shorten to format
     */
    public function shorten(
        ?Format $format = null
    ): string {
        $format ??= Guidance::getDefaultShortFormat();
        return $format->encode($this->bytes);
    }

    /**
     * Get timestamp
     */
    public function getTimestamp(): ?int
    {
        if (null === ($date = $this->getDateTime())) {
            return null;
        }

        return $date->getTimestamp();
    }

    /**
     * Get datetime
     */
    public function getDateTime(): ?DateTimeInterface
    {
        if ($this->dateTime === false) {
            $this->dateTime = Guidance::getDateTime($this);
        }

        return $this->dateTime;
    }

    /**
     * Convert to string
     */
    public function __toString(): string
    {
        return
            bin2hex(substr($this->bytes, 0, 4)) . '-' .
            bin2hex(substr($this->bytes, 4, 2)) . '-' .
            bin2hex(substr($this->bytes, 6, 2)) . '-' .
            bin2hex(substr($this->bytes, 8, 2)) . '-' .
            bin2hex(substr($this->bytes, 10, 6));
    }

    /**
     * Export for dump inspection
     */
    public function glitchDump(): iterable
    {
        yield 'text' => $this->__toString();

        yield 'meta' => [
            'bytes' => bin2hex($this->bytes),
            'version' => $this->getVersion(),
            'variant' => $this->getVariant(),
        ];
    }
}
