
#--------------------------------------------------------------------------------------#
#                                Stage 1 - Lexing                                      #
#--------------------------------------------------------------------------------------#
# Lexing (or tokenizing) is the process of turning a string into a sequence of tokens. #
# A token is simply a named identifier for the value it has matched. PHP uses re2c to  #
# generate its lexer from the zend_language_scanner.l definition file.                 #
#                                                                                      #
# We can see the output of the lexing stage via the tokenizer extension:               #
#--------------------------------------------------------------------------------------#

<?php
$code = <<<'code'
<?php
$a = 1;
code;

$tokens = token_get_all($code);

foreach ($tokens as $token) {
    if (is_array($token)) {
        echo "Line {$token[2]}: ", token_name($token[0]), " ('{$token[1]}')", PHP_EOL;
    } else {
        echo PHP_EOL;
        var_dump($token);
    }
}
?>

#--------------------------------------------------------------------------------------#
#                                                                                      #
# There’s a couple of points from the above output:                                    #
#                                                                                      #
# 1) Not all pieces of the source code are named tokens. Instead, some symbols are     #
#    considered tokens in and of themselves (such as =, ;, :, ?, etc).                 #
#                                                                                      #
# 2) The lexer actually does a little more than simply output a stream of tokens, it   #
#    also stores the lexeme (the value matched by the token) and the line number of    #
#    the matched token (which is used for things like stack traces).                   #
#                                                                                      #
#--------------------------------------------------------------------------------------#
