<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;

class FirstSetupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'nama_depan' => 'required|string|max:50',
            'nama_belakang' => 'nullable|string|max:50',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'nullable|in:Islam,Kristen,Hindu,Buddha,Konghucu',
            'gol_darah' => 'nullable|in:A,B,AB,O',
            'tinggi_badan' => 'nullable|integer|min:50|max:300',
            'berat_badan' => 'nullable|integer|min:10|max:500',
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'nama_depan.required' => 'First name is required',
            'tempat_lahir.required' => 'Place of birth is required',
            'tanggal_lahir.required' => 'Date of birth is required',
            'tanggal_lahir.before' => 'Date of birth must be in the past',
            'jenis_kelamin.required' => 'Gender is required',
        ];
    }
}
