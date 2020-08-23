
@extends('layouts.master')
@section('title')
    {{__('dashboard.update_profile')}}
@endsection
@section('styles')
    <style>
        #data tbody tr {
            word-break: break-all;
        }
    </style>
@endsection
@section('modals')
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="row">

                <div class="col-12">
                    <h3 style="text-align: right">{{__('dashboard.update_profile')}}</h3>
                </div>
                <div class="col-12">

                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <form action="{{route('user.updateProfile')}}" method="post" enctype="multipart/form-data" data-parsley-validate>
                                @csrf
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">{{__('dashboard.name')}}</label>
                                        <input type="text" id="name" class="form-control" name="name" value="{{auth()->user()->name}}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="national_id">{{__('dashboard.national_id')}}</label>
                                        <input type="text" id="national_id" name="national_id" class="form-control" value="{{auth()->user()->national_id}}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="password">{{__('dashboard.password')}}</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="{{__('dashboard.leave_it_empty_if_dont_change_it')}}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="bio">{{__('dashboard.bio')}}</label>
                                        <textarea name="bio" class="form-control">{{auth()->user()->bio}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="address">{{__('dashboard.address')}}</label>
                                        <input type="text" id="address" name="address" class="form-control" value="{{auth()->user()->address}}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="experience">{{__('dashboard.experience')}}</label>
                                        <input type="text" id="experience" name="experience" class="form-control" value="{{auth()->user()->experience}}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="avatar">{{__('dashboard.image')}}</label>
                                        <input type="file" id="avatar" name="avatar">
                                    </div>
                                    <img src="{{auth()->user()->avatar}}" alt="profile image" width="200" height="200">
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="casecount">{{__('dashboard.casecount')}}</label>
                                        <input type="text" id="casecount" name="casecount" class="form-control" value="{{auth()->user()->casecount}}">
                                    </div>
                                </div>
                                <br>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-lg btn-success" value="{{__('dashboard.save')}}">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('scripts')
@endsection

