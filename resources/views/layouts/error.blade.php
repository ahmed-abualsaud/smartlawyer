@if(count($errors))
    <div class="form-group">
        <div class="alert alert-danger">
            <ul style="@if(App::getLocale() == 'en')text-align:left; @else text-align:right; @endif margin-bottom: 0px;">
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif