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

            // nếu có rule nullable và value rỗng => skip luôn field này
            if (in_array('nullable', $rules) && ($value === null || $value === '')) {
                continue;
            }

            foreach ($rules as $rule) {
                $params = null;

                if (strpos($rule, ':') !== false) {
                    [$ruleName, $params] = explode(':', $rule, 2);
                } else {
                    $ruleName = $rule;
                }

                if (!$this->checkRule($field, $ruleName, $value, $params)) {
                    $key = $field . '.' . $ruleName;
                    $message = $this->messages()[$key] ?? "$field validation failed: $ruleName";
                    $this->errors[$field][] = $message;
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
            case 'min':
                return strlen($value) >= (int)$params;
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
            case 'exists':
                [$table, $column, $id] = explode(',', $params);
                return User::checkIdExists($table, $column, $value, (int)$id);
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
