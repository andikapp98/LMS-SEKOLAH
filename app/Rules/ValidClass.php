<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Helpers\ClassHelper;

class ValidClass implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return; // Kelas opsional, skip jika kosong
        }

        if (!ClassHelper::validateClass($value)) {
            $examples = implode(', ', ClassHelper::getExamples());
            $fail("Format kelas tidak valid. Contoh format yang benar: {$examples}");
        }
    }
}
