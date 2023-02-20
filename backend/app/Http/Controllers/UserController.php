<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Mail\NewUserWelcomeMail;
use App\Models\Response;
use Exception;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|same:confirm_password',
            'phone' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'favorite_food' => 'required|string|max:255',
            'favorite_artist' => 'required|string|max:255',
            'favorite_place' => 'required|string|max:255',
            'favorite_color' => 'required|string|max:255',
            'profile_image' => 'required|image|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = new User;
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->phone = $request->phone;
        $user->country = $request->country;
        /*$user->favorite_food = $request->favorite_food;
        $user->favorite_artist = $request->favorite_artist;
        $user->favorite_place = $request->favorite_place;
        $user->favorite_color = $request->favorite_color;*/

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $path = $image->store('public/profile_images');
            $user->profile_image = Storage::url($path);
        }

        $user->save();

        $response = new Response();
        $response->favorite_food = $request->favorite_food;
        $response->favorite_artist = $request->favorite_artist;
        $response->favorite_place = $request->favorite_place;
        $response->favorite_color = $request->favorite_color;
        $response->user_id = $user->id;
        $response->save();

        Mail::to($user->email)->send(new NewUserWelcomeMail($user));

        return response()->json(['message' => 'Registro exitoso. Se ha enviado un correo electrónico de bienvenida.'], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            /** @var App\Models\User $user **/
            $user = Auth::user();
            $user->load('response');
            $token = $user->createToken('Token')->plainTextToken;
            $user->api_token = $token;
            $user->save();
            return response()->json([
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ]);
        } else {
            return response()->json([
                'message' => 'Invalid email or password'
            ], 401);
        }
    }

    public function logout(Request $request)
    {

        $request->validate([
            'user_id' => ['required']
        ]);
        try {
            $user = User::find($request->user_id);
            $user->tokens()->delete();
            return response()->json(['message' => "Has cerrado sesión", 'status' => 200]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
