<?php

namespace TNO\EssifLab\Application\Workflows;

use mysql_xdevapi\Exception;
use TNO\EssifLab\Contracts\Abstracts\Workflow;

class ManageHooks extends Workflow {
	public static function options(): array {
		// TODO: load all selectable options to be displayed in the select lists.
		return ['context' => ['hello', 'world'], 'target' => ['foo', 'bar']];
	}

	public function add($request) {
//        $attrs = array("context" => "test2", "target" => "test2");
//        $attrs = array("context" => "test3", "target" => "test3");
        $attrs = array("context" => "test4", "target" => "test4");
        if(!empty($this->getJsonPostContentAsArray()["hooks"])) {
            $equal = false;
            foreach ($this->getJsonPostContentAsArray()["hooks"] as $post_content_hooks_array) {
                if ($post_content_hooks_array["context"] != $attrs["context"] && $post_content_hooks_array["target"] != $attrs["target"]) {
                    //hook is not linked to this validation policy yet
                } else {
                    $equal = true;
                    //hook is already linked to this validation policy
                }
            }
            if(!$equal) $this->post->post_content = json_encode(array("hooks" => array_merge($this->getJsonPostContentAsArray()["hooks"], array($attrs))));
        }
        else{
            $this->post->post_content = json_encode(array("hooks" => array($attrs)));
        }
        wp_update_post($this->post, true);
	}

	public function edit($request) {
		// TODO: edit a hook of a validation policy
	}

	public function delete($request) {
		// TODO: delete a hook of a validation policy
        var_dump($_POST);
        var_dump($request, $this->getJsonPostContentAsArray(), $request["id"]);
        $array_deleted = $this->getJsonPostContentAsArray();
        unset($array_deleted["hooks"][$request["id"]]);
        var_dump("array_deleted:", $array_deleted);
        $this->post->post_content = json_encode($array_deleted);
        var_dump("post_content:", $this->post->post_content);
        wp_update_post($this->post, true);
//        die();
	}

    private function getJsonPostContentAsArray($post = null): array {
        $post_content = 'post_content';
        $post = empty($post) ? get_post() : $post;
        $content = is_array($post) && array_key_exists($post_content, $post) ? $post[$post_content] : null;
        $content = empty($content) && is_object($post) && property_exists($post, $post_content) ? $post->{$post_content} : $content;
        $content = json_decode($content, true);

        return empty($content) || ! is_array($content) ? [] : $content;
    }
}