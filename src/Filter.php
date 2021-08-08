<?php
declare(strict_types=1);

namespace Mnohosten\DataGrid;

abstract class Filter
{
    protected string $name;
    protected string $label;
    /** @var callable|null */
    protected $condition = null;

    public function __construct(
        string $name,
        string $label
    )
    {
        $this->name = $name;
        $this->label = $label;
    }

    public function setCondition(callable $condition): void
    {
        $this->condition = $condition;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return callable|null
     */
    public function getCondition(): ?callable
    {
        return $this->condition;
    }

}
