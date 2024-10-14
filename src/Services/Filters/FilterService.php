<?php

namespace Ivus\Filter\Services\Filters;

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
use Ivus\Filter\Enums\Rules\NumericRule;
use Ivus\Filter\Enums\Rules\RelationableRule;
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
     * @return string
     */
    public static function getMethodByRule(RuleInterface $rule): string
    {
        return ($rule instanceof ExistingBuilderRuleInterface) ? $rule->value : 'where';
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

        return match ($rule) {
            BooleanRule::WHERE_TRUE, BooleanRule::OR_WHERE_TRUE,
            BooleanRule::WHERE_FALSE, BooleanRule::OR_WHERE_FALSE => Operator::EQUALS,
            BooleanRule::WHERE_NOT_TRUE, BooleanRule::OR_WHERE_NOT_TRUE,
            BooleanRule::WHERE_NOT_FALSE, BooleanRule::OR_WHERE_NOT_FALSE => Operator::NOT_EQUALS,
            NumericRule::WHERE_EQUAL, NumericRule::OR_WHERE_EQUAL => Operator::EQUALS,
            NumericRule::WHERE_NOT_EQUAL, NumericRule::OR_WHERE_NOT_EQUAL => Operator::NOT_EQUALS,
            NumericRule::WHERE_GREATER_THAN, NumericRule::OR_WHERE_GREATER_THAN => Operator::GREATER_THAN,
            NumericRule::WHERE_LESS_THAN, NumericRule::OR_WHERE_LESS_THAN => Operator::LESS_THAN,
            NumericRule::WHERE_GREATER_THAN_OR_EQUAL, NumericRule::OR_WHERE_GREATER_THAN_OR_EQUAL => Operator::GREATER_THAN_OR_EQUALS,
            NumericRule::WHERE_LESS_THAN_OR_EQUAL, NumericRule::OR_WHERE_LESS_THAN_OR_EQUAL => Operator::LESS_THAN_OR_EQUALS,
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
            $rule instanceof NumericRule => (int) $value,
            $rule instanceof StringableRule && in_array($rule, [
                StringableRule::WHERE_LIKE, StringableRule::OR_WHERE_LIKE,
                StringableRule::WHERE_NOT_LIKE, StringableRule::OR_WHERE_NOT_LIKE,
            ]) => "%$value%",
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
