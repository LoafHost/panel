<?php

namespace LoafPanel\\Tests\Unit\Http\Middleware;

use LoafPanel\\Tests\TestCase;
use LoafPanel\\Tests\Traits\Http\RequestMockHelpers;
use LoafPanel\\Tests\Traits\Http\MocksMiddlewareClosure;
use LoafPanel\\Tests\Assertions\MiddlewareAttributeAssertionsTrait;

abstract class MiddlewareTestCase extends TestCase
{
    use MiddlewareAttributeAssertionsTrait;
    use MocksMiddlewareClosure;
    use RequestMockHelpers;

    /**
     * Setup tests with a mocked request object and normal attributes.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->buildRequestMock();
    }
}
