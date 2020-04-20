<?php

use PHPUnit\Framework\TestCase;
use TNO\EssifLab\Application\Workflows\ManageCredentials;

final class ManageCredentialsTest extends TestCase {
    public function testAdd(): void {
        $this->assertEquals('test', ManageCredentials::add("test"));
    }
}