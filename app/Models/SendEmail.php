<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mail;

class SendEmail extends Model
{
    //


    public static  function SendInvitationLink($param){


        $data = array(
            'shop_name' => $param['shop_name'],
            'name' => $param['name'],
            'email' => $param['email'],
            'role' => $param['role'],
            'access_level' => $param['access_level'],
            'selected_outlets'=>$param['selected_outlets'],
            'link' => $param['link']
        );


          $r = Mail::send('mails.invite', $data, function ($message) use ($data)
          {

              $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));

              $message->to($data['email'])->subject("You've been invited to ".env('MAIL_FROM_NAME'))->bcc('mujtabaahmad1985@gmail.com');;;;
              //dd($message);


          });

     //     dump($r);

   //       dd(Mail::failures());

    //      return response()->json(['message' => 'Request completed']);

    }

	public static  function SendRestPasswordLink($param){


        $data = array(

            'name' => $param['name'],
            'email' => $param['email'],
            'link' => $param['link'],

        );


          $r = Mail::send('mails.reset-password', $data, function ($message) use ($data)
          {

              $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));

              $message->to($data['email'])->subject("Email reset link ".env('MAIL_FROM_NAME'))->bcc('mujtabaahmad1985@gmail.com');;;;
              //dd($message);


          });

     //     dump($r);

   //       dd(Mail::failures());

          return response()->json(['type'=>'success','message' => 'Request completed']);

    }



    public static function sendRestoUserCredentials($param){
        $data = array(
            'shop_name' => $param['shop_name'],
            'name' => $param['name'],
            'email' => $param['email'],
            'username'=> $param['username'],
            'password'=> $param['password']
        );

        $r = Mail::send('mails.resto-user-credentials', $data, function ($message) use ($data)
          {

              $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));

              $message->to($data['email'])->subject("Your login credentials for ".env('MAIL_FROM_NAME'))->bcc('mujtabaahmad1985@gmail.com');;;;
              //dd($message);


          });
    }

	public static function sendAdminUserCredentials($param){
        $data = array(

            'name' => $param['name'],
            'email' => $param['email'],
            'username'=> $param['username'],
            'password'=> $param['password']
        );

        $r = Mail::send('mails.admin-user-credentials', $data, function ($message) use ($data)
          {

              $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));

              $message->to($data['email'])->subject("Your login credentials for ".env('MAIL_FROM_NAME'))->bcc('mujtabaahmad1985@gmail.com');;;;
              //dd($message);


          });
    }


    public static function sendOrderNotification($param){

        $data = array(
            'shop_name' => $param['shop_name'],
            'email' => $param['email'],
            'order_message' => $param['order_message'],
            'logo'=> $param['logo'],
        );


        $r = Mail::send('mails.send-order-notification', $data, function ($message) use ($data)
        {

            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));

            $message->to($data['email'])->subject("You received a new order from ".env('MAIL_FROM_NAME'))->bcc('mujtabaahmad1985@gmail.com');;;;
            //dd($message);


        });
    }


}
