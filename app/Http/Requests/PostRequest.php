<?php

namespace App\Http\Requests;

use App\Http\Controllers\PostController;
use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //return true;
        if (PostController::postOnceDay())
        {
            return true;
        }
        else{
            back()->withError("Вы можете отправлять не более 1 сообщения в сутки. Следующее не ранее ".Post::where('user_id', Auth::id())->orderBy('created_at','desc')->first()->created_at->addDays(1)->toDateTimeString());
            return true;
        }

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'subject' => 'required|max:255',
            'message' => 'required',
            'file' => 'max:1024',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'subject.required' => ':attribute обязательное поле',
            'subject.max' => 'Поле :attribute должно содержать не бошльше 255 знаков',
            'message.required' => ':attribute обязательное поле',
            'file.max' => 'Размер файла не более :max КБ',
            'file.uploaded' => 'Ошибка загрузки файла',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'subject' => 'Тема',
            'message' => 'Сообщение',
        ];
    }
}
