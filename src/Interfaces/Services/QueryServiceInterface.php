<?php

namespace Ivus\Filter\Interfaces\Services;

use Ivus\Filter\DTOs\Queries\{Defaults\DefaultQueryDTO, Relations\RelationQueryDTO};

interface QueryServiceInterface
{
    public static function getQuerySeparators(): array;
    public static function getSanitizedQuery(string $query): string;
    /* @return array<DefaultQueryDTO|RelationQueryDTO> */
    public static function getQueryDTOs(): array;
}
