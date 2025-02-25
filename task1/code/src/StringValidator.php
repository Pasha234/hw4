<?php

namespace Pasha234\Hw41;

use Pasha234\Hw41\InvalidStringException;

class StringValidator
{
    function validateBrackets($string) {
        if (empty($string)) {
            throw new InvalidStringException("Строка пуста.");
        }
    
        if (preg_match('/[^()]/', $string)) {
            throw new InvalidStringException("Строка должна содержать только скобки");
        }
    
        $stack = [];
        $brackets = ['(' => ')'];
    
        for ($i = 0; $i < strlen($string); $i++) {
            $char = $string[$i];
    
            if (array_key_exists($char, $brackets)) {
                // Открывающая скобка, добавляем в стек
                array_push($stack, $char);
            } elseif (in_array($char, $brackets)) {
                // Закрывающая скобка
                if (empty($stack)) {
                    throw new InvalidStringException("Некорректная последовательность: лишняя закрывающая скобка.");
                }
    
                $top = array_pop($stack);
                if ($brackets[$top] !== $char) {
                    throw new InvalidStringException("Некорректная последовательность: несовпадающие скобки.");
                }
            }
        }
    
        if (!empty($stack)) {
            throw new InvalidStringException("Некорректная последовательность: незакрытые скобки.");
        }
    }
}