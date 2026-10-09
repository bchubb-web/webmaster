<?php

declare(strict_types=1);

namespace Webmaster\Blocks;

use Symfony\Component\Form\Forms;

class FormGenerator
{
    public function __construct(
    ) {
    }

    public function __invoke(Entity\Block $block): \Symfony\Component\Form\FormInterface
    {
        $builder = $this->getFormBuilder();

        $ref = new \ReflectionClass($block->blockClassName);
        $targetMethod = $ref->getConstructor();

        foreach ($targetMethod->getParameters() as $param) {
            $attributes = $param->getAttributes(\Webmaster\Forms\Attribute\FormField::class);
            if (count($attributes) < 1) {
                continue;
            }

            /** @var \Webmaster\Forms\Attribute\FormField $formField */
            $formField = $attributes[0]->newInstance();
            $builder->add(
                $formField->name ?? $param->getName(),
                $formField->type,
                [
                    'label' => $formField->label,
                    'required' => $formField->required,
                    'attr' => [
                        'placeholder' => $formField->placeholder,
                    ],
                ]
            );
        }

        return $builder->getForm();
    }

    protected function getFormBuilder(): FormBuilderInterface
    {
        return Forms::createFormFactory()->createBuilder();
    }

}
