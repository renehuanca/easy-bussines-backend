<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

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
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'password' => Hash::make($request->password),
        ]);
        
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => $user
        ], Response::HTTP_CREATED);        
    }


    /**
     * @OA\Post(
     *     tags={"Auth"},
     *     path="/login",
     *     summary="Login user and create token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="jhon@gmail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", example="1|abcdef123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid credentials")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     tags={"Auth"},
     *     path="/logout",
     *     summary="Logout user (Revoke the token)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logged out successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], Response::HTTP_OK);
    }
}
