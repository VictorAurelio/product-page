<?php

namespace App\Core\Validation\Rule;

use InvalidArgumentException;

class InRule implements Rule
{
    public function validate(array $data, string $field, array $params)
    {
        if (empty($params)) {
            throw new InvalidArgumentException('specify a list of valid values');
        }

        return in_array($data[$field], $params);
    }

    public function getMessage(array $data, string $field, array $params)
    {
        $validValues = implode(', ', $params);

        return "The value of {$field} must be one of the following: {$validValues}";
    }
}
