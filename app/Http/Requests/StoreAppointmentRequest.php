<?php

namespace App\Http\Requests;

use App\Rules\FilledByAPerson;
use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * Digits (Western, Arabic-Indic or Persian), spaces and + ( ) . / -
     */
    public const PHONE = '/^[+0-9\x{0660}-\x{0669}\x{06F0}-\x{06F9}\s().\/-]{6,25}$/u';

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Mirrors the booking modal (resources/views/layouts/footer/index.blade.php).
     * type_of_service is a hidden field that only the package buttons fill in.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'regex:'.self::PHONE],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'type_of_service' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'website' => ['prohibited'],
            'form_started' => ['required', new FilledByAPerson],
        ];
    }
}
