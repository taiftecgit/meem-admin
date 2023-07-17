@php
    $current_page = strtolower(Route::currentRouteName());

    $permissions = [];

    if(\Illuminate\Support\Facades\Auth::user()->role=='resto_user'){
        $permissions = $roles[\Illuminate\Support\Facades\Auth::user()->resto_users->role];
    }




    $main_pages = ['dashboard','orderlisting','show_order','marketing','order-history','outlets','outlets-form','outlets-address',
    'outlets-delivery','outlets-ordering-mode','outlets-pickup','outlets-contactless-dining','pause-orders','inventory','discounts','payment-links'];
$outlet = ['outlets','outlets-form','outlets-address','outlets-delivery','outlets-ordering-mode','outlets-pickup','outlets-contactless-dining'];
$menu_pages = ['recipes','categories'];
$resto_id = \App\Helpers\CommonMethods::getRestuarantID();

$mta = \App\Models\RestoMetas::where('bussiness_id',$resto_id)->where('meta_def_id',84)->first();


    @endphp
     <style type="text/css">

        .resto-name-long{
                max-width: 150px;
    display: inline-block;
    white-space: nowrap;
    overflow: hidden !important;
    text-overflow: ellipsis;
    top: 4px;
    position: relative;
			color:#000;
        }

        @if(app()->getLocale()=="ar")
        .treeview-menu > li > a{
            padding: 5px 25px 5px 25px;
        }
        @endif

		 .theme-primary.light-skin .sidebar-menu > li:hover svg, .theme-primary.light-skin .sidebar-menu > li:active svg, .theme-primary.light-skin .sidebar-menu > li.active svg{
			 fill:#FFF !important;
		 }
		 .sidebar-menu  li svg{
			 margin-top:-8px;

		 }
         .close-button{
             position: absolute;
         }

        .sidebar-menu li > a > .pull-right-container, .sidebar-menu li > a > .pull-left-container{
            font-size: 1.5714285714rem;
            top:40%
        }
        .theme-primary.light-skin .sidebar-menu > li.active.treeview > a{
            color:#FFF !important;
        }
        .treeview li.active a{
            color:#FFF !important;
        }
        .sidebar-menu .menu-open > a > .pull-right-container > .fa-angle-right, .sidebar-menu .menu-open > a > .pull-right-container > .fa-angle-left{
            color:#FFAB00
        }
        .sidebar-menu .menu-open > a > .pull-left-container > .fa-angle-left {
            -webkit-transform: rotate(270deg);
            -ms-transform: rotate(270deg);
            -o-transform: rotate(270deg);
            transform: rotate(270deg);
        }
        .treeview-menu li:hover a,.treeview-menu li:hover svg{
            color:#fff !important;
        }

        .treeview-menu li.active a,.treeview-menu li.active svg{
            color:#fff !important;
        }
    </style>
<aside class="main-sidebar d-none d-sm-none d-md-none d-lg-block">
    <!-- sidebar-->
    <section class="sidebar position-relative">
        <div class="multinav">
            <div class="multinav-scroll" style="height: 100%;">
                <!-- sidebar menu-->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="@if(in_array($current_page,$main_pages) && $current_page=="dashboard") active @endif">
                        <a href="{!! env('APP_URL') !!}dashboard">
                            <i class="icon-Library">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3"/><path fill="currentColor" d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z"/></svg>	</i>
                            <span>{{__('label.dashboard')}}</span>

                        </a>

                    </li>

                    <li class="@if(in_array($current_page,$main_pages)  && ($current_page=="orderlisting" || $current_page=="show_order")) active @endif">

                        <a href="{!! env('APP_URL') !!}orders">
                            <i class="icon-Clipboard-check"><svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M6 20q-1.25 0-2.125-.875T3 17H1V6q0-.825.588-1.412Q2.175 4 3 4h14v4h3l3 4v5h-2q0 1.25-.875 2.125T18 20q-1.25 0-2.125-.875T15 17H9q0 1.25-.875 2.125T6 20Zm0-2q.425 0 .713-.288Q7 17.425 7 17t-.287-.712Q6.425 16 6 16t-.713.288Q5 16.575 5 17t.287.712Q5.575 18 6 18Zm-3-3h.8q.425-.45.975-.725Q5.325 14 6 14t1.225.275q.55.275.975.725H15V6H3Zm15 3q.425 0 .712-.288Q19 17.425 19 17t-.288-.712Q18.425 16 18 16t-.712.288Q17 16.575 17 17t.288.712Q17.575 18 18 18Zm-1-5h4.25L19 10h-2Zm-8-2.5Z"/></svg></i>
                            @php
                                $order = \App\Models\Orders::select(DB::raw(' count(status) as status_count'),'status')->where('resto_id',$resto_id)
                                ->where('status','Placed')->groupBy('status')->first();
                          $count = isset($order)?$order->status_count:0;
                            @endphp
                            <span>{{__('label.live_orders')}}</span><span class="notification-badge" @if(app()->getLocale()=="ar") dir="rtl" @endif  id="order-counter" style="font-size: 75%; @if($count == 0) display:none; background-color: red !important @endif">{!! $count !!}</span>

                        </a>

                    </li>
                    <li class="@if(in_array($current_page,$main_pages)  && ($current_page=="order-history")) active @endif">
                        <a href="{!! env('APP_URL') !!}order/history">
                            <i class="icon-Library">
							<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M3 10V4h2v2.35q1.275-1.6 3.113-2.475Q9.95 3 12 3q3.75 0 6.375 2.625T21 12h-2q0-2.925-2.038-4.963Q14.925 5 12 5q-1.725 0-3.225.8T6.25 8H9v2Zm.05 3H5.1q.3 2.325 1.913 3.938 1.612 1.612 3.862 1.962l1.2 2.1q-3.45 0-6.05-2.288Q3.425 16.425 3.05 13Zm10.3 1.75L11 12.4V7h2v4.6l1.4 1.4ZM17.975 24l-.3-1.5q-.3-.125-.562-.262-.263-.138-.538-.338l-1.45.45-1-1.7 1.15-1q-.05-.325-.05-.65t.05-.65l-1.15-1 1-1.7 1.45.45q.275-.2.538-.338.262-.137.562-.262l.3-1.5h2l.3 1.5q.3.125.575.287.275.163.525.363l1.45-.5 1 1.75-1.15 1q.05.325.05.625t-.05.625l1.15 1-1 1.7-1.45-.45q-.275.2-.537.338-.263.137-.563.262l-.3 1.5Zm1-3q.825 0 1.413-.587.587-.588.587-1.413 0-.825-.587-1.413Q19.8 17 18.975 17q-.825 0-1.413.587-.587.588-.587 1.413 0 .825.587 1.413.588.587 1.413.587Z"/></svg>
							</i>
                            <span>{{__('label.order_history')}}</span>

                        </a>

                    </li>

                    <li class="@if(in_array($current_page,$outlet)) active @endif">
                        <a href="{!! env('APP_URL') !!}outlets">
                            <i class="icon-Cart"><!--<span class="path1"></span><span class="path2"></span>-->

<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M4 6V4h16v2Zm0 14v-6H3v-2l1-5h16l1 5v2h-1v6h-2v-6h-4v6Zm2-2h6v-4H6Zm-.95-6h13.9Zm0 0h13.9l-.6-3H5.65Z"/></svg>
							</i>

                            <span>{{__('label.outlets')}} </span>

                        </a>

                    </li>




                    <li class="treeview @if(in_array($current_page,$menu_pages))  active menu-open @endif">
                        <a href="#">
                            <i class="icon-Clipboard-check">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-menu-button-fill" viewBox="0 0 16 16">
                                    <path d="M1.5 0A1.5 1.5 0 0 0 0 1.5v2A1.5 1.5 0 0 0 1.5 5h8A1.5 1.5 0 0 0 11 3.5v-2A1.5 1.5 0 0 0 9.5 0h-8zm5.927 2.427A.25.25 0 0 1 7.604 2h.792a.25.25 0 0 1 .177.427l-.396.396a.25.25 0 0 1-.354 0l-.396-.396zM0 8a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8zm1 3v2a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2H1zm14-1V8a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v2h14zM2 8.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0 4a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5z"/>
                                </svg>
                            </i>
                            <span>{{__('label.main_menu')}}</span>
                            <span class=" @if(app()->getLocale()=="ar") pull-left-container @else pull-right-container @endif">
					  <i class=" @if(app()->getLocale()=="ar") fa fa-angle-left pull-left @else fa fa-angle-right pull-right @endif"></i>
					</span>
                        </a>
                        <ul class="treeview-menu" @if(in_array($current_page,$menu_pages)) style="display: block;" @else style="display: none;" @endif>
                            <li @if(in_array($current_page,$menu_pages) && $current_page=="categories") class="active" @endif><a href="{!! env('APP_URL') !!}categories"><i class="icon-Commit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><circle cx="17.5" cy="17.5" r="2.5" fill="currentColor" opacity=".3"/><path fill="currentColor" d="M5 15.5h4v4H5zm7-9.66L10.07 9h3.86z" opacity=".3"/><path fill="currentColor" d="m12 2l-5.5 9h11L12 2zm0 3.84L13.93 9h-3.87L12 5.84zM17.5 13c-2.49 0-4.5 2.01-4.5 4.5s2.01 4.5 4.5 4.5s4.5-2.01 4.5-4.5s-2.01-4.5-4.5-4.5zm0 7a2.5 2.5 0 0 1 0-5a2.5 2.5 0 0 1 0 5zM11 13.5H3v8h8v-8zm-2 6H5v-4h4v4z"/></svg>
                                    </i>{{__('label.categories')}}</a></li>
                            <li @if(in_array($current_page,$menu_pages) && $current_page=="recipes") class="active" @endif><a href="{!! env('APP_URL') !!}recipes"><i class="icon-Commit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><circle cx="5" cy="19" r="1" fill="currentColor"/><path fill="currentColor" d="M4 4h2v9H4z"/><path fill="currentColor" d="M7 2H3a1 1 0 0 0-1 1v18a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1Zm0 19H3V3h4Z"/><circle cx="12" cy="19" r="1" fill="currentColor"/><path fill="currentColor" d="M11 4h2v9h-2z"/><path fill="currentColor" d="M14 2h-4a1 1 0 0 0-1 1v18a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1Zm0 19h-4V3h4Z"/><circle cx="19" cy="19" r="1" fill="currentColor"/><path fill="currentColor" d="M18 4h2v9h-2z"/><path fill="currentColor" d="M21 2h-4a1 1 0 0 0-1 1v18a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1Zm0 19h-4V3h4Z"/></svg>
                                    </i>{{__('label.items')}}</a></li>
                        </ul>
                    </li>



                     <li class="@if(in_array($current_page,$main_pages) && ($current_page=="inventory")) active @endif">
                        <a href="{!! env('APP_URL') !!}inventory">
                            <i class="icon-Chart-pie">

<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M3 20V8.7q-.425-.275-.712-.7Q2 7.575 2 7V4q0-.825.588-1.413Q3.175 2 4 2h16q.825 0 1.413.587Q22 3.175 22 4v3q0 .575-.288 1-.287.425-.712.7V20q0 .825-.587 1.413Q19.825 22 19 22H5q-.825 0-1.413-.587Q3 20.825 3 20ZM5 9v11h14V9Zm15-2V4H4v3ZM9 14h6v-2H9Zm-4 6V9v11Z"/></svg>
							</i>
                            <span>{{__('label.inventory')}} </span>

                        </a>

                    </li>
                    <li class="@if(in_array($current_page,$main_pages) && ($current_page=="pause-orders")) active @endif">
                        <a href="{!! env('APP_URL') !!}pause/orders">
                            <i class="icon-Chart-pie">
								<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M9 16h2V8H9Zm4 0h2V8h-2Zm-1 6q-2.075 0-3.9-.788-1.825-.787-3.175-2.137-1.35-1.35-2.137-3.175Q2 14.075 2 12t.788-3.9q.787-1.825 2.137-3.175 1.35-1.35 3.175-2.138Q9.925 2 12 2t3.9.787q1.825.788 3.175 2.138 1.35 1.35 2.137 3.175Q22 9.925 22 12t-.788 3.9q-.787 1.825-2.137 3.175-1.35 1.35-3.175 2.137Q14.075 22 12 22Zm0-2q3.35 0 5.675-2.325Q20 15.35 20 12q0-3.35-2.325-5.675Q15.35 4 12 4 8.65 4 6.325 6.325 4 8.65 4 12q0 3.35 2.325 5.675Q8.65 20 12 20Zm0-8Z"/></svg>
							</i>
                            <span>{{__('label.pause_orders')}} </span>

                        </a>

                    </li>




					<li class="@if(in_array($current_page,$main_pages) && ($current_page=="discounts")) active @endif">
                        <a href="{!! env('APP_URL') !!}discounts">
                            <i class="icon-Dinner">

<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M14.35 8.55q-.3-.75-.887-1.175-.588-.425-1.413-.425-.45 0-.875.125t-.775.475L8.95 6.1q.35-.35.95-.637.6-.288 1.1-.363V3h2v2.05q1.125.225 1.975.912.85.688 1.275 1.788ZM19.8 22.6 15.2 18q-.375.375-1.025.613-.65.237-1.175.287V21h-2v-2.15q-1.4-.35-2.337-1.275-.938-.925-1.363-2.325l2-.8q.3 1.05 1.012 1.8.713.75 1.888.75.45 0 .825-.113.375-.112.725-.337L1.4 4.2l1.4-1.4 18.4 18.4Z"/></svg>
							</i>
                            <span>{{__('label.discounts')}} </span>

                        </a>

                    </li>
                    <li class="@if(in_array($current_page,$main_pages)  && ($current_page=="marketing")) active @endif">
                        <a href="{!! env('APP_URL') !!}marketing">
                            <i class="icon-Group">
							<svg xmlns="http://www.w3.org/2000/svg" height="26" width="26"><path d="M18 13v-2h4v2Zm1.2 7L16 17.6l1.2-1.6 3.2 2.4Zm-2-12L16 6.4 19.2 4l1.2 1.6ZM5 19v-4H4q-.825 0-1.412-.588Q2 13.825 2 13v-2q0-.825.588-1.413Q3.175 9 4 9h4l5-3v12l-5-3H7v4Zm9-3.65v-6.7q.675.6 1.088 1.463.412.862.412 1.887t-.412 1.887q-.413.863-1.088 1.463ZM4 11v2h4.55L11 14.45v-4.9L8.55 11Zm3.5 1Z"/></svg>
							</i>
                            <span>{{__('label.marketing')}} </span>

                        </a>

                    </li>
                    <li class="@if(in_array($current_page,$main_pages)  && ($current_page=="payment-links")) active @endif">
                        <a href="{!! env('APP_URL') !!}payment/links">
                            <i class="icon-Group">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-credit-card-fill" viewBox="0 0 16 16">
                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1H0V4zm0 3v5a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7H0zm3 2h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1a1 1 0 0 1 1-1z"/>
                                </svg>
                            </i>
                            <span>{{__('label.paymentlinks')}} </span>

                        </a>

                    </li>


					@if(isset($mta))
<li class="@if(in_array($current_page,$main_pages) && ($current_page=="categories") && isset($_GET['menu'])) active @endif">
                        <a href="{!! env('APP_URL') !!}categories?menu=tablet">
                            <i class="icon-Chart-pie">
							<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M6.5 11 12 2l5.5 9Zm11 11q-1.875 0-3.188-1.312Q13 19.375 13 17.5q0-1.875 1.312-3.188Q15.625 13 17.5 13q1.875 0 3.188 1.312Q22 15.625 22 17.5q0 1.875-1.312 3.188Q19.375 22 17.5 22ZM3 21.5v-8h8v8ZM17.5 20q1.05 0 1.775-.725Q20 18.55 20 17.5q0-1.05-.725-1.775Q18.55 15 17.5 15q-1.05 0-1.775.725Q15 16.45 15 17.5q0 1.05.725 1.775Q16.45 20 17.5 20ZM5 19.5h4v-4H5ZM10.05 9h3.9L12 5.85ZM12 9Zm-3 6.5Zm8.5 2Z"/></svg>
							</i>
                            <span>Tablet Menu </span>

                        </a>

                    </li>
@endif
                    {{--<li class="treeview">
                        <a href="#">
                            <i class="icon-Clipboard-check"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            <span>Settings</span>
                            <span class="pull-right-container">
					  <i class="fa fa-angle-right pull-right"></i>
					</span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{!! env('APP_URL') !!}restaurant/edit/{!! \App\Helpers\CommonMethods::encrypt($resto_id) !!}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Restaurant's Settings</a></li>
                        </ul>
                    </li>--}}
                </ul>

            </div>
        </div>
        <div class="sidebar-footer">
            <!-- <ul class="sidebar-menu tree" data-widget="tree">
                <li>
                       <a >
                           <i class="icon-User"><span class="path1"></span><span class="path2"></span></i>
                           <span>Users</span>
                       </a>
                     </li>
            </ul> -->
            <div class="dropdown">

                <button class="footer-drop btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="icon-User"><span class="path1"></span><span class="path2"></span></i>
                    <span class="resto-name-long">
                    @php

                        $resto = \App\Models\Restaurants::find($resto_id);
                @endphp
						@if($resto->default_lang=="en")
                			{!! $resto->name !!}
						@endif
						@if($resto->default_lang=="ar")
                			{!! $resto->arabic_name !!}
						@endif

            </span>

                    <span class="pull-right-container">
								  <i class="fa fa-angle-right pull-right"></i>
								</span>
                </button>

                <ul class="dropdown-menu footer-drop mx-40" aria-labelledby="dropdownMenu2" data-popper-placement="top-start">
                    @if(\Illuminate\Support\Facades\Auth::user()->role=="restaurant")
                   <li><button class="dropdown-item" onclick="location.href='{!! env('APP_URL') !!}users'" type="button">{{__('label.users')}} </button></li>
                    <li><button class="dropdown-item" type="button" onclick="location.href='{!! env('APP_URL') !!}restaurant/edit/{!! \App\Helpers\CommonMethods::encrypt($resto_id) !!}'">{{__('label.business_settings')}}</button></li>
                    @endif
					<li><button class="dropdown-item" type="button" onclick="location.href='{!! env('APP_URL') !!}change/password'">{{__('label.change_password')}}</button></li>
                    <li><button class="dropdown-item" type="button" onclick="location.href='{!! env('APP_URL') !!}logout'">{{__('label.logout')}}</button></li>
                </ul>
            </div>
        </div>
    </section>
</aside>



