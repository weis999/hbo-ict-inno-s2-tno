<?php

use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase {
    public function testHelloWorld(): void {
        $this->assertEquals('Hello world', 'Hello world');
    }
}