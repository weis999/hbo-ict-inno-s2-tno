<?php

namespace TNO\EssifLab\Tests\Views;

use TNO\EssifLab\Tests\Stubs\ItemDisplayable;
use TNO\EssifLab\Tests\Stubs\ItemNonDisplayable;
use TNO\EssifLab\Tests\TestCase;
use TNO\EssifLab\Views\TableListItem;

class TableListItemTest extends TestCase {
	/** @test */
	function renders_nothing_without_any_items() {
		$subject = new TableListItem($this->integration, $this->model, []);

		$actual = $subject->render();

		$expected = '';

		$this->assertEquals($expected, $actual);
	}

	/** @test */
	function renders_nothing_without_any_displayable_items() {
		$subject = new TableListItem($this->integration, $this->model, [
			new ItemDisplayable('', 'value is empty so not displayable'),
			new ItemNonDisplayable(['array']),
		]);

		$actual = $subject->render();

		$expected = '';

		$this->assertEquals($expected, $actual);
	}

	/** @test */
	function renders_first_item_with_row_actions() {
		$subject = new TableListItem($this->integration, $this->model, [
			new ItemDisplayable('hello', 'world'),
			new ItemDisplayable('foo', 'bar'),
		]);

		$actual = $subject->render();

		$expected = '/^<td.*<strong.*\/strong><div.*class="row-actions".*\/div><\/td><td/';

		$this->assertRegExp($expected, $actual);
	}
}