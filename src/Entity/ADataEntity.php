<?php
declare(strict_types=1);

namespace DP\Entity;

use Kentron\Facade\DT;
use Kentron\Support\Type\Type;
use Kentron\Template\Entity\ACoreEntity;

abstract class ADataEntity extends ACoreEntity
{
    public function formatDatetime(mixed $datetime): mixed
    {
        if (is_string($datetime)) {
            return DT::then($datetime)->format();
        }
        return $datetime;
    }

    public function toDataScalarEntity(mixed $value, string $key): DataScalarEntity
    {
        if (Type::isIterable($value)) {
            if ($this->arrayMimicksDataScalarEntity($value)) {
                $matchDataScalar = new DataScalarEntity($key);
                $matchDataScalar->hydrate($value);

                return $matchDataScalar;
            }
        }
        return new DataScalarEntity($key, $value);
    }

    public function fromDataScalarEntity(?DataScalarEntity $matchDataScalar): mixed
    {
        return $matchDataScalar?->normalise();
    }

    /**
     * Private methods
     */

    private function arrayMimicksDataScalarEntity(array|object $value): bool
    {
        if (Type::hasProperty($value, "key") && Type::hasProperty($value, "value") && Type::hasProperty($value, "rag")) {
            return true;
        }
        return false;
    }
}
