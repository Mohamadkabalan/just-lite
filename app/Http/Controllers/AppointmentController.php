<?php

namespace App\Http\Controllers;

use App\Appointment;

class AppointmentController extends Controller
{

    public function store(){
        try {
            $data=request()->all();
            $appointment=new Appointment();
            $appointment->name=$data["name"];
            $appointment->email=$data["email"];
            $appointment->phone=$data["phone"];
            /*        $appointment->appointment_date=$data["appointment_date"];*/
            $appointment->type_of_service=$data["type_of_service"];
            $appointment->message=$data["message"];
            $appointment->save();
            return response()->json(['status'=>200,'message'=>'appointment added successfully']);
        }catch (\Exception $e){
            return response()->json(['status'=>500,'message'=>'appointment Is not added ,Please contact our office']);
        }

    }
}