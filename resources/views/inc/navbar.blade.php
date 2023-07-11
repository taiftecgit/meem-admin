<nav class="sb-topnav navbar navbar-expand navbar-light bg-white shadow-sm">
    <a class="navbar-brand" href="{!! env('APP_URL') !!}dashboard">
            @if(\Illuminate\Support\Facades\Auth::user()->role=="restaurant" && isset(\Illuminate\Support\Facades\Auth::user()->restaurants->photos))
                <img class="img-fluid" style="height: 40px" alt="logo" src="{!! \Illuminate\Support\Facades\Auth::user()->restaurants->photos->file_name !!}"
                     alt="{!! \Illuminate\Support\Facades\Auth::user()->restaurants->name !!}">
            @else
                 <img style="max-height: 40px"  alt="logo" src="{!! env('APP_ASSETS') !!}img/logo.png">
            @endif
    </a>
    <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2" />
            <div class="input-group-append">
                <button class="btn btn-primary btn-sm" type="button"><i class="feather-search"></i></button>
            </div>
        </div>
    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ml-auto ml-md-0">
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="feather-search mr-2"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow-sm animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control border-0 shadow-none" placeholder="Search people, jobs and more..." aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn" type="button">
                                <i class="feather-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        @if(\Illuminate\Support\Facades\Auth::user()->role=="restaurant" && isset(\Illuminate\Support\Facades\Auth::user()->restaurants->order_notifications_unread))
@php
$notifications = \Illuminate\Support\Facades\Auth::user()->restaurants->order_notifications_unread;
@endphp
        <li class="nav-item dropdown no-arrow mx-1 osahan-list-dropdown notifications">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="feather-bell"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-info badge-counter "  data-count="{!! $notifications->count() > 0?$notifications->count():0 !!}">{!! $notifications->count() > 0?$notifications->count():"" !!}</span>
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow-sm">
                <h6 class="dropdown-header">
                    Notifications
                </h6>
                <div id="notifications" style="max-height: 300px; overflow: auto">
                    @if(isset($notifications) && $notifications->count() > 0)
                        @foreach($notifications as $notification)
                        <a class="dropdown-item d-flex align-items-center" href="{!! env('APP_URL') !!}order/show/{!! $notification->order_id !!}" target="_blank">
                            <div class="mr-3">
                                <div class="icon-circle bg-warning">
                                    <i class="fas fa-folder text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">{!! $notification->created_at !!}</div>
                               {!! $notification->notification_text !!}
                            </div>
                        </a>
                        @endforeach
                        @endif
                </div>


                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Notifications</a>
            </div>
        </li>
        @endif
        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow ml-1 osahan-profile-dropdown">
            <a class="nav-link dropdown-toggle pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                @if(\Illuminate\Support\Facades\Auth::user()->role=="restaurant" && isset(\Illuminate\Support\Facades\Auth::user()->restaurants->photos))
                    <img class="img-profile rounded-circle" src="{!! \Illuminate\Support\Facades\Auth::user()->restaurants->photos->file_name !!}">
                    @else
                    <img class="img-profile rounded-circle" src="{!! env('APP_ASSETS') !!}img/user/1.png">
                @endif
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow-sm">
                <div class="p-3 d-flex align-items-center">
                    <div class="dropdown-list-image mr-3">
                        @if(\Illuminate\Support\Facades\Auth::user()->role=="restaurant" && isset(\Illuminate\Support\Facades\Auth::user()->restaurants->photos))
                            <img class="rounded-circle" src="{!! \Illuminate\Support\Facades\Auth::user()->restaurants->photos->file_name !!}" alt="">
                        @else
                            <img class="rounded-circle" src="{!! env('APP_ASSETS') !!}img/user/1.png" alt="">
                            @endif
                        <div class="status-indicator bg-success"></div>
                    </div>
                    <div class="font-weight-bold">
@if(\Illuminate\Support\Facades\Auth::user()->role=="restaurant")
<div class="text-truncate">{!! \Illuminate\Support\Facades\Auth::user()->restaurants->name !!}</div>
                            <div class="small text-gray-500">Restaurants Owner</div>
    @else
                            <div class="text-truncate">Administrator</div>
                        @endif

</div>
</div>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{!! env('APP_URL') !!}change/password"><i class="feather-edit"></i> Change Password</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{!! env('APP_URL') !!}logout"><i class="feather-log-out"></i> Logout</a>
</div>
</li>
</ul>
</nav>