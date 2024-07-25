<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function index()
    {
        //get all posts
        $blog = Blog::latest()->paginate(5);

        //return collection of posts as a resource
        return new BlogResource(true, 'Blogs Lists', $blog);
    }

     public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'title'     => 'required | max:100',
            'desc'     => 'required',
            'author'   => 'required | max:100',
            'date'     => 'required | date',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //create post
        $blog = Blog::create([
            'title'     => $request->title,
            'desc'     => $request->desc,
            'author'   => $request->author,
            'date'     => $request->date,
        ]);

        //return response
        return new BlogResource(true, 'Adding Blog Succes', $blog);
    }

     public function show($id)
    {
        //find post by ID
        $blog = Blog::find($id);
        if(!$blog){
            return response()->json(['message' => 'Blog not found!'], 404);
        }

        //return single post as a resource
        return new BlogResource(true, 'Blog Found!', $blog);
    }

     public function update(Request $request, $id)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'title'     => 'required | max:100',
            'desc'     => 'required',
            'author'   => 'required | max:100',
            'date'     => 'required | date',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find blog by ID
        $blog = Blog::find($id);

        if(!$blog){
            return response()->json(['message' => 'Blog not found!'], 404);
        }

            //update blog without image
            $blog->update([
            'title'     => $request->title,
            'desc'     => $request->desc,
            'author'   => $request->author,
            'date'     => $request->date,
            ]);
        //return response
        return new BlogResource(true, 'Blog Updated!', $blog);
    }

        public function destroy($id)
        {
            //find blog by ID
            $blog = Blog::find($id);
    
            if(!$blog){
                return response()->json(['message' => 'Blog not found!'], 404);
            }
    
            //delete blog
            $blog->delete();
    
            //return response
            return new BlogResource(true, 'Blog Deleted!', $blog);
        }
}
