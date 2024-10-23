<?php

namespace Ivus\Filter\Interfaces\DTOs;

use Ivus\Filter\Interfaces\Enums\{Operators\OperatorInterface, Rules\RuleInterface};

interface QueryDTOInterface
{
    public function __construct(
        RuleInterface $rule,
        string $method,
        string $columnName,
        ?OperatorInterface $columnOperator,
        array | int | string | bool | null $columnValue,
    );
}
