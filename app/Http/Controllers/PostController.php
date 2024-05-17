<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Helper;

class PostController extends Controller
{
    public function index(Request $request)
    {
    	$getPost = Post::where('publish',1)->get();
    	$data = [];
    	if($getPost) {
    		foreach ($getPost as $key => $value) {
    			$url = Storage::url('uploads/' . $value->image);
	    		/*$tagsArray = [];
	    		if($value->tags) {
	    			$tags = explode(",", $value->tags);
	    			if(isset($tags) && count($tags) > 0) {
	    				foreach ($tags as $key => $value) {
	    					$tagsArray[] = [
	    						'id' =>$value,
	    						'tag_name' =>Helper::getTagName($value),
	    					];
	    				}
	    			}
	    		}*/
    			$data[] = [
	    			'id' => $value->id,
	    			'user' => Helper::getUserName($value->users_id),
	    			'category' => Helper::getCategoryName($value->categories_id),
	    			'title' => $value->title,
	    			'content' => $value->content,
	    			'slug' => $value->slug,
	    			'image' => url($url),
	    			'publish_or_not' => $value->publish == 1 ? "Published" : "Not Published",
	    		];
    		}
    	}
	    return response()->json(['status'=>1,'msg'=>'Data found','data'=>$data]);
    }

    public function store(Request $request)
    {
    	$userObj = $request->user();
    	if($userObj) {
    		$fileName = time().'.'.$request->image->extension();
    		$request->image->storeAs('uploads', $fileName);
    		$Post = new Post();
    		$Post->users_id = $userObj->id;
    		$Post->categories_id = $request->category_id;
    		$Post->title = $request->title;
    		$Post->content = $request->content;
    		$Post->slug = $request->slug;
    		$Post->image = $fileName;
    		$Post->tags = $request->tags;
    		$Post->save();
    		return response()->json(['status'=>1,'msg'=>'Post saved','data'=>null]);
    	} else {
    		return response()->json(['status'=>0,'msg'=>'User not exist!','data'=>null]);
    	}
    }

    public function view(Request $request, $id)
    {
    	if($id) {
    		$getPost = Post::where('id',$id)->first();
    		$url = Storage::url('uploads/' . $getPost->image);
    		$tagsArray = [];
    		if($getPost->tags) {
    			$tags = explode(",", $getPost->tags);
    			if(isset($tags) && count($tags) > 0) {
    				foreach ($tags as $key => $value) {
    					$tagsArray[] = [
    						'id' =>$value,
    						'tag_name' =>Helper::getTagName($value),
    					];
    				}
    			}
    		}
    		$data[] = [
    			'id' => $getPost->id,
    			'user' => Helper::getUserName($getPost->users_id),
    			'category' => Helper::getCategoryName($getPost->categories_id),
    			'title' => $getPost->title,
    			'content' => $getPost->content,
    			'slug' => $getPost->slug,
    			'image' => url($url),
    			'publish_or_not' => $getPost->publish == 1 ? "Published" : "Not Published",
    			'tags' => $tagsArray,
    		];
    		return response()->json(['status'=>1,'msg'=>'Data found','data'=>$data]);
    	} else {
    		return response()->json(['status'=>0,'msg'=>'Something went wrong!','data'=>null]);
    	}
    }

    public function delete(Request $request, $id)
    {
    	$userObj = $request->user();
    	$getPost = Post::where('id',$id)->first();
    	if($getPost->users_id == $userObj->id) {
    		$getPost->delete();
    		return response()->json(['status'=>1,'msg'=>'Post deleted!','data'=>null]);
    	}
    	return response()->json(['status'=>0,'msg'=>'You can not delete this post!','data'=>null]);
    }

    public function publish(Request $request, $id)
    {
    	if($id) {
    		$Post = Post::where('id', $id)->first();
    		if($Post) {
    			$Post->publish = 1;
    			$Post->save();
    			return response()->json(['status'=>1,'msg'=>'Post published!','data'=>null]);
    		}
    		return response()->json(['status'=>1,'msg'=>'Post not found!','data'=>null]);
    		// Post::where('id', $id)->update(['publish' => 1 ]);
    		return response()->json(['status'=>1,'msg'=>'Post published!','data'=>null]);
    	} else {
    		return response()->json(['status'=>0,'msg'=>'Something went wrong!','data'=>null]);	
    	}
    }

    public function update(Request $request, $id)
    {
    	$userObj = $request->user();
    	$Post = Post::where('id',$id)->first();
    	$fileName = time().'.'.$request->image->extension();

    	$filePath = 'uploads/' . $Post->image;
    	if (Storage::exists($filePath)) {
    		Storage::delete($filePath);
    	}

    	$request->image->storeAs('uploads', $fileName);
    	if($Post->users_id == $userObj->id) {
    		$Post->categories_id = $request->category_id;
    		$Post->title = $request->title;
    		$Post->content = $request->content;
    		$Post->slug = $request->slug;
    		$Post->image = $fileName;
    		$Post->tags = $request->tags;
    		$Post->save();
    		return response()->json(['status'=>1,'msg'=>'Post updated','data'=>null]);
    	}
    	return response()->json(['status'=>0,'msg'=>'You can not update this post!','data'=>null]);
    }
}
