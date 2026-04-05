<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="Net Respond API",
 *     version="1.0.0",
 *     description="API documentation for Net Respond application"
 * )
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API server"
 * )
 */
class ApiDocumentationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/documentation",
     *     summary="Get API documentation",
     *     @OA\Response(
     *         response=200,
     *         description="API documentation retrieved successfully"
     *     )
     * )
     */
    public function index()
    {
        return response()->json(['message' => 'API Documentation']);
    }
}
