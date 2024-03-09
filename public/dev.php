<?php

use \Mamba\Traits\DigitTester;

require '../../vendor/autoload.php';

class MyClass
{
    use DigitTester;

    public function val(mixed $value)
    {
        return $value;
    }
}

$val = "12.5";
$i = new MyClass;

var_dump($i->isPositive($val));

