<?php

use PHPUnit\Framework\TestCase;
use \TNO\Example;

final class ExampleTest extends TestCase {
	public function testCanReturnString(): void {
		$this->assertEquals(Example::toString(), 'Hello world');
	}

    public function testHelloWorld(): void {
        $this->assertEquals('Hello world', 'Hello world');
    }
}