<?php
namespace tests\DuckPhp\SingletonEx;

use DuckPhp\SingletonEx\SingletonExTrait;

class SingletonExTest extends \PHPUnit\Framework\TestCase
{
    public function testAll()
    {
        \MyCodeCoverage::G()->begin(SingletonExTrait::class);
        
        SingletonExObject::G();
        SingletonExObject::G(new SingletonExObject());
        $t=\MyCodeCoverage::G();
        define('__SINGLETONEX_REPALACER',SingletonExObject::class.'::CreateObject');
        \MyCodeCoverage::G($t);
        SingletonExObject::G();
        
        \MyCodeCoverage::G()->end();
        /*
        SingletonEx::G()->G($object=null);
        //*/
    }
}
class SingletonExObject
{
    use \DuckPhp\SingletonEx\SingletonExTrait;
    
    public static function CreateObject($class, $object)
    {
        static $_instance;
        $_instance=$_instance??[];
        $_instance[$class]=$object?:($_instance[$class]??($_instance[$class]??new $class));
        return $_instance[$class];
    }

}
