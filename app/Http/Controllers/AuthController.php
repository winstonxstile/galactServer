<?php
namespace App\Http\Controllers;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function signup(SignupRequest $request)
    {
        $data = $request->validated();
        /** @var \App\Models\User $user */
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);
        $token = $user->createToken('main')->plainTextToken;
        return response([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $remember = $credentials['remember'] ?? false;
        unset($credentials['remember']);

        if (!Auth::attempt($credentials, $remember)) {
            return response([
                'error' => 'The Provided credentials are not correct'
            ], 422);
        }
        $user = Auth::user();
        $token = $user->createToken('main')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        // Revoke the token that was used to authenticate the current request...

        $user->currentAccessToken()->delete();
        return response([
            'success' => true
        ]);
    }

    public function setUserOrder(Request $request)
    {
        $name = $request->input('user');
        $value = $request->input('value');

        $user = DB::table('users')->where('name', $name)->first();
        if ($user) {
            DB::table('users')->where('name', $name)->update(['order_name' => $value]);
            return response()->json(['message' => 'Value updated successfully.']);
        } else {
            return response()->json(['message' => 'User not found.']);
        }
    }
    public function getUserOrder(Request $request){
        $name = $request->input('name');
            $order = DB::table('users')
                ->where('name', '=', $name)
                ->select('order_name')
                ->get();

                return response()->json($order);
            }

    public function me(Request $request)
    {
        return $request->user();
    }
        }

