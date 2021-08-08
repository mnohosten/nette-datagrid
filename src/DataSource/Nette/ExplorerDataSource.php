<?php
declare(strict_types=1);

namespace Mnohosten\DataGrid\DataSource\Nette;

use Mnohosten\DataGrid\DataSourceInterface;
use Mnohosten\DataGrid\Filter;
use Nette\Database\Explorer;

class ExplorerDataSource implements DataSourceInterface
{
    private Explorer $explorer;
    private QueryHelper $queryHelper;
    private string $sql;

    public function __construct(
        Explorer $explorer,
        string $sql
    )
    {
        $this->explorer = $explorer;
        $this->sql = $sql;
        $this->queryHelper = new QueryHelper($this->sql);
    }

    public function filter(array $filters, iterable $values = []): void
    {
        foreach ($values as $key => $value) {
            if ($value) {
                $this->applyFilter($filters[$key], $value);
            }
        }
    }

    private function applyFilter(Filter $filter, $value): void
    {
        if ($filter->getCondition()) {
            $this->sql = call_user_func_array($filter->getCondition(), [$this->sql, $value]);
            $this->queryHelper = new QueryHelper($this->sql);
            return;
        }
        $this->sql = $this->queryHelper->where($filter->getName(), $value, '=');
    }

    public function sort(iterable $sort = []): void
    {
        // TODO: Implement sort() method.
    }

    public function reduce(int $limit, int $offset = 0): void
    {
        $this->sql = $this->queryHelper->limit($limit, $offset);
    }

    public function getData(): iterable
    {
        return $this->explorer->query($this->sql);
    }
}
