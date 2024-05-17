<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
    	$userObj = $request->user();
    	if($userObj) {
    		$Categories = new Categories();
    		$Categories->users_id = $userObj->id;
    		$Categories->name = $request->name;
    		$Categories->save();
    		return response()->json(['status'=>1,'msg'=>'Category saved','data'=>null]);
    	} else {
    		return response()->json(['status'=>0,'msg'=>'User not exist!','data'=>null]);
    	}
    }
}
