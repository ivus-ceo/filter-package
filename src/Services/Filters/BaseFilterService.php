<?php

namespace Ivus\Filter\Services\Filters;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Ivus\Filter\DTOs\Queries\QueryDTO;
use Ivus\Filter\Enums\Rules;

abstract class BaseFilterService
{
    /**
     * Filter model
     *
     * @param bool $isPaginated
     * @return LengthAwarePaginator|Collection
     */
    public function filter(bool $isPaginated = true): LengthAwarePaginator | Collection
    {
        $builder = static::getBuilder();
        $queryDTOs = static::getQueryDTOs();

        foreach ($queryDTOs as $queryDTO) {
            // Skip non-searchable columns
            if (!in_array($queryDTO->column, $this->getSearchableColumns()))
                continue;
            // Applying queries
            $builder->{$queryDTO->rule->value}(
                $queryDTO->column,
                match ($queryDTO->rule) {
                    Rules::WHERE_LIKE, Rules::OR_WHERE_LIKE => "%$queryDTO->value%",
                    default => $queryDTO->value,
                }
            );
        }

        return ($isPaginated) ? $this->getLengthAwarePaginator($builder) : $this->getCollection($builder);
    }

    /**
     * Get filterables from query string
     *
     * @return array<QueryDTO>
     */
    public static function getQueryDTOs(): array
    {
        $queryDTOs = [];
        $filters = request()->query(config('filters.query_name', 'filters'), []);

        if (empty($filters))
            return [];

        try {
            foreach (explode(config('filters.union_separator', ';'), $filters) as $query)
            {
                list($rule, $filterable) = explode(config('filters.rule_separator', '|'), $query);
                list($column, $value) = explode(config('filters.column_separator', ':'), $filterable);

                $inputs = [
                    'rule'   => $rule,
                    'column' => $column,
                    'value'  => $value
                ];
                // Remove old inputs
                unset($rule, $filterable, $column, $value);
                // Sanitize current inputs
                array_walk($inputs, fn (&$input) => htmlspecialchars($input, ENT_QUOTES, 'UTF-8', false));
                // Check if rule is valid
                $inputs['rule'] = Rules::tryFrom($inputs['rule']);

                if (
                    $inputs['rule'] instanceof Rules &&
                    !empty($inputs['column']) &&
                    !empty($inputs['value'])
                )
                {
                    $queryDTOs[] = new QueryDTO(
                        rule: $inputs['rule'],
                        column: $inputs['column'],
                        value: static::getQueryValue($inputs['rule'], $inputs['value'])
                    );
                }
            }
        } catch (Exception $exception) {
            return [];
        }

        return $queryDTOs;
    }

    /**
     * Get valid query value
     *
     * @param Rules $rule
     * @param string $value
     * @return string|int|array
     */
    public static function getQueryValue(Rules $rule, string $value): string | int | array
    {
        return match ($rule) {
            Rules::WHERE_IN, Rules::WHERE_NOT_IN,
            Rules::WHERE_BETWEEN, Rules::WHERE_NOT_BETWEEN,
            Rules::WHERE_BETWEEN_COLUMNS, Rules::WHERE_NOT_BETWEEN_COLUMNS, => explode(',', $value),
            default => $value,
        };
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
     * Get filtered paginated data
     *
     * @param Builder $builder
     * @return LengthAwarePaginator
     */
    private function getLengthAwarePaginator(Builder $builder): LengthAwarePaginator
    {
        return $builder->paginate(
            $this->getLengthAwarePaginatorPerPage()
        );
    }

    /**
     * Get filtered collection of data
     *
     * @param Builder $builder
     * @return Collection
     */
    private function getCollection(Builder $builder): Collection
    {
        return $builder->get();
    }

    /**
     * Get model builder
     *
     * @return Builder
     */
    abstract protected function getBuilder(): Builder;

    /**
     * Get searchable columns of model
     *
     * @return array
     */
    abstract protected function getSearchableColumns(): array;
}
