<?php
declare(strict_types=1);

namespace DP\Entity;

use Kentron\Template\Entity\ACoreEntity;

final class DataScalarEntity extends ACoreEntity
{
    public const RAG_RED = "red";
    public const RAG_AMBER = "amber";
    public const RAG_GREEN = "green";

    public string $key;
    public mixed $value;
    public string $displayName;
    public string|null $rag = null;
    public bool|null $pass = null;
    public bool $used = false;

    protected array $propertyMap = [
        "key" => "key",
        "display_name" => "displayName",
        "value" => "value",
        "rag" => "rag",
        "pass" => "pass",
        "used" => "used",
    ];

    public function __construct(?string $key = null, mixed $value = null) {
        if (!is_null($key)) {
            $this->key = $key;
            $this->displayName = $this->camelToSentence($this->key);
        }

        $this->value = is_float($value) ? round($value, 2) : $value;
    }

    /**
     * Helpers
     */

    public function resolve(bool $pass): void
    {
        $this->pass = $pass;

        if (is_null($this->rag)) {
            $this->rag = $this::determineRag($this->pass);
        }
    }

    public static function determineRag(?bool $status): ?string
    {
        return is_bool($status) ? [self::RAG_RED, self::RAG_GREEN][(int)$status] : null;
    }

    /**
     * Private methods
     */

    private function camelToSentence($camel): string
    {
        return ucfirst(preg_replace('/(?<!^)([A-Z]|\d+)/', ' $0', $camel));
    }
}
