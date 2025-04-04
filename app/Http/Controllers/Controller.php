<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Easy Business API Documentation",
 *     description="API documentation for Easy Business application",
 *     @OA\Contact(
 *         email="renehuanca999@gmail.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 * 
 * @OA\PathItem(
 *     path="/api"
 * )
 */
abstract class Controller
{
    //
}
