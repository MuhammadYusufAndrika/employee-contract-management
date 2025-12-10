<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContractRequest extends FormRequest
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
            'employee_name' => 'required|string|max:255',
            'nik' => 'required|string|max:16|unique:contracts,nik,' . $this->contract->id,
            'birthdate' => 'required|date|before:today',
            'birthplace' => 'required|string|max:255',
            'address' => 'required|string',
            'job_position' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'department' => 'required|string|max:255',
            'work_location' => 'required|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'employee_name.required' => 'Employee name is required.',
            'nik.required' => 'NIK is required.',
            'nik.unique' => 'NIK already exists.',
            'nik.max' => 'NIK must not exceed 16 characters.',
            'birthdate.required' => 'Birth date is required.',
            'birthdate.date' => 'Birth date must be a valid date.',
            'birthdate.before' => 'Birth date must be before today.',
            'birthplace.required' => 'Birth place is required.',
            'address.required' => 'Address is required.',
            'job_position.required' => 'Job position is required.',
            'start_date.required' => 'Start date is required.',
            'start_date.date' => 'Start date must be a valid date.',
            'end_date.required' => 'End date is required.',
            'end_date.date' => 'End date must be a valid date.',
            'end_date.after' => 'End date must be after start date.',
            'department.required' => 'Department is required.',
            'work_location.required' => 'Work location is required.',
        ];
    }
}
