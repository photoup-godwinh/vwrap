<?php
require '../vendor/autoload.php';

use SimplePhp\Validator;
use Respect\Validation\Validator as v;

$input = array(
    'age'   => 17,
    'firstname' => 'Jonathan Doe'
);

$rules = array(
    'age'   => array(
            v::intVal()->setName('Age')->setTemplate("{{name}} must contain letters and numbers only"),
            v::min(18)->setName('Age')->setTemplate("{{name}} must be at least 18 years of age")
    ),
    'firstname' => array(
            v::alnum()->noWhitespace()->length(1,7)->setName('Firstname')
    )
);

$v = new Validator($input, $rules);
$v->validate();

echo "<p>This is an example showing a single input but with multiple rules and each rules can have its own name and template </p>";
echo $v->pass() ?  "Validation Passed!" : "Validation Failed!";

echo "<p>Errors: </p>";
print_r($v->getErrors());
