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
use DecodeLabs\Guidance\Dictionary;
use DecodeLabs\Monarch;

#[Attribute]
class NanoId extends Parameter
{
    protected Dictionary $dictionary;

    public function __construct(
        string $name,
        ?Dictionary $dictionary = null,
        ?string $default = null
    ) {
        parent::__construct($name, $default);
        $this->dictionary ??= Dictionary::NanoId;
    }

    public function getRegexFragment(): string
    {
        if ($this->dictionary === Dictionary::NanoId) {
            $length = '21';
        } else {
            $length = '21,';
        }

        return '(?P<' . $this->name . '>[' . preg_quote($this->dictionary->value, '/') . ']{' . $length . '})';
    }


    public function validate(
        ?string $value
    ): bool {
        $value ??= $this->default;
        $guidance = Monarch::getService(Guidance::class);
        return $guidance->isValidNanoId($value, $this->dictionary);
    }

    /**
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'type' => 'nanoId',
            'name' => $this->name,
            'dictionary' => $this->dictionary,
            'default' => $this->default,
        ];
    }
}
