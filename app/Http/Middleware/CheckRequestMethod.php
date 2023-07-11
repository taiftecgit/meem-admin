<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CheckRequestMethod
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);




        $req_method = $request->getMethod();



        if($req_method=="POST"){
            $keys = Redis::keys('*');
            $resto = \App\Models\Restaurants::find(\App\Helpers\CommonMethods::getRestuarantID());
            $resto_id = $resto->user_id;
            //dd($resto_id);
            /*  $key_id =
         dump(Auth::id().': '.Redis::get(str_replace('meem_orders_','',"resto_pakistan_".Auth::id()))); */
            foreach($keys as $k){
                //

                if(str_contains($k,"_".$resto_id)){

                    Redis::del(str_replace('prod_meem_orders_','',$k));
                }

                //dump($k.': '.Redis::get(str_replace('meem_orders_','',$k)));
            }
            //dump($req_method);
        }


        return $response;
    }
}
