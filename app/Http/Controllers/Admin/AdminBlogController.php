<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreBlogRequest;
use App\Models\Blog;
use App\Http\Requests\Admin\UpdateBlogRequest;
use Illuminate\Support\Facades\Storage;

class AdminBlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = Blog::latest("updated_at")->paginate(10);
        return view("admin.blogs.index", ["blogs" => $blogs]);
    }

 
    public function create()
    {
        return view("admin.blogs.create");
    }


    public function store(StoreBlogRequest $request)
    {
        $savedImagePath = $request->file("image")->store("blogs", "public");
        $blog = new Blog($request->validated());
        $blog->image = $savedImagePath;
        $blog->save();

        return to_route("admin.blogs.index")->with("success", "ブログを投稿しました");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $blog = Blog::findOrFail($id);

        return view("admin.blogs.edit", ["blogs" => $blog]);
    }


    public function update(UpdateBlogRequest $request, $id)
    {
        $blog = Blog::findOrFail($id);
        $updateData = $request->validated();

        if($request->has("image")){
            Storage::disk("public")->delete($blog->image);
            $updateData["image"] = $request->file("image")->store("blogs", "public");
        }

        $blog->update($updateData);

        return to_route("admin.blogs.index")->with("success", "ブログを更新しました");
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();
        Storage::disk("public")->delete($blog->image);

        return to_route("admin.blogs.index")->with("success", "ブログを削除しました");
    }
}
