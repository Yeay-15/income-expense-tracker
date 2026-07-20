<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ownership dicek di controller
    }

    public function rules(): array
    {
        $userId = Auth::id();

        return [
            'account_id' => ['required', 'exists:accounts,id,user_id,' . $userId],
            'category_id' => ['required', 'exists:categories,id'],
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'description' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'receipt' => ['nullable', 'image', 'max:2048'], // max 2MB
        ];
    }
}
