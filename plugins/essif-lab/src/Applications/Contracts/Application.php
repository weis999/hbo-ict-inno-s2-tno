<?php

namespace TNO\EssifLab\Applications\Contracts;

interface Application {
	function getName(): string;

	function getNamespace(): string;

	function getAppDir(): string;
}