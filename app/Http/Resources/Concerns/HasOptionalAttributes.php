<?php

namespace App\Http\Resources\Concerns;

trait HasOptionalAttributes
{
    protected array $optionalAttributes = [];

    // Use in Controllers
    public function includeAttributes(array $attributes): static
    {
        foreach ($attributes as $attribute) {
            $this->optionalAttributes[$attribute] = true;
        }

        return $this;
    }

    // Use in Resources
    public function shouldInclude(string $attribute): bool
    {
        return $this->optionalAttributes[$attribute] ?? false;
    }
}
