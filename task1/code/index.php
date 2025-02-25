<?php

function validateBrackets($string) {
    if (empty($string)) {
        throw new Exception("Строка пуста.");
    }

    if (preg_match('/[^()]/', $string)) {
        throw new Exception("Строка должна содержать только скобки");
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
                throw new Exception("Некорректная последовательность: лишняя закрывающая скобка.");
            }

            $top = array_pop($stack);
            if ($brackets[$top] !== $char) {
                throw new Exception("Некорректная последовательность: несовпадающие скобки.");
            }
        }
    }

    if (!empty($stack)) {
        throw new Exception("Некорректная последовательность: незакрытые скобки.");
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['string'])) {
        $inputString = $_POST['string'];
        try {
            validateBrackets($inputString);
            echo "Строка содержит корректную последовательность скобок.";
        } catch (Exception $e) {
            http_response_code(400);
            echo $e->getMessage();
        }
    } else {
        http_response_code(400);
        echo "Параметр 'string' не найден в POST-запросе.";
    }
} else {
    http_response_code(400);
    echo "Это должен быть POST-запрос.";
}
  