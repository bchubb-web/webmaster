<?php

declare(strict_types=1);

namespace Webmaster\SimplePages\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Webmaster\Forms\Contract\HasForm as HasFormContract;
use Webmaster\Forms\Trait\HasFormFields;
use Webmaster\SimplePages\Blocks\Entity\Block as BlockEntity;
use Webmaster\Forms\Attribute\FormField;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;

#[ORM\Entity]
#[ORM\Table(name: 'simple_pages')]
class SimplePage implements HasFormContract
{
    use HasFormFields;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[FormField(type: Type\TextType::class, label: 'Page Title', required: true)]
    public string $title = '';

    #[ORM\Column(type: 'string', length: 255)]
    #[FormField(type: Type\TextType::class, label: 'Slug', required: true)]
    public ?string $slug = null;

    #[ORM\OneToMany(targetEntity: BlockEntity::class, mappedBy: 'page', cascade: ['persist', 'remove'], orphanRemoval: true)]
    public Collection $blocks;

    public function __construct()
    {
        $this->blocks = new ArrayCollection();
    }

    public function getForm(FormBuilder $builder): FormInterface
    {
        $fields = $this->getFormFields();
        $slugField = $fields['slug'] ?? null;

        unset($fields['slug']);

        foreach ($fields as $name => $formField) {
            $builder->add($formField->name ?? $name, $formField->type, [
                'label' => $formField->label,
                'required' => $formField->required,
                'attr' => [
                    'placeholder' => $formField->placeholder,
                ],
            ]);
        }

        // if this instance isnt persisted yet, dont add the slug field, as it will be generated from the title
        if ($this->id !== null && $slugField !== null) {
            $builder->add($slugField->name ?? 'slug', $slugField->type, [
                'label' => $slugField->label,
                'required' => $slugField->required,
                'attr' => [
                    'placeholder' => $slugField->placeholder,
                ],
            ]);
        }


        // if it is persisted, set the data to the form
        if ($this->id !== null) {

            //$builder->setData($formData);
        }

        return $builder->getForm();
    }
}
