<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="Authentication endpoints"
 * )
 */
class AuthController extends Controller
{

    /**
     * @OA\Post(
     *     tags={"Auth"},
     *     path="/register",
     *     summary="Register a new user",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="jhon@gmail.com"),
     *             @OA\Property(property="phone_number", type="string", example="081234567890"),
     *             @OA\Property(property="address", type="string", example="AV. 1, No. 2, 3rd Floor"),
     *             @OA\Property(property="password", type="string", example="secretpassword"),
     *             @OA\Property(property="password_confirmation", type="string", example="secretpassword"),
     *         )
     *     ),
     *     @OA\Response(
     *          response="201", 
     *          description="Created successfully",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="token", type="string", example="0|WYgnFtfJU6oMR8Q852lFPjtyjRSuSHjolb98q9kx"),
     *              @OA\Property(property="user", type="object",
     *                  @OA\Property(property="name", type="string", example="John Doe"),
     *                  @OA\Property(property="email", type="string", format="email", example="jhon@gmail.com"),
     *                  @OA\Property(property="role", type="string", example="customer"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2023-11-02T14:03:55.000000Z"),
     *                  @OA\Property(property="created_at", type="string", format="date-time", example="2023-11-02T14:03:55.000000Z"),
     *                  @OA\Property(property="id", type="integer", example=1),
     *              )
     *          )
     *     ),
     *     @OA\Response(response="422", description="Unprocessable Content"),
     * )
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
        ]);        
    }

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

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
        ]);
    }
}
