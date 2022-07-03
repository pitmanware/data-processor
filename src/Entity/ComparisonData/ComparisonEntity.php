<?php
declare(strict_types=1);

namespace DP\Entity\ComparisonData;

use DP\Entity\DataScalarEntity;

use Kentron\Template\Entity\AEntity;

class ComparisonEntity extends AEntity
{
    public string $name;
    public string|null $input = null;
    public string|null $found = null;
    public int|null $similarity = null;
    public string|null $rag = null;

    /**
     * Setters/Adders
     */

    public function setAddInput(string $input, string $separator = " "): void
    {
        if (!isset($this->input)) {
            $this->input = $input;
        }
        else {
            $this->input = $separator . $input;
        }
    }

    public function setAddFound(string $found, string $separator = " "): void
    {
        if (!isset($this->found)) {
            $this->found = $found;
        }
        else {
            $this->found = $separator. $found;
        }
    }

    public function setAddSimilarity(int $similarity, int $total): void
    {
        if (!isset($this->similarity)) {
            $this->similarity = $similarity;
        }
        else {
            $this->similarity =
                (int)((($this->similarity + $similarity) / $total) * 100)
            ;
        }
    }

    /**
     * Helpers
     */

    public function processSimilarityRag(): void
    {
        if (is_null($this->similarity)) {
            return;
        }

        if ($this->similarity === 100) {
            $this->rag = DataScalarEntity::RAG_GREEN;
        }
        else if ($this->similarity < 70) {
            $this->rag = DataScalarEntity::RAG_RED;
        }
        else {
            $this->rag = DataScalarEntity::RAG_AMBER;
        }
    }
}
