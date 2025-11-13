<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePacoteRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'observations' => 'nullable|string',
            
            // O README diz que 'exams' (array) é obrigatório na criação
            'exams' => 'required|array', 
            
            // Valida cada item dentro do array 'exams'
            // Cada item deve ser um ID que existe na tabela 'exames'
            'exams.*' => 'required|integer|exists:exames,id'
        ];
    }
}