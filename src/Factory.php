<?php

/**
 * @package Guidance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Guidance;

use Brick\Math\BigInteger;
use DateTimeInterface;

interface Factory
{
    /**
     * @return static
     */
    public function setDefaultShortFormat(
        Format $format
    ): static;

    public function getDefaultShortFormat(): Format;

    public function createVoid(): Uuid;

    public function createV1(
        int|string|null $node = null,
        ?int $clockSeq = null
    ): Uuid;

    public function createV3(
        string $name,
        ?string $namespace = null
    ): Uuid;

    public function createV4(): Uuid;
    public function createV4Comb(): Uuid;

    public function createV5(
        string $name,
        ?string $namespace = null
    ): Uuid;

    public function createV6(
        int|string|null $node = null,
        ?int $clockSeq = null
    ): Uuid;

    public function createV7(
        ?DateTimeInterface $date = null
    ): Uuid;


    public function isValid(
        string|BigInteger|Uuid|null $uuid
    ): bool;

    public function from(
        string|BigInteger|Uuid $uuid
    ): Uuid;

    public function tryFrom(
        string|BigInteger|Uuid|null $uuid
    ): ?Uuid;

    public function fromBytes(
        string $bytes
    ): Uuid;

    public function fromString(
        string $uuid
    ): Uuid;

    public function fromShortString(
        string $uuid,
        ?Format $format = null
    ): Uuid;

    public function fromBigInteger(
        BigInteger $uuid
    ): Uuid;

    public function shorten(
        string|BigInteger|Uuid $uuid,
        ?Format $format = null
    ): string;
}
