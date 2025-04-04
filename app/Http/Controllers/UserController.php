<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"Auth"},
     *     path="/me",
     *     summary="Get the authenticated user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *          response="200", 
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", format="email", example="jhon@gmail.com"),
     *              @OA\Property(property="role", type="string", example="customer"),
     *              @OA\Property(property="updated_at", type="string", format="date-time", example="2023-11-02T14:03:55.000000Z"),
     *              @OA\Property(property="created_at", type="string", format="date-time", example="2023-11-02T14:03:55.000000Z"),
     *              @OA\Property(property="id", type="integer", example=1),
     *          )
     *     ),
     *     @OA\Response(response="401", description="Unauthorized"),
     * )
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
