<?php

namespace TNO\EssifLab\Application\Workflows;

use mysql_xdevapi\Exception;
use TNO\EssifLab\Contracts\Abstracts\Workflow;

class ManageHooks extends Workflow {
	public static function options(): array {
		// TODO: load all selectable options to be displayed in the select lists.
		return ['context' => ['hello' => 'hello', 'world' => 'world'], 'target' => ['foo' => 'foo', 'bar' => 'bar']];
	}

	public function add($request) {
//	    var_dump($request);
//	    die();
        if(!empty($this->getJsonPostContentAsArray()["hook"])) {
            $equal = false;
            foreach ($this->getJsonPostContentAsArray()["hook"] as $post_content_hook_array) {
                if ($post_content_hook_array["context"] != $request["context"] || $post_content_hook_array["target"] != $request["target"]) {
                    //hook is not linked to this validation policy yet
                } else {
                    $equal = true;
                    //hook is already linked to this validation policy
                }
            }
            if(!$equal){
                $merged_array = array_merge($this->getJsonPostContentAsArray()["hook"], array($request));
                $merged_array[array_key_last($merged_array)]["id"] = array_key_last($merged_array);
//                var_dump("key", array_key_last($merged_array));
//                var_dump("array", $merged_array);
//                die();
                $this->post->post_content = json_encode(array("hook" => $merged_array));
            }
        }
        else{
            $this->post->post_content = json_encode(array("hook" => array($request)));
        }
        wp_update_post($this->post, true);
	}

	public function edit($request) {
		// TODO: edit a hook of a validation policy
	}

	public function delete($request) {
		// TODO: delete a hook of a validation policy
//        var_dump($request, $this->getJsonPostContentAsArray(), $request["id"]);
        $array_deleted = $this->getJsonPostContentAsArray();
        unset($array_deleted["hook"][$request["id"]]);
//        var_dump("array_deleted:", $array_deleted);
        $this->post->post_content = json_encode($array_deleted);
//        var_dump("post_content:", $this->post->post_content);
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