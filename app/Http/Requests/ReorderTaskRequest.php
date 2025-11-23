<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ReorderTaskRequest extends FormRequest
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
            'task_ids' => 'required|array',
            'task_ids.*' => [
                'required',
                Rule::exists('tasks', 'id')->where(function ($query) {
                    $query->where('project_id', $this->route('project'));
                })
            ],
        ];
    }
}
