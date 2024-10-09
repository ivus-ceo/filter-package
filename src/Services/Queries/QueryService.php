<?php

namespace Ivus\Filter\Services\Queries;

use Exception;
use Ivus\Filter\DTOs\Queries\QueryDTO;
use Ivus\Filter\Enums\Rules\{ArrayableRule, CustomableRule, NullableRule, StringableRule};
use Ivus\Filter\Services\Rules\RuleService;

class QueryService
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
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8', false);
        $input = strip_tags($input);

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
        $filterQueries = [];
        $separators = static::getSeparators();
        $queries = request()->query(config('filters.query_name', static::DEFAULT_QUERY_NAME));

        try {
            foreach (explode($separators['union'], $queries) as $query) {
                list($rule, $columnable) = explode($separators['rule'], $query);
                $array = explode($separators['column'], $columnable);
                $column = $array[0];
                $value = $array[1] ?? null;
                if (empty($rule) || empty($column)) continue;
                // Sanitize current inputs
                $filterQueries[] = [
                    'rule'   => static::getSanitizedString($rule),
                    'column' => static::getSanitizedString($column),
                    'value'  => (!empty($value)) ? static::getSanitizedString($value) : null,
                ];
            }

            foreach ($filterQueries as $filterQuery) {
                $rule = RuleService::getResolvedRule($filterQuery['rule']);
                if (empty($rule)) continue;

                $queryDTOs[] = new QueryDTO(
                    rule: $rule,
                    column: $filterQuery['column'],
                    value: static::getValueByRule($rule, $filterQuery['value']),
                );
            }
        } catch (Exception $exception) {
            return [];
        }

        return $queryDTOs;
    }

    /**
     * @param ArrayableRule|CustomableRule|NullableRule|StringableRule $rule
     * @param string|int|array|null $value
     * @return string|array|null
     */
    public static function getValueByRule(
        ArrayableRule | CustomableRule | NullableRule | StringableRule $rule,
        string | int | array | null $value
    ): string | array | null
    {
        $separators = static::getSeparators();

        return match (get_class($rule)) {
            ArrayableRule::class => explode($separators['value'], $value),
            CustomableRule::class => $value,
            StringableRule::class => in_array($rule, [
                StringableRule::WHERE_LIKE,
                StringableRule::WHERE_NOT_LIKE,
                StringableRule::OR_WHERE_LIKE,
                StringableRule::OR_WHERE_NOT_LIKE
            ]) ? '%' . $value . '%' : (string) $value,
            default => null,
        };
    }
}
