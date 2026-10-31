<?php

namespace App\Http\Controllers;


use DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ServicesController extends Controller
{

    function index()
    {
        $data = $this->page();
        return view('services.index', compact('data'));
    }


    function fetch_data()
    {
        abort_unless(request()->ajax(), 404);

        $data = $this->page();
        return view('services.services-content', compact('data'))->render();
    }
    function serviceDetails($id){
        $service =\App\Service::findOrFail($id);
        return view('services.service-details', compact('service'));
    }

    /**
     * Six services per page. The page links point at the list itself
     * (/services?page=2) so they also work without JavaScript; pages past the
     * end are 404s.
     */
    private function page(): LengthAwarePaginator
    {
        $data = DB::table('services')->orderBy('id')->paginate(6)->withPath(localized_route('services.index'));
        abort_if($data->currentPage() > max(1, $data->lastPage()), 404);

        return $data;
    }
}
