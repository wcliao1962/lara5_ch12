<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

class MailController extends Controller
{
    public function send(){
        Mail::send('email.test', [], function($message){
            $message->subject('Laravel 5.7 Mail');
            $message->to('y3939889@gmail.com');
        });
        return "Email 已寄出";
    }
}
