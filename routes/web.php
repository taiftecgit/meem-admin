<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('config:clear');
    // return what you want
});
Route::get('/get/polygons',[\App\Http\Controllers\HomeController::class,'get_polygons']);
Route::get('/', function () {
    if(\Illuminate\Support\Facades\Auth::check()){
        return redirect()->intended('/dashboard');
    }
    return view('auth.login');
});

Auth::routes();

Route::post('/login',[\App\Http\Controllers\Auth\LoginController::class,'postLogin']);
Route::get('/home', [\App\Http\Controllers\HomeController::class,'index'])->name('home');
Route::get('/user/invite/{id}',[\App\Http\Controllers\RestoUser::class,'create_user']);
Route::post('/create/user',[\App\Http\Controllers\RestoUser::class,'save_user']);

Route::post('/reset/send/link/password',[\App\Http\Controllers\HomeController::class,'send_reset_link']);
Route::get('/reset/my/password',[\App\Http\Controllers\HomeController::class,'reset_password']);
Route::post('update/password/user',[\App\Http\Controllers\HomeController::class,'reset_update_password']);

Route::group(['middleware'=>['auth','admin.routes']],function(){

    Route::post('/save/translation',[\App\Http\Controllers\Translation::class,'save_translation']);
    Route::get('/reset/customers',[\App\Http\Controllers\HomeController::class,'reset_customer']);
    Route::post('reset/phone/customer',[\App\Http\Controllers\HomeController::class,'reset_phone_customer']);

    Route::get('/download/translation/{type}/{for}',[\App\Http\Controllers\Translation::class,'download_translation_file']);
    Route::get('/translations/admin',[\App\Http\Controllers\Translation::class,'translations']);
    Route::get('/translations/front-end',[\App\Http\Controllers\Translation::class,'translations_frontend']);
    Route::get("/blogs",[\App\Http\Controllers\Blog::class,'blogs']);
    Route::get("/blog/new",[\App\Http\Controllers\Blog::class,'new_blog']);
    Route::get("/blog/edit/{id}",[\App\Http\Controllers\Blog::class,'edit_blog']);
    Route::post("/blog/save",[\App\Http\Controllers\Blog::class,'save_blog']);
    Route::get("/blog/delete/{id}",[\App\Http\Controllers\Blog::class,'delete_blog']);

    Route::get('/admin/users',[\App\Http\Controllers\AdminUser::class,'users']);



    Route::get('/admin/user/new',[\App\Http\Controllers\AdminUser::class,'new_user']);
    Route::post('/save/admin/user',[\App\Http\Controllers\AdminUser::class,'save_user']);
});


Route::group([ 'middleware' => ['auth','check.request']], function() {

    Route::get('/payment/links',[\App\Http\Controllers\PaymentLink::class,'payment_links'])->name('payment-links');
    Route::get('/payment/link/new',[\App\Http\Controllers\PaymentLink::class,'new_payment']);
    Route::post('/payment/link/save',[\App\Http\Controllers\PaymentLink::class,'save_payment_links']);
    Route::get('/payment/link/{unique_id}',[\App\Http\Controllers\PaymentLink::class,'view_payment']);
    Route::get('change/lang/{lang}',[\App\Http\Controllers\HomeController::class,'change_lang']);
    Route::post('/get/variation/attributes',[\App\Http\Controllers\Recipe::class,'get_variation_attributes']);
    Route::post('/save/variations',[\App\Http\Controllers\Recipe::class,'save_variation_data']);
    Route::get('/delete/variation/{id}',[\App\Http\Controllers\Recipe::class,'delete_variation']);
    Route::get('/get/variation/{id}',[\App\Http\Controllers\Recipe::class,'getVaraitionDataBasedOnID']);
    Route::post("/update/category/order",[\App\Http\Controllers\Category::class,'update_display_orders']);
    Route::post("/update/recipe/order",[\App\Http\Controllers\Recipe::class,'update_recipe_orders']);

    Route::get('/change/password',[\App\Http\Controllers\HomeController::class,'change_password']);
    Route::post('/reset/password',[\App\Http\Controllers\HomeController::class,'update_password']);
    Route::get('/logout',[\App\Http\Controllers\HomeController::class,'getLogout']);
    Route::get('/dashboard', [\App\Http\Controllers\HomeController::class,'dashboard'])->name('dashboard');
    Route::get('/businesses', [\App\Http\Controllers\Restaurant::class,'restaurants'])->name('restaurants');
    Route::get('/restaurant/new', [\App\Http\Controllers\Restaurant::class,'new_restaurant'])->name('new_restaurant');
    Route::post('/restaurant/save', [\App\Http\Controllers\Restaurant::class,'save'])->name('save_restaurant');
    Route::get('/restaurant/show/{id}', [\App\Http\Controllers\Restaurant::class,'show'])->name('show_restaurant');
    Route::get('/restaurant/edit/{id}', [\App\Http\Controllers\Restaurant::class,'edit'])->name('edit_restaurant');
    Route::get('/restaurant/delete/{id}', [\App\Http\Controllers\Restaurant::class,'delete'])->name('delete_restaurant');
    Route::get('/restaurant/get/credentials/{id}', [\App\Http\Controllers\Restaurant::class,'generate_credentials'])->name('generate_credentials');
    Route::get('/user/get/credentials/{id}', [\App\Http\Controllers\RestoUser::class,'generate_credentials'])->name('generate_user_credentials');
    Route::post('/update/password', [\App\Http\Controllers\Restaurant::class,'update_password'])->name('update_password_restaurant');
    Route::post('/user/update/password', [\App\Http\Controllers\RestoUser::class,'update_password'])->name('update_password_user');
    Route::get('/get/invitation/info/{unique_key}', [\App\Http\Controllers\RestoUser::class,'get_invitation_link'])->name('get_invitation_link');


    Route::post('/upload/gallery/resto',[\App\Http\Controllers\Restaurant::class,'upload_gallery']);
    Route::post('/download/qrcode',[\App\Http\Controllers\HomeController::class,'download_image']);
    Route::get("/delete/image/{id}",[\App\Http\Controllers\Photo::class,'delete_image']);


    Route::get('/categories',[\App\Http\Controllers\Category::class,'categories'])->name('categories');
    Route::get('/category/new', [\App\Http\Controllers\Category::class,'new_category'])->name('new_category');
    Route::post('/category/save', [\App\Http\Controllers\Category::class,'save'])->name('save_category');
    Route::get('/category/edit/{id}', [\App\Http\Controllers\Category::class,'edit'])->name('edit_category');
    Route::get('/category/delete/{id}', [\App\Http\Controllers\Category::class,'delete'])->name('delete_category');

    Route::get('/inventory',[\App\Http\Controllers\Recipe::class,'inventory']);
    Route::get('/users',[\App\Http\Controllers\RestoUser::class,'users']);
    Route::get('/invite',[\App\Http\Controllers\RestoUser::class,'invite']);

    Route::post('/send/invitation',[\App\Http\Controllers\RestoUser::class,'send_invitation']);
    Route::get('/delete/invitation/{id}',[\App\Http\Controllers\RestoUser::class,'delete_invitation']);
    Route::get('/delete/saved/user/{id}',[\App\Http\Controllers\RestoUser::class,'delete_saved_user']);
    Route::get('/user/tanent/{id}',[\App\Http\Controllers\RestoUser::class,'user_profile']);
    Route::post('/tanent/save/changes',[\App\Http\Controllers\RestoUser::class,'save_changes']);


    Route::get('/pause/orders',[\App\Http\Controllers\Outlet::class,'pause_orders'])->name('pause-orders');
    Route::post('/outlet/feature/update/status',[\App\Http\Controllers\Outlet::class,'update_feature_status_1']);


    Route::get('/recipes',[\App\Http\Controllers\Recipe::class,'recipes'])->name('recipes');
    Route::get('/recipe/new', [\App\Http\Controllers\Recipe::class,'new_recipe'])->name('new_recipe');
    Route::post('/recipe/save', [\App\Http\Controllers\Recipe::class,'save'])->name('save_recipe');
    Route::get('/recipe/edit/{id}', [\App\Http\Controllers\Recipe::class,'edit'])->name('edit_recipe');
    Route::get('/recipenew/edit/{id}', [\App\Http\Controllers\Recipe::class,'editnew'])->name('editnew_recipe');
    Route::get('/recipe/delete/{id}', [\App\Http\Controllers\Recipe::class,'delete'])->name('delete_recipe');
    Route::post('/upload/gallery/recipe',[\App\Http\Controllers\Recipe::class,'upload_gallery']);
    Route::get('/recipe/show/{id}', [\App\Http\Controllers\Recipe::class,'show'])->name('show_recipe');
    Route::post('/save/extra/options',[\App\Http\Controllers\ExtraOption::class,'save']);
    Route::get('/view/items/{id}',[\App\Http\Controllers\ExtraOption::class,'load_items']);
    Route::get('/edit/option/{id}',[\App\Http\Controllers\ExtraOption::class,'load_option']);
    Route::post('update/option',[\App\Http\Controllers\ExtraOption::class,'update']);
    Route::get('/extra/option/delete/{id}',[\App\Http\Controllers\ExtraOption::class,'delete_option']);
    Route::get('/extra/item/delete/{id}',[\App\Http\Controllers\ExtraOption::class,'delete_item_option']);
    Route::get('/edit/item/{id}',[\App\Http\Controllers\ExtraOption::class,'load_item']);
    Route::post('/update/item',[\App\Http\Controllers\ExtraOption::class,'update_item']);
    Route::post('/save/add/items',[\App\Http\Controllers\ExtraOption::class,'add_new_items']);

    Route::post('/update/mandatory/item',[\App\Http\Controllers\ExtraOption::class,'update_mandatory_item']);
    Route::get('/remove/mandatory/{id}',[\App\Http\Controllers\ExtraOption::class,'remove_mandatory_item']);

    Route::get('/waiters',[\App\Http\Controllers\Waiter::class,'waiters']);
    Route::get('/waiter/new', [\App\Http\Controllers\Waiter::class,'new_waiter'])->name('new_waiter');
    Route::post('/waiter/save', [\App\Http\Controllers\Waiter::class,'save'])->name('save_waiter');
    Route::get('/waiter/edit/{id}', [\App\Http\Controllers\Waiter::class,'edit'])->name('edit_waiter');
    Route::get('/waiter/delete/{id}', [\App\Http\Controllers\Waiter::class,'delete'])->name('delete_waiter');
    Route::get('/waiter/show/{id}', [\App\Http\Controllers\Waiter::class,'show'])->name('show_waiter');
    Route::post('waiter/update/password', [\App\Http\Controllers\Waiter::class,'update_password'])->name('update_password_waiter');
    Route::get('/waiter/get/credentials/{id}', [\App\Http\Controllers\Waiter::class,'generate_credentials'])->name('delete_restaurant');


    Route::get('/orders',[\App\Http\Controllers\Order::class,'orders'])->name('OrderListing');
    Route::get('/order/show/{id}', [\App\Http\Controllers\Order::class,'show'])->name('show_order');
    Route::post('/update/order/status',[\App\Http\Controllers\Order::class,'update_status'])->name('update_order');
    Route::get('/order/counts',[\App\Http\Controllers\Order::class,'all_status_count'])->name('count_orders');
    Route::post('/update/instruction',[\App\Http\Controllers\Order::class,'update_instruction'])->name('update_instruction');

    Route::get('/tables',[\App\Http\Controllers\RestoTable::class,'restoTables']);
    Route::get('/table/new', [\App\Http\Controllers\RestoTable::class,'new_table'])->name('new_table');
    Route::post('/table/save', [\App\Http\Controllers\RestoTable::class,'save'])->name('save_table');
    Route::get('/table/edit/{id}', [\App\Http\Controllers\RestoTable::class,'edit'])->name('edit_tables');
    Route::get('/table/delete/{id}', [\App\Http\Controllers\RestoTable::class,'delete'])->name('delete_table');

    Route::post('/save/offer',[\App\Http\Controllers\SpecialOffer::class,'save_offers']);
    Route::post('/offer/make/active',[\App\Http\Controllers\SpecialOffer::class,'activate_offers']);

    Route::get('/edit/offer/{id}',[\App\Http\Controllers\SpecialOffer::class,'edit_special_offer']);
    Route::get('/delete/offer/{id}',[\App\Http\Controllers\SpecialOffer::class,'delete_special_offer']);


    Route::get('/places',[\App\Http\Controllers\DMCity::class,'places']);
    Route::get('/place/new',[\App\Http\Controllers\DMCity::class,'new_place']);
    Route::get('/place/edit/{id}',[\App\Http\Controllers\DMCity::class,'edit_place']);
    Route::get('/place/delete/{id}',[\App\Http\Controllers\DMCity::class,'delete_place']);
    Route::get('/get/cities/by/country/{code}',[\App\Http\Controllers\DMCity::class,'get_cities']);
    Route::get('/get/places/by/city/{name}',[\App\Http\Controllers\DMCity::class,'get_places']);
    Route::post('/place/save',[\App\Http\Controllers\DMCity::class,'save_place']);
    Route::post('/add/country',[\App\Http\Controllers\DMCity::class,'save_country']);
    Route::post('/save/delivery/fee',[\App\Http\Controllers\DMCity::class,'save_delivery_fee']);


    Route::get('/countries',[\App\Http\Controllers\PlaceManagement::class,'countries']);
    Route::get('/edit/country/{id}',[\App\Http\Controllers\PlaceManagement::class,'edit_country']);
    Route::get('/delete/country/{id}',[\App\Http\Controllers\PlaceManagement::class,'delete_country']);
    Route::post('/update/status/country',[\App\Http\Controllers\PlaceManagement::class,'update_status']);

    Route::get('/place/categories',[\App\Http\Controllers\PlaceManagement::class,'categories']);
    Route::get('/place/edit/category/{id}',[\App\Http\Controllers\PlaceManagement::class,'edit_category']);
    Route::get('/delete/place/category/{id}',[\App\Http\Controllers\PlaceManagement::class,'delete_category']);
    Route::post('/update/status/place/category',[\App\Http\Controllers\PlaceManagement::class,'update_status_category']);
    Route::post('/add/place/category',[\App\Http\Controllers\PlaceManagement::class,'save_place_category']);


    Route::get('/cities',[\App\Http\Controllers\PlaceManagement::class,'cities']);
    Route::get('/edit/city/{id}',[\App\Http\Controllers\PlaceManagement::class,'edit_city']);
    Route::get('/delete/city/{id}',[\App\Http\Controllers\PlaceManagement::class,'delete_city']);
    Route::post('/update/status/city',[\App\Http\Controllers\PlaceManagement::class,'update_status_city']);
    Route::post('/add/city',[\App\Http\Controllers\PlaceManagement::class,'save_city']);

    Route::get('/get/city/by/country/{id}',[\App\Http\Controllers\PlaceManagement::class,'getCityByCountryID']);
    Route::post('/map/category/resto',[\App\Http\Controllers\PlaceManagement::class,'save_resto_category']);
    Route::get('/delete/save/delivery/fee/{id}',[\App\Http\Controllers\PlaceManagement::class,'delete_saved_delivery_fee']);


    Route::get('/read/notifications',[\App\Http\Controllers\Restaurant::class,'read_notifications']);

    Route::get('/get/ajax/orders',[\App\Http\Controllers\Order::class,'ajax_order']);
    Route::get('/order/print/{id}',[\App\Http\Controllers\Order::class,'print_order']);


    Route::get('/marketing',[\App\Http\Controllers\HomeController::class,'marketings'])->name('marketing');
    Route::post('/create/campaign_link',[\App\Http\Controllers\HomeController::class,'create_link']);

    Route::get('/order/history',[\App\Http\Controllers\Order::class,'order_history'])->name('order-history');
    Route::post('/liveorders',[\App\Http\Controllers\Order::class,'load_live_order']);

    Route::get('/get/order/detail/{id}',[\App\Http\Controllers\Order::class,'get_detail_json']);

    Route::get('/outlets',[\App\Http\Controllers\Outlet::class,'outlets'])->name('outlets');
    Route::get('/new/outlet',[\App\Http\Controllers\Outlet::class,'outlet_form'])->name('outlets-form');
    Route::get('/outlet/address',[\App\Http\Controllers\Outlet::class,'outlet_address'])->name('outlets-address');
    Route::get('/outlet/delivery',[\App\Http\Controllers\Outlet::class,'outlet_delivery'])->name('outlets-delivery');
    Route::get('/outlet/ordering-mode',[\App\Http\Controllers\Outlet::class,'outlet_ordering_mode'])->name('outlets-ordering-mode');
    Route::get('/outlet/pickup',[\App\Http\Controllers\Outlet::class,'outlet_pickup'])->name('outlets-pickup');
    Route::get('/outlet/contactless/dining',[\App\Http\Controllers\Outlet::class,'outlet_dining'])->name('outlets-contactless-dining');
    Route::get('/outlet/digital/menu',[\App\Http\Controllers\Outlet::class,'outlet_digital_menu'])->name('outlets-digital-menu');
    Route::post('/save/outlet',[\App\Http\Controllers\Outlet::class,'save_outlet']);
    Route::post('/outlet/update/status',[\App\Http\Controllers\Outlet::class,'update_outlet']);
    Route::get('/outlet/edit/{unique_key}',[\App\Http\Controllers\Outlet::class,'outlet_edit'])->name('OutletEdit');
    Route::post('/save/addrss/outlet',[\App\Http\Controllers\Outlet::class,'save_address']);
    Route::post('/save/features/outlet',[\App\Http\Controllers\Outlet::class,'save_branch_feature']);
    Route::post('/update/outlet/feature/status',[\App\Http\Controllers\Outlet::class,'update_feature_status']);
    Route::get('/outlet/area/delivery',[\App\Http\Controllers\Outlet::class,'outlet_delivery_area_listing'])->name('outlets-delivery-area');
    Route::get('/new/outlet/area',[\App\Http\Controllers\Outlet::class,'outlet_delivery_area'])->name('outlets-new-delivery-area');
    Route::get('/outlet/delete/{id}',[\App\Http\Controllers\Outlet::class,'delete_outlet']);
    Route::post('save/outlet/area',[\App\Http\Controllers\Outlet::class,'save_outlet_area']);
    Route::get('/area/delete/{id}',[\App\Http\Controllers\Outlet::class,'delete_area']);
    Route::get('/area/edit/{id}',[\App\Http\Controllers\Outlet::class,'edit_area']);
    Route::post('/area/update/status',[\App\Http\Controllers\Outlet::class,'update_area_status']);

    Route::get('/get/place/by/city/{id}',[\App\Http\Controllers\PlaceManagement::class,'getPlaceByCityID']);

    Route::post("/remove/recipe/main-image",[\App\Http\Controllers\Recipe::class,'remove_main_image']);

    Route::post('exclude/recipe/outlet',[\App\Http\Controllers\Recipe::class,'exclude_outlet']);

    Route::get('/discounts',[\App\Http\Controllers\Discount::class,'discounts']);
    Route::get('/discount/{id}',[\App\Http\Controllers\Discount::class,'edit_discount']);
    Route::post('/discount/save',[\App\Http\Controllers\Discount::class,'save_discount']);
    Route::get('/delete/discount/{id}',[\App\Http\Controllers\Discount::class,'delete_discount']);
    Route::get('/business/{resto_id}/new/discount',[\App\Http\Controllers\Discount::class,'discount']);
    Route::post('/discount/update/status',[\App\Http\Controllers\Discount::class,'update_status_discount']);
    Route::post('/save/faq',[\App\Http\Controllers\Recipe::class,'save_faq']);
    Route::post('/delete/faq',[\App\Http\Controllers\Recipe::class,'delete_faq']);
    Route::post('/delete/color-image',[\App\Http\Controllers\Recipe::class,'delete_color_image']);
   Route::get('/menus',[\App\Http\Controllers\ShopMenu::class,'menus']);
    Route::get('/menu/edit/{id}',[\App\Http\Controllers\ShopMenu::class,'menu_edit']);

});
