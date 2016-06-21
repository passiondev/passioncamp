@if (session('error'))
    <div class="ui error message">
        {{ session('error') }}
    </div>
@endif
@if($errors->count())
    <div class="ui error message">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
