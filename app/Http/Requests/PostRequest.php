<?php

namespace App\Http\Requests;

use App\Http\Controllers\PostController;
use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
            'user_id' => 'required',
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

    /**
     * Подготовить данные для валидации.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->request->set('user_id', \Auth::id());
        if (!PostController::postOnceDay()) {
            back()->withError("Вы можете отправлять не более 1 сообщения в сутки. Следующее не ранее " . PostController::postOnceDayDate());
        }
    }
}
