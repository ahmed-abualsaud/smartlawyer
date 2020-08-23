
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
        <section  class="sidebar">

            <div style="background-color: #0f4c75; position: absolute;  width: 100%; height: 100%;"></div>

            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree" id="list">
                <li class="user-profile treeview licustom">
                    <a href="{{route('profile')}}">
                        <img src="{{Auth::user()->avatar}}" alt="user">
                        <span  class="span-item1">{{Auth::user()->name}}</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-left pull-left"></i>
              </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="span-item"><a href="{{route('profile')}}">{{__('dashboard.profile')}}</a></li>
                    </ul>
                </li>


                <li class="nav-devider"></li>
                @if(auth()->user()->role == "admin")
                    <li class="treeview licustom">
                        <a href="#" class="{{str_contains(url()->current(), '/offices') || str_contains(url()->current(), '/users') ? 'active' : ''}}">
                            <i class="fa fa-users"></i>
                            <span  class="span-item">{{__('dashboard.users')}}</span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-right pull-right"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu" style="display: none;">
                            <li><a href="{{route('offices')}}" class="{{str_contains(url()->current(), '/offices') ? 'active' : ''}}" >
                                    {{__('dashboard.offices')}}</a></li>
                            <li><a href="{{route('users')}}" class="{{str_contains(url()->current(), '/users') ? 'active' : ''}}">{{__('dashboard.clients')}}</a></li>
                        </ul>
                    </li>
                @endif
                <li class="licustom">
                    <a href="{{route('dashboard')}}" class="{{str_contains(url()->current(), '/dashboard') ? 'active' : ''}}">
                        <i class="fa fa-tachometer" aria-hidden="true"></i>
                        <span  class="span-item">{{__('dashboard.dashboard1')}}</span>
                        @if(auth()->user()->role == 'office' && $causesMessagesCount > 0)
                            <span class="count"></span>
                        @endif
                    </a>
                </li>
                <li class="licustom">

                    <a href="{{route('causes')}}">
                        <i class="fa fa-balance-scale"></i>
                        <span>{{__('dashboard.causes')}}</span>
                        @if(auth()->user()->role == 'office' && $causesMessagesCount > 0)
                            <span class="count"></span>
                        @endif
                    </a>
                </li>
                <li class="licustom">
                    <a href="{{route('consultations')}}" class="{{str_contains(url()->current(), '/consultations') ? 'active' : ''}}">
                        <i class="fa fa-pencil"></i>
                        <span class="span-item">{{__('dashboard.consultations')}}</span>
                        @if(auth()->user()->role == 'office' && $consultationsMessagesCount > 0)
                            <span class="count"></span>
                        @endif
                    </a>
                </li>
                @if(auth()->user()->role == "admin")
                    <li class="licustom">
                        <a href="{{route('complaints')}}" class="{{str_contains(url()->current(), '/complaints') ? 'active' : ''}}">
                            <i class="fa fa-database"></i>
                            <span class="span-item">{{__('dashboard.complaints')}}</span>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->role == "admin")
                    <li class="licustom">
                        <a href="{{route('settings')}}" class="{{str_contains(url()->current(), '/settings') ? 'active' : ''}}">
                            <i class="fa fa-cog"></i>
                            <span class="span-item">{{__('dashboard.settings')}}</span>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->role == "office" && auth()->user()->office_id == 0)
                    <li class="licustom">
                        <a href="{{route('users')}}" class="{{str_contains(url()->current(), '/employees') ? 'active' : ''}}">
                            <i class="fa fa-users"></i>
                            <span class="span-item">{{__('dashboard.employees')}}</span>
                        </a>
                    </li>
                @endif
                <li class="licustom">
                    <a href="{{route('free_lawyer')}}" class="{{str_contains(url()->current(), '/free-lawyer') ? 'active' : ''}}">
                        <i class="fa fa-balance-scale"></i>
                        <span class="span-item">{{__('dashboard.free_lawyer')}}</span>
                    </a>
                </li>

                @if(auth()->user()->role == "admin")
                    <li class="licustom">
                        <a href="{{route('showCasesToAdmin')}}" class="{{str_contains(url()->current(), '/showcases') ? 'active' : ''}}">
                            <i class="fa fa-money"></i>
                            <span class="span-item">{{__('dashboard.freecauses')}}</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->role == "admin")
                    <li class="licustom">
                        <a href="{{route('showPaymentsToAdmin')}}" class="{{str_contains(url()->current(), '/show-payments') ? 'active' : ''}}">
                            <i class="fa fa-money"></i>
                            <span class="span-item">{{__('dashboard.paymentlist')}}</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->role == "office")
                    <li class="licustom">
                        <a href="{{route('showPaymentsToLawyer')}}" class="{{str_contains(url()->current(), '/list-payments') ? 'active' : ''}}">
                            <i class="fa fa-money"></i>
                            <span class="span-item">{{__('dashboard.paymentlist')}}</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->role == "office")
                    <li class="licustom">
                        <a href="{{route('freecauses',['pages' => 10, 'i'=>0])}}" class="{{str_contains(url()->current(), '/freecauses') ? 'active' : ''}}">
                            <i class="fa fa-money"></i>
                            <span class="span-item">{{__('dashboard.freecauses')}}</span>
                        </a>
                    </li>
                @endif
                <li class="licustom">
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">

                        <i class="fa fa-key"></i>
                        <span  class="span-item">{{__('dashboard.logout')}}</span>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </section>

</aside>
<script>
</script>
