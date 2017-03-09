<?php
/**
 * Created by PhpStorm.
 * User: Manas
 * Date: 8/26/2015
 * Time: 10:15 AM
 */

namespace Mb\DQL\DateTime;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class Date extends FunctionNode
{
    public $dateExpression;

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->dateExpression = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        $driver = $sqlWalker->getConnection()->getDriver();

        switch($driver) {
            case ("pdo_mysql"):
                return "DATE(".$this->dateExpression->dispatch($sqlWalker).")";
                break;
            case ("pdo_pgsql"):
                return $this->dateExpression->dispatch($sqlWalker)."::timestamp::date";
                break;
            default:
                return $this->dateExpression->dispatch($sqlWalker);
                break;
        }
    }
}
