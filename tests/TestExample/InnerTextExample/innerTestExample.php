<?php
class InnerTestExample extends PHPUnit_Framework_TestCase
{	
	public function testExample()
	{
		$this->assertTrue(true);
	}
	
	public function testExample2()
	{
		sleep(1);
		$this->assertTrue(true);
	}
}
?>