@extends('layouts.master')
@section('title')
    {{__('dashboard.add_employee')}}
@endsection
@section('styles')
@endsection
@section('modals')
@endsection
@section('content')
    <div class="content-wrapper" style="min-height: 1295.8px;">
        <section class="content">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{__('dashboard.add_employee')}}</h3>
                </div>
                <!-- /.box-header -->
                <form action="{{route('users.storeEmployee')}}" method="post" enctype="multipart/form-data" data-parsley-validate="" novalidate class="error">
                    @csrf
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="example-name-input" class="col-sm-2 col-form-label">الاسم</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" value="{{old('name')}}" name="name" type="text" id="example-name-input" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="example-email-input" class="col-sm-2 col-form-label">البريد الالكتروني</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" value="{{old('email')}}" name="email" type="email" id="example-email-input" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="example-password-input" class="col-sm-2 col-form-label">كلمة المرور</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" name="password" type="password" id="example-password-input" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="example-phone-input" class="col-sm-2 col-form-label">التليفون</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" value="{{old('phone')}}" data-parsley-type="digits"	 name="phone" type="text" id="example-phone-input" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="example-national-id-input" class="col-sm-2 col-form-label">الرقم القومي</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" value="{{old('national_id')}}" name="national_id" type="text" id="example-national-id-input" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="example-address-input" class="col-sm-2 col-form-label">العنوان</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" value="{{old('address')}}" name="address" type="text" id="example-address-input" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="example-experience-input" class="col-sm-2 col-form-label">الخبره</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" value="{{old('experience')}}" name="experience" type="text" id="example-experience-input">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="example-bio-input" class="col-sm-2 col-form-label">السيرة الذاتيه</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" value="{{old('bio')}}" name="bio" type="text" id="example-bio-input"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="example-avatar-input" class="col-sm-2 col-form-label">الصورة</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" value="{{old('avatar')}}" name="avatar" type="file" id="example-avatar-input">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <input style="margin-right: 10px" type="submit" class="btn btn-blue" value="حفظ">
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                </form>
                <!-- /.box-body -->
            </div>
        </section>
    </div>
@endsection
@section('scripts')
@endsection

