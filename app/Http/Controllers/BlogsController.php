<?php

namespace App\Http\Controllers;


use DB;

class BlogsController extends Controller
{

    function index()
    {
        $data = DB::table('blogs')->paginate(6);
        return view('blogs.index', compact('data'));
    }

    function fetchData()
    {
        if(request()->ajax())
        {
            $data = DB::table('blogs')->paginate(6);
            return view('blogs.index', compact('data'))->render();
        }
    }
    function blogDetails($id){
        $blog =\App\Blog::find($id);
        return view('blogs.blog-details', compact('blog'));
    }
}