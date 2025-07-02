<?php

namespace Tests\Feature\V1\Middleware;

use App\Http\Middleware\V1\ApiResponseMiddleware;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;
use Exception;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;

#[Group('middleware')]
#[Group('middleware:api_response_middleware')]
class ApiResponseMiddlewareTest extends TestCase
{
    private ApiResponseMiddleware $middleware;

    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware = new ApiResponseMiddleware();
    }

    #[Test]
    #[Group('middleware:api_response_middleware:formats_success')]
    public function it_formats_successful_response_correctly()
    {
        // Arrange
        $request = Request::create('/test/success', 'GET');
        $originalData = ['id' => 1, 'name' => 'Test Mission'];
        $next = function () use ($originalData) {
            return new JsonResponse($originalData, Response::HTTP_OK);
        };

        // Act
        $response = $this->middleware->handle($request, $next);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $responseData = $response->getData(true);
        $this->assertTrue($responseData['success']);
        $this->assertEquals($originalData, $responseData['data']);
        $this->assertEquals('Request processed successfully', $responseData['message']);
        $this->assertArrayHasKey('timestamp', $responseData);
    }

    #[Test]
    #[Group('middleware:api_response_middleware:formats_error')]
    public function it_formats_error_response_correctly()
    {
        // Arrange
        $request = Request::create('/test/error', 'GET');
        $errorMessage = 'Test error somewhere';
        $next = function () use ($errorMessage) {
            throw new Exception($errorMessage);
        };

        // Act
        $response = $this->middleware->handle($request, $next);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());

        $responseData = $response->getData(true);
        $this->assertFalse($responseData['success']);
        $this->assertNull($responseData['data']);
        $this->assertEquals($errorMessage, $responseData['message']);
        $this->assertArrayHasKey('timestamp', $responseData);
    }

    #[Test]
    #[Group('middleware:api_response_middleware:preserves_custom_status_code')]
    public function it_preserves_custom_status_codes_in_success_responses()
    {
        // Arrange
        $request = Request::create('/test/custom-status', 'POST');
        $next = function () {
            return new JsonResponse(['created' => true], Response::HTTP_CREATED);
        };

        // Act
        $response = $this->middleware->handle($request, $next);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        $responseData = $response->getData(true);
        $this->assertTrue($responseData['success']);
    }

    #[Test]
    #[Group('middleware:api_response_middleware:empty_response')]
    public function it_handles_empty_response_data()
    {
        // Arrange
        $request = Request::create('/test', 'DELETE');
        $next = function () {
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        };

        // Act
        $response = $this->middleware->handle($request, $next);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());

        $responseData = $response->getData(true);
        $this->assertTrue($responseData['success']);
        $this->assertEquals([], $responseData['data']);
    }
}
