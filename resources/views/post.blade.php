
    <p>Тема: {{ $post->subject }}</p>
    <p>Сообщение: {{ $post->message }}
    <p>Имя: {{ $post->user->name }}</p>
    <p>Email: {{ $post->user->email }}</p>
    <p>Файл:
        @if($post->file != "")
            <a target="_blank" href="{{ url(''.Config::get('constants.display_folder').'/'.$post->file) }}">{{ $post->file_name }}</a>
        @endif
    </p>
    <p>Дата: {{ $post->created_at }}</p>

    <form  enctype="multipart/form-data" method="post" action="{{ route('post.answer.add', ['id' => $post->id]) }}">
        @csrf
        Ответ: <textarea name="answer"></textarea>
        <input type="submit">
    </form>

    @if ($errors->any())
        <div style="color: red">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
