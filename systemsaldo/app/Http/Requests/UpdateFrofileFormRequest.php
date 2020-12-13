<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFrofileFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        # 42 mudo para true
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        #42 para aceitar o mesmo email passamos o id do usu logado
        $id = auth()->user()->id;
        return [
            // 42
            'name' => 'required|string|max:255',
            #42 e acrescento email,{$id},id"
            'email' => "required|string|email|max:255|unique:users,email,{$id},id",
            'password' => 'max:20|',
            #42 só garante imagem
            'image' => 'image'
            #42 e vou no UserController
        ];
    }
}
