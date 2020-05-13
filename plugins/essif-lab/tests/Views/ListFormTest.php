<?php

namespace TNO\EssifLab\Tests\Views;

use TNO\EssifLab\Tests\Stubs\ItemDisplayable;
use TNO\EssifLab\Tests\Stubs\ItemNonDisplayable;
use TNO\EssifLab\Tests\TestCase;
use TNO\EssifLab\Views\ListForm;

class ListFormTest extends TestCase {
	/** @test */
	function renders_nothing_found_message_without_any_items() {
		$subject = new ListForm($this->integration, $this->model, []);

		$actual = $subject->render();

		$expected = '/'.sprintf(ListForm::NO_VALUES, 'models', '<a.*>.*<\/a>').'/';

		$this->assertRegExp($expected, $actual);
	}

	/** @test */
	function renders_nothing_found_message_without_any_displayable_items() {
		$subject = new ListForm($this->integration, $this->model, [
			new ItemDisplayable('', 'no value so not displayable'),
			new ItemNonDisplayable(['array']),
		]);

		$actual = $subject->render();

		$expected = '/'.sprintf(ListForm::NO_VALUES, 'models', '<a.*>.*<\/a>').'/';

		$this->assertRegExp($expected, $actual);
	}

	/** @test */
	function renders_actions_only_with_displayable_values() {
		$subject = new ListForm($this->integration, $this->model, [
			new ItemDisplayable('hello', 'world'),
		]);

		$actual = $subject->render();

		$expected = '/<div.*class="actions".*<\/div>/';

		$this->assertRegExp($expected, $actual);
	}

	/** @test */
	function renders_add_new_button_with_displayable_values() {
		$subject = new ListForm($this->integration, $this->model, [
			new ItemDisplayable('hello', 'world'),
		]);

		$actual = $subject->render();

		$expected = '/'.sprintf(ListForm::ADD_NEW, 'Model').'/';

		$this->assertRegExp($expected, $actual);
	}

	/** @test */
	function renders_add_new_button_with_nothing_found_message() {
		$subject = new ListForm($this->integration, $this->model, []);

		$actual = $subject->render();

		$expected = '/'.sprintf(ListForm::ADD_NEW, 'Model').'/';

		$this->assertRegExp($expected, $actual);
	}
}