<?php

namespace App\Http\Requests;

use App\Rules\FilledByAPerson;
use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Every field is required in the contact form (resources/views/contact-us/index.blade.php).
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone_number' => ['required', 'string', 'regex:'.StoreAppointmentRequest::PHONE],
            'msg_subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'website' => ['prohibited'],
            'form_started' => ['required', new FilledByAPerson],
        ];
    }
}
