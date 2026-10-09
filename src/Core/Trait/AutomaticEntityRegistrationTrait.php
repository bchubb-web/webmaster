<?php

declare(strict_types=1);

namespace Webmaster\Core\Trait;

/**
 * Uses the composer autoloader to identify all classes decorated with 
 * #[\Doctrine\ORM\Mapping\Entity], registers them to the container (if necessary)
 */
trait AutomaticEntityRegistrationTrait
{
    protected const array ENTITY_SEARCH_TERMS = [
        'Doctrine', 'Entity', 'ORM',
        'ORM\Entity', 'ORM\Table', 'ORM\Column', 'ORM\Id', 'ORM\GeneratedValue',
        '#[ORM\Entity', '#[ORM\Table', '#[ORM\Column', '#[ORM\Id', '#[ORM\GeneratedValue',
        'use Doctrine\ORM\Mapping as ORM', 'use Doctrine\ORM\Mapping\Entity', 'use Doctrine\ORM\Mapping\Table', 'use Doctrine\ORM\Mapping\Column', 'use Doctrine\ORM\Mapping\Id', 'use Doctrine\ORM\Mapping\GeneratedValue',
    ];
    public function getEntities(): array
    {
        $potentialEntities = [];
        $entities = [];
        $allClasses = $this->getClassMap();

        foreach ($allClasses as $className => $filePath) {
            // checking class_exists loads the class, which in this case means EVERY class will be loaded, not great.
            // instead, we can grep the file for the search terms, and only load the class if it contains one of them
            $fileContents = file_get_contents($filePath);
            foreach (self::ENTITY_SEARCH_TERMS as $searchTerm) {
                if (strpos($fileContents, $searchTerm) !== false) {
                    $potentialEntities[] = $className;
                    break;
                }
            }
        }

        foreach ($potentialEntities as $className) {
            if (class_exists($className)) {
                $reflectionClass = new \ReflectionClass($className);
                $attributes = $reflectionClass->getAttributes(\Doctrine\ORM\Mapping\Entity::class);
                if (!empty($attributes)) {
                    $entities[] = $className;
                }
            }
        }

        return $entities;
    }

    private function getClassMap(): array
    {
        $autoloaderClassName = '';
        foreach (get_declared_classes() as $className) {
            if (strpos($className, 'ComposerAutoloaderInit') === 0) {
                $autoloaderClassName = $className;
                break;
            }
        }
        $classLoader = $autoloaderClassName::getLoader();
        return $classLoader->getClassMap();
    }
}

