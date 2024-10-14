<?php

namespace Ivus\Filter\Enums\Rules;

use Ivus\Filter\Interfaces\Enums\{RuleInterface, ExistingBuilderRuleInterface};

enum RelationableRule: string implements RuleInterface, ExistingBuilderRuleInterface
{
    case HAS = 'has';
    case OR_HAS = 'orHas';
    case DOESNT_HAVE = 'doesntHave';
    case OR_DOESNT_HAVE = 'orDoesntHave';

    case WHERE_HAS = 'whereHas';
    case OR_WHERE_HAS = 'orWhereHas';
    case WHERE_DOESNT_HAVE = 'whereDoesntHave';
    case OR_WHERE_DOESNT_HAVE = 'orWhereDoesntHave';

    case WITH_COUNT = 'withCount';
    case WITH_SUM = 'withSum';
    case WITH_MIN = 'withMin';
    case WITH_MAX = 'withMax';
    case WITH_AVG = 'withAvg';
    case WITH_EXISTS = 'withExists';
}
