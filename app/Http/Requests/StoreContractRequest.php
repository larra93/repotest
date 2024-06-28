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
            'is_revisor_pyc_required' => 'boolean',
            'is_revisor_cc_required' => 'boolean',
            'is_revisor_other_area_required' => 'boolean',
            'revisor_pyc' => 'array',
            'revisor_pyc.*' => 'exists:users,id',
            'revisor_cc' => 'array',
            'revisor_cc.*' => 'exists:users,id',
            'revisor_other_area' => 'array',
            'revisor_other_area.*' => 'exists:users,id',
            'cc' => 'required|string|max:255',
            'admin_contrato' => 'required|array',
            'admin_contrato.*' => 'exists:users,id',
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
            'revisor_pyc.*.exists' => 'El revisor PYC seleccionado no existe.',
            'revisor_cc.*.exists' => 'El revisor CC seleccionado no existe.',
            'revisor_other_area.*.exists' => 'El revisor de otra área seleccionado no existe.',
            'cc.required' => 'El campo CC es obligatorio.',
            'admin_contrato.required' => 'El campo Admin de Contrato es obligatorio.',
            'admin_contrato.*.exists' => 'El usuario seleccionado no es válido.'
        ];
    }
}
