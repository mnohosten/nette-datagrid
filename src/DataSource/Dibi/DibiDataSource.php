<?php
declare(strict_types=1);

namespace Mnohosten\DataGrid\DataSource\Dibi;

use Dibi\Fluent;
use Mnohosten\DataGrid\DataSourceInterface;
use Mnohosten\DataGrid\Filter;

class DibiDataSource implements DataSourceInterface
{
    private Fluent $fluent;

    public function __construct(
        Fluent $fluent
    )
    {
        $this->fluent = $fluent;
    }

    public function getData(): iterable
    {
        return $this->fluent;
    }

    /**
     * @param array|Filter[] $filters
     * @param iterable|array $values
     */
    public function filter(array $filters, iterable $values = []): void
    {
        foreach ($values as $key=>$value) {
            if(!isset($filters[$key])) continue;
            $filter = $filters[$key];
            if ($filter->getCondition() && $value !== '') {
                call_user_func_array($filter->getCondition(), [$this->fluent, $value]);
                continue;
            }
            if($value !== '') {
                $this->fluent->where([$filter->getName() => $value]);
            }
        }
    }

    public function sort(iterable $sort = []): void
    {
        foreach ($sort as $key=>$dir) {
            $this->fluent->orderBy($key, $dir);
        }
    }

    public function reduce(int $limit, int $offset = 0): void
    {
        $this->fluent->limit($limit)->offset($offset);
    }
}