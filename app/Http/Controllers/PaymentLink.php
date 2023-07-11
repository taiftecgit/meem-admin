<?php

namespace App\Http\Controllers;

use App\Helpers\CommonMethods;
use App\Models\Outlets;
use App\Models\PaymentLinks;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentLink extends Controller
{
    //
    public function payment_links(){
        $resto_id = CommonMethods::getRestuarantID();

        $payment_links = PaymentLinks::whereNull('deleted_at')->where('resto_id',$resto_id)->paginate(50);

        return view('payment-links.index',['payment_links'=>$payment_links]);
    }

    public function save_payment_links(Request  $request){
        $id = $request->id;
        $resto_id = CommonMethods::getRestuarantID();
        if(empty($id)){
            $payment_link = new PaymentLinks();
            $payment_link->resto_id = $resto_id;
            $payment_link->unique_id = "PL-".$resto_id.'-'.rand(1000000,9999999);
            $payment_link->status = 'active';
        }

        else
            $payment_link = PaymentLinks::find($id);

        $payment_link->number_of_uses = $request->number_of_uses;
        $payment_link->amount = $request->amount;
        $payment_link->outlet_id = $request->outlet_id;
        $payment_link->purpose_payment = $request->purpose_payment;
        $payment_link->payment_message = $request->payment_message;

        $payment_link->save();

        $id = $payment_link->id;

        if($id > 0){
            echo json_encode(array('type'=>'success','message'=>'Payment Link saved!.'));
            exit;
        }else{
            echo json_encode(array('type'=>'error','message'=>'Payment Link is saved!.'));
            exit;
        }


    }

    public function delete_payment_link($id){
        $payment_link = PaymentLinks::find($id);

        $payment_link->deleted_at = Carbon::now()->format('Y-m-d H:i:s');
        $payment_link->save();
    }

    public function new_payment(){
        $resto_id = CommonMethods::getRestuarantID();
        $outlets = Outlets::whereNull('deleted_at')->where('active',1)->where('resto_id',$resto_id)->get();

        return view('payment-links.payment-links-form',['outlets'=>$outlets]);
    }

    public function view_payment($unique_id){
        $payment_link = PaymentLinks::withCount('payments')->where('unique_id',$unique_id)->first();

        return view('payment-links.payment-links-view',['payment_link'=>$payment_link]);
    }
}
