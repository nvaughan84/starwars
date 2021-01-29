<?php

use PHPUnit\Framework\TestCase;
use Droid\Droid;

class DroidTest extends TestCase
{

	public function testCheckMove()
	{
		$droid = new Droid('empire.php','Nick');
	
		$this->assertContains($droid->checkMove('ffffrffrff'),array(200,410,417),'Test Failed');
	}

    public function testReverseThrusters()
	{
	    $droid = new Droid('empire.php','Nick');
	    $this->assertEquals($droid->reverseThrusters('ffffrffrff'),'ffffrffrf');
	}

	public function testSwitchLateralMovement()
	{
		$droid = new Droid('empire.php','Nick');
	    $this->assertEquals($droid->switchLateralMovement(array('f','l','r')),array('f','r','l'));
	}


}