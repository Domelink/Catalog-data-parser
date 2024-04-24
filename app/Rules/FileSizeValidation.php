<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

final readonly class FileSizeValidation implements ValidationRule
{
    public function __construct(private int $maxSize)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value > $this->maxSize * 1024) {
            $fail('The file exceeds the maximum allowed size of ' . $this->maxSize . ' KB.');
        }
    }
}
