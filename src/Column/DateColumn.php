<?php
declare(strict_types=1);

namespace Mnohosten\DataGrid\Column;

use DateTimeInterface;
use Mnohosten\DataGrid\Column;

class DateColumn extends Column
{
    private string $format;

    public function __construct(string $name, string $label, string $format)
    {
        parent::__construct($name, $label);
        $this->format = $format;
    }

    public function render($item)
    {
        return isset($this->render)
            ? parent::render($item)
            : $this->renderValue($this->getValue($item));
    }

    private function renderValue($value)
    {
        if($value instanceof DateTimeInterface) {
            return $value->format($this->format);
        }
        return $value;
    }

}
