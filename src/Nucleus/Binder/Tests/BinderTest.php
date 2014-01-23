<?php

namespace Nucleus\Binder\Tests;

use Nucleus\Binder\Binder;
use Nucleus\Binder\IBinder;
use Nucleus\Binder\InvalidScopeException;
use Nucleus\Binder\MemoryVariableRegistry;

class BinderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IBinder
     */
    private $binder;

    public function loadBinder()
    {
        return new Binder();
    }

    public function setUp()
    {
        $this->binder = $this->loadBinder();
    }


    public function testRestoreBindAll()
    {
        $this->binder->registerVariableRegistry(new MemoryVariableRegistry());
        $classToBind1 = new ClassToBind();

        $this->binder->restore($classToBind1,'public');
        $this->binder->restore($classToBind1,'protected');
        $this->binder->restore($classToBind1,'private');

        $this->assertEquals('public',$classToBind1->public);
        $this->assertEquals('protected',$classToBind1->getProtected());
        $this->assertEquals('private',$classToBind1->getPrivate());

        $classToBind1->public = 'public1';
        $classToBind1->setProtected('protected1');
        $classToBind1->setPrivate('private1');

        $this->binder->bindAll();

        $classToBind2 = new ClassToBind();

        $this->binder->restore($classToBind2,'public');
        $this->binder->restore($classToBind2,'protected');
        $this->binder->restore($classToBind2,'private');

        $this->assertEquals('public1',$classToBind2->public);
        $this->assertEquals('protected1',$classToBind2->getProtected());
        $this->assertEquals('private1',$classToBind2->getPrivate());
    }

    public function testBindRestore()
    {
        $this->binder->registerVariableRegistry(new MemoryVariableRegistry());
        $classToBind1 = new ClassToBind();

        $this->binder->bind($classToBind1,'public');

        $classToBind1->public = 'changed';

        $this->assertEquals('changed',$classToBind1->public);

        $this->binder->restore($classToBind1,'public');

        $this->assertEquals('public',$classToBind1->public);
    }

    public function testInvalidScopeException()
    {
        try {
            $this->binder->restore(new ClassToBind(),'public');
            $this->fail('A exception of type [Nucleus\Binder\InvalidScopeException] should have be thrown');
        } catch(InvalidScopeException $e) {
            $this->assertEquals(InvalidScopeException::formatMessage(IBinder::DEFAULT_SCOPE),$e->getMessage());
        }

    }
}

class ClassToBind
{
    public $public = 'public';

    protected $protected = 'protected';

    private $private = 'private';

    public function getProtected()
    {
        return $this->protected;
    }

    public function setProtected($value)
    {
        $this->protected = $value;
    }

    public function getPrivate()
    {
        return $this->private;
    }

    public function setPrivate($value)
    {
        $this->private = $value;
    }
}