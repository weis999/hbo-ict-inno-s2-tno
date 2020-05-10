<?php

namespace TNO\EssifLab\Tests\Views;

use TNO\EssifLab\Constants;
use TNO\EssifLab\Tests\Stubs\ItemDisplayable;
use TNO\EssifLab\Tests\TestCase;
use TNO\EssifLab\Views\Select;

class SelectTest extends TestCase {
	/** @test */
	function renders_nothing_without_items() {
		$subject = new Select($this->integration, $this->model);

		$expected = '';

		$actual = $subject->render();

		$this->assertEquals($expected, $actual);
	}

	/** @test */
	function renders_nothing_when_all_items_have_non_displayable_values() {
		$subject = new Select($this->integration, $this->model, [
			new ItemDisplayable(['array']),
			new ItemDisplayable(function () { return 'closure'; }),
		]);

		$expected = '';

		$actual = $subject->render();

		$this->assertEquals($expected, $actual);
	}

	/** @test */
	function renders_only_the_items_which_are_displayable() {
		$subject = new Select($this->integration, $this->model, [
			new ItemDisplayable('hello', 'world'),
			new ItemDisplayable(['array']),
		]);

		$expected = '/.*"><option value="hello">world<\/option>\<\/select>/';

		$actual = $subject->render();

		$this->assertRegExp($expected, $actual);
	}

	/** @test */
	function renders_value_as_label_when_no_label_set() {
		$subject = new Select($this->integration, $this->model, [
			new ItemDisplayable('hello'),
		]);

		$expected = '/.*value="hello".*hello.*/';

		$actual = $subject->render();

		$this->assertRegExp($expected, $actual);
	}

	/** @test */
	function renders_select_name_correctly() {
		$subject = new Select($this->integration, $this->model, [
			new ItemDisplayable('hello'),
		]);

		$expected = '/.*name="'.$this->application->getNamespace().'\['.Constants::ACTION_NAME_ADD_RELATION.']"*/';

		$actual = $subject->render();

		$this->assertRegExp($expected, $actual);
	}
}