<?php

declare(strict_types=1);

namespace Webmaster\Blocks;

use Webmaster\SimplePages\Blocks\Entity\Block;
use Webmaster\View\Contract\ViewRenderer;

class RenderWrapper implements \Stringable
{
    public function __construct(
        private Block $block,
        private ViewRenderer $viewRenderer,
    ) {
    }

    public function __toString(): string
    {
        return $this->viewRenderer->render(
            $this->getBlockTemplate(),
            array_merge(
                ['block' => $this->block],
                $this->block->data
            )
        );
    }

    protected function getBlockTemplate(): string
    {
        $templateAttribute = (new \ReflectionClass($this->block->blockClassName))->getAttributes(Attribute\Template::class)[0] ?? null;
        if (!$templateAttribute) {
            throw new \RuntimeException("Block class {$this->block->blockClassName} does not have a Template attribute.");
        }

        /** @var Template $templateInstance */
        $templateInstance = $templateAttribute->newInstance();
        return $templateInstance->template;
    }
}
