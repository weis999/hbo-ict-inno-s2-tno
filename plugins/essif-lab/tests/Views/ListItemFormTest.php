<?php

namespace TNO\EssifLab\Tests\Views;

use TNO\EssifLab\Tests\Stubs\ItemDisplayable;
use TNO\EssifLab\Tests\Stubs\ItemNonDisplayable;
use TNO\EssifLab\Tests\TestCase;
use TNO\EssifLab\Utilities\Contracts\BaseUtility;
use TNO\EssifLab\Views\ListItemForm;

class ListItemFormTest extends TestCase {
	/** @test */
	function does_only_render_the_first_item() {
		$subject = new ListItemForm($this->integration, $this->model, [
			new ItemDisplayable('hello', 'world'),
			new ItemDisplayable('foo', 'bar'),
		]);

		$actual = $subject->render();

		$expected = [
			'/.*hello.*world.*/',
			'/.*foo.*bar.*/'
		];

		$this->assertRegExp($expected[0], $actual);
		$this->assertNotRegExp($expected[1], $actual);
	}

	/** @test */
	function renders_nothing_without_any_items() {
		$subject = new ListItemForm($this->integration, $this->model, []);

		$actual = $subject->render();

		$expected = '';

		$this->assertEquals($expected, $actual);
	}

	/** @test */
	function renders_nothing_without_any_displayable_items() {
		$subject = new ListItemForm($this->integration, $this->model, [
			new ItemDisplayable('', 'value is empty so not displayable'),
			new ItemNonDisplayable(['array']),
		]);

		$actual = $subject->render();

		$expected = '';

		$this->assertEquals($expected, $actual);
	}

	/** @test */
	function filters_out_preceding_non_displayable_items_and_renders_with_first_displayable() {
		$subject = new ListItemForm($this->integration, $this->model, [
			new ItemNonDisplayable(['Array']),
			new ItemDisplayable('hello', 'world'),
		]);

		$actual = $subject->render();

		$expected = [
			'/.*hello.*world.*/',
			'/.*Array.*/'
		];

		$this->assertRegExp($expected[0], $actual);
		$this->assertNotRegExp($expected[1], $actual);
	}

	/** @test */
	function renders_edit_and_remove_buttons() {
		$subject = new ListItemForm($this->integration, $this->model, [
			new ItemDisplayable('hello', 'world'),
		]);

		$actual = $subject->render();

		$expected = [
			'/.*<button.*value="hello".*>'.ListItemForm::REMOVE.'<\/button>.*/',
			'/.*<a.*>'.ListItemForm::EDIT.'<\/a>.*/'
		];

		$this->assertRegExp($expected[0], $actual);
		$this->assertRegExp($expected[1], $actual);

		$history = $this->utility->getHistoryByFuncName(BaseUtility::GET_EDIT_MODEL_LINK);
		$this->assertCount(1, $history);

		$entry = current($history);
		$params = $entry->getParams();
		$this->assertEquals('hello', $params[0]);
	}
}