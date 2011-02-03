<?php
class TestExample extends PHPUnit_Framework_TestCase
{	
	public function testExample()
	{
		$this->assertTrue(true);
	}
	
	public function testExample2()
	{
		sleep(100);
		$this->assertTrue(true);
	}
}
?>