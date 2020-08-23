@extends('layouts.master')
@section('title')
    {{__('dashboard.dashboard')}}
@endsection
@section('styles')
@endsection
@section('modals')
@endsection
@section('content')
    <div class="content-wrapper" style="min-height: 945.797px; background-color: transparent" dir="rtl">
        <!-- Content Header (Page header) -->
        <section class="content-header" dir="rtl">
            <div style="font-family: 'Cortoba', Sans-Serif; color: gold; margin-top: 1vh; margin-bottom: 2vh;
                        -webkit-text-stroke: 1px black; font-size: 40px; text-shadow: 2px 2px 6px black;">
                * {{__('dashboard.dashboard')}}: <small>{{__('dashboard.control_panel')}}</small>
            </div>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                @if(auth()->user()->role == "admin")


                    <div class="col-xl-3 col-md-6 col-12">
			     	  <a href="{{route('users')}}">

                        <div class="info-box hovering"
                             style="background-color: white; opacity: 0.9;
                                 box-shadow: 8px 8px 18px 10px black">
                            <span class="info-box-icon bg-red"><i class="ion ion-person-stalker"></i></span>
                            <div class="info-box-content">
									<span class="info-box-number">{{$usersCount}}</span>
								<span class="info-box-text">{{__('dashboard.users')}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </a>

                    </div>

                    <!-- /.col -->

                @endif
                <div class="col-xl-3 col-md-6 col-12">
                 <a href="{{route('causes',['pages' => 10,'i'=>0])}}">
                    <div class="info-box hovering"
                         style="background-color: white; opacity: 0.9;
                                box-shadow: 8px 8px 18px 10px black">
                        <span class="info-box-icon bg-success"><i class="ion ion-android-hand"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-number">{{$causesCount}}</span>
                            <span class="info-box-text">{{__('dashboard.causes')}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
					</a>

                    <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <!-- fix for small devices only -->
                <div class="clearfix visible-sm-block"></div>

                <div class="col-xl-3 col-md-6 col-12">
			     <a href="{{route('consultations')}}">

                    <div class="info-box hovering"
                         style="background-color: white; opacity: 0.9;
                                box-shadow: 8px 8px 18px 10px black">
                        <span class="info-box-icon bg-purple"><i class="ion ion-document"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-number">{{$consultationsCount}}</span>
                            <span class="info-box-text">{{__('dashboard.consultations')}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
			       </a>

                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                @if(auth()->user()->role == "admin")
                    <div class="col-xl-3 col-md-6 col-12">
					  <a href="{{route('complaints')}}">
                        <div class="info-box hovering"
                             style="background-color: white; opacity: 0.9;
                                box-shadow: 8px 8px 18px 10px black">
                            <span class="info-box-icon bg-blue"><i class="ion ion-stats-bars"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-number">{{$complaintsCount}}</span>
                                <span class="info-box-text">{{__('dashboard.complaints')}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
						</a>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                @endif

            </div>
        </section>
        <!-- /.content -->
    </div>
@endsection
@section('scripts')
@endsection
