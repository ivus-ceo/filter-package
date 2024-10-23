<?php

namespace Ivus\Filter\DTOs\Queries\Defaults;

use Ivus\Filter\Interfaces\DTOs\{QueryDTOInterface};
use Ivus\Filter\Interfaces\Enums\{Operators\OperatorInterface, Rules\RuleInterface};

readonly class DefaultQueryDTO implements QueryDTOInterface
{
    /**
     * @param RuleInterface $rule
     * @param string $method
     * @param string $columnName
     * @param OperatorInterface|null $columnOperator
     * @param array|int|string|bool|null $columnValue
     */
    public function __construct(
        public RuleInterface $rule,
        public string $method,
        public string $columnName,
        public ?OperatorInterface $columnOperator = null,
        public array | int | string | bool | null $columnValue = null,
    )
    {}
}
