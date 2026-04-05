<?php

namespace App\Services\RuleEngine;

use App\Models\IncidentRule;

class IncidentRuleService
{
    public function evaluate(array $data): ?array
    {
        $rules = IncidentRule::where('is_active', true)->get();

        foreach ($rules as $rule) {
            if ($this->matchRule($data, $rule)) {
                return [
                    'issue_type' => $rule->issue_type,
                    'priority'   => $rule->priority,
                ];
            }
        }

        return null;
    }

    private function matchRule(array $data, IncidentRule $rule): bool
    {
        if (!array_key_exists($rule->field, $data)) {
            return false;
        }

        $left  = $data[$rule->field];
        $right = $rule->value;

        return match ($rule->operator) {
            '='  => $left == $right,
            '>'  => $left > $right,
            '<'  => $left < $right,
            '>=' => $left >= $right,
            '<=' => $left <= $right,
            default => false,
        };
    }
}