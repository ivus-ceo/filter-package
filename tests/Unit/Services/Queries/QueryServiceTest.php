<?php

namespace Ivus\Filter\Tests\Unit\Services\Queries;

use Illuminate\Foundation\Testing\TestCase;
use Ivus\Filter\Interfaces\Services\QueryServiceInterface;
use Ivus\Filter\Services\Queries\QueryService;
use Mockery\MockInterface;

class QueryServiceTest extends TestCase
{
    public function test_get_separators_returns_array(): void
    {
        // Call the getSeparators method
        $result = QueryService::getSeparators();

        // Check that the result is an array
        $this->assertIsArray($result);

        // Check that the array has 4 elements
        $this->assertCount(4, $result);

        // Array keys should exist
        $this->assertArrayHasKey('union', $result);
        $this->assertArrayHasKey('rule', $result);
        $this->assertArrayHasKey('column', $result);
        $this->assertArrayHasKey('value', $result);

        // Expect that the values are strings
        $this->assertIsString($result['union']);
        $this->assertIsString($result['rule']);
        $this->assertIsString($result['column']);
        $this->assertIsString($result['value']);

        // Expect that the values are not empty
        $this->assertEquals(config('filters.union_separator', QueryService::DEFAULT_UNION_SEPARATOR), $result['union']);
        $this->assertEquals(config('filters.rule_separator', QueryService::DEFAULT_RULE_SEPARATOR), $result['rule']);
        $this->assertEquals(config('filters.column_separator', QueryService::DEFAULT_COLUMN_SEPARATOR), $result['column']);
        $this->assertEquals(config('filters.value_separator', QueryService::DEFAULT_VALUE_SEPARATOR), $result['value']);
    }

    public function test_get_sanitized_string_removes_html_tags(): void
    {
        // Input string with HTML tags
        $input = '<p>This is a <strong>test</strong> string</p>';

        // Call the method
        $result = QueryService::getSanitizedString($input);

        // Expect HTML tags to be removed
        $expected = 'This is a test string';
        $this->assertEquals($expected, $result);
    }

    public function test_get_sanitized_string_encodes_special_characters(): void
    {
        // Input string with quotes and ampersand
        $input = '"This is a \'test\' & string"';

        // Call the method
        $result = QueryService::getSanitizedString($input);

        // Expected output where quotes and ampersand are encoded
        $expected = '&quot;This is a &#039;test&#039; &amp; string&quot;';
        $this->assertEquals($expected, $result);
    }

    public function test_get_sanitized_string_safe_input_remains_unchanged(): void
    {
        // Safe string without HTML or special characters
        $input = 'This is a safe string';

        // Call the method
        $result = QueryService::getSanitizedString($input);

        // Expect the string to remain unchanged
        $this->assertEquals($input, $result);
    }

    public function test_get_sanitized_string_prevents_xss(): void
    {
        // Input string with potential XSS
        $input = '<script>alert("XSS")</script>';

        // Call the method
        $result = QueryService::getSanitizedString($input);

        // Expect script tags to be removed
        $expected = 'alert(&quot;XSS&quot;)';  // The script tag is removed, but content is encoded
        $this->assertEquals($expected, $result);

        $input = "<script>alert('XSS')</script>";

        // Call the method
        $result = QueryService::getSanitizedString($input);

        // Expect script tags to be removed
        $expected = 'alert(&#039;XSS&#039;)';  // The script tag is removed, but content is encoded
        $this->assertEquals($expected, $result);
    }

    public function test_get_sanitized_string_handles_empty_string(): void
    {
        // Empty input
        $input = '';

        // Call the method
        $result = QueryService::getSanitizedString($input);

        // Expect an empty result
        $this->assertEquals('', $result);
    }
}