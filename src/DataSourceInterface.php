<?php
declare(strict_types=1);

namespace Mnohosten\DataGrid;

interface DataSourceInterface
{
    public function getData(): iterable;

    /**
     * @param array|Filter[] $filters
     * @param iterable|array $values
     */
    public function filter(array $filters, iterable $values = []): void;

    public function sort(iterable $sort = []): void;

    public function reduce(int $limit, int $offset = 0): void;
}
