<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserApiController extends Controller
{
    //show user list
    public function showUserList($id=null){
        if($id==null){
            $users = User::orderBy('id','desc')->get();
            return response()->json([
                'success'=>true,
                'code'=>200,
                'users'=>$users,

            ]);
        } else {
            $user = User::find($id);
            if($user){
                return response()->json([
                    'success'=>true,
                    'code'=>200,
                    'user'=>$user,

                ]);
            } else {
                return response()->json([
                    'success'=>false,
                    'message'=>'User not found.',

                ]);
            }
        }

    }//end method


    //update single user
    public function updateUser(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
          ]);

        if ($validator->fails()) {
        return response()->json($validator->errors());
        }

        $user = User::find($id);
        if($user){
            $user->name = $request->name;
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'success'=>true,
                'message'=>'User updated successfully.',
                'user'=> $user,
            ]);
        } else {
            return response()->json([
                'success'=>false,
                'message'=>'User not found.',
            ]);
        }
    }//end method


    //delete single user
    public function deleteUser($id){
        $user = User::find($id);
        if($user){
           $user->delete();

            return response()->json([
                'success'=>true,
                'message'=>'User deleted successfully.',
            ]);
        } else {
            return response()->json([
                'success'=>false,
                'message'=>'User not found.',
            ]);
        }
    }//end method


    //delete single user with json
    public function deleteUserJson(Request $request){
        $data = $request->all();
        $user = User::where('id',$data['id'])->first();

        if($user){
            $user->delete();

             return response()->json([
                 'success'=>true,
                 'message'=>'User deleted successfully.',
             ]);
         } else {
             return response()->json([
                 'success'=>false,
                 'message'=>'User not found.',
             ]);
         }
    }//end method


    //delete multiple user
    public function deleteMultipleUser($ids){
        $ids = explode(',',$ids);
        User::whereIn('id',$ids)->delete();
        return response()->json([
            'success'=>true,
            'message'=>'User deleted successfully.',
        ]);
    }//end method


    //delete multiple user with json
    public function deleteMultipleUserJson(Request $request){
        $data = $request->all();

        User::whereIn('id',$data['ids'])->delete();
        return response()->json([
            'success'=>true,
            'message'=>'User deleted successfully.',
        ]);

    }//end method
}//end class
