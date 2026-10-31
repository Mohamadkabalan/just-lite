<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Http\Requests\StoreContactRequest;

class ContactController extends Controller
{

    public function store(StoreContactRequest $request){
        try {
            $data=$request->validated();
            $contact=new Contact();
            $contact->name=$data["name"];
            $contact->email=$data["email"];
            $contact->phone=$data["phone_number"];
            $contact->subject=$data["msg_subject"];
            $contact->message=$data["message"];
            $contact->save();
            return response()->json(['status'=>200,'message'=>'Your message has been sent successfully']);
        }catch (\Exception $e){
            report($e);
            return response()->json(['status'=>500,'message'=>'Message not sent ,Please contact our office']);
        }

    }
}
