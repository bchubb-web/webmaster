<?php

declare(strict_types=1);

namespace Webmaster\Forms\Trait;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Webmaster\Forms\Contract\HasForm as HasFormContract;

trait HasFormTrait
{
    public function getForm(FormBuilder $builder): FormInterface
    {
        $reflectionClass = new \ReflectionClass($this);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $attributes = $property->getAttributes(\Webmaster\Forms\Attribute\FormField::class);
            if (!empty($attributes)) {
                /** @var \Webmaster\Forms\Attribute\FormField $formField */
                $formField = $attributes[0]->newInstance();
                $builder->add($property->getName(), $formField->type, [
                    'label' => $formField->label,
                    'required' => $formField->required,
                ]);
            }
        }

        return $builder->getForm();
    }
}
