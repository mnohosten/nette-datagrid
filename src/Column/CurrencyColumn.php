<?php
declare(strict_types=1);

namespace Mnohosten\DataGrid\Column;

use Mnohosten\DataGrid\Column;

class CurrencyColumn extends Column
{
    private string $format;

    public function __construct(string $name, string $label, string $format)
    {
        parent::__construct($name, $label, Column::CURRENCY);
        $this->format = $format;
    }

    public function render($item)
    {
        return isset($this->render)
            ? parent::render($item)
            : sprintf($this->format, $this->getValue($item));
    }

}
