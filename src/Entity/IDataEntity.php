<?php
declare(strict_types=1);

namespace DP\Entity;

use DP\Entity\DataScalarEntity;

interface IDataEntity
{
    public function processData(): void;

    public function getAllActionedWithRagStatus(): array;

    /**
     * Get a property or array of properties
     *
     * @param array $properties
     *
     * @return DataScalarEntity|DataScalarEntity[]|null
     */
    public function getPropertyByChain(array $properties): DataScalarEntity|array|null;
}
