<h2>Оставить заявку:</h2>
<form  enctype="multipart/form-data" method="post" action="{{ url('add-post') }}">
    @csrf
    Тема: <input type="text" name="subject">
    Сообщение: <input type="text" name="message">
    Файл: <input type="file" name="file">
    <input type="submit">
</form>

@if ($errors->any())
    <div style="color: green">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('error'))
    <div style="color: red">Ошибка, обратитесь к админитсратору: <br>{{ session('error') }}</div>
@endif

<h1>Ваши запросы:</h1>
<table>
    <thead>
    <tr>
        <td>№</td>
        <td>Тема</td>
        <td>Сообщение</td>
        <td>Файл</td>
        <td>Время создания</td>
        <td>Ответ</td>
        <td>Время ответа</td>
    </tr>
    </thead>
    @foreach(\App\Models\Post::where('user_id', \Illuminate\Support\Facades\Auth::id())->orderBy('created_at')->get() as $index=>$post)
        <tr>
            <td>{{ ++$index }}</td>
            <td>{{ $post->subject }}</td>
            <td>{{ $post->message }}
            <td>
                @if($post->file != "")
                    <a target="_blank" href="{{ url(''.Config::get('constants.display_folder').'/'.$post->file) }}">{{ $post->file_name }}</a>
                @endif
            </td>
            <td>{{ $post->created_at }}</td>
            @if($post->answer != "")
                <td>{{ $post->answer }}</td>
                <td>{{ $post->updated_at }}</td>
            @else
                <td></td>
                <td></td>
            @endif
        </tr>
    @endforeach
</table>
