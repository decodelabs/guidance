<?php

/**
 * @package Guidance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Lucid\Processor;

use DecodeLabs\Exceptional;
use DecodeLabs\Guidance;
use DecodeLabs\Guidance\Uuid as UuidInterface;
use DecodeLabs\Lucid\Processor;
use DecodeLabs\Lucid\ProcessorTrait;
use DecodeLabs\Lucid\Sanitizer;

/**
 * @implements Processor<UuidInterface>
 */
class Uuid implements Processor
{
    /**
     * @use ProcessorTrait<UuidInterface>
     */
    use ProcessorTrait;

    public const array OutputTypes = ['Guidance:Uuid', UuidInterface::class];

    public function __construct(
        protected Sanitizer $sanitizer,
        protected Guidance $guidance
    ) {
    }

    public function coerce(
        mixed $value
    ): ?UuidInterface {
        if ($value === null) {
            return null;
        }

        if (!$this->guidance->isValidUuid($value)) {
            throw Exceptional::InvalidArgument(
                message: 'Not a valid UUID',
                data: $value
            );
        }

        // @phpstan-ignore-next-line
        return $this->guidance->uuidFrom($value);
    }
}
