<?php

/**
 * @package Guidance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Guidance;

use Brick\Math\BigInteger;
use DateTimeInterface;
use Stringable;

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
    public function createVoidString(): string;

    public function createV1(
        int|string|null $node = null,
        ?int $clockSeq = null
    ): Uuid;

    public function createV1String(
        int|string|null $node = null,
        ?int $clockSeq = null
    ): string;

    public function createV3(
        string $name,
        ?string $namespace = null
    ): Uuid;

    public function createV3String(
        string $name,
        ?string $namespace = null
    ): string;

    public function createV4(): Uuid;
    public function createV4String(): string;
    public function createV4Comb(): Uuid;
    public function createV4CombString(): string;

    public function createV5(
        string $name,
        ?string $namespace = null
    ): Uuid;

    public function createV5String(
        string $name,
        ?string $namespace = null
    ): string;

    public function createV6(
        int|string|null $node = null,
        ?int $clockSeq = null
    ): Uuid;

    public function createV6String(
        int|string|null $node = null,
        ?int $clockSeq = null
    ): string;

    public function createV7(
        ?DateTimeInterface $date = null
    ): Uuid;

    public function createV7String(
        ?DateTimeInterface $date = null
    ): string;


    public function isValid(
        string|Stringable|BigInteger|Uuid|null $uuid
    ): bool;

    public function from(
        string|Stringable|BigInteger|Uuid $uuid
    ): Uuid;

    public function tryFrom(
        string|Stringable|BigInteger|Uuid|null $uuid
    ): ?Uuid;

    public function fromBytes(
        string $bytes
    ): Uuid;

    public function fromString(
        string|Stringable$uuid
    ): Uuid;

    public function fromShortString(
        string|Stringable $uuid,
        ?Format $format = null
    ): Uuid;

    public function fromBigInteger(
        BigInteger $uuid
    ): Uuid;

    public function shorten(
        string|Stringable|BigInteger|Uuid $uuid,
        ?Format $format = null
    ): string;
}
