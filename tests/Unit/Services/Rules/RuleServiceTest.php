<?php

namespace Ivus\Filter\Tests\Unit\Services\Rules;

use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\File;
use Ivus\Filter\Enums\Rules\ArrayableRule;
use Ivus\Filter\Services\Rules\RuleService;
use Mockery;

class RuleServiceTest extends TestCase
{
    public function test_get_resolved_rule_returns_correct_rule(): void
    {
        // Mock the File::files method to return a list of file names
        File::shouldReceive('files')
            ->with(__DIR__ . '../../../../src/Enums/Rules/')
            ->andReturn([Mockery::mock(['getFilename' => 'SomeRule.php'])]);

        // Mock the SomeRule class to return an instance when tryFrom is called
        $mockRule = Mockery::mock(ArrayableRule::class);
        $mockRule->shouldReceive('tryFrom')
            ->with('whereIn')
            ->andReturn($mockRule);

        // Register the mock
        $this->app->instance(ArrayableRule::class, $mockRule);

        // Call the method under test
        $resolvedRule = RuleService::getResolvedRule('whereIn');

        // Assert that the resolved rule is an instance of SomeRule
        $this->assertInstanceOf(ArrayableRule::class, $resolvedRule);
    }
}
