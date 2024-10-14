<?php

namespace Ivus\Filter\Interfaces\Services;

use Ivus\Filter\DTOs\Queries\QueryDTO;

interface QueryServiceInterface
{
    public static function getSeparators(): array;
    public static function getSanitizedString(string $input): string;
    /* @return array<QueryDTO> */
    public static function getQueryDTOs(): array;
}
