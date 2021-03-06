<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Hash;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    private $idCabang;

    public function updatePassword(Request $request, $id)
    {
        $this->validateWith([
            'password_lama' => 'required',
            'password_baru' => 'required',

          ]);
          $user = User::findOrFail($id);
          if(Hash::check($request->password_lama, $user->password)){
            $user->password = Hash::make($request->password_baru);    
            $json=['status' => 'success','msg'=>'Password berhasil diubah'];
          }else{
            $json=['status' => 'failed','msg'=>'Password yang anda masukkan salah, silahkan coba lagi'];
          }
          $user->save();
          
          return response()->json($json);
    }
    public function register(Request $request)
    {
      $this->validateWith([
        'username' => 'required',
        'password' => 'required',
      ]);
      $user = new User();
      $user->username = $request->username;
      $user->password = bcrypt($request->password); 
      $user->save();

      return $this->login($request);
    }

    public function login(Request $request)
    {
      $credentials = $request->only(['username', 'password']);

      if (!$token = auth()->attempt($credentials)) {
        return response()->json(['error' => 'Unauthorized'], 401);
      }

      try{
        $this->idCabang = auth()->user()->detail()->first()->id_cabang;
      }catch(Exception $e){
        $this->idCabang = -1;
      }

      return $this->respondWithToken($token);
    }
    public function getPegawaiId(){
      return JWTAuth::parseToken()->authenticate()->detail()->first()->id;
    }
    protected function respondWithToken($token)
    {
      
      return response()->json([
        'access_token' => $token,
        'id' => auth()->user()->id,
        'status' => auth()->user()->status,
        'role' => auth()->user()->role()->first()->name,
        'idCabang' => $this->idCabang
      ]);
    }
    public function getAuthenticatedUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        $userdata = User::with(['role','detail'])->where('id',$user->id)->first();
        return response()->json(compact('userdata'));
    }
}
