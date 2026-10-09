<?php

declare(strict_types=1);

namespace Webmaster\Forms\Trait;

use Webmaster\Forms\Attribute\FormField;

trait HasFormFields
{
    /**
     * @return array<string, FormField>
     */
    public function getFormFields(): array
    {
        $fields = [];
        $reflectionClass = new \ReflectionClass($this);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $attributes = $property->getAttributes(FormField::class);
            if (empty($attributes)) {
                continue;
            }

            /** @var FormField $formField */
            $formField = $attributes[0]->newInstance();
            $fields[$property->getName()] = $formField;
        }

        return $fields;
    }
}
