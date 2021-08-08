<?php
declare(strict_types=1);

namespace Mnohosten\DataGrid;

use Mnohosten\DataGrid\Column\CurrencyColumn;
use Mnohosten\DataGrid\Column\DateColumn;
use Mnohosten\DataGrid\Column\NumberColumn;
use Mnohosten\DataGrid\Column\TextColumn;
use Mnohosten\DataGrid\Filter\DateFilter;
use Mnohosten\DataGrid\Filter\SelectFilter;
use Mnohosten\DataGrid\Filter\TextFilter;

class DataGrid
{
    private DataSourceInterface $dataSource;
    private string $primaryKey = 'id';
    private int $pageLimit = 50;
    private array $columns = [];
    private array $filters = [];
    private array $actions = [];

    public function setDataSource(DataSourceInterface  $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    public function getDataSource(): DataSourceInterface
    {
        return $this->dataSource;
    }

    public function addColumnText(string $name, string $label): TextColumn
    {
        return $this->columns[$name] = new TextColumn($name, $label);
    }

    public function addColumnDate(string $name, string $label, string $format = 'j. n. Y'): Column
    {
        return $this->columns[$name] = new DateColumn($name, $label, $format);
    }

    public function addColumnNumber(string $name, string $label): Column
    {
        return $this->columns[$name] = new NumberColumn($name, $label);
    }

    public function addCurrencyColumn(string $name, string $label, string $format = '%.2f Kč')
    {
        $this->columns[$name] = new CurrencyColumn($name, $label, $format);
    }

    public function addFilterText(string $name, string $label): Filter
    {
        return $this->filters[$name] = new TextFilter($name, $label);
    }

    public function addFilterDate(string $name, string $label): Filter
    {
        return $this->filters[$name] = new DateFilter($name, $label);
    }

    public function addFilterSelect(string $name, string $label, array $values): Filter
    {
        return $this->filters[$name] = new SelectFilter($name, $label, $values);
    }

    public function addAction(string $name, string $label, string $href): Action
    {
        return $this->actions[$name] = new Action($name, $label, $href);
    }

    public function setPageLimit(int $pageLimit): void
    {
        $this->pageLimit = $pageLimit;
    }

    public function setPrimaryKey(string $primaryKey): void
    {
        $this->primaryKey = $primaryKey;
    }

    public function setDefaultSort(array $sort): void
    {
        $this->dataSource->sort($sort);
    }

    /**
     * @return array|Column[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return array|Filter[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @return array|Action[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    public function getPageLimit(): int
    {
        return $this->pageLimit;
    }

    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }
}
