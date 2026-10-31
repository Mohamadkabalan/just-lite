<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Spam check for the public forms. Each form carries an encrypted timestamp
 * of when the page was shown (see form_started() in app/helpers.php). Bots
 * that post straight to the URL have no valid token, and those that fill the
 * form instantly are faster than a person can type.
 */
class FilledByAPerson implements ValidationRule
{
    public const MIN_SECONDS = 3;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $startedAt = (int) decrypt((string) $value);
        } catch (DecryptException) {
            $fail(__('translation.form-expired'));

            return;
        }

        $elapsed = now()->timestamp - $startedAt;

        if ($elapsed < self::MIN_SECONDS) {
            $fail(__('translation.form-too-fast'));
        } elseif ($elapsed > 86400 * 2 || $elapsed < -60) {
            $fail(__('translation.form-expired'));
        }
    }
}
