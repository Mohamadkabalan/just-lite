<?php

namespace App\Http\Controllers;

use App\Contact;

class ContactController extends Controller
{

    public function store(){
        try {
            $data=request()->all();
            $contact=new Contact();
            $contact->name=$data["name"];
            $contact->email=$data["email"];
            $contact->phone=$data["phone_number"];
            $contact->subject=$data["msg_subject"];
            $contact->message=$data["message"];
            $contact->save();
            return response()->json(['status'=>200,'message'=>'Your message has been sent successfully']);
        }catch (\Exception $e){
            return response()->json(['status'=>500,'message'=>'Message not sent ,Please contact our office']);
        }

    }
}