<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function store(Request $request)
    {
    	$userObj = $request->user();
    	if($userObj) {
    		$Categories = new Tag();
    		$Categories->users_id = $userObj->id;
    		$Categories->name = $request->name;
    		$Categories->save();
    		return response()->json(['status'=>1,'msg'=>'Tag saved','data'=>null]);
    	} else {
    		return response()->json(['status'=>0,'msg'=>'User not exist!','data'=>null]);
    	}
    }
}
