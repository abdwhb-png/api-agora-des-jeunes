<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResumeRequest extends FormRequest
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
        $required = $this->isMethod('POST') ? 'required' : 'sometimes';

        return [
            'user_id' => 'sometimes|exists:users,id',
            'user_email' => 'nullable|email',
            'resume_id' => $required . '|string',
            'title' => $required . '|string',
            'theme_color' => 'nullable|string',
            'sections' => 'nullable|array',
            'file_path' => 'nullable|string',
            'first_name' => 'sometimes|string',
            'last_name' => 'sometimes|string',
            'job_title' => 'sometimes|string',
            'address' => 'sometimes|string',
            'phone' => 'sometimes|string',
            'email' => 'sometimes|string',
            'summery' => 'sometimes|string',
            'experiences' => 'sometimes|array',
            'educations' => 'sometimes|array',
            'skills' => 'sometimes|array',
            'languages' => 'sometimes|array',
            'interests' => 'sometimes|array',
        ];
    }
}
