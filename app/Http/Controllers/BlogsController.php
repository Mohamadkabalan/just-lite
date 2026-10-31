<?php

namespace App\Http\Controllers;


use DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BlogsController extends Controller
{

    function index()
    {
        $data = $this->page();
        return view('blogs.index', compact('data'));
    }

    function fetchData()
    {
        abort_unless(request()->ajax(), 404);

        $data = $this->page();
        return view('blogs.blogs-content', compact('data'))->render();
    }
    function blogDetails($id){
        $blog =\App\Blog::findOrFail($id);
        return view('blogs.blog-details', compact('blog'));
    }

    /**
     * Six posts per page. The page links point at the list itself (/blogs?page=2)
     * so they also work without JavaScript; pages past the end are 404s.
     */
    private function page(): LengthAwarePaginator
    {
        $data = DB::table('blogs')->orderBy('id')->paginate(6)->withPath(localized_route('blogs.index'));
        abort_if($data->currentPage() > max(1, $data->lastPage()), 404);

        return $data;
    }
}
