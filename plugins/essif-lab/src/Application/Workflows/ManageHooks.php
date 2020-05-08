<?php

namespace TNO\EssifLab\Application\Workflows;

use TNO\EssifLab\Contracts\Abstracts\Workflow;

class ManageHooks extends Workflow {
	public static function options(): array {
		// TODO: load all selectable options to be displayed in the select lists.
		return ['context' => ['hello' => 'hello', 'world' => 'world'], 'target' => ['foo' => 'foo', 'bar' => 'bar']];
	}

	public function add($request) {
		$data = $this->getJsonPostContentAsArray();
		$hooks = array_key_exists('hook', $data) ? $data['hook'] : null;

		if ($this->requestIsEmpty($request)) {
			// TODO: add custom exception (request is empty)
			throw new \Exception('request is empty');
		}

		if (! empty($hooks)) {
			if ($this->doesHookAlreadyExists($hooks, $request)) {
				// TODO: add custom exception (request already exists)
				throw new \Exception('request already exists');
			}
			$hooks[] = $this->generateHookRecord($hooks, $request);
		} else {
			$hooks = [$this->generateHookRecord($hooks, $request)];
		}

		$this->post->post_content = json_encode(array_merge($data, ['hook' => $hooks]));
		wp_update_post($this->post);
	}

	private function requestIsEmpty($request): bool {
		return empty(array_filter($request));
	}

	private function doesHookAlreadyExists($hooks, $request): bool {
		return count(array_filter($hooks, function ($v) use ($request) {
				return $v['context'] !== $request['context'] || $v['target'] !== $request['target'];
			})) !== count($hooks);
	}

	private function generateID($hooks): int {
		$last = is_array($hooks) && array_key_exists(count($hooks) - 1, $hooks) ? $hooks[count($hooks) - 1] : null;

		return ! empty($last) && array_key_exists('ID', $last) ? $last['ID'] : 1;
	}

	private function generateHookRecord($hooks, $request): array {
		return array_merge(['ID' => $this->generateID($hooks)], $request);
	}

	public function edit($request) {
		// TODO: edit a hook of a validation policy
	}

	public function delete($request) {
        $array_deleted = $this->getJsonPostContentAsArray($this->post);
        unset($array_deleted["hook"][$request["id"]]);
        $this->post->post_content = json_encode($array_deleted);
        wp_update_post($this->post, true);
        wp_redirect($_SERVER['HTTP_REFERER']);
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