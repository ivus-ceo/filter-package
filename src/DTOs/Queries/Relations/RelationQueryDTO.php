<?php

namespace Ivus\Filter\DTOs\Queries\Relations;

use Ivus\Filter\DTOs\Queries\Defaults\DefaultQueryDTO;
use Ivus\Filter\Interfaces\DTOs\{QueryDTOInterface};
use Ivus\Filter\Interfaces\Enums\{Operators\OperatorInterface, Rules\RuleInterface};

readonly class RelationQueryDTO implements QueryDTOInterface
{
    /**
     * @param RuleInterface $rule
     * @param string $method
     * @param string $columnName
     * @param OperatorInterface|null $columnOperator
     * @param array|int|string|bool|null $columnValue
     * @param array<DefaultQueryDTO> $defaultQueryDTOs
     */
    public function __construct(
        public RuleInterface $rule,
        public string $method,
        public string $columnName,
        public ?OperatorInterface $columnOperator = null,
        public array | int | string | bool | null $columnValue = null,
        public array $defaultQueryDTOs = [],
    )
    {}
}
