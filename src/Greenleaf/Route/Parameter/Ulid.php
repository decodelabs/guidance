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
use DecodeLabs\Monarch;

#[Attribute]
class Ulid extends Parameter
{
    public function getRegexFragment(): string
    {
        return '(?P<' . $this->name . '>[0-9a-z]{26}+)';
    }


    public function validate(
        ?string $value
    ): bool {
        $value ??= $this->default;
        $guidance = Monarch::getService(Guidance::class);
        return $guidance->isValidUlid($value);
    }

    /**
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'type' => 'ulid',
            'name' => $this->name,
            'default' => $this->default,
        ];
    }
}
