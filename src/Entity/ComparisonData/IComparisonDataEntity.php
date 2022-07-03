<?php
declare(strict_types=1);

namespace DP\Entity\ComparisonData;

interface IComparisonDataEntity
{
    public function processComparisonData(): void;
    public function getComparisonCollectionEntity(): ?ComparisonCollectionEntity;
}

