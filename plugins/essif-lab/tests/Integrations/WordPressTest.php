<?php

namespace TNO\EssifLab\Tests\Integrations;

use Closure;
use TNO\EssifLab\Integrations\Contracts\BaseIntegration;
use TNO\EssifLab\Integrations\WordPress;
use TNO\EssifLab\Tests\TestCase;

class WordPressTest extends TestCase {
	protected $subject;

	protected function setUp(): void {
		parent::setUp();
		$utilities = [
			BaseIntegration::REGISTER_TYPE => function ($x, $y = []) {
				$this->callStubFunc(BaseIntegration::REGISTER_TYPE, $x, $y);
			},
			BaseIntegration::REGISTER_RELATION => function ($id, $title, $callback, $screen) {
				$this->callStubFunc(BaseIntegration::REGISTER_RELATION, $id, $title, $callback, $screen);
			},
			WordPress::ADD_ACTION => function ($x, $y) {
				call_user_func_array($y, []);
				$this->callStubFunc(WordPress::ADD_ACTION, $x, $y);
			},
			WordPress::ADD_MENU_PAGE => function (
				$page_title,
				$menu_title,
				$capability,
				$menu_slug,
				$callback,
				$icon_url
			) {
				$this->callStubFunc(WordPress::ADD_MENU_PAGE, $page_title, $menu_title, $capability, $menu_slug, $callback, $icon_url);
			},
		];
		$this->subject = new WordPress($this->application, $this->manager, $utilities);
	}

	/** @test */
	function registers_model_type_as_custom_post_type() {
		$this->subject->registerModelType($this->model);

		$this->assertArrayHasKey(BaseIntegration::REGISTER_TYPE, $this->was_called);
		$this->assertEquals(1, $this->was_called[BaseIntegration::REGISTER_TYPE][self::TIMES_CALLED]);
		$this->assertEquals([
			$this->model->getTypeName(),
			array_merge(WordPress::DEFAULT_TYPE_ARGS, [
				'show_in_menu' => $this->application->getNamespace(),
				'supports' => $this->model->getFields(),
				'labels' => WordPress::generateLabels($this->model),
			]),
		], $this->was_called[BaseIntegration::REGISTER_TYPE][self::LAST_CALL_WITH]);
	}

	/** @test */
	function register_model_relations_as_meta_boxes() {
		$this->subject->registerModelRelations($this->model);

		$id = 'model_model';
		$title = 'Models';
		$post_type = 'model';
		$is_closure = function ($x): bool {
			return $x instanceof Closure;
		};

		$this->assertArrayHasKey(BaseIntegration::REGISTER_RELATION, $this->was_called);
		// $id parameter is equal to:
		$this->assertEquals($id, $this->was_called[BaseIntegration::REGISTER_RELATION][self::LAST_CALL_WITH][0]);
		// $title parameter is equal to:
		$this->assertEquals($title, $this->was_called[BaseIntegration::REGISTER_RELATION][self::LAST_CALL_WITH][1]);
		// $callback is a closure:
		$this->assertTrue($is_closure($this->was_called[BaseIntegration::REGISTER_RELATION][self::LAST_CALL_WITH][2]));
		// $screen parameter is equal to:
		$this->assertEquals($post_type, $this->was_called[BaseIntegration::REGISTER_RELATION][self::LAST_CALL_WITH][3]);
	}

	/** @test */
	function registers_models_when_running_install() {
		$this->subject->install();

		$this->assertArrayHasKey(BaseIntegration::REGISTER_TYPE, $this->was_called);
		/**
		 * Types
		 * 1. Credential
		 * 2. Hook
		 * 3. Input
		 * 4. Issuer
		 * 5. Schema
		 * 6. Target
		 * 7. ValidationPolicy
		 */
		$this->assertEquals(7, $this->was_called[BaseIntegration::REGISTER_TYPE][self::TIMES_CALLED]);
	}

	/** @test */
	function registers_model_relations_when_running_install() {
		$this->subject->install();

		$this->assertArrayHasKey(BaseIntegration::REGISTER_RELATION, $this->was_called);
		/**
		 * Relations
		 * - Credential:
		 *   1. Input
		 *   2. Issuer
		 *   3. Schema
		 * - Hook:
		 *   4. Target
		 * - ValidationPolicy:
		 *   5. Hook
		 *   6. Credential
		 */
		$this->assertEquals(6, $this->was_called[BaseIntegration::REGISTER_RELATION][self::TIMES_CALLED]);
	}

	/** @test */
	function registers_menu_item_when_installing() {
		$this->subject->install();

		$page_title = $this->application->getName();
		$menu_title = $page_title;
		$capability = WordPress::ADMIN_MENU_CAPABILITY;
		$menu_slug = $this->application->getNamespace();
		$callback = null;
		$icon_url = WordPress::ADMIN_MENU_ICON_URL;

		$this->assertArrayHasKey(WordPress::ADD_MENU_PAGE, $this->was_called);
		$this->assertEquals(1, $this->was_called[WordPress::ADD_MENU_PAGE][self::TIMES_CALLED]);
		$this->assertEquals($page_title, $this->was_called[WordPress::ADD_MENU_PAGE][self::LAST_CALL_WITH][0]);
		$this->assertEquals($menu_title, $this->was_called[WordPress::ADD_MENU_PAGE][self::LAST_CALL_WITH][1]);
		$this->assertEquals($capability, $this->was_called[WordPress::ADD_MENU_PAGE][self::LAST_CALL_WITH][2]);
		$this->assertEquals($menu_slug, $this->was_called[WordPress::ADD_MENU_PAGE][self::LAST_CALL_WITH][3]);
		$this->assertEquals($callback, $this->was_called[WordPress::ADD_MENU_PAGE][self::LAST_CALL_WITH][4]);
		$this->assertEquals($icon_url, $this->was_called[WordPress::ADD_MENU_PAGE][self::LAST_CALL_WITH][5]);
	}
}