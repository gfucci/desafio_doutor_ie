<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLivroRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'titulo' => 'required|string|max:255',
            'usuario_publicador_id' => 'required|exists:users,id',
            'indices' => 'array',
            'indices.*.titulo' => 'required|string|max:255',
            'indices.*.pagina' => 'required|integer',
            'indices.*.subindices' => 'nullable|array', 
            'indices.*.subindices.*.titulo' => 'nullable|string|max:255',
            'indices.*.subindices.*.pagina' => 'nullable|integer',
            'indices.*.subindices.*.subindices' => 'nullable|array',
        ];
    }
}
