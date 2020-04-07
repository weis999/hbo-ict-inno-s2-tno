<?php

namespace TNO\EssifLab\Application\Workflows;

use mysql_xdevapi\Exception;
use TNO\EssifLab\Contracts\Abstracts\Workflow;

class ManageHooks extends Workflow {
	public static function getActionName(): string {
		return 'hooks';
	}

	public function add($attrs) {
		// TODO: add a hook to a validation policy
        var_dump($this->post->post_content);
//        $this->post->post_content->items = $this->post->post_content->items + json_encode($attrs);
        wp_update_post($this->post, true);
        var_dump($attrs, $this->post);
        die();
	}

	public function edit($request) {
		// TODO: edit a hook of a validation policy
	}

	public function delete($request) {
		// TODO: delete a hook of a validation policy
	}

    private function getPostContentAsJson($post = null) {
        $post_content = 'post_content';
        $post = empty($post) ? get_post() : $post;
        $content = is_array($post) && array_key_exists($post_content, $post) ? $post[$post_content] : null;
        $content = empty($content) && is_object($post) && property_exists($post, $post_content) ? $post->{$post_content} : $content;
        $content = json_decode($content, true);

        return empty($content) || ! is_array($content) ? [] : $content;
    }
}