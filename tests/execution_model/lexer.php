<?php

use Parle\{Token, Lexer};

$lex = new Lexer;

$lex->push("\$[a-zA-Z_][a-zA-Z0-9_]*", 1);
$lex->push("=", 2);
$lex->push("\d+", 3);
$lex->push(";", 4);

$lex->build();

$lex->consume('$x = 42; $y = 24;');


$store = [];
do {
    $lex->advance();
    $tok = $lex->getToken();
//    var_dump($tok);
} while (Token::EOI != $tok->id);

$identifiers = [
    "1. (320) " . token_name(320) . PHP_EOL,   // T_VARIABLE
    "2. (382) " . token_name(382) . PHP_EOL,   // T_WHITESPACE
    "3. (317) " . token_name(317) . PHP_EOL,   // T_LNUMBER
    "4. (379) " . token_name(379) . PHP_EOL,   // T_OPEN_TAG
];

print_r($identifiers);