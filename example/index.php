<?php

use example\Shop;
use src\ArrayToObject;

require_once __DIR__ . '/../vendor/autoload.php';

// ここに array がある
$param = [
    'apple' => 100,
    'banana' => 200,
    'lemon' => false,
    'aaa' => [1, '2', 3],
    'shop1' => [
        'prop1' => 'a',
        'shop2' => [
            'prop2' => 'b',
            'prop3' => 'c',
        ],
        'shop22' => [
            'prop2' => 'bb',
            'prop3' => 'cc',
        ],
    ],
    'bbb' => [
        ['prop2' => 'b1', 'prop3' => 'c1'],
        ['prop2' => 'b2', 'prop3' => 'c2'],
        ['prop2' => 'b3', 'prop3' => 'c3'],
    ],
];

// これを Shop クラスのインスタンスに変換する
$converter = new ArrayToObject();
$shop = $converter->convert($param, Shop::class);
var_dump($shop);
