@extends('layouts.app')

@section('content')
<div class="container" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="background-color: transparent;
                                     border-radius: 1rem; border: 3px dashed gold;
                                     margin-top: 8%; box-shadow: 20px 20px 50px 15px black">
                <div class="card-header" style="text-align: right; border-bottom: 3px dashed gold;">
                    <h4>{{ __('dashboard.login') }}</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}" data-parsley-validate>
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('dashboard.email') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @if ($errors->has('email')) is-invalid @endif"
                                       data-parsley-required-message="{{__('dashboard.required_filed')}}"
                                       name="email" value="{{ old('email') }}" required autocomplete="email"
                                       style="border-bottom: 3px solid gold; opacity: 0.9;" autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('dashboard.password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @if ($errors->has('password')) is-invalid @endif"
                                       data-parsley-required-message="{{__('dashboard.required_filed')}}"
                                       name="password" required autocomplete="current-password"
                                       style="border-bottom: 3px solid gold; opacity: 0.9;">

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('dashboard.remember_me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('dashboard.login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('dashboard.forget_password') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
