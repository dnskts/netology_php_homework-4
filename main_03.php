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

function clearScreen(): void
{
    if (stripos(PHP_OS, 'WIN') === 0) {
        system('cls');
    } else {
        system('clear');
    }
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

function showMenu(array $operations): void
{
    echo 'Выберите операцию для выполнения: ' . PHP_EOL;
    echo implode(PHP_EOL, $operations) . PHP_EOL . '> ';
}

function ask(string $question): string
{
    echo $question . PHP_EOL . '> ';
    return trim(fgets(STDIN));
}

function askForOperation(array $items, array $operations): int
{
    do {
        printItems($items);
        showMenu($operations);
        
        $operationNumber = (int) trim(fgets(STDIN));

        if (!array_key_exists($operationNumber, $operations)) {
            clearScreen();
            echo '!!! Неизвестный номер операции, повторите попытку.' . PHP_EOL;
        }

    } while (!array_key_exists($operationNumber, $operations));

    return $operationNumber;
}

function addItem(array &$items): void
{
    $name = ask('Введите название товара:');
    $quantity = (int) ask('Введите количество:');

    if (array_key_exists($name, $items)) {
        $items[$name] = $items[$name] + $quantity;
    } else {
        $items[$name] = $quantity;
    }
}

function deleteItem(array &$items): void
{
    printItems($items);
    $name = ask('Введите название товара для удаления:');
    
    if (array_key_exists($name, $items)) {
        unset($items[$name]);
        echo "Товар '$name' удалён." . PHP_EOL;
    } else {
        echo "Товар '$name' не найден." . PHP_EOL;
    }
}

function showItems(array $items): void
{
    printItems($items);
    echo 'Всего ' . count($items) . ' позиций.' . PHP_EOL;
    echo 'Нажмите Enter для продолжения';
    fgets(STDIN);
}

function editItem(array &$items): void
{
    printItems($items);
    $oldName = ask('Введите название товара, который хотите изменить:');
    
    if (!array_key_exists($oldName, $items)) {
        echo "Товар '$oldName' не найден." . PHP_EOL;
        return;
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
}

do {
    clearScreen();

    $operationNumber = askForOperation($items, $operations);

    echo 'Выбрана операция: ' . $operations[$operationNumber] . PHP_EOL;

    switch ($operationNumber) {
        case OPERATION_ADD:
            addItem($items);
            break;

        case OPERATION_DELETE:
            deleteItem($items);
            break;

        case OPERATION_PRINT:
            showItems($items);
            break;

        case OPERATION_EDIT:
            editItem($items);
            break;
    }

    echo "\n ----- \n";
} while ($operationNumber > 0);

echo 'Программа завершена' . PHP_EOL;
