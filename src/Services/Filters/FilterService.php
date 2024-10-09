<?php

namespace Ivus\Filter\Services\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Ivus\Filter\Enums\Rules\CustomableRule;
use Ivus\Filter\Services\Queries\QueryService;

abstract class FilterService
{
    protected readonly Builder $builder;
    protected readonly array $columns;

    /**
     * FilterService constructor.
     */
    public function __construct()
    {
        $this->builder = $this->getBuilder();
        $this->columns = $this->getColumns();
    }

    /**
     * Filter model
     *
     * @param bool $isPaginated
     * @return LengthAwarePaginator|Collection
     */
    public function filter(bool $isPaginated = true): LengthAwarePaginator | Collection
    {
        $queryDTOs = QueryService::getQueryDTOs();

        foreach ($queryDTOs as $queryDTO) {
            // Skip non-searchable columns
            if (!in_array($queryDTO->column, $this->columns)) continue;
            // Applying queries
            if ($queryDTO->rule instanceof CustomableRule) {
                $this->{$queryDTO->column}($queryDTO->value);
            } else {
                $this->builder->{$queryDTO->rule->value}(
                    $queryDTO->column,
                    $queryDTO->value
                );
            }
        }

        return ($isPaginated) ?
            $this->builder->paginate($this->getLengthAwarePaginatorPerPage()) :
            $this->builder->get();
    }

    /**
     * Get length aware paginator per page number
     *
     * @return int
     */
    protected function getLengthAwarePaginatorPerPage(): int
    {
        return 10;
    }

    /**
     * Get model builder
     *
     * @return Builder
     */
    abstract protected function getBuilder(): Builder;

    /**
     * Get searchable columns of model builder
     *
     * @return array<string>
     */
    abstract protected function getColumns(): array;
}
