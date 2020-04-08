<?php

namespace TNO\EssifLab\Contracts\Traits;

use TNO\EssifLab\Presentation\Components\Fieldset;

trait WithAddPostLink {
	public function getAddPostLink($postType, $singular = ''): Fieldset {
		$singular = empty($singular) ? $postType : $singular;
		return new Fieldset($this, [
			'baseName' => $this->baseName,
			'formControls' => [
				[
					'tag' => 'a',
					'class' => 'button',
					'href' => admin_url('post-new.php?post_type='.$postType),
					'children' => __('Add New', $this->getDomain()).' '.ucfirst($singular),
				],
			],
		]);
	}
}