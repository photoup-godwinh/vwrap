<?php
require '../vendor/autoload.php';

use SimplePhp\Validator;
use Respect\Validation\Validator as v;

$input = 'a';
$rules = array(
    v::intVal()->setName('Age')->setTemplate("{{name}} must contain letters and numbers only"),
    v::between(18, 50)->setName('Age')->setTemplate("{{name}} must be at least 18 years of age")
);

$v = new Validator($input, $rules);
$v->validate(); // initiate validation

echo "<p>This is an example showing a single input but with multiple rules and each rules can have its own name and template </p>";
echo $v->pass() ?  "Validation Passed!" : "Validation Failed!";

echo "<p>Errors: </p>";
print_r($v->getErrors());
