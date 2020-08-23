
<header class="main-header">
  <!-- Logo -->


    <a href="/" class="logo" style="background-image: url('{{asset("images/logo1.png")}}');
        background-repeat: no-repeat; background-size: cover">
        <!-- mini logo for sidebar mini 50x50 pixels -->
    </a>

  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top" style="background-color: transparent!important; box-shadow: 0 0 transparent!important;">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle hovering" data-toggle="push-menu" role="button"
       style="box-shadow: 0px 2px 3px black; margin-top: 2vh; margin-right: 1vw; border-radius: 0.5rem;">
      <span class="sr-only">Toggle navigation</span>
    </a>

    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
          <li class="dropdown notifications-menu">
              <a href="#" class="dropdown-toggle hovering" data-toggle="dropdown" aria-expanded="true"
              style="box-shadow: 0px 2px 3px black; margin-top: 1vh; border-radius: 0.5rem;">
                  <i class="mdi mdi-bell"></i>
              </a>
              <ul class="dropdown-menu scale-up" style="max-height: 288px;overflow-y: scroll;">
                  <li class="header" id="notification_count"><span class="notification_count">{{count($notifications)}}</span> {{__('dashboard.you_have_notifications')}}</li>
                  @if(count($notifications) > 0)
                      @foreach($notifications as $key => $notification)
                          @if(auth()->user()->role == 'admin')
                            <a href='/management/complaints/replies/{{$notification->complaint_id}}'>
                                <li style='text-align: right;margin-right: 10px;margin-top: 15px;border-bottom: 1px solid #f3f3f3;padding-bottom: 10px;'>
                                    <i class="mdi mdi-bell text-aqua" style='margin-left: 8px;'></i>
                                    <strong>{{$notification->reply_text}}</strong><p style='margin-right: 13px;'>{{date('h:i A',strtotime($notification->created_at))}}</p>
                                </li>
                            </a>
                          @else
                              @if($notification->offerable_type == "App\Cause")
                                  <a href='/offices/causes/offers/{{$notification->offerable_id}}'>
                                      <li style='text-align: right;margin-right: 10px;margin-top: 15px;border-bottom: 1px solid #f3f3f3;padding-bottom: 10px;'>
                                          <i class="mdi mdi-bell text-aqua" style='margin-left: 8px;'></i>
                                          <strong>{{__('dashboard.new_offer')}}</strong><p style='margin-right: 13px;'>{{date('h:i A',strtotime($notification->created_at))}}</p>
                                      </li>
                                  </a>
                              @else
                                  <a href='/offices/consultations/offers/{{$notification->offerable_id}}'>
                                      <li style='text-align: right;margin-right: 10px;margin-top: 15px;border-bottom: 1px solid #f3f3f3;padding-bottom: 10px;'>
                                          <i class="mdi mdi-bell text-aqua" style='margin-left: 8px;'></i>
                                          <strong>{{__('dashboard.new_offer')}}</strong><p style='margin-right: 13px;'>{{date('h:i A',strtotime($notification->created_at))}}</p>
                                      </li>
                                  </a>
                              @endif
                          @endif
                      @endforeach
                  @endif
              </ul>
          </li>
	  <!-- User Account: style can be found in dropdown.less -->
        <li class="dropdown user user-menu">
          <a class="hovering" href="#" class="dropdown-toggle" data-toggle="dropdown"
             style="box-shadow: 0px 2px 3px black; margin-top: 1vh; margin-right: 2vw; margin-left: 1vw; border-radius: 0.5rem;">
            <img src="{{ auth()->user()->avatar }}" class="user-image rounded-circle" alt="User Image">
          </a>
          <ul class="dropdown-menu scale-up">
            <!-- User image -->
            <li class="user-header">
              <img src="{{Auth::user()->avatar}}" class="float-right rounded-circle" alt="User Image">

              <p>
                  {{Auth::user()->name}}
                <small class="mb-5">{{Auth::user()->email}}</small>
{{--                <a href="#" class="btn btn-danger btn-sm btn-rounded">View Profile</a>--}}
              </p>
            </li>
            <!-- Menu Body -->
            <li class="user-body">
              <div class="row no-gutters">
			<div role="separator" class="divider col-12"></div>
			  <div class="col-12 text-right">
                  <a class="dropdown-item" href="{{ route('logout') }}"
                     onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                      {{ __('Logout') }}
                  </a>

                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      @csrf
                  </form>
                </div>
              </div>
              <!-- /.row -->
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>


