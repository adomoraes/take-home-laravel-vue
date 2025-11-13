<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // Importe a classe Rule

class StoreExameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Para este teste, vamos permitir todas as requisições.
        // Numa aplicação real, aqui viria a lógica de autorização (ex: ACL, Gates).
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $gruposPermitidos = ['Individual', 'Grupo 1', 'Grupo 2', 'Grupo 3', 'Grupo 4', 'Grupo 5'];
        $lateralidadesPermitidas = ['OD', 'OE', 'AO'];

        return [
            'name' => 'required|string|max:255',
            'comment' => 'required|string',
            
            // Opcional, mas se existir, tem de ser um dos valores permitidos
            'laterality' => ['nullable', Rule::in($lateralidadesPermitidas)],
            
            // Obrigatório e tem de ser um dos valores permitidos
            'group' => ['required', Rule::in($gruposPermitidos)],
        ];
    }
}