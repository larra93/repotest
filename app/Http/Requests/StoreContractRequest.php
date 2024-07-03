<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContractRequest extends FormRequest
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
        'name_contract' => 'required|string|max:255',
        'NSAP' => 'required|string|max:255',
        'DEN' => 'required|string|max:255',
        'project' => 'required|string|max:255',
        'API' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date',
        'id_company' => 'required|exists:companies,id',
        'created_by' => 'required|exists:users,id',
        'revisorPYCRequired' => 'boolean',
        'revisorCCRequired' => 'boolean',
        'revisorOtraAreaRequired' => 'boolean',
        'revisorPYC' => 'array',
        'revisorPYC.*' => 'exists:users,id',
        'revisorCC' => 'array',
        'revisorCC.*' => 'exists:users,id',
        'revisorOtraArea' => 'array',
        'revisorOtraArea.*' => 'exists:users,id',
        'adminDeTerreno' => 'required|array',
        'adminDeTerreno.*' => 'exists:users,id',
        'visualizador' => 'required|array',
        'visualizador.*' => 'exists:users,id',
        'encargadoContratista' => 'required|array',
        'encargadoContratista.*' => 'exists:users,id',
        'encargadoCodelco' => 'required|array',
        'encargadoCodelco.*' => 'exists:users,id',
    ];
}

    public function messages()
    {
        return [
            'name_contract.required' => 'El nombre del contrato es obligatorio.',
            'NSAP.required' => 'El NSAP es obligatorio.',
            'DEN.required' => 'El DEN es obligatorio.',
            'project.required' => 'El proyecto es obligatorio.',
            'API.required' => 'El API es obligatorio.',
            'start_date.required' => 'La fecha de inicio es obligatoria.',
            'end_date.required' => 'La fecha de fin es obligatoria.',
            'id_company.required' => 'La compañía es obligatoria.',
            'created_by.required' => 'El creador es obligatorio.',
            'revisorPYC.*.exists' => 'El revisor PYC seleccionado no existe.',
            'revisorCC.*.exists' => 'El revisor CC seleccionado no existe.',
            'revisorOtraArea.*.exists' => 'El revisor de otra área seleccionado no existe.',
            'adminDeTerreno.required' => 'El campo Administrador de terreno es obligatorio.',
            'adminDeTerreno.*.exists' => 'El usuario seleccionado no es válido.',
            'visualizador.required' => 'El campo visualizador es obligatorio.',
            'visualizador.*.exists' => 'El usuario seleccionado no es válido.',
            'encargadoContratista.required' => 'El campo encargado contratista  es obligatorio.',
            'encargadoContratista.*.exists' => 'El usuario seleccionado no es válido.',
            'encargadoCodelco.*.exists' => 'El usuario seleccionado no es válido.'
        ];
    }
}
