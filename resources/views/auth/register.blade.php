@extends('layouts.app')
@section('title')
    {{__('dashboard.register')}}
@stop
@section('content')
<div class="container" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card"  style="background-color: transparent;
                                     border-radius: 1rem; border: 3px dashed gold;
                                     box-shadow: 20px 20px 50px 15px black">
                <div class="card-header" style="text-align: right; border-bottom: 3px dashed gold;">
                    <h4>{{__('dashboard.register')}}</h4>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li style="text-align: right;">{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>

                @endif
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" data-parsley-validate>
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{__('dashboard.name')}}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control"
                                       name="name" value="{{ old('name') }}" required autocomplete="name"
                                       data-parsley-required-message="{{__('dashboard.required_filed')}}"
                                       style="border-bottom: 3px solid gold; opacity: 0.9;"autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{__('dashboard.email')}}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control"
                                       name="email" value="{{ old('email') }}" required autocomplete="email"
                                       data-parsley-required-message="{{__('dashboard.required_filed')}}"
                                       style="border-bottom: 3px solid gold; opacity: 0.9;">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{__('dashboard.password')}}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control"
                                       name="password" required autocomplete="new-password"
                                       data-parsley-required-message="{{__('dashboard.required_filed')}}"
                                       style="border-bottom: 3px solid gold; opacity: 0.9;">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{__('dashboard.password_confirmation')}}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control"
                                       name="password_confirmation" required autocomplete="new-password"
                                       data-parsley-required-message="{{__('dashboard.required_filed')}}"
                                       style="border-bottom: 3px solid gold; opacity: 0.9;">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="national_id" class="col-md-4 col-form-label text-md-right">{{ __('dashboard.national_id') }}</label>

                            <div class="col-md-6">
                                <input id="national_id" type="text" class="form-control" name="national_id"
                                       data-parsley-required-message="{{__('dashboard.required_filed')}}"
                                       value="{{ old('national_id') }}" required autocomplete="national_id"
                                       style="border-bottom: 3px solid gold; opacity: 0.9;" autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('dashboard.phone') }}</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control"
                                       data-parsley-required-message="{{__('dashboard.required_filed')}}"
                                       name="phone" value="{{ old('phone') }}" required autocomplete="phone"
                                       style="border-bottom: 3px solid gold; opacity: 0.9;" autofocus>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{__('dashboard.register')}}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
