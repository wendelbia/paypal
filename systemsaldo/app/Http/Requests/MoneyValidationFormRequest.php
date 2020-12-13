<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MoneyValidationFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
    #23coloco como true
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
            //23 colo as regras, preenchimento obrigatório e numérico e vou no BalanceController para trocar Request por essa classe
            'value' => 'required|numeric',

        ];
    }
}
