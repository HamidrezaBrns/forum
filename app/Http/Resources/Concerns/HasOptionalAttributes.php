<?php

namespace App\Http\Resources\Concerns;

trait HasOptionalAttributes
{
    protected array $optionalAttributes = [];

    // Use in Controllers
    public function withAttribute(string $attribute): static
    {
        $this->optionalAttributes[$attribute] = true;

        return $this;
    }

    // Use in Resources
    public function hasAttribute(string $attribute): bool
    {
        return $this->optionalAttributes[$attribute] ?? false;
    }
}
