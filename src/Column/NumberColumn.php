<?php
declare(strict_types=1);

namespace Mnohosten\DataGrid\Column;

use Mnohosten\DataGrid\Column;

class NumberColumn extends Column
{
    public function __construct(string $name, string $label)
    {
        parent::__construct($name, $label);
        $this->setRender(function($item, $name) {
            return number_format(
                $this->getAccessor()->getValue($item, $name),
                0,
                '.',
                ' '
            );
        });
    }
}