<?php

use Parle\{Lexer, Token, Parser, ParserException};

$p = new Parser;
$p->token("WORD");
$p->push("START", "SENTENCE");
$p->push("SENTENCE", "WORDS");
$prod_word_0 = $p->push("WORDS", "WORDS WORD");
$prod_word_1 = $p->push("WORDS", "WORD");
$p->build();

$lex = new Lexer;
$lex->push("[^\s]{-}[\.,\:\;\?]+", $p->tokenId("WORD"));
$lex->push("[\s\.,\:\;\?]+", Token::SKIP);
$lex->build();

$in = "My name is Chris Rowles";
$p->consume($in, $lex);
do {
    switch ($p->action) {
        case Parser::ACTION_ERROR:
            throw new ParserException("Error");
            break;
        case Parser::ACTION_SHIFT:
        case Parser::ACTION_GOTO:
            var_dump($p->trace());
            break;
        case Parser::ACTION_REDUCE:
            $rid = $p->reduceId;
            if ($rid == $prod_word_1) {
                var_dump($p->sigil(0));
            } if ($rid == $prod_word_0) {
            var_dump($p->sigil(1));
        }
            break;
    }
    $p->advance();
} while (Parser::ACTION_ACCEPT != $p->action);