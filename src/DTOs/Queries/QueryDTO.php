<?php

namespace Ivus\Filter\DTOs\Queries;

use Ivus\DataTransferObject\DTOs\BaseDTO;
use Ivus\Filter\Interfaces\DTOs\{QueryDTOInterface};
use Ivus\Filter\Interfaces\Enums\{RuleInterface, OperatorInterface};

class QueryDTO extends BaseDTO implements QueryDTOInterface
{
    public function __construct(
        public readonly RuleInterface $rule,
        public readonly string $method,
        public readonly string $columnName,
        public readonly ?OperatorInterface $columnOperator = null,
        public readonly array | int | string | bool | null $columnValue = null,
    )
    {}
}
