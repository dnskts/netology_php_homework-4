<?php
declare(strict_types=1);

const OPERATION_EXIT = 0;
const OPERATION_ADD = 1;
const OPERATION_DELETE = 2;
const OPERATION_PRINT = 3;
const OPERATION_EDIT = 4;

$operations = [
    OPERATION_EXIT => OPERATION_EXIT . '. Завершить программу.',
    OPERATION_ADD => OPERATION_ADD . '. Добавить товар в список покупок.',
    OPERATION_DELETE => OPERATION_DELETE . '. Удалить товар из списка покупок.',
    OPERATION_PRINT => OPERATION_PRINT . '. Отобразить список покупок.',
    OPERATION_EDIT => OPERATION_EDIT . '. Изменить товар в списке.',
];

$items = [];

function ask(string $question): string
{
    echo $question . PHP_EOL . '> ';
    return trim(fgets(STDIN));
}

function printItems(array $items): void
{
    if (count($items) > 0) {
        echo 'Ваш список покупок: ' . PHP_EOL;
        foreach ($items as $name => $quantity) {
            echo "- $name: $quantity шт." . PHP_EOL;
        }
    } else {
        echo 'Ваш список покупок пуст.' . PHP_EOL;
    }
}

function addItem(array $items): array
{
    $name = ask('Введите название товара:');
    $quantity = ask('Введите количество:');

    if (array_key_exists($name, $items)) {
        $items[$name] = $items[$name] + (int) $quantity;
    } else {
        $items[$name] = (int) $quantity;
    }
    
    return $items;
}

function deleteItem(array $items): array
{
    printItems($items);
    $name = ask('Введите название товара для удаления:');
    
    if (array_key_exists($name, $items)) {
        unset($items[$name]);
        echo "Товар '$name' удалён." . PHP_EOL;
    } else {
        echo "Товар '$name' не найден." . PHP_EOL;
    }
    
    return $items;
}

function editItem(array $items): array
{
    printItems($items);
    $oldName = ask('Введите название товара, который хотите изменить:');
    
    if (!array_key_exists($oldName, $items)) {
        echo "Товар '$oldName' не найден." . PHP_EOL;
        return $items;
    }
    
    $newName = ask('Введите новое название (или оставьте пустым):');
    $newQuantity = ask('Введите новое количество (или оставьте пустым):');
    
    $quantity = $items[$oldName];

    if ($newName !== '') {
        unset($items[$oldName]);
        $oldName = $newName;
    }

    if ($newQuantity !== '') {
        $quantity = (int) $newQuantity;
    }
    
    $items[$oldName] = $quantity;
    
    echo "Товар изменён!" . PHP_EOL;
    
    return $items;
}

do {
    system('cls');

    do {
        printItems($items);
        
        echo PHP_EOL . 'Выберите операцию для выполнения: ' . PHP_EOL;
        echo implode(PHP_EOL, $operations) . PHP_EOL . '> ';
        
        $operationNumber = (int) trim(fgets(STDIN));

        if (!array_key_exists($operationNumber, $operations)) {
            system('cls');
            echo '!!! Неизвестный номер операции, повторите попытку.' . PHP_EOL;
        }

    } while (!array_key_exists($operationNumber, $operations));

    echo 'Выбрана операция: ' . $operations[$operationNumber] . PHP_EOL;

    switch ($operationNumber) {
        case OPERATION_ADD:
            $items = addItem($items);
            break;

        case OPERATION_DELETE:
            $items = deleteItem($items);
            break;

        case OPERATION_PRINT:
            printItems($items);
            echo 'Всего ' . count($items) . ' позиций.' . PHP_EOL;
            echo 'Нажмите Enter для продолжения';
            fgets(STDIN);
            break;

        case OPERATION_EDIT:
            $items = editItem($items);
            break;
    }

    echo "\n ----- \n";
} while ($operationNumber > 0);

echo 'Программа завершена' . PHP_EOL;
