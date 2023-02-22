<?php

namespace example;

use src\ArrayTypesInterface;

class Shop implements ArrayTypesInterface
{
    public int $apple;
    public int $banana;
    public bool $lemon;
    public array $aaa;
    public Shop1 $shop1;
    public array $bbb;

    public function arrayTypes(): array
    {
        return [
            'aaa' => 'int',
            'bbb' => Shop2::class,
        ];
    }
}
