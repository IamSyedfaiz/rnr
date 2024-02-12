<?php

namespace App\Helpers;

class NotificationHelper
{
    public static function extractVariablesAndOperators($inputString)
    {
        $tokens = [];
        $operators = ['AND', 'OR', '(', ')'];

        // Build a regular expression pattern for capturing tokens
        $pattern = '/\b(?:' . implode('|', array_map('preg_quote', $operators)) . ')\b|\d+|\(|\)/';

        // Use preg_match_all to capture all matching tokens
        preg_match_all($pattern, $inputString, $matches);

        // Flatten the matches array
        $matches = array_reduce($matches, 'array_merge', []);

        foreach ($matches as $match) {
            $tokens[] = ['type' => is_numeric($match) ? 'variable' : 'operator', 'value' => $match];
        }

        return $tokens;
    }
    public static function rebuildString($tokens)
    {
        $result = '';

        foreach ($tokens as $token) {
            if ($token['type'] === 'operator' && ($token['value'] === '(' || $token['value'] === ')')) {
                $result .= $token['value'] . ' ';
            } else {
                $result .= $token['value'] . ' ';
            }
        }

        return trim(preg_replace('/\s+/', ' ', $result));
    }
    public static function getVariables($tokens)
    {
        $variables = [];

        foreach ($tokens as $token) {
            if ($token['type'] === 'variable') {
                $variables[] = $token['value'];
            }
        }

        return $variables;
    }
    public static function evaluateLogic($notifications, $fieldDatas, $request)
    {
        $results = [];

        foreach ($notifications as $notification) {
            $inputString = $notification->advanced_operator_logic;
            $allFilterCriterias = $notification->filterCriterias;
            $bolos = [];
            foreach ($fieldDatas as $value) {
                foreach ($notification->filterCriterias as $filterCriteria) {
                    if ($filterCriteria->field_id == $value->id) {
                        switch ($filterCriteria->filter_operator) {
                            case 'C':
                                if (strpos($request[$value->name], $filterCriteria->filter_value) !== false) {
                                    $bolos[] = true;
                                    logger("Contains comparison: IDs match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                } else {
                                    $bolos[] = false;
                                    logger("Contains comparison: IDs do not match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                }
                                break;
                            case 'DNC':
                                if (strpos($request[$value->name], $filterCriteria->filter_value) === false) {
                                    $bolos[] = true;
                                    logger("Does not contain comparison: IDs match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                } else {
                                    $bolos[] = false;
                                    logger("Does not contain comparison: IDs do not match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                }
                                break;
                            case 'E':
                                if ($request[$value->name] == $filterCriteria->filter_value) {
                                    $bolos[] = true;

                                    logger("Equals comparison: IDs match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                } else {
                                    $bolos[] = false;
                                    logger("Equals comparison: IDs do not match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                }
                                break;
                            case 'CH': // Changed
                                // Perform action for 'Changed' case
                                break;
                            case 'CT': // Changed To
                                // Perform action for 'Changed To' case
                                break;
                            case 'CF': // Changed From
                                // Perform action for 'Changed From' case
                                break;
                            // Handle other comparison cases
                        }
                    }
                }
            }
            logger($bolos);

            $extractedTokens = self::extractVariablesAndOperators($inputString);
            $variables = self::getVariables($extractedTokens);
            $reconstructedString = self::rebuildString($extractedTokens);

            foreach ($extractedTokens as &$token) {
                if ($token['type'] === 'variable') {
                    $variableValue = intval($token['value']);
                    if (isset($bolos[$variableValue - 1])) {
                        $token['value'] = $bolos[$variableValue - 1] ? '1' : '0';
                    }
                }
            }
            $reconstructedString = self::rebuildString($extractedTokens);
            $reconstructedString = str_replace('AND', '&&', $reconstructedString);
            $reconstructedString = str_replace('OR', '||', $reconstructedString);

            logger($reconstructedString);
            logger(eval("return $reconstructedString;"));
            if (eval("return $reconstructedString;")) {
                logger('---true---');
            } else {
                logger('---false---');
            }
            logger('---isme hai---');
        }
        return $results;
    }
}
