<h1>Новые запросы:</h1>
<table>
    <thead>
        <tr>
          <td>№</td>
          <td>Тема</td>
          <td>Сообщение</td>
          <td>Имя клиента</td>
          <td>Почта клиента</td>
          <td>Файл</td>
          <td>Время создания</td>
          <td></td>
        </tr>
    </thead>
@foreach(\App\Models\Post::where('answer', "")->orderBy('created_at')->get() as $index=>$post)
    <tr>
        <td>{{ ++$index }}</td>
        <td>{{ $post->subject }}</td>
        <td>{{ $post->message }}
        <td>{{ $post->user->name }}</td>
        <td>{{ $post->user->email }}</td>
        <td>
            @if($post->file != "")
                <a target="_blank" href="{{ url(''.Config::get('constants.display_folder').'/'.$post->file) }}">{{ $post->file_name }}</a>
            @endif
        </td>
        <td>{{ $post->created_at }}</td>
        <td><a target="_blank" href="{{ url('post/'.$post->id) }}">Ответить</a></td>
    </tr>
@endforeach
</table>

<h1>Отвеченные запросы:</h1>
<table>
    <thead>
    <tr>
        <td>№</td>
        <td>Тема</td>
        <td>Сообщение</td>
        <td>Имя клиента</td>
        <td>Почта клиента</td>
        <td>Файл</td>
        <td>Время создания</td>
        <td>Ответ</td>
        <td>Время ответа</td>
    </tr>
    </thead>
    @foreach(\App\Models\Post::where('answer', '!=', "")->orderBy('updated_at')->get() as $index=>$post)
        <tr>
            <td>{{ ++$index }}</td>
            <td>{{ $post->subject }}</td>
            <td>{{ $post->message }}
            <td>{{ $post->user->name }}</td>
            <td>{{ $post->user->email }}</td>
            <td>
                @if($post->file != "")
                    <a target="_blank" href="{{ url(''.Config::get('constants.display_folder').'/'.$post->file) }}">{{ $post->file_name }}</a>
                @endif
            </td>
            <td>{{ $post->created_at }}</td>
            <td>{{ $post->answer }}</td>
            <td>{{ $post->updated_at }}</td>
        </tr>
    @endforeach
</table>
