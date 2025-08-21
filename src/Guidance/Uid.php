<?php

/**
 * @package Guidance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Guidance;

use DateTimeInterface;
use JsonSerializable;
use Stringable;

interface Uid extends
    JsonSerializable,
    Stringable
{
    public string $bytes { get; }
    public int $size { get; }
    public string $urn { get; }

    public ?int $timestamp { get; }
    public ?DateTimeInterface $dateTime { get; }

    public function isNil(): bool;
}
