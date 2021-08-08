<?php
declare(strict_types=1);

namespace Mnohosten\DataGrid\Filter;

use Mnohosten\DataGrid\Filter;

class SelectFilter extends Filter
{

    private array $values;

    public function __construct(
        string $name,
        string $label,
        array $values = []
    )
    {
        parent::__construct($name, $label);
        $this->values = $values;
    }

    public function getValues(): array
    {
        return $this->values;
    }
}
