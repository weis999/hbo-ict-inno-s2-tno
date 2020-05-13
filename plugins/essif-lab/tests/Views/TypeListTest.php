<?php

namespace TNO\EssifLab\Tests\Views;

use TNO\EssifLab\Tests\Stubs\ItemDisplayable;
use TNO\EssifLab\Tests\Stubs\ItemNonDisplayable;
use TNO\EssifLab\Tests\TestCase;
use TNO\EssifLab\Views\TypeList;

class TypeListTest extends TestCase {
	/** @test */
	function shows_no_result_message_with_empty_items() {
		$subject = new TypeList($this->integration, $this->model, []);

		$actual = $subject->render();

		$expected = '/No models found.*No models found/';

		$this->assertRegExp($expected, $actual);
	}

	/** @test */
	function shows_list_items_when_supplying_an_item_with_list_item_label() {
		$subject = new TypeList($this->integration, $this->model, [
			new ItemNonDisplayable([
				new ItemNonDisplayable([
					new ItemDisplayable(1, 'hello'),
					new ItemDisplayable(1, 'world'),
				]),
			], TypeList::LIST_ITEMS),
		]);

		$actual = $subject->render();

		$expected = '/No models found.*hello.*world/';

		$this->assertRegExp($expected, $actual);
	}

	/** @test */
	function shows_form_items_when_supplying_an_item_with_form_item_label() {
		$subject = new TypeList($this->integration, $this->model, [
			new ItemNonDisplayable([
				new ItemDisplayable(1, 'hello'),
				new ItemDisplayable(1, 'world'),
			], TypeList::FORM_ITEMS),
		]);

		$actual = $subject->render();

		$expected = '/hello.*world.*No models found/';

		$this->assertRegExp($expected, $actual);
	}

	/** @test */
	function renders_form_and_list_items() {
		$subject = new TypeList($this->integration, $this->model, [
			new ItemNonDisplayable([
				new ItemDisplayable(1, 'hello'),
				new ItemDisplayable(1, 'world'),
			], TypeList::FORM_ITEMS),
			new ItemNonDisplayable([
				new ItemNonDisplayable([
					new ItemDisplayable(1, 'foo'),
					new ItemDisplayable(1, 'bar'),
				]),
			], TypeList::LIST_ITEMS),
		]);

		$actual = $subject->render();

		$expected = '/hello.*world.*foo.*bar/';

		$this->assertRegExp($expected, $actual);
	}
}