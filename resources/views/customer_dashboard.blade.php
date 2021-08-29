<form  enctype="multipart/form-data" method="post" action="{{ url('add-post') }}">
    @csrf
    Тема: <input type="text" name="subject">
    Сообщение: <input type="text" name="message">
    Файл: <input type="file" name="file">
    <input type="submit">
</form>
