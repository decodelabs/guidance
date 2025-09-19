<?php

/**
 * @package Guidance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Lucid\Processor;

use DecodeLabs\Exceptional;
use DecodeLabs\Guidance;
use DecodeLabs\Guidance\NanoId as NanoIdInterface;
use DecodeLabs\Lucid\Processor;
use DecodeLabs\Lucid\ProcessorTrait;

/**
 * @implements Processor<NanoIdInterface>
 */
class NanoId implements Processor
{
    /**
     * @use ProcessorTrait<NanoIdInterface>
     */
    use ProcessorTrait;

    public const array OutputTypes = ['Guidance:NanoId', NanoIdInterface::class];

    public function __construct(
        protected Guidance $guidance
    ) {
    }

    public function coerce(
        mixed $value
    ): ?NanoIdInterface {
        if ($value === null) {
            return null;
        }

        if (!$this->guidance->isValidNanoId($value)) {
            throw Exceptional::InvalidArgument(
                message: 'Not a valid NanoId',
                data: $value
            );
        }

        // @phpstan-ignore-next-line
        return $this->guidance->nanoIdFrom($value);
    }
}
