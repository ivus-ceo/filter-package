<?php

namespace Ivus\Filter\Services\Queries;

use Exception;
use Illuminate\Support\Facades\Log;
use Ivus\Filter\Enums\Rules\{Existables\RelationExistableRule, Imaginables\RelationImaginableRule};
use Ivus\Filter\DTOs\Queries\{Defaults\DefaultQueryDTO, Relations\RelationQueryDTO};
use Ivus\Filter\Exceptions\FilterQueryException;
use Ivus\Filter\Interfaces\Enums\Rules\RuleInterface;
use Ivus\Filter\Interfaces\Services\QueryServiceInterface;
use Ivus\Filter\Services\Filters\FilterService;
use Ivus\Filter\Services\Rules\RuleService;

class QueryService implements QueryServiceInterface
{
    const DEFAULT_QUERY_NAME = 'filters';
    const DEFAULT_UNION_SEPARATOR = '|';
    const DEFAULT_RULE_SEPARATOR = ':';
    const DEFAULT_COLUMN_SEPARATOR = '=';
    const DEFAULT_VALUE_SEPARATOR = ',';
    const DEFAULT_RELATION_SEPARATOR = '~';

    /**
     * Returns separators
     *
     * @return array{
     *     union: string,
     *     rule: string,
     *     column: string,
     *     value: string,
     *     relation: string,
     * }
     */
    public static function getQuerySeparators(): array
    {
        return [
            'union' => config('filters.union_separator', static::DEFAULT_UNION_SEPARATOR),
            'rule' => config('filters.rule_separator', static::DEFAULT_RULE_SEPARATOR),
            'column' => config('filters.column_separator', static::DEFAULT_COLUMN_SEPARATOR),
            'value' => config('filters.value_separator', static::DEFAULT_VALUE_SEPARATOR),
            'relation' => config('filters.relation_separator', static::DEFAULT_RELATION_SEPARATOR),
        ];
    }

    /**
     * Get sanitized string
     *
     * @param string $query
     * @return string
     */
    public static function getSanitizedQuery(string $query): string
    {
        $query = strip_tags($query);
        $query = htmlspecialchars($query, ENT_QUOTES, 'UTF-8', false);
        $query = str_replace(' ', '', $query);

        return $query;
    }

    /**
     * Get filterables from query string
     *
     * @param string|null $query
     * @return array<DefaultQueryDTO|RelationQueryDTO>
     */
    public static function getQueryDTOs(string $query = null): array
    {
        $queryDTOs = [];
        $separators = static::getQuerySeparators();
        $queries = static::getSanitizedQuery(
            $query ?? request()->query(config('filters.query_name', static::DEFAULT_QUERY_NAME), '')
        );

        try {
            // Parse queries
            foreach (explode($separators['union'], $queries) as $query) {
                // Check if first rule is relationable
                $ruleName = explode($separators['rule'], $query)[0];
                $rule = RuleService::getResolvedRule($ruleName);
                if (!$rule instanceof RuleInterface) continue;

                // If rule is relationable
                if (in_array(get_class($rule), [RelationExistableRule::class, RelationImaginableRule::class])) {
                    $relationables = explode($separators['relation'], $query);
                    $relationQuery = static::getDecodedQuery($relationables[0]);
                    $defaultQueryDTOs = [];
                    unset($relationables[0]);
                    // Subqueries for relation
                    foreach ($relationables as $relationable) {
                        $defaultQuery = static::getDecodedQuery($relationable);
                        $defaultQueryRule = RuleService::getResolvedRule($defaultQuery['rule']);

                        $defaultQueryDTOs[] = new DefaultQueryDTO(
                            rule: $defaultQueryRule,
                            method: FilterService::getMethodByRule($defaultQueryRule, $defaultQuery['columnName']),
                            columnName: $defaultQuery['columnName'],
                            columnOperator: FilterService::getOperatorByRule($defaultQueryRule),
                            columnValue: FilterService::getValueByRule($defaultQueryRule, $defaultQuery['columnValue']),
                        );
                    }

                    $queryDTOs[] = new RelationQueryDTO(
                        rule: $rule,
                        method: FilterService::getMethodByRule($rule, $relationQuery['columnName']),
                        columnName: $relationQuery['columnName'],
                        columnOperator: FilterService::getOperatorByRule($rule),
                        columnValue: FilterService::getValueByRule($rule, $relationQuery['columnValue']),
                        defaultQueryDTOs: $defaultQueryDTOs
                    );

                    continue;
                }

                $defaultQuery = static::getDecodedQuery($query);
                $queryDTOs[] = new DefaultQueryDTO(
                    rule: $rule,
                    method: FilterService::getMethodByRule($rule, $defaultQuery['columnName']),
                    columnName: $defaultQuery['columnName'],
                    columnOperator: FilterService::getOperatorByRule($rule),
                    columnValue: FilterService::getValueByRule($rule, $defaultQuery['columnValue']),
                );
            }
        } catch (Exception $exception) {
            if (config('filters.debug', false))
                Log::error($exception->getMessage());
            return [];
        }

        return $queryDTOs;
    }

    /**
     * Get decoded query
     *
     * @param string $query
     * @return array
     * @throws FilterQueryException
     */
    private static function getDecodedQuery(string $query): array
    {
        $separators = static::getQuerySeparators();

        list($ruleName, $columnable) = explode($separators['rule'], $query);
        $columnable = explode($separators['column'], $columnable);
        $columnName = $columnable[0];
        $columnValue = $columnable[1] ?? null;

        if (empty($ruleName) || empty($columnName))
            throw new FilterQueryException(trans('filters::errors.queries.E1', ['query' => $query]));

        return [
            'rule' => $ruleName,
            'columnName' => $columnName,
            'columnValue' => $columnValue
        ];
    }
}
