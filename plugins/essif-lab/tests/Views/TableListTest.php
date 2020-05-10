<?php

namespace TNO\EssifLab\Tests\Views;

use TNO\EssifLab\Tests\Stubs\ItemDisplayable;
use TNO\EssifLab\Tests\Stubs\ItemNonDisplayable;
use TNO\EssifLab\Tests\TestCase;
use TNO\EssifLab\Views\Items\Displayable;
use TNO\EssifLab\Views\TableList;

class TableListTest extends TestCase {
	/** @test */
	function renders_nothing_found_message_without_any_items() {
		$subject = new TableList($this->integration, $this->model, []);

		$actual = $subject->render();

		$expected = '/'.sprintf(TableList::NO_RESULTS, 'models').'/';

		$this->assertRegExp($expected, $actual);
	}

	/** @test */
	function renders_nothing_found_message_without_any_non_displayable_items() {
		$subject = new TableList($this->integration, $this->model, [
			new ItemDisplayable('hello', 'world')
		]);

		$actual = $subject->render();

		$expected = '/'.sprintf(TableList::NO_RESULTS, 'models').'/';

		$this->assertRegExp($expected, $actual);
	}


	/** @test */
	function renders_correctly_with_non_displayable_items_with_displayable_items_as_value() {
		$subject = new TableList($this->integration, $this->model, [
			new ItemNonDisplayable([
				new Displayable(1, 'hello'),
				new Displayable(1, 'world'),
			])
		]);

		$actual = $subject->render();

		$expected = '/hello.*world/';

		$this->assertRegExp($expected, $actual);
	}

}