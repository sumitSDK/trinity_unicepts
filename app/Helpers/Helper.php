<?php

namespace App\Helpers;
use App\Models\User;
use App\Models\Categories;
use App\Models\Tag;

class Helper {

	public static function getUserName($id){
        $userName = User::where('id',$id)->first();
        return $userName->name;
    }

    public static function getCategoryName($id){
        $categoriesName = Categories::where('id',$id)->first();
        return $categoriesName->name;
    }

    public static function getTagName($id){
        $tagName = Tag::where('id',$id)->first();
        return $tagName->name;
    }
}