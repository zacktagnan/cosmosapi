<?php

namespace App\Http\Middleware\V1;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class ApiResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        try {
            $response = $next($request);

            return $this->formatSuccessResponse($response);
        } catch (\Throwable $exception) {
            return $this->formatErrorResponse($exception);
        }
    }

    private function formatSuccessResponse($response): JsonResponse
    {
        $data = $response->getData(true) ?? [];

        return response()->json([
            'success' => true,
            'message' => 'Request processed successfully',
            'data' => $data,
            'timestamp' => now()->toIso8601String(),
        ], $response->getStatusCode());
    }

    private function formatErrorResponse(Throwable $exception): JsonResponse
    {
        $statusCode = $exception instanceof HttpException
            ? $exception->getStatusCode()
            : 500;

        return response()->json([
            'success' => false,
            'message' => $exception->getMessage(),
            'data' => null,
            'timestamp' => now()->toIso8601String(),
        ], $statusCode);
    }
}
