<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Http\Requests\StoreContactRequest;
use App\Mail\NewContactMessage;
use App\Support\Notify;

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
        }catch (\Exception $e){
            report($e);
            return response()->json(['status'=>500,'message'=>__('translation.form-failed')]);
        }

        Notify::afterResponse(new NewContactMessage($contact));

        return response()->json(['status'=>200,'message'=>__('translation.message-sent')]);
    }
}
