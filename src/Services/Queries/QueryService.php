<?php

namespace Ivus\Filter\Services\Queries;

use Exception;
use Illuminate\Support\Facades\Log;
use Ivus\Filter\DTOs\Queries\QueryDTO;
use Ivus\Filter\Enums\Rules\{ArrayableRule, CustomableRule, NullableRule, StringableRule};
use Ivus\Filter\Exceptions\FilterQueryException;
use Ivus\Filter\Interfaces\Enums\ImaginableBuilderRuleInterface;
use Ivus\Filter\Interfaces\Enums\RuleInterface;
use Ivus\Filter\Interfaces\Services\QueryServiceInterface;
use Ivus\Filter\Services\Builders\BuilderService;
use Ivus\Filter\Services\Filters\FilterService;
use Ivus\Filter\Services\Rules\RuleService;

class QueryService implements QueryServiceInterface
{
    const DEFAULT_QUERY_NAME = 'filters';
    const DEFAULT_UNION_SEPARATOR = '|';
    const DEFAULT_RULE_SEPARATOR = ':';
    const DEFAULT_COLUMN_SEPARATOR = '=';
    const DEFAULT_VALUE_SEPARATOR = ',';

    /**
     * Returns separators
     *
     * @return array{
     *     union: string,
     *     rule: string,
     *     column: string,
     *     value: string,
     * }
     */
    public static function getSeparators(): array
    {
        return [
            'union' => config('filters.union_separator', static::DEFAULT_UNION_SEPARATOR),
            'rule' => config('filters.rule_separator', static::DEFAULT_RULE_SEPARATOR),
            'column' => config('filters.column_separator', static::DEFAULT_COLUMN_SEPARATOR),
            'value' => config('filters.value_separator', static::DEFAULT_VALUE_SEPARATOR),
        ];
    }

    /**
     * Get sanitized string
     *
     * @param string $input
     * @return string
     */
    public static function getSanitizedString(string $input): string
    {
        $input = strip_tags($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8', false);

        return $input;
    }

    /**
     * Get filterables from query string
     *
     * @return array<QueryDTO>
     */
    public static function getQueryDTOs(): array
    {
        $queryDTOs = [];
        $separators = static::getSeparators();
        $queries = static::getSanitizedString(
            request()->query(config('filters.query_name', static::DEFAULT_QUERY_NAME)) ?? ''
        );

        try {
            // Parse queries
            foreach (explode($separators['union'], $queries) as $query) {
                list($ruleName, $columnable) = explode($separators['rule'], $query);
                $columnable = explode($separators['column'], $columnable);
                $columnName = $columnable[0];
                $columnValue = $columnable[1] ?? null;

                if (empty($ruleName) || empty($columnName))
                    throw new FilterQueryException(trans('filters::errors.queries.E1', ['query' => $query]));

                $rule = RuleService::getResolvedRule($ruleName);

                if (empty($rule))
                    throw new FilterQueryException(trans('filters::errors.queries.E2', ['rule' => $ruleName]));

                $method = ($rule instanceof CustomableRule) ? $columnName : FilterService::getMethodByRule($rule);

                $queryDTOs[] = new QueryDTO(
                    rule: $rule,
                    method: $method,
                    columnName: $columnName,
                    columnOperator: FilterService::getOperatorByRule($rule),
                    columnValue: FilterService::getValueByRule($rule, $columnValue),
                );
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return [];
        }

        return $queryDTOs;
    }
}
