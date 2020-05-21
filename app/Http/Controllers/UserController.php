<?php

namespace App\Http\Controllers;

use App\PasswordEntry;
use Illuminate\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class UserController extends Controller
{
    //
    public function AddUserPassword(Request $request){

        $this->validate($request,[
            'uid'=>'required|int',
            'site' => 'required|string',
            'password' => 'required|string',
        ]);
        $passwordEntry = new PasswordEntry();
        $passwordEntry->setUId($request->uid);
        $passwordEntry->setSite($request->site);
        $passwordEntry->setPassword($request->password);

        $passwordEntry->save();
        return response()->json($passwordEntry);
    }
    public function UpdateUserPassword(Request $request){
        $this->validate($request,[
            'id'=>'required|int',
            'site' => 'required|string',
            'password' => 'required|string',
        ]);
        $passwordEntry = PasswordEntry::find($request->id);
        $passwordEntry->setSite($request->site);
        $passwordEntry->setPassword($request->password);

        $passwordEntry->save();
        return response()->json($passwordEntry);
    }
    public function GetPasswords(Request $request){
        $this->validate($request,[
            'uid'=>'required|int'
        ]);
        return response()->json(  PasswordEntry::where("uid", '=',$request->uid)->get());
    }
}
