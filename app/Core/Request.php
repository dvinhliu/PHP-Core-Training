<?php
abstract class BaseRequest
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

                if (!$this->checkRule($ruleName, $value, $params)) {
                    $key = $field . '.' . $ruleName; // ví dụ: title.required
                    $message = $this->messages()[$key] ?? "$field validation failed: $ruleName";
                    $this->errors[$field][] = $message;
                }
            }
        }
    }

    protected function checkRule($rule, $value, $params = null)
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
            case 'exists':
                // giả lập, thực tế thì query DB
                return true;
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
