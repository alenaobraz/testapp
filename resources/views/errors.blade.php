@if ($errors->any())
    <div style="color: limegreen">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('error'))
    <div style="color: red">{{ session('error') }}</div>
@endif

