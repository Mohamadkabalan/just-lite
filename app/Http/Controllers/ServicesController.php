<?php

namespace App\Http\Controllers;


use DB;

class ServicesController extends Controller
{

    function index()
    {
        $data = DB::table('services')->paginate(6);
        return view('services.index', compact('data'));
    }


    function fetch_data()
    {
        if(request()->ajax())
        {
            $data = DB::table('services')->paginate(6);
            return view('services.services-content', compact('data'))->render();
        }
    }
    function serviceDetails($id){
        $service =\App\Service::find($id);
        return view('services.service-details', compact('service'));
    }
}