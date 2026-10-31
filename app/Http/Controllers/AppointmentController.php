<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Http\Requests\StoreAppointmentRequest;
use Illuminate\Support\Carbon;

class AppointmentController extends Controller
{

    public function store(StoreAppointmentRequest $request){
        try {
            $data=$request->validated();
            $appointment=new Appointment();
            $appointment->name=$data["name"];
            $appointment->email=$data["email"];
            $appointment->phone=$data["phone"];
            // The column is a DATE; normalise formats such as jQuery UI's mm/dd/yyyy.
            $appointment->appointment_date=Carbon::parse($data["appointment_date"])->toDateString();
            $appointment->type_of_service=$data["type_of_service"] ?? null;
            $appointment->message=$data["message"];
            $appointment->save();
            return response()->json(['status'=>200,'message'=>'appointment added successfully']);
        }catch (\Exception $e){
            report($e);
            return response()->json(['status'=>500,'message'=>'appointment Is not added ,Please contact our office']);
        }

    }
}
