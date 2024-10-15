<?php

namespace Ivus\Filter\Services\Filters;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Ivus\Filter\Enums\Operators\Operator;
use Ivus\Filter\Enums\Rules\ArrayableRule;
use Ivus\Filter\Enums\Rules\BooleanRule;
use Ivus\Filter\Enums\Rules\CustomableRule;
use Ivus\Filter\Enums\Rules\DateableRule;
use Ivus\Filter\Enums\Rules\NumericRule;
use Ivus\Filter\Enums\Rules\RelationableRule;
use Ivus\Filter\Enums\Rules\SearchableRule;
use Ivus\Filter\Enums\Rules\StringableRule;
use Ivus\Filter\Interfaces\Enums\ExistingBuilderRuleInterface;
use Ivus\Filter\Interfaces\Enums\RuleInterface;
use Ivus\Filter\Interfaces\Services\FilterServiceInterface;
use Ivus\Filter\Services\Queries\QueryService;

abstract class FilterService implements FilterServiceInterface
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

        try {
            foreach ($queryDTOs as $queryDTO) {
                if ($queryDTO->rule instanceof CustomableRule) {
                    $this->{$queryDTO->method}($queryDTO->columnValue);
                } else {
                    // Skip non-searchable columns
                    if (!in_array($queryDTO->columnName, $this->columns))
                        continue;

                    if ($queryDTO->columnOperator instanceof Operator) {
                        $this->builder->{$queryDTO->method}(
                            $queryDTO->columnName,
                            $queryDTO->columnOperator->value,
                            $queryDTO->columnValue
                        );
                    } else {
                        $this->builder->{$queryDTO->method}(
                            $queryDTO->columnName,
                            $queryDTO->columnValue
                        );
                    }
                }
            }

            return ($isPaginated) ?
                $this->builder->paginate($this->getLengthAwarePaginatorPerPage()) :
                $this->builder->get();
        } catch (Exception $exception) {
            Log::error(trans('filters::errors.filters.E1', ['page' => request()->getUri(), 'error' => $exception->getMessage()]));
            return collect();
        }
    }

    /**
     * Get method by rule
     *
     * @param RuleInterface $rule
     * @param string $customMethod
     * @return string
     */
    public static function getMethodByRule(RuleInterface $rule, string $customMethod = 'where'): string
    {
        return match (true) {
            $rule instanceof CustomableRule => $customMethod,
            $rule instanceof ExistingBuilderRuleInterface => $rule->value,
            default => 'where',
        };
    }

    /**
     * Get operator by rule
     *
     * @param RuleInterface $rule
     * @return Operator|null
     */
    public static function getOperatorByRule(RuleInterface $rule): ?Operator
    {
        if ($rule instanceof ExistingBuilderRuleInterface)
            return null;

        return match (true) {
            in_array($rule, [
                BooleanRule::WHERE_TRUE, BooleanRule::OR_WHERE_TRUE,
                BooleanRule::WHERE_FALSE, BooleanRule::OR_WHERE_FALSE,

                NumericRule::WHERE_EQUAL, NumericRule::OR_WHERE_EQUAL,

                DateableRule::WHERE_DATE_EQUAL, DateableRule::OR_WHERE_DATE_EQUAL,
                DateableRule::WHERE_DAY_EQUAL, DateableRule::OR_WHERE_DAY_EQUAL,
                DateableRule::WHERE_MONTH_EQUAL, DateableRule::OR_WHERE_MONTH_EQUAL,
                DateableRule::WHERE_YEAR_EQUAL, DateableRule::OR_WHERE_YEAR_EQUAL,
                DateableRule::WHERE_TIME_EQUAL, DateableRule::OR_WHERE_TIME_EQUAL,
            ]) => Operator::EQUAL,
            in_array($rule, [
                BooleanRule::WHERE_NOT_TRUE, BooleanRule::OR_WHERE_NOT_TRUE,
                BooleanRule::WHERE_NOT_FALSE, BooleanRule::OR_WHERE_NOT_FALSE,

                NumericRule::WHERE_NOT_EQUAL, NumericRule::OR_WHERE_NOT_EQUAL,

                DateableRule::WHERE_DATE_NOT_EQUAL, DateableRule::OR_WHERE_DATE_NOT_EQUAL,
                DateableRule::WHERE_DAY_NOT_EQUAL, DateableRule::OR_WHERE_DAY_NOT_EQUAL,
                DateableRule::WHERE_MONTH_NOT_EQUAL, DateableRule::OR_WHERE_MONTH_NOT_EQUAL,
                DateableRule::WHERE_YEAR_NOT_EQUAL, DateableRule::OR_WHERE_YEAR_NOT_EQUAL,
                DateableRule::WHERE_TIME_NOT_EQUAL, DateableRule::OR_WHERE_TIME_NOT_EQUAL,
            ]) => Operator::NOT_EQUAL,
            in_array($rule, [
                NumericRule::WHERE_GREATER_THAN, NumericRule::OR_WHERE_GREATER_THAN,

                DateableRule::WHERE_DATE_GREATER_THAN, DateableRule::OR_WHERE_DATE_GREATER_THAN,
                DateableRule::WHERE_DAY_GREATER_THAN, DateableRule::OR_WHERE_DAY_GREATER_THAN,
                DateableRule::WHERE_MONTH_GREATER_THAN, DateableRule::OR_WHERE_MONTH_GREATER_THAN,
                DateableRule::WHERE_YEAR_GREATER_THAN, DateableRule::OR_WHERE_YEAR_GREATER_THAN,
                DateableRule::WHERE_TIME_GREATER_THAN, DateableRule::OR_WHERE_TIME_GREATER_THAN,
            ]) => Operator::GREATER_THAN,
            in_array($rule, [
                NumericRule::WHERE_LESS_THAN, NumericRule::OR_WHERE_LESS_THAN,

                DateableRule::WHERE_DATE_LESS_THAN, DateableRule::OR_WHERE_DATE_LESS_THAN,
                DateableRule::WHERE_DAY_LESS_THAN, DateableRule::OR_WHERE_DAY_LESS_THAN,
                DateableRule::WHERE_MONTH_LESS_THAN, DateableRule::OR_WHERE_MONTH_LESS_THAN,
                DateableRule::WHERE_YEAR_LESS_THAN, DateableRule::OR_WHERE_YEAR_LESS_THAN,
                DateableRule::WHERE_TIME_LESS_THAN, DateableRule::OR_WHERE_TIME_LESS_THAN,
            ]) => Operator::LESS_THAN,
            in_array($rule, [
                NumericRule::WHERE_GREATER_THAN_OR_EQUAL, NumericRule::OR_WHERE_GREATER_THAN_OR_EQUAL,

                DateableRule::WHERE_DATE_GREATER_THAN_OR_EQUAL, DateableRule::OR_WHERE_DATE_GREATER_THAN_OR_EQUAL,
                DateableRule::WHERE_DAY_GREATER_THAN_OR_EQUAL, DateableRule::OR_WHERE_DAY_GREATER_THAN_OR_EQUAL,
                DateableRule::WHERE_MONTH_GREATER_THAN_OR_EQUAL, DateableRule::OR_WHERE_MONTH_GREATER_THAN_OR_EQUAL,
                DateableRule::WHERE_YEAR_GREATER_THAN_OR_EQUAL, DateableRule::OR_WHERE_YEAR_GREATER_THAN_OR_EQUAL,
                DateableRule::WHERE_TIME_GREATER_THAN_OR_EQUAL, DateableRule::OR_WHERE_TIME_GREATER_THAN_OR_EQUAL,
            ]) => Operator::GREATER_THAN_OR_EQUAL,
            in_array($rule, [
                NumericRule::WHERE_LESS_THAN_OR_EQUAL, NumericRule::OR_WHERE_LESS_THAN_OR_EQUAL,

                DateableRule::WHERE_DATE_LESS_THAN_OR_EQUAL, DateableRule::OR_WHERE_DATE_LESS_THAN_OR_EQUAL,
                DateableRule::WHERE_DAY_LESS_THAN_OR_EQUAL, DateableRule::OR_WHERE_DAY_LESS_THAN_OR_EQUAL,
                DateableRule::WHERE_MONTH_LESS_THAN_OR_EQUAL, DateableRule::OR_WHERE_MONTH_LESS_THAN_OR_EQUAL,
                DateableRule::WHERE_YEAR_LESS_THAN_OR_EQUAL, DateableRule::OR_WHERE_YEAR_LESS_THAN_OR_EQUAL,
                DateableRule::WHERE_TIME_LESS_THAN_OR_EQUAL, DateableRule::OR_WHERE_TIME_LESS_THAN_OR_EQUAL,
            ]) => Operator::LESS_THAN_OR_EQUAL,
            default => null,
        };
    }

    /**
     * Get value by rule
     *
     * @param RuleInterface $rule
     * @param string|null $value
     * @return array|string|int|bool|null
     */
    public static function getValueByRule(RuleInterface $rule, ?string $value): array | string | int | bool | null
    {
        $separators = QueryService::getSeparators();

        return match (true) {
            $rule instanceof ArrayableRule => explode($separators['value'], $value),
            $rule instanceof BooleanRule => Str::contains($rule->name, 'TRUE'),
            $rule instanceof DateableRule => Carbon::parse($value),
            $rule instanceof NumericRule => (int) $value,
            $rule instanceof SearchableRule => "%$value%",
            $rule instanceof StringableRule, $rule instanceof RelationableRule, $rule instanceof CustomableRule => $value,
            default => null,
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
