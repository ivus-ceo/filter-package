<?php

namespace Ivus\Filter\Tests\Unit\Services\Queries;

use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Ivus\Filter\DTOs\Queries\Defaults\DefaultQueryDTO;
use Ivus\Filter\DTOs\Queries\QueryDTO;
use Ivus\Filter\DTOs\Queries\Relations\RelationQueryDTO;
use Ivus\Filter\Enums\Operators\Operator;
use Ivus\Filter\Enums\Rules\Existables\ArrayExistableRule;
use Ivus\Filter\Enums\Rules\Existables\BooleanExistableRule;
use Ivus\Filter\Enums\Rules\Existables\CustomExistableRule;
use Ivus\Filter\Enums\Rules\Existables\DateExistableRule;
use Ivus\Filter\Enums\Rules\Imaginables\ArrayImaginableRule;
use Ivus\Filter\Enums\Rules\Imaginables\BooleanImaginableRule;
use Ivus\Filter\Enums\Rules\Imaginables\CustomImaginableRule;
use Ivus\Filter\Enums\Rules\Imaginables\DateImaginableRule;
use Ivus\Filter\Interfaces\Services\FilterServiceInterface;
use Ivus\Filter\Services\Filters\FilterService;
use Ivus\Filter\Services\Queries\QueryService;
use Ivus\Filter\Services\Rules\RuleService;
use Mockery;

class QueryServiceTest extends TestCase
{
    public function test_get_separators_returns_array(): void
    {
        // Call the getQuerySeparators method
        $result = QueryService::getQuerySeparators();

        // Check that the result is an array with 5 elements
        $this->assertIsArray($result);
        $this->assertCount(5, $result);

        $expectedKeys = ['union', 'rule', 'column', 'value', 'relation'];
        foreach ($expectedKeys as $key) {
            // Check the array contains correct keys
            $this->assertArrayHasKey($key, $result);
            // Check values from config or defaults
            $this->assertEquals(config("filters.{$key}_separator", QueryService::{'DEFAULT_' . strtoupper($key) . '_SEPARATOR'}), $result[$key]);
        }
    }

    public function test_get_sanitized_string_removes_html_tags(): void
    {
        // Input string with HTML tags
        $input = '<p>This is a <strong>test</strong> string</p>';

        // Call the method
        $result = QueryService::getSanitizedQuery($input);

        // Expect HTML tags to be removed
        $expected = 'Thisisateststring';
        $this->assertEquals($expected, $result);

        $query = '<b>bold</b><script>alert("test")</script>';

        // Call the method
        $result = QueryService::getSanitizedQuery($query);

        // Expect HTML tags to be removed
        $expected = 'boldalert(&quot;test&quot;)';
        $this->assertEquals($expected, $result);
    }

    public function test_get_sanitized_string_encodes_special_characters(): void
    {
        // Input string with quotes and ampersand
        $input = '"This is a \'test\' & string"';

        // Call the method
        $result = QueryService::getSanitizedQuery($input);

        // Expected output where quotes and ampersand are encoded
        $expected = '&quot;Thisisa&#039;test&#039;&amp;string&quot;';
        $this->assertEquals($expected, $result);
    }

    public function test_get_sanitized_string_safe_input_remains_unchanged(): void
    {
        // Safe string without HTML or special characters
        $input = 'ThisIsASafeString';

        // Call the method
        $result = QueryService::getSanitizedQuery($input);

        // Expect the string to remain unchanged
        $this->assertEquals($input, $result);

        // Safe string without HTML or special characters
        $input = 'whereHas:rooms=5~where:roominess=1~whereNotNull:published_at|whereEqualTrue:is_built|whereBetween:developer_id=1,10';

        // Call the method
        $result = QueryService::getSanitizedQuery($input);

        // Expect the string to remain unchanged
        $this->assertEquals($input, $result);
    }

    public function test_get_sanitized_string_prevents_xss(): void
    {
        // Input string with potential XSS
        $input = '<script>alert("XSS")</script>';

        // Call the method
        $result = QueryService::getSanitizedQuery($input);

        // Expect script tags to be removed
        $expected = 'alert(&quot;XSS&quot;)';  // The script tag is removed, but content is encoded
        $this->assertEquals($expected, $result);

        $input = "<script>alert('XSS')</script>";

        // Call the method
        $result = QueryService::getSanitizedQuery($input);

        // Expect script tags to be removed
        $expected = 'alert(&#039;XSS&#039;)';  // The script tag is removed, but content is encoded
        $this->assertEquals($expected, $result);
    }

    public function test_get_sanitized_string_handles_empty_string(): void
    {
        // Empty input
        $input = '';

        // Call the method
        $result = QueryService::getSanitizedQuery($input);

        // Expect an empty result
        $this->assertEquals('', $result);
    }

    public function test_get_sanitized_string_filters_valid_input(): void
    {
        $input = 'whereIn:id=1,2,3|whereIsTrue:boolean|whereNotNull:updated_at|whereGreaterThan:number=5';
        $expected = 'whereIn:id=1,2,3|whereIsTrue:boolean|whereNotNull:updated_at|whereGreaterThan:number=5';
        $result = QueryService::getSanitizedQuery($input);

        $this->assertEquals($expected, $result);
    }

    public function test_get_query_dtos_with_array_rules(): void
    {
        $this->assertRules([ArrayExistableRule::class, ArrayImaginableRule::class], function ($case) {
            $expectedRule = $case->value;
            $expectedColumn = fake()->word();
            $expectedOperator = null;
            $expectedValue = [];

            // Parsing the filter string
            $result = QueryService::getQueryDTOs("{$expectedRule}:{$expectedColumn}");
            $this->assertCount(1, $result);
            $this->assertDefaultQueryDTO($result[0], $expectedRule, $expectedColumn, $expectedOperator, $expectedValue);

            // Now parsing with actual values
            for ($index = 0; $index < fake()->numberBetween(1, 10); $index++)
                $expectedValue[] = (fake()->boolean()) ? fake()->word() : fake()->randomDigit();

            // Parsing the filter string
            $result = QueryService::getQueryDTOs("{$expectedRule}:{$expectedColumn}=" . implode(',', $expectedValue));
            $this->assertDefaultQueryDTO($result[0], $expectedRule, $expectedColumn, $expectedOperator, $expectedValue);
        });
    }

    public function test_get_query_dtos_with_boolean_rules(): void
    {
        $this->assertRules([BooleanExistableRule::class, BooleanImaginableRule::class], function ($case) {
            $expectedRule = $case->value;
            $expectedColumn = fake()->word();
            $expectedOperator = FilterService::getOperatorByRule($case);
            $expectedValue = FilterService::getValueByRule($case, null);

            $this->assertInstanceOf(Operator::class, $expectedOperator);
            $expectedOperator = $expectedOperator->value;
            $this->assertIsString($expectedOperator);
            $this->assertIsBool($expectedValue);

            // Parsing the filter string
            $result = QueryService::getQueryDTOs("{$expectedRule}:{$expectedColumn}");
            $this->assertCount(1, $result);
            $this->assertDefaultQueryDTO($result[0], $expectedRule, $expectedColumn, $expectedOperator, $expectedValue);

            // Fake data that should not be used
            $fakeValues = [];
            for ($index = 0; $index < fake()->numberBetween(1, 10); $index++)
                $fakeValues[] = (fake()->boolean()) ? fake()->word() : fake()->randomDigit();

            // Parsing the filter string
            $result = QueryService::getQueryDTOs("{$expectedRule}:{$expectedColumn}=" . implode(',', $fakeValues));
            $this->assertDefaultQueryDTO($result[0], $expectedRule, $expectedColumn, $expectedOperator, $expectedValue);
        });
    }

    public function test_get_query_dtos_with_custom_rules(): void
    {
        $this->assertRules([CustomExistableRule::class, CustomImaginableRule::class], function ($case) {
            $expectedRule = $case->value;
            $expectedMethod = fake()->word();
            $expectedOperator = null;
            $expectedParam = null;

            // Parsing the filter string
            $result = QueryService::getQueryDTOs("{$expectedRule}:{$expectedMethod}");
            $this->assertCount(1, $result);
            $this->assertDefaultQueryDTO($result[0], $expectedRule, $expectedMethod, $expectedOperator, $expectedParam);

            // Now parsing with actual values
            for ($index = 0; $index < fake()->numberBetween(1, 10); $index++)
                $expectedParam[] = (fake()->boolean()) ? fake()->word() : fake()->randomDigit();
            $expectedParam = implode(',', $expectedParam);

            // Parsing the filter string
            $result = QueryService::getQueryDTOs("{$expectedRule}:{$expectedMethod}={$expectedParam}");
            $this->assertDefaultQueryDTO($result[0], $expectedRule, $expectedMethod, $expectedOperator, $expectedParam);
        });
    }

    public function test_get_query_dtos_with_date_rules(): void
    {
        $this->assertRules([DateExistableRule::class, DateImaginableRule::class], function ($case) {
            $expectedRule = $case->value;
            $expectedColumn = fake()->word();
            $expectedOperator = null;
            $expectedValue = null;

            // Parsing the filter string
            $result = QueryService::getQueryDTOs("{$expectedRule}:{$expectedColumn}");
            $this->assertCount(1, $result);
            $this->assertDefaultQueryDTO($result[0], $expectedRule, $expectedColumn, $expectedOperator, $expectedValue);

            // Now parsing with actual values
            for ($index = 0; $index < fake()->numberBetween(1, 10); $index++)
                $expectedValue[] = (fake()->boolean()) ? fake()->word() : fake()->randomDigit();

            // Parsing the filter string
            $result = QueryService::getQueryDTOs("{$expectedRule}:{$expectedColumn}=" . implode(',', $expectedValue));
            $this->assertDefaultQueryDTO($result[0], $expectedRule, $expectedColumn, $expectedOperator, $expectedValue);
        });
    }





    public function test_get_query_dtos_returns_correct_dtos(): void
    {
        // Mocking request query string
        $this->mockRequestQuery('?filters=whereIn:id=1,2,3|whereTrue:boolean|whereNotNull:nullable|whereEqual:numeric=5|whereLike:name=stringable');

        // Call the method
        $result = QueryService::getQueryDTOs();

        // Assert that the result contains correct number of QueryDTOs
        $this->assertCount(5, $result);

        // Validate each QueryDTO
        $this->assertQueryDTO($result[0], 'id', 'whereIn', null, [1, 2, 3]);
        $this->assertQueryDTO($result[1], 'boolean', 'whereTrue', '=', true);
        $this->assertQueryDTO($result[2], 'nullable', 'whereNotNull', null, null);
        $this->assertQueryDTO($result[3], 'numeric', 'whereEqual', '=', 5);
        $this->assertQueryDTO($result[4], 'name', 'whereLike', null, '%stringable%');
    }

    public function test_get_query_dtos_returns_empty_on_invalid_query(): void
    {
        // Mock an invalid query string
        $this->mockRequestQuery('?filters=invalidRule:id');

        // Call the method
        $result = QueryService::getQueryDTOs();

        // Assert that the result is an empty array due to invalid query
        $this->assertEmpty($result);
    }

    public function test_get_query_dtos_logs_error_on_exception(): void
    {
        // Mock an invalid query string
        $this->mockRequestQuery('?filters=invalidRule:id');

        // Mock the Log facade to check if error was logged
        Log::shouldReceive('error')->once();

        // Call the method
        $result = QueryService::getQueryDTOs();

        // Assert that the result is an empty array due to the exception
        $this->assertEmpty($result);
    }

    private function mockRequestQuery(string $queryString): void
    {
        // Bind the request instance to the container
        $this->app->instance('request', Request::create('http://example.com' . $queryString, 'GET'));
    }

    private function assertQueryDTO(
        $queryDTO,
        string $expectedRule,
        string $expectedColumn,
        string $expectedOperator = null,
        $expectedValue = null
    ): void
    {
        $this->assertEquals($expectedColumn, $queryDTO->columnName);
        $this->assertEquals($expectedRule, $queryDTO->rule->value);
        $this->assertEquals($expectedOperator, $queryDTO->columnOperator->value ?? null);
        $this->assertEquals($expectedValue, $queryDTO->columnValue ?? null);
    }

    private function assertDefaultQueryDTO(
        $defaultQueryDTO,
        string $expectedRule,
        string $expectedColumn,
        string $expectedOperator = null,
        $expectedValue = null
    ): void
    {
        $this->assertInstanceOf(DefaultQueryDTO::class, $defaultQueryDTO);
        $this->assertQueryDTO($defaultQueryDTO, $expectedRule, $expectedColumn, $expectedOperator, $expectedValue);
    }

    private function assertRelationQueryDTO(
        $relationQueryDTO,
        string $expectedRule,
        string $expectedColumn,
        string $expectedOperator = null,
        $expectedValue = null
    ): void
    {
        $this->assertInstanceOf(RelationQueryDTO::class, $relationQueryDTO);
        $this->assertQueryDTO($relationQueryDTO, $expectedRule, $expectedColumn, $expectedOperator, $expectedValue);
    }

    private function assertRules(array $rules, \Closure $closure): void
    {
        foreach ($rules as $rule) {
            foreach ($rule::cases() as $case) {
                $closure($case);
            }
        }
    }
}
