<?php

namespace Crowles\Cophi;

use Exception;

class Lexer extends Base
{

    protected static $_terminals = array(
        "/^(root)/" => "T_ROOT",
        "/^(map)/" => "T_MAP",
        "/^(\s+)/" => "T_WHITESPACE",
        "/^(\/[A-Za-z0-9\/:]+[^\s])/" => "T_URL",
        "/^(->)/" => "T_BLOCKSTART",
        "/^(::)/" => "T_DOUBLESEPARATOR",
        "/^(\w+)/" => "T_IDENTIFIER",
    );

    public function __construct($debug)
    {
        parent::__construct($debug);
    }

    /**
     * @param $source
     * @return array
     * @throws Exception
     */
    public static function run($source) {
        $tokens = array();

        foreach($source as $number => $line) {
            $offset = 0;
            while($offset < strlen($line)) {
                $result = static::_match($line, $number, $offset);
                if($result === false) {
                    throw new Exception("Unable to parse line " . ($line+1) . ".");
                }
                $tokens[] = $result;
                $offset += strlen($result['match']);
            }
        }

        return $tokens;
    }

    protected static function _match($line, $number, $offset) {
        $string = substr($line, $offset);

        foreach(static::$_terminals as $pattern => $name) {
            if(preg_match($pattern, $string, $matches)) {
                return array(
                    'match' => $matches[1],
                    'token' => $name,
                    'line' => $number+1
                );
            }
        }

        return false;
    }
}