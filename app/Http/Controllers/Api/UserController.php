<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user;
use Illuminate\Notification\HasApiToken;
use Validator;

class UserController extends Controller
{
    public function index()
   {
    $user = User::latest()->get();
    $res =[
        'success' => true,
        'message' => "daftar user",
        'data' => $user
    ];
    return response()->json($res, 200);
   }

   public function store( Request  $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'email' => 'required|unique:users',
      'password' => 'required|min:8',
    ]);

    if($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'validasi gagal',
            'errors' => $validator->errors(),
        ], 422);
    }

    try {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'data berhasil dibuat',
            'errors' => $user,
        ], 201);
    }catch(\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'terjadi kesalahan',
            'errors' => $e->getMessage(),
        ], 500);
    }
  }

  public function show($id)
  {
    try{
        $user = User::findOrFail($id);
        return response()->json([
            'success' => true,
            'message' => 'detail user',
            'data' => $user,
        ], 200);
    } catch(\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'data tidak ditemukan',
            'errors' => $e->getMessage(),
        ], 404);
    }
  }

  public function update( Request  $request,$id)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'email' => 'required|unique:users',
    ]);

    if($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'validasi gagal',
            'errors' => $validator->errors(),
        ], 422);
    }

    try {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email= $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'data berhasil diperbaharui',
            'errors' => $user,
        ], 200);
    }catch(\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'terjadi kesalahan',
            'errors' => $e->getMessage(),
        ], 500);
    }
  }

  public function destroy($id)
  {
    try{
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json([
            'success' => true,
            'message' =>'Data ' . $user-> nama_user . ' berhasil dihapus',
        ], 200);
    } catch(\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'data tidak ditemukan',
            'errors' => $e->getMessage(),
        ], 404);
    }
  }
}
