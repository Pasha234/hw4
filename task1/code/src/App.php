<?php

namespace Pasha234\Hw41;

class App
{
    public function run(): string
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['string'])) {
                $inputString = $_POST['string'];
                try {
                    $sv = new StringValidator();
                    $sv->validateBrackets($inputString);
                    return "Строка содержит корректную последовательность скобок.";
                } catch (InvalidStringException $e) {
                    http_response_code(400);
                    return $e->getMessage();
                }
            } else {
                http_response_code(400);
                return "Параметр 'string' не найден в POST-запросе.";
            }
        } else {
            http_response_code(400);
            return "Это должен быть POST-запрос.";
        }
    }
}