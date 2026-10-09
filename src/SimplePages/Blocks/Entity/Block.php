<?php

declare(strict_types=1);

namespace Webmaster\SimplePages\Blocks\Entity;

use Doctrine\ORM\Mapping as ORM;
use Webmaster\SimplePages\Entity\SimplePage;

#[ORM\Entity]
#[ORM\Table(name: 'blocks')]
class Block
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    public int $id = -1;

    #[ORM\ManyToOne(targetEntity: SimplePage::class, inversedBy: 'blocks')]
    public ?SimplePage $page = null;


    #[ORM\Column(type: 'string', length: 255)]
    public string $blockClassName;

    #[ORM\Column(type: 'json')]
    public array $data;
}
