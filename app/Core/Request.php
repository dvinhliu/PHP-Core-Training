<?php

namespace App\Core;

use App\Models\User;
use Soap\Url;

abstract class Request
{
    protected $data;
    protected $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->validate();
    }

    abstract public function rules(): array;
    abstract public function messages(): array;

    public function validate()
    {
        foreach ($this->rules() as $field => $ruleString) {
            $rules = explode('|', $ruleString);
            $value = $this->data[$field] ?? null;

            foreach ($rules as $rule) {
                $params = null;

                // rule có param (vd: max:255, in:a,b,c)
                if (strpos($rule, ':') !== false) {
                    [$ruleName, $params] = explode(':', $rule, 2);
                } else {
                    $ruleName = $rule;
                }

                if (!$this->checkRule($field, $ruleName, $value, $params)) {
                    $key = $field . '.' . $ruleName; // ví dụ: title.required
                    $message = $this->messages()[$key] ?? "$field validation failed: $ruleName";
                    $this->errors[$field][] = $message;

                    //dừng kiểm tra tiếp rule khác cho field này
                    break;
                }
            }
        }
    }

    protected function checkRule($field, $rule, $value, $params = null)
    {
        switch ($rule) {
            case 'required':
                return !empty($value);
            case 'string':
                return is_string($value);
            case 'max':
                return strlen($value) <= (int)$params;
            case 'in':
                $options = explode(',', $params);
                return in_array($value, $options);
            case 'regex':
                // nếu có tham số regex thì kiểm tra
                if ($params && $value !== null) {
                    return preg_match($params, $value);
                }
                return true;
            case 'confirmed':
                $confirmField = $field . '_confirmation';
                return isset($this->data[$confirmField]) && $this->data[$confirmField] === $value;

            case 'unique':
                // unique:table,column
                [$table, $column] = explode(',', $params);
                return User::checkUnique($table, $column, $value);
            default:
                return true;
        }
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
