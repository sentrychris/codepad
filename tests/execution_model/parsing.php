
#--------------------------------------------------------------------------------------#
#                                Stage 2 - Parsing                                     #
#--------------------------------------------------------------------------------------#
# The parser is also generated, this time with Bison via a BNF grammar file. PHP uses  #
# a LALR(1) (look ahead, left-to-right) context-free grammar. The look ahead part      #
# means that the parser is able to look n tokens ahead (1, in this case) to resolve    #
# ambiguities it may encounter whilst parsing. The left-to-right part means that it    #
# parses the token stream from left-to-right.                                          #
#                                                                                      #
# The generated parser stage takes the token stream from the lexer as input and has    #
# two jobs. It firstly verifies the validity of the token order by attempting to       #
# match them against any one of the grammar rules defined in its BNF grammar file.     #
# This ensures that valid language constructs are being formed by the tokens in the    #
# token stream. The second job of the parser is to generate the abstract syntax tree   #
# (AST) – a tree view of the source code that will be used during the next stage       #
# (compilation).                                                                       #
#                                                                                      #
# We can view a form of the AST produced by the parser using the php-ast extension.    #
# The internal AST is not directly exposed because it is not particularly “clean”      #
# to work with (in terms of consistency and general usability), and so the php-ast     #
# extension performs a few transformations upon it to make it nicer to work with.      #
#                                                                                      #
# Let’s have a look at the AST for a rudimentary piece of code:                        #
#--------------------------------------------------------------------------------------#

<?php
$code = <<<'code'
<?php
$ast = 1;
code;

print_r(ast\parse_code($code, 30));
?>

#--------------------------------------------------------------------------------------#
#                                                                                      #
#  The tree nodes (which are typically of type ast\Node) have several properties:      #
#                                                                                      #
#  kind – An integer value to depict the node type; each has a corresponding constant  #
#  (e.g. AST_STMT_LIST => 132, AST_ASSIGN => 517, AST_VAR => 256)                      #
#                                                                                      #
#  flags – An integer that specifies overloaded behaviour (e.g. an ast\AST_BINARY_OP   #
#  node will have flags to differentiate which binary operation is occurring)          #
#                                                                                      #
#  lineno – The line number, as seen from the token information earlier                #
#                                                                                      #
#  children – sub nodes, typically parts of the node broken down further (e.g. a       #
#  function node will have the children: parameters, return type, body, etc)           #
#                                                                                      #
#--------------------------------------------------------------------------------------#
