<?php

namespace Tests\Mask\Predefined\Br;

use Mask\Predefined\Br\Cpf;
use PHPUnit_Framework_TestCase;

class CpfTest extends PHPUnit_Framework_TestCase
{
    use \Tests\Mask\Structure\SetUp;

    public function testGetStringMask() 
    {
        $this->assertContains('.', (new Cpf)->getStringMask());
        $this->assertNotContains('/', (new Cpf)->getStringMask());
        $this->assertNotContains('\\', (new Cpf)->getStringMask());
        $this->assertContains('#', (new Cpf)->getStringMask());
        $this->assertContains('-', (new Cpf)->getStringMask());
    }
    
    public function provider()
    {
        return array(
            array('05265403496'),
            array('0526540349'),
            array('052654034'),
            array('05265403'),
            array('0526540'),
        );
    }


    /**
     * @dataProvider provider
     */
    public function testMaskDefault($cpf) 
    {
        $this->assertContains('-', (new Cpf)->mask($cpf)->toString());
        $this->assertTrue(strlen($cpf) < strlen((new Cpf)->mask($cpf)->toString()));
    }

    /**
     * @dataProvider provider
     */
    public function testMaskCompleteWith($cpf) 
    {
        $this->assertContains('-', (new Cpf)->mask($cpf, '0', STR_PAD_RIGHT)->toString());
        $this->assertTrue(strlen((new Cpf)->mask($cpf)) <= strlen((new Cpf)->mask($cpf, '0', STR_PAD_RIGHT)->toString()));
        $this->assertTrue(strlen((new Cpf)->mask($cpf)) === strlen((new Cpf)->mask($cpf, ' ')->toString()));
    }
    
    public function testMaskReal() 
    {
        $this->assertContains('-', (new Cpf)->mask('05265403496')->toString());
        $this->assertContains('.', (new Cpf)->mask('05265403496')->toString());
        $this->assertNotContains('/', (new Cpf)->mask('05265403496')->toString());
        $this->assertEquals('052.654.034-96', (new Cpf)->mask('05265403496')->toString());
        $this->assertEquals('052.654.034-90', (new Cpf)->mask('0526540349', '0', STR_PAD_RIGHT)->toString());
        $this->assertEquals('052.654.034-00', (new Cpf)->mask('052654034', '0', STR_PAD_RIGHT)->toString());
        $this->assertEquals('052.654.000-00', (new Cpf)->mask('052654', '0', STR_PAD_RIGHT)->toString());
        $this->assertEquals('.   .  0-52', (new Cpf)->mask('052')->toString());
    }
    
}
