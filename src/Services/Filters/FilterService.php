<?php

namespace Ivus\Filter\Services\Filters;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\{Collection, Str};
use Ivus\Filter\DTOs\Queries\{Defaults\DefaultQueryDTO, Relations\RelationQueryDTO};
use Ivus\Filter\Enums\Operators\Operator;
use Ivus\Filter\Enums\Rules\Existables\{
    ArrayExistableRule,
    BooleanExistableRule,
    CustomExistableRule,
    DateExistableRule,
    IntegerExistableRule,
    RelationExistableRule,
    SearchExistableRule,
    StringExistableRule
};
use Ivus\Filter\Enums\Rules\Imaginables\{
    ArrayImaginableRule,
    BooleanImaginableRule,
    CustomImaginableRule,
    DateImaginableRule,
    IntegerImaginableRule,
    RelationImaginableRule,
    SearchImaginableRule,
    StringImaginableRule
};
use Ivus\Filter\Interfaces\Enums\Rules\{
    Existables\ExistableRuleInterface,
    Imaginables\ImaginableRuleInterface
};
use Ivus\Filter\Interfaces\Enums\Rules\RuleInterface;
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
     * @param string|null $query
     * @param bool $isPaginated
     * @param bool $isBuilder
     * @return LengthAwarePaginator|Collection|Builder
     */
    public function filter(string $query = null, bool $isPaginated = true, bool $isBuilder = false): LengthAwarePaginator | Collection | Builder
    {
        $queryDTOs = QueryService::getQueryDTOs($query);

//        try {
            foreach ($queryDTOs as $queryDTO) {
                switch ($queryDTO->rule) {
                    case RelationExistableRule::WITH_SUM:
                    case RelationExistableRule::WITH_MIN:
                    case RelationExistableRule::WITH_MAX:
                    case RelationExistableRule::WITH_AVG:
                        $this->builder->{$queryDTO->method}($queryDTO->columnName, $queryDTO->columnValue);
                        break;
                    case RelationExistableRule::WITH_EXISTS:
                    case RelationExistableRule::WITH_COUNT:
                        $this->builder->{$queryDTO->method}($queryDTO->columnName);
                        break;
                    case RelationExistableRule::WHERE_HAS:
                    case RelationExistableRule::OR_WHERE_HAS:
                    case RelationImaginableRule::WHERE_HAS_EQUAL:
                    case RelationImaginableRule::OR_WHERE_HAS_EQUAL:
                    case RelationImaginableRule::WHERE_HAS_NOT_EQUAL:
                    case RelationImaginableRule::OR_WHERE_HAS_NOT_EQUAL:
                    case RelationImaginableRule::WHERE_HAS_GREATER_THAN:
                    case RelationImaginableRule::OR_WHERE_HAS_GREATER_THAN:
                    case RelationImaginableRule::WHERE_HAS_LESS_THAN:
                    case RelationImaginableRule::OR_WHERE_HAS_LESS_THAN:
                    case RelationImaginableRule::WHERE_HAS_GREATER_THAN_OR_EQUAL:
                    case RelationImaginableRule::OR_WHERE_HAS_GREATER_THAN_OR_EQUAL:
                    case RelationImaginableRule::WHERE_HAS_LESS_THAN_OR_EQUAL:
                    case RelationImaginableRule::OR_WHERE_HAS_LESS_THAN_OR_EQUAL:
                        $this->builder->{$queryDTO->method}(
                            $queryDTO->columnName,
                            function ($query) use ($queryDTO) {
                                foreach ($queryDTO->defaultQueryDTOs as $defaultQueryDTO) {
                                    $column = $queryDTO->columnName . '.' . $defaultQueryDTO->columnName;
                                    // Skip non-searchable columns
                                    // if (!in_array($column, $this->columns))
                                    //     continue;

                                    if ($defaultQueryDTO->columnOperator instanceof Operator) {
                                        $query->{$defaultQueryDTO->method}(
                                            $column,
                                            $defaultQueryDTO->columnOperator->value,
                                            $defaultQueryDTO->columnValue
                                        );
                                    } else if (!empty($defaultQueryDTO->columnValue)) {
                                        $query->{$defaultQueryDTO->method}(
                                            $column,
                                            $defaultQueryDTO->columnValue
                                        );
                                    } else {
                                        $query->{$defaultQueryDTO->method}(
                                            $column
                                        );
                                    }
                                }
                            },
                            ($queryDTO->columnOperator instanceof Operator) ? $queryDTO->columnOperator->value : Operator::GREATER_THAN_OR_EQUAL->value,
                            ($queryDTO->columnOperator instanceof Operator) ? $queryDTO->columnValue : 1,
                        );
                        break;
                    case RelationExistableRule::WHERE_DOESNT_HAVE:
                    case RelationExistableRule::OR_WHERE_DOESNT_HAVE:
                        $this->builder->{$queryDTO->method}(
                            $queryDTO->columnName,
                            function ($query) use ($queryDTO) {
                                foreach ($queryDTO->defaultQueryDTOs as $defaultQueryDTO) {
                                    $column = $queryDTO->columnName . '.' . $defaultQueryDTO->columnName;
                                    // Skip non-searchable columns
                                    // if (!in_array($column, $this->columns))
                                    //     continue;

                                    if ($defaultQueryDTO->columnOperator instanceof Operator) {
                                        $query->{$defaultQueryDTO->method}(
                                            $column,
                                            $defaultQueryDTO->columnOperator->value,
                                            $defaultQueryDTO->columnValue
                                        );
                                    } else if (!empty($defaultQueryDTO->columnValue)) {
                                        $query->{$defaultQueryDTO->method}(
                                            $column,
                                            $defaultQueryDTO->columnValue
                                        );
                                    } else {
                                        $query->{$defaultQueryDTO->method}(
                                            $column
                                        );
                                    }
                                }
                            },
                        );
                        break;
                    default:
                        if ($queryDTO->columnOperator instanceof Operator) {
                            $this->builder->{$queryDTO->method}(
                                $queryDTO->columnName,
                                $queryDTO->columnOperator->value,
                                $queryDTO->columnValue
                            );
                        } else if (!empty($queryDTO->columnValue)) {
                            $this->builder->{$queryDTO->method}(
                                $queryDTO->columnName,
                                $queryDTO->columnValue
                            );
                        } else {
                            $this->builder->{$queryDTO->method}(
                                $queryDTO->columnName
                            );
                        }
                        break;
                }
            }

//            dd($this->builder->toSql(), $this->builder->getBindings());

            return ($isBuilder)   ? $this->builder : (
                   ($isPaginated) ? $this->builder->paginate($this->getLengthAwarePaginatorPerPage()) :
                                    $this->builder->get()
            );
//        } catch (Exception $exception) {
//            Log::error(trans('filters::errors.filters.E1', ['page' => request()->getUri(), 'error' => $exception->getMessage()]));
//            return collect();
//        }
    }

    /**
     * Get method by rule
     *
     * @param RuleInterface $rule
     * @param string $customInvokeMethod
     * @return string
     */
    public static function getMethodByRule(RuleInterface $rule, string $customInvokeMethod): string
    {
        return match (true) {
            $rule instanceof CustomExistableRule || $rule instanceof CustomImaginableRule => $customInvokeMethod,
            $rule instanceof ExistableRuleInterface => $rule->value,
            in_array($rule, [
                RelationImaginableRule::WHERE_HAS_EQUAL,
                RelationImaginableRule::OR_WHERE_HAS_EQUAL,
                RelationImaginableRule::WHERE_HAS_NOT_EQUAL,
                RelationImaginableRule::OR_WHERE_HAS_NOT_EQUAL,
                RelationImaginableRule::WHERE_HAS_GREATER_THAN,
                RelationImaginableRule::OR_WHERE_HAS_GREATER_THAN,
                RelationImaginableRule::WHERE_HAS_LESS_THAN,
                RelationImaginableRule::OR_WHERE_HAS_LESS_THAN,
                RelationImaginableRule::WHERE_HAS_GREATER_THAN_OR_EQUAL,
                RelationImaginableRule::OR_WHERE_HAS_GREATER_THAN_OR_EQUAL,
                RelationImaginableRule::WHERE_HAS_LESS_THAN_OR_EQUAL,
                RelationImaginableRule::OR_WHERE_HAS_LESS_THAN_OR_EQUAL,
            ]) => 'whereHas',
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
        return match (true) {
            Str::contains($rule->name, 'GREATER_THAN_OR_EQUAL') => Operator::GREATER_THAN_OR_EQUAL,
            Str::contains($rule->name, 'LESS_THAN_OR_EQUAL') => Operator::LESS_THAN_OR_EQUAL,
            Str::contains($rule->name, 'GREATER_THAN') => Operator::GREATER_THAN,
            Str::contains($rule->name, 'LESS_THAN') => Operator::LESS_THAN,
            Str::contains($rule->name, 'NOT_EQUAL') => Operator::NOT_EQUAL,
            Str::contains($rule->name, 'EQUAL') => Operator::EQUAL,
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
        $separators = QueryService::getQuerySeparators();

        return match (true) {
            $rule instanceof ArrayExistableRule || $rule instanceof ArrayImaginableRule => (!empty($value)) ? explode($separators['value'], $value) : [],
            $rule instanceof BooleanExistableRule || $rule instanceof BooleanImaginableRule => Str::contains($rule->name, 'TRUE'),
            $rule instanceof DateExistableRule || $rule instanceof DateImaginableRule => Carbon::parse($value),
            $rule instanceof IntegerExistableRule || $rule instanceof IntegerImaginableRule => (int) $value,
            $rule instanceof SearchExistableRule || $rule instanceof SearchImaginableRule => "%$value%",
            $rule instanceof StringExistableRule, $rule instanceof StringImaginableRule,
            $rule instanceof RelationExistableRule, $rule instanceof RelationImaginableRule,
            $rule instanceof CustomExistableRule, $rule instanceof CustomImaginableRule => $value,
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
