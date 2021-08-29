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
        </tr>
    </thead>
@foreach(\App\Models\Post::all()->where('answer', "") as $index=>$post)
    <tr>
        <td>{{ ++$index }}</td>
        <td>{{ $post->subject }}</td>
        <td>{{ $post->message }}
        <td>{{ $post->user->name }}</td>
        <td>{{ $post->user->email }}</td>
        <td><a target="_blank" href="{{ url(''.Config::get('constants.display_folder').'/'.$post->file) }}">{{ $post->file_name }}</a></td>
        <td>{{ $post->created_at }}</td>
    </tr>
@endforeach
</table>


<form  enctype="multipart/form-data" method="post" action="{{ url('add-post') }}">
    @csrf
    Тема: <input type="text" name="subject">
    Сообщение: <input type="text" name="message">
    Файл: <input type="file" name="file">
    <input type="submit">
</form>
