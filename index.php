<?php

declare(strict_types=1);

const OPERATION_EXIT = 0;
const OPERATION_ADD = 1;
const OPERATION_DELETE = 2;
const OPERATION_PRINT = 3;
const OPERATION_RENAME = 4;
const OPERATION_ADDQUANTITY = 5;

$operations = [
    OPERATION_EXIT => OPERATION_EXIT . '. Завершить программу.',
    OPERATION_ADD => OPERATION_ADD . '. Добавить товар в список покупок.',
    OPERATION_DELETE => OPERATION_DELETE . '. Удалить товар из списка покупок.',
    OPERATION_PRINT => OPERATION_PRINT . '. Отобразить список покупок.',
    OPERATION_RENAME => OPERATION_RENAME . '. Изменить название товара.',
    OPERATION_ADDQUANTITY => OPERATION_ADDQUANTITY . '. Добавить количество товара.',
];

$items = []; 
$itemsQuantity = []; 

function getOperationNumber(array $operations, array $items, array $itemsQuantity): string {
    
    do {
        if (count($items)) {
            echo 'Ваш список покупок: ' . PHP_EOL;
            localPrint($items, $itemsQuantity);
        } else {
            echo 'Ваш список покупок пуст.' . PHP_EOL;
        }    

        echo 'Выберите операцию для выполнения: ' . PHP_EOL;
        // Проверить, есть ли товары в списке? Если нет, то не отображать пункт про удаление товаров
        echo implode(PHP_EOL, $operations) . PHP_EOL . '> ';
        $operationNumber = trim(fgets(STDIN));

        if (!array_key_exists($operationNumber, $operations)) {
            system('clear');

            echo '!!! Неизвестный номер операции, повторите попытку.' . PHP_EOL;
        }

    } while (!array_key_exists($operationNumber, $operations));
    
    return $operationNumber;

}

function addItem(array &$items, array &$itemsQuantity): void {
    echo "Введение название товара для добавления в список: \n> ";
    $itemName = trim(fgets(STDIN));    

    echo "Введение количество товара: \n> ";
    $itemQuantity = trim(fgets(STDIN));    

    $itemIndex = array_search($itemName, $items);
    if ($itemIndex !== false) {
        $itemsQuantity[$itemIndex] += $itemQuantity;
    } else {
        $items[] = $itemName;
        $itemsQuantity[] = $itemQuantity;
    }
}

function localPrint(array $items, array $itemsQuantity): void {
    for ( $i = 0 ; $i < count($items); $i++) {
        echo $items[$i] . "   " . $itemsQuantity[$i] . PHP_EOL;
    }    
}

function printItems(array $items, array $itemsQuantity): void {
    echo 'Ваш список покупок: ' . PHP_EOL;
    localPrint($items, $itemsQuantity);
    echo 'Всего ' . count($items) . ' позиций. '. PHP_EOL;
    echo 'Нажмите enter для продолжения';
    fgets(STDIN);
}

function deleteItem(array &$items, array $itemsQuantity): void {
    // Проверить, есть ли товары в списке? Если нет, то сказать об этом и попросить ввести другую операцию
    echo 'Текущий список покупок:' . PHP_EOL;
    echo 'Список покупок: ' . PHP_EOL;
    
    localPrint($items, $itemsQuantity);

    echo 'Введение название товара для удаления из списка:' . PHP_EOL . '> ';
    $itemName = trim(fgets(STDIN));

    if (in_array($itemName, $items, true) !== false) {
        while (($key = array_search($itemName, $items, true)) !== false) {
            unset($items[$key]);
            unset($itemsQuantity[$key]);
        }
    }
}

function renameItem(array &$items, array $itemsQuantity): void {
    
    localPrint($items, $itemsQuantity);

    echo 'Введите название товара для переименования:' . PHP_EOL;
    $itemName = trim(fgets(STDIN));
    $itemIndex = array_search($itemName, $items);
    if ($itemIndex !== false) {
        echo 'Введите новое название товара:' . PHP_EOL;
        $newItemName = trim(fgets(STDIN));
        $items[$itemIndex] = $newItemName;
    } else {
        echo "Товар $itemName не найден в списке покупок. \n";
    }   
}

function addQuantity(array $items, array &$itemsQuantity): void {
    localPrint($items, $itemsQuantity);
    echo "Введите название товара для изменения количества: \n>";
    $itemName = trim(fgets(STDIN));
    $itemIndex = array_search($itemName, $items);
    if ($itemIndex !== false) {
        echo "Введите добавляемое количество: \n >";
        $itemQuantity = trim(fgets(STDIN));
        $itemsQuantity[$itemIndex] += $itemQuantity;
    } else {
        echo "Товар $itemName не найден в списке покупок. \n";
    }   
}

do {
    //system('clear');
    system('cls'); // windows

    $operationNumber = getOperationNumber($operations, $items, $itemsQuantity);

    echo 'Выбрана операция: '  . $operations[$operationNumber] . PHP_EOL;

    switch ($operationNumber) {
        case OPERATION_ADD:
            addItem($items, $itemsQuantity);            
            break;

        case OPERATION_DELETE:
            deleteItem($items, $itemsQuantity);            
            break;

        case OPERATION_PRINT:
            printItems($items, $itemsQuantity);
            break;
        
        case OPERATION_RENAME:
            renameItem($items, $itemsQuantity);
            break;

        case OPERATION_ADDQUANTITY:
            addQuantity($items, $itemsQuantity);
            break;
    }

    echo "\n ----- \n";
} while ($operationNumber > 0);

echo 'Программа завершена' . PHP_EOL;