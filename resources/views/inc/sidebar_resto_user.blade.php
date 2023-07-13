@php

    $current_page = strtolower(Route::currentRouteName());

    $permissions = [];

$role = \Illuminate\Support\Facades\Auth::user()->resto_users->role;
   

    if(\Illuminate\Support\Facades\Auth::user()->role=='resto_user'){
        $permissions = $roles[\Illuminate\Support\Facades\Auth::user()->resto_users->role];

    }




    $main_pages = ['dashboard','orderlisting','show_order','marketing','order-history','outlets','outlets-form','outlets-address','outlets-delivery','outlets-ordering-mode','outlets-pickup','outlets-contactless-dining','categories','recipes','pause-orders'];
$outlet = ['outlets','outlets-form','outlets-address','outlets-delivery','outlets-ordering-mode','outlets-pickup','outlets-contactless-dining','invetory'];

$resto_id = \App\Helpers\CommonMethods::getRestuarantID();

    @endphp
    <style type="text/css">
        
        .resto-name-long{
                max-width: 150px;
    display: inline-block;
    white-space: nowrap;
    overflow: hidden !important;
    text-overflow: ellipsis;
    top: 4px;
    position: relative
        }
		
		.theme-primary.light-skin .sidebar-menu > li:hover svg, .theme-primary.light-skin .sidebar-menu > li:active svg, .theme-primary.light-skin .sidebar-menu > li.active svg{
			 fill:#FFF !important;
		 }
		 .sidebar-menu  li svg{
			 margin-top:-8px;
			 
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
                            <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M13 9V3h8v6ZM3 13V3h8v10Zm10 8V11h8v10ZM3 21v-6h8v6Zm2-10h4V5H5Zm10 8h4v-6h-4Zm0-12h4V5h-4ZM5 19h4v-2H5Zm4-8Zm6-4Zm0 6Zm-6 4Z"/></svg>
                            <span>{{__('label.dashboard')}}</span>

                        </a>

                    </li>

                    @if(in_array('orderlisting',$permissions) || $role=="administrator")

                    <li class="@if(in_array($current_page,$main_pages)  && ($current_page=="orderlisting" || $current_page=="show_order")) active @endif">
                        <a href="{!! env('APP_URL') !!}orders">
                             <i class="icon-Clipboard-check"><svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M6 20q-1.25 0-2.125-.875T3 17H1V6q0-.825.588-1.412Q2.175 4 3 4h14v4h3l3 4v5h-2q0 1.25-.875 2.125T18 20q-1.25 0-2.125-.875T15 17H9q0 1.25-.875 2.125T6 20Zm0-2q.425 0 .713-.288Q7 17.425 7 17t-.287-.712Q6.425 16 6 16t-.713.288Q5 16.575 5 17t.287.712Q5.575 18 6 18Zm-3-3h.8q.425-.45.975-.725Q5.325 14 6 14t1.225.275q.55.275.975.725H15V6H3Zm15 3q.425 0 .712-.288Q19 17.425 19 17t-.288-.712Q18.425 16 18 16t-.712.288Q17 16.575 17 17t.288.712Q17.575 18 18 18Zm-1-5h4.25L19 10h-2Zm-8-2.5Z"/></svg></i>
                            @php
                                $order = \App\Orders::select(DB::raw(' count(status) as status_count'),'status')->where('resto_id',$resto_id)
                                ->where('status','Placed')->groupBy('status')->first();
                          $count = isset($order)?$order->status_count:0;
                            @endphp
                            <span>{{__('label.live_orders')}}</span><span class="notification-badge" id="order-counter" style="font-size: 75%; @if($count == 0) display:none; background-color: red !important @endif">{!! $count !!}</span>

                        </a>

                    </li>
                    @endif
                     @if(in_array('order-history',$permissions) || $role=="administrator")
                    <li class="@if(in_array($current_page,$main_pages)  && ($current_page=="order-history")) active @endif">
                        <a href="{!! env('APP_URL') !!}order/history">
                            <i class="icon-Library">
							<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M3 10V4h2v2.35q1.275-1.6 3.113-2.475Q9.95 3 12 3q3.75 0 6.375 2.625T21 12h-2q0-2.925-2.038-4.963Q14.925 5 12 5q-1.725 0-3.225.8T6.25 8H9v2Zm.05 3H5.1q.3 2.325 1.913 3.938 1.612 1.612 3.862 1.962l1.2 2.1q-3.45 0-6.05-2.288Q3.425 16.425 3.05 13Zm10.3 1.75L11 12.4V7h2v4.6l1.4 1.4ZM17.975 24l-.3-1.5q-.3-.125-.562-.262-.263-.138-.538-.338l-1.45.45-1-1.7 1.15-1q-.05-.325-.05-.65t.05-.65l-1.15-1 1-1.7 1.45.45q.275-.2.538-.338.262-.137.562-.262l.3-1.5h2l.3 1.5q.3.125.575.287.275.163.525.363l1.45-.5 1 1.75-1.15 1q.05.325.05.625t-.05.625l1.15 1-1 1.7-1.45-.45q-.275.2-.537.338-.263.137-.563.262l-.3 1.5Zm1-3q.825 0 1.413-.587.587-.588.587-1.413 0-.825-.587-1.413Q19.8 17 18.975 17q-.825 0-1.413.587-.587.588-.587 1.413 0 .825.587 1.413.588.587 1.413.587Z"/></svg>
							</i>
                            <span>{{__('label.order_history')}}</span>

                        </a>

                    </li>
                    @endif
                    @if(in_array('outlets',$permissions) || $role=="administrator")
                    <li class="@if(in_array($current_page,$outlet)) active @endif">
                        <a href="{!! env('APP_URL') !!}outlets">
                            <i class="icon-Cart"><svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M4 6V4h16v2Zm0 14v-6H3v-2l1-5h16l1 5v2h-1v6h-2v-6h-4v6Zm2-2h6v-4H6Zm-.95-6h13.9Zm0 0h13.9l-.6-3H5.65Z"/></svg></i>
                            <span>{{__('label.outlets')}} </span>

                        </a>

                    </li>
                    @endif

                    @if(in_array('categories',$permissions) || $role=="administrator")
                    <li class="@if(in_array($current_page,$main_pages) && ($current_page=="categories")) active @endif">
                        <a href="{!! env('APP_URL') !!}categories">
                            <i class="icon-Chart-pie">
							<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M6.5 11 12 2l5.5 9Zm11 11q-1.875 0-3.188-1.312Q13 19.375 13 17.5q0-1.875 1.312-3.188Q15.625 13 17.5 13q1.875 0 3.188 1.312Q22 15.625 22 17.5q0 1.875-1.312 3.188Q19.375 22 17.5 22ZM3 21.5v-8h8v8ZM17.5 20q1.05 0 1.775-.725Q20 18.55 20 17.5q0-1.05-.725-1.775Q18.55 15 17.5 15q-1.05 0-1.775.725Q15 16.45 15 17.5q0 1.05.725 1.775Q16.45 20 17.5 20ZM5 19.5h4v-4H5ZM10.05 9h3.9L12 5.85ZM12 9Zm-3 6.5Zm8.5 2Z"/></svg>
							</i>
                            <span>{{__('label.categories')}} </span>

                        </a>

                    </li>
                    @endif
                    @if(in_array('invetory',$permissions) || $role=="administrator")
                     <li class="@if(in_array($current_page,$main_pages) && ($current_page=="invetory")) active @endif">
                        <a href="{!! env('APP_URL') !!}inventory">
                            <i class="icon-Chart-pie">
							
<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M3 20V8.7q-.425-.275-.712-.7Q2 7.575 2 7V4q0-.825.588-1.413Q3.175 2 4 2h16q.825 0 1.413.587Q22 3.175 22 4v3q0 .575-.288 1-.287.425-.712.7V20q0 .825-.587 1.413Q19.825 22 19 22H5q-.825 0-1.413-.587Q3 20.825 3 20ZM5 9v11h14V9Zm15-2V4H4v3ZM9 14h6v-2H9Zm-4 6V9v11Z"/></svg>
							</i>
                            <span>{{__('label.inventory')}} </span>

                        </a>

                    </li>
                    @endif
                      @if(in_array('pause-orders',$permissions) || $role=="administrator")
                    <li class="@if(in_array($current_page,$main_pages) && ($current_page=="pause-orders")) active @endif">
                        <a href="{!! env('APP_URL') !!}pause/orders">
                            <i class="icon-Chart-pie">
								<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M9 16h2V8H9Zm4 0h2V8h-2Zm-1 6q-2.075 0-3.9-.788-1.825-.787-3.175-2.137-1.35-1.35-2.137-3.175Q2 14.075 2 12t.788-3.9q.787-1.825 2.137-3.175 1.35-1.35 3.175-2.138Q9.925 2 12 2t3.9.787q1.825.788 3.175 2.138 1.35 1.35 2.137 3.175Q22 9.925 22 12t-.788 3.9q-.787 1.825-2.137 3.175-1.35 1.35-3.175 2.137Q14.075 22 12 22Zm0-2q3.35 0 5.675-2.325Q20 15.35 20 12q0-3.35-2.325-5.675Q15.35 4 12 4 8.65 4 6.325 6.325 4 8.65 4 12q0 3.35 2.325 5.675Q8.65 20 12 20Zm0-8Z"/></svg>
							</i>
                            <span>{{__('label.pause_orders')}} </span>

                        </a>

                    </li>
                    @endif
					
					

                      @if(in_array('recipes',$permissions) || $role=="administrator")
                    <li class="@if(in_array($current_page,$main_pages) && ($current_page=="recipes")) active @endif">
                        <a href="{!! env('APP_URL') !!}recipes">
                           <i class="icon-Chart-pie">
								 
								 <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M6 22q-1.25 0-2.125-.875T3 19v-3h3V2l1.5 1.5L9 2l1.5 1.5L12 2l1.5 1.5L15 2l1.5 1.5L18 2l1.5 1.5L21 2v17q0 1.25-.875 2.125T18 22Zm12-2q.425 0 .712-.288Q19 19.425 19 19V5H8v11h9v3q0 .425.288.712.287.288.712.288ZM9 9V7h6v2Zm0 3v-2h6v2Zm8-3q-.425 0-.712-.288Q16 8.425 16 8t.288-.713Q16.575 7 17 7t.712.287Q18 7.575 18 8t-.288.712Q17.425 9 17 9Zm0 3q-.425 0-.712-.288Q16 11.425 16 11t.288-.713Q16.575 10 17 10t.712.287Q18 10.575 18 11t-.288.712Q17.425 12 17 12ZM6 20h9v-2H5v1q0 .425.287.712Q5.575 20 6 20Zm-1 0v-2 2Z"/></svg>
							</i>
                            <span>{{__('label.items')}} </span>

                        </a>

                    </li>
                    @endif
					
					@if(in_array('marketing',$permissions) || $role=="administrator")
					<li class="@if(in_array($current_page,$main_pages) && ($current_page=="discounts")) active @endif">
                        <a href="{!! env('APP_URL') !!}discounts">
                            <i class="icon-Dinner">
							
<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M14.35 8.55q-.3-.75-.887-1.175-.588-.425-1.413-.425-.45 0-.875.125t-.775.475L8.95 6.1q.35-.35.95-.637.6-.288 1.1-.363V3h2v2.05q1.125.225 1.975.912.85.688 1.275 1.788ZM19.8 22.6 15.2 18q-.375.375-1.025.613-.65.237-1.175.287V21h-2v-2.15q-1.4-.35-2.337-1.275-.938-.925-1.363-2.325l2-.8q.3 1.05 1.012 1.8.713.75 1.888.75.45 0 .825-.113.375-.112.725-.337L1.4 4.2l1.4-1.4 18.4 18.4Z"/></svg>
							</i>
                            <span>{{__('label.discounts')}} </span>

                        </a>

                    </li>
					@endif
					
					
                    @if(in_array('marketing',$permissions) || $role=="administrator")
                    <li class="@if(in_array($current_page,$main_pages)  && ($current_page=="marketing")) active @endif">
                        <a href="{!! env('APP_URL') !!}marketing">
                           <i class="icon-Group">
							<svg xmlns="http://www.w3.org/2000/svg" height="26" width="26"><path d="M18 13v-2h4v2Zm1.2 7L16 17.6l1.2-1.6 3.2 2.4Zm-2-12L16 6.4 19.2 4l1.2 1.6ZM5 19v-4H4q-.825 0-1.412-.588Q2 13.825 2 13v-2q0-.825.588-1.413Q3.175 9 4 9h4l5-3v12l-5-3H7v4Zm9-3.65v-6.7q.675.6 1.088 1.463.412.862.412 1.887t-.412 1.887q-.413.863-1.088 1.463ZM4 11v2h4.55L11 14.45v-4.9L8.55 11Zm3.5 1Z"/></svg>
							</i>
                            <span>{{__('label.marketing')}} </span>

                        </a>

                    </li>
                    @endif
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
                    <span  class="resto-name-long">
                    @php
                     
                        $resto = \App\Restaurants::find($resto_id);
                @endphp
                {!! $resto->name !!}
            </span>

                    <span class="pull-right-container">
								  <i class="fa fa-angle-right pull-right"></i>
								</span>
                </button>
              
                <ul class="dropdown-menu footer-drop mx-40" aria-labelledby="dropdownMenu2" data-popper-placement="top-start">
                    <li><button class="dropdown-item" type="button" onclick="location.href='{!! env('APP_URL') !!}logout'">Logout</button></li>
                </ul>
            </div>
        </div>
    </section>
</aside>



