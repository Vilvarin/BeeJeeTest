<?php

namespace App;

class Validator
{
    /** @var string the validated value */
    protected string $_value;

    /** @var string[] the validation rules */
    protected array $_rules;

    /** @var string the error message */
    protected string $_message = '';

    public function getMessage()
    {
        return $this->_message;
    }

    /** @var string */
    protected string $_failedRule = '';

    public function getFailedRule()
    {
        return $this->_failedRule;
    }

    /**
     * @param string $value the validated value
     * @param string[] $rules set of rules for validation
     */
    public function __construct(string $value, array $rules)
    {
        $this->_value = $value;
        $this->_rules = $rules;
    }

    /**
     * Validate all passed validators
     * @param array<string, Validator>  $validators FieldName => Validator pairs
     * @return array<string, string> FieldName => validationError pairs
     */
    public static function validateAll(array $validators): array
    {
        $result = [];

        foreach ($validators as $fieldName => $validator) {
            if (!$validator->validate()) {
                $result[$fieldName] = $validator->getMessage();
            }
        }

        return $result;
    }

    /**
     * Validate the passed value by passed rule
     * @return bool
     */
    public function validate(): bool
    {
        foreach ($this->_rules as $rule) {
            $exp = explode(':', $rule);
            $ruleName = $exp[0];
            $ruleParam = isset($exp[1]) ? intval($exp[1]) : null;
            $methodName = $ruleName . 'Rule';

            if ($this->$methodName($ruleParam) === false) {
                $messageMethodName = $ruleName . 'Message';
                $this->_message = $this->$messageMethodName();
                $this->_failedRule = $ruleName;
                return false;
            }
        }

        return true;
    }

    protected function requiredRule(): bool
    {
        return !empty($this->_value);
    }

    protected function requiredMessage(): string
    {
        return 'Empty value';
    }

    protected function isStringRule(): bool
    {
        return is_string($this->_value);
    }

    protected function isStringMessage(): string
    {
        return 'Incorrect format';
    }

    protected function minRule(int $min): bool
    {
        return strlen($this->_value) >= $min;
    }

    protected function minMessage(): string
    {
        return 'Too short value';
    }

    protected function maxRule(int $max): bool
    {
        return strlen($this->_value) <= $max;
    }

    protected function maxMessage(): string
    {
        return 'Too long value';
    }

    protected function emailRule(): bool
    {
        return filter_var($this->_value, FILTER_VALIDATE_EMAIL);
    }

    protected function emailMessage(): string
    {
        return 'The value is not an email';
    }

    protected function adminRule(): bool
    {
        return $this->_value === 'admin';
    }

    protected function passwordRule(): bool
    {
        return $this->_value === '123';
    }

    protected function adminMessage(): string
    {
        return 'Incorrect username or password';
    }

    protected function passwordMessage(): string
    {
        return $this->adminMessage();
    }
}
