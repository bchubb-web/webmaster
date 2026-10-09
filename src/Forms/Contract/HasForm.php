<?php

declare(strict_types=1);

namespace Webmaster\Forms\Contract;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;

interface HasForm
{
    public function getForm(FormBuilder $builder): FormInterface;
}
