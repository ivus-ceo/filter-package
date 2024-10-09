<?php

namespace Ivus\Filter\DTOs\Queries;

use Ivus\DataTransferObject\DTOs\BaseDTO;
use Ivus\Filter\Enums\Rules\{ArrayableRule, CustomableRule, NullableRule, StringableRule};

class QueryDTO extends BaseDTO
{
    public function __construct(
        public readonly ArrayableRule | CustomableRule | NullableRule | StringableRule $rule,
        public readonly string $column,
        public readonly string | int | array | null $value,
    )
    {}
}
