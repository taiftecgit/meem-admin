@php
    $current_page = strtolower(Route::currentRouteName());



    $main_pages = ['dashboard','restaurants','categories'];

    @endphp
<aside class="main-sidebar d-none d-sm-none d-md-none d-lg-block">
    <section class="sidebar position-relative">
        <div class="multinav">
            <div class="multinav-scroll" style="height: 100%;">
                  <ul class="sidebar-menu" data-widget="tree">
                    
<li>
                        <a href="{!! env('APP_URL') !!}blogs">
                            <i class="icon-Home"></i>
                            <span>Blogs</span>

                        </a>

                    </li>
                  </ul>
            </div>
 <div class="sidebar-footer">
             <div class="dropdown">

                <button class="footer-drop btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="icon-User"><span class="path1"></span><span class="path2"></span></i>
                    <span class="resto-name-long">Admin User</span>

                    <span class="pull-right-container">
                                  <i class="fa fa-angle-right pull-right"></i>
                                </span>
                </button>
                <ul class="dropdown-menu footer-drop" aria-labelledby="dropdownMenu2" data-popper-placement="top-start">

               
                    
                    <li><button class="dropdown-item" type="button" onclick="location.href='{!! env('APP_URL') !!}logout'">Logout</button></li>
                </ul>
            </div>
        </div>
    </section>
</aside>


