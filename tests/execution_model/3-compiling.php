
#--------------------------------------------------------------------------------------#
#                                Stage 3 - Compiling                                   #
#--------------------------------------------------------------------------------------#
# The compilation stage consumes the AST, where it emits opcodes by recursively        #
# traversing the tree. This stage also performs a few optimizations. These include     #
# resolving some function calls with literal arguments (such as strlen("abc") to       #
# int(3)) and folding constant mathematical expressions (such as 60 * 60 * 24 to       #
# int(86400)).                                                                         #
#                                                                                      #
# We can inspect the opcode output at this stage in a number of ways, we're going      #
# to use VLD in this instance.                                                         #
#--------------------------------------------------------------------------------------#

<?php
shell_exec('php -dopcache.enable_cli=1 -dopcache.optimization_level=0 -dvld.active=1 -dvld.execute=0 test.php');
?>