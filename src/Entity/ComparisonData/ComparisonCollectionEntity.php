<?php
declare(strict_types=1);

namespace DP\Entity\ComparisonData;

use Kentron\Template\Entity\ACoreCollectionEntity;

class ComparisonCollectionEntity extends ACoreCollectionEntity
{
    public function __construct() {
        $this->entityClass = ComparisonEntity::class;
    }

    public function getNewEntity(string $name): ComparisonEntity
    {
        $comparisonEntity = parent::newEntity();
        $comparisonEntity->name = $name;

        parent::addEntity($comparisonEntity);

        return $comparisonEntity;
    }
}
