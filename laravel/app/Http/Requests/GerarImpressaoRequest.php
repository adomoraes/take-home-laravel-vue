<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GerarImpressaoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Vamos permitir para o nosso teste
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Esperamos um array de IDs de exames avulsos
            'exames' => 'nullable|array',
            'exames.*' => 'integer|exists:exames,id',

            // Esperamos um array de IDs de pacotes selecionados
            'pacotes' => 'nullable|array',
            'pacotes.*' => 'integer|exists:pacotes,id',
        ];
    }
}