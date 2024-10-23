<?php

use Ivus\Filter\DTOs\Queries\{Defaults\DefaultQueryDTO, Relations\RelationQueryDTO};
use Ivus\Filter\Services\Queries\QueryService;

if (!function_exists('getQuerySeparators'))
{
    function getQuerySeparators(): array
    {
        return QueryService::getQuerySeparators();
    }
}

if (!function_exists('getSanitizedQuery'))
{
    function getSanitizedQuery(string $query): string
    {
        return QueryService::getSanitizedQuery($query);
    }
}

if (!function_exists('getQueryDTOs'))
{
    function getQueryDTOs(string $query = null): array
    {
        return QueryService::getQueryDTOs($query);
    }
}
