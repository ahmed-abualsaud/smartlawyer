@if(count($errors) > 0)
    <div class="alert alert-error" style="text-align: center;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (session('success'))
    <div class="alert alert-success" style="text-align: center;">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-error" style="text-align: center;">
        {{ session('error') }}
    </div>
@endif
@if (session('warning'))
    <div class="alert alert-warning" style="text-align: center;">
        {{ session('warning') }}
    </div>
@endif
