<?php

namespace Ivus\Filter\DTOs\Queries;

use Ivus\DataTransferObject\DTOs\BaseDTO;
use Ivus\Filter\Enums\Rules;

class QueryDTO extends BaseDTO
{
    public function __construct(
        public readonly Rules $rule,
        public readonly string $column,
        public readonly string | int | array $value,
    )
    {}
}
