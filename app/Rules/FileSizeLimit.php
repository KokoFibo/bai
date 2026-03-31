<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FileSizeLimit implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    protected $maxSize;

    public function __construct($maxSize)
    {
        $this->maxSize = $maxSize;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value->getClientOriginalExtension() === 'pdf' && $value->getSize() > $this->maxSize * 1024) {
            $fail('File PDF tidak boleh melebihi ' . $this->maxSize . ' Kb.');
        }
    }
}
