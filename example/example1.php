<?php
require '../vendor/autoload.php';

use SimplePhp\Validator;
use Respect\Validation\Validator as v;

// CASE1: 1 input; 1 rule
$input = 'abc';
$rule = v::alnum()->setName('First name');
//$rule = v::alnum()->noWhitespace()->length(1, 15)->setName('First name');

echo "<p>This show a single input and single rule validation </p>";
$v = new Validator($input, $rule);
$v->validate();

echo "<p>This is an example showing a single input but with multiple rules and each rules can have its own name and template </p>";
echo $v->pass() ?  "Validation Passed!" : "Validation Failed!";

echo "<p>Errors: </p>";
print_r($v->getErrors());
