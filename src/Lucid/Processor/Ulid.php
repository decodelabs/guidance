<?php

/**
 * @package Guidance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Lucid\Processor;

use DecodeLabs\Exceptional;
use DecodeLabs\Guidance;
use DecodeLabs\Guidance\Ulid as UlidInterface;
use DecodeLabs\Lucid\Processor;
use DecodeLabs\Lucid\ProcessorTrait;
use DecodeLabs\Lucid\Sanitizer;

/**
 * @implements Processor<UlidInterface>
 */
class Ulid implements Processor
{
    /**
     * @use ProcessorTrait<UlidInterface>
     */
    use ProcessorTrait;

    public const array OutputTypes = ['Guidance:Ulid', UlidInterface::class];

    public function __construct(
        protected Sanitizer $sanitizer,
        protected Guidance $guidance
    ) {
    }

    public function coerce(
        mixed $value
    ): ?UlidInterface {
        if ($value === null) {
            return null;
        }

        if (!$this->guidance->isValidUlid($value)) {
            throw Exceptional::InvalidArgument(
                message: 'Not a valid ULID',
                data: $value
            );
        }

        // @phpstan-ignore-next-line
        return $this->guidance->ulidFrom($value);
    }
}
