<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserCollection;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function info($id)
    {
        return new UserCollection(User::where('id', $id)->get());
    }

    public function updateName(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->update([
            'name' => $request->name,
            'password' => Hash::make($request->password),
        ]);
        return response()->json([
            'message' => 'Profile information has been updated successfully'
        ]);
    }
}
