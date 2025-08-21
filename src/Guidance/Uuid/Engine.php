<?php

/**
 * @package Guidance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Guidance\Uuid;

use DateTimeInterface;
use DecodeLabs\Guidance\Uuid;

interface Engine
{
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

    public function getDateTimeFromBytes(
        string $bytes
    ): ?DateTimeInterface;
}
