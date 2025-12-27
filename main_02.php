<?php
declare(strict_types=1);

const OPERATION_EXIT = 0;
const OPERATION_ADD = 1;
const OPERATION_DELETE = 2;
const OPERATION_PRINT = 3;

$operations = [
    OPERATION_EXIT => OPERATION_EXIT . '. Завершить программу.',
    OPERATION_ADD => OPERATION_ADD . '. Добавить товар в список покупок.',
    OPERATION_DELETE => OPERATION_DELETE . '. Удалить товар из списка покупок.',
    OPERATION_PRINT => OPERATION_PRINT . '. Отобразить список покупок.',
];

$items = [];

function printItems(array $items): void
{
    if (count($items) > 0) {
        echo 'Ваш список покупок: ' . PHP_EOL;
        echo implode(PHP_EOL, $items) . PHP_EOL;
    } else {
        echo 'Ваш список покупок пуст.' . PHP_EOL;
    }
}

function ask(string $question): string
{
    echo $question . PHP_EOL . '> ';
    return trim(fgets(STDIN));
}

do {
    system('cls');

    do {
        printItems($items);
        
        echo 'Выберите операцию для выполнения: ' . PHP_EOL;
        echo implode(PHP_EOL, $operations) . PHP_EOL . '> ';
        
        $operationNumber = (int) trim(fgets(STDIN));

        if (!array_key_exists($operationNumber, $operations)) {
            system('clear');
            echo '!!! Неизвестный номер операции, повторите попытку.' . PHP_EOL;
        }

    } while (!array_key_exists($operationNumber, $operations));

    echo 'Выбрана операция: ' . $operations[$operationNumber] . PHP_EOL;

    switch ($operationNumber) {
        case OPERATION_ADD:
            $itemName = ask('Введите название товара для добавления:');
            $items[] = $itemName;
            break;

        case OPERATION_DELETE:
            printItems($items);
            $itemName = ask('Введите название товара для удаления:');
            
            while (($key = array_search($itemName, $items, true)) !== false) {
                unset($items[$key]);
            }
            break;

        case OPERATION_PRINT:
            printItems($items);
            echo 'Всего ' . count($items) . ' позиций.' . PHP_EOL;
            echo 'Нажмите Enter для продолжения';
            fgets(STDIN);
            break;
    }

    echo "\n ----- \n";
} while ($operationNumber > 0);

echo 'Программа завершена' . PHP_EOL;
