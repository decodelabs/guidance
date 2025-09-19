<?php

/**
 * @package Greenleaf
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Greenleaf\Route\Parameter;

use Attribute;
use DecodeLabs\Greenleaf\Route\Parameter;
use DecodeLabs\Guidance;
use DecodeLabs\Guidance\Uuid\Format as UuidFormat;
use DecodeLabs\Monarch;

#[Attribute]
class Uuid extends Parameter
{
    public function __construct(
        string $name,
        protected ?UuidFormat $shortFormat = null,
        ?string $default = null
    ) {
        parent::__construct($name, $default);
    }

    public function getRegexFragment(): string
    {
        if ($this->shortFormat === null) {
            return '(?P<' . $this->name . '>([0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}+)?)';
        }

        $length = $this->shortFormat->getLength();
        return '(?P<' . $this->name . '>[' . preg_quote($this->shortFormat->value, '/') . ']{' . ($length - 1) . ',' . ($length + 1) . '})';
    }


    public function validate(
        ?string $value
    ): bool {
        $value ??= $this->default;
        $guidance = Monarch::getService(Guidance::class);
        return $guidance->isValidUuid($value, $this->shortFormat);
    }

    /**
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'type' => 'uuid',
            'name' => $this->name,
            'shortFormat' => $this->shortFormat,
            'default' => $this->default,
        ];
    }
}
