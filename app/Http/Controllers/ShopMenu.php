<?php

namespace App\Http\Controllers;

use App\Helpers\CommonMethods;
use App\Models\ShopMenus;
use Illuminate\Http\Request;

class ShopMenu extends Controller
{
    //
    public function menus(){
        $resto_id = CommonMethods::getRestuarantID();
        $menus = ShopMenus::whereNull('deleted_at')->where('resto_id',$resto_id)->get();

        return view('shop-menu.menus',['menus'=>$menus]);
    }

    public function menu_edit($id){
        $menu = ShopMenus::where('unique_key',$id)->first();

        return view('shop-menu.menu-form',['menu'=>$menu]);
    }
}
