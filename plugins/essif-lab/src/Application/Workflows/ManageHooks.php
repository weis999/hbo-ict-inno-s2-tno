<?php

namespace TNO\EssifLab\Application\Workflows;

use Exception;
use TNO\EssifLab\Contracts\Abstracts\Workflow;
use TNO\EssifLab\Services\PostUtil;

class ManageHooks extends Workflow {
    private const MAX_ID = "maxID";
    private const CONTEXT = 'context';
    private const TARGET = 'target';

    public static function options(): array {
		// TODO: load all selectable options to be displayed in the select lists.
		return [self::CONTEXT => ['hello' => 'hello', 'world' => 'world'], self::TARGET => ['foo' => 'foo', 'bar' => 'bar']];
	}

	public function add($request) {
		$data = PostUtil::getJsonPostContentAsArray();
		$hooks = array_key_exists('hook', $data) ? $data['hook'] : null;

		if ($this->requestIsEmpty($request)) {
			// TODO: add custom exception (request is empty)
			throw new Exception('request is empty');
		}

		if (!empty($hooks)) {
			if ($this->doesHookAlreadyExists($hooks, $request)) {
				// TODO: add custom exception (request already exists)
				throw new Exception('request already exists');
			}
			$request = $this->generateHookRecord($hooks, $request);
            $hooks[self::MAX_ID] = $request["ID"];
			$hooks[] = $request;
		} else {
            $request = $this->generateHookRecord($hooks, $request);
            $hooks = [];
            $hooks[self::MAX_ID] = $request["ID"];
            $hooks[] = $request;
		}

		$this->post->post_content = json_encode(array_merge($data, ['hook' => $hooks]));
		wp_update_post($this->post, true);
	}

	private function requestIsEmpty($request): bool {
		return empty(array_filter($request));
	}

	private function doesHookAlreadyExists($hooks, $request): bool {
		return count(array_filter($hooks, function ($v) use ($request) {
				return $v[self::CONTEXT] !== $request[self::CONTEXT] || $v[self::TARGET] !== $request[self::TARGET];
			})) !== count($hooks);
	}

	private function generateHookRecord($hooks, $request): array {
	    $request["ID"] = $this->generateID($hooks);
		return $request;
	}

	private function generateID($hooks): int {
        return is_array($hooks) && array_key_exists(self::MAX_ID, $hooks) ? ++$hooks[self::MAX_ID] : 1;
	}

	public function edit($request) {
		// TODO: edit a hook of a validation policy
	}

	public function delete($request) {
        $array_deleted = PostUtil::getJsonPostContentAsArray($this->post);
        foreach ($array_deleted["hook"] as $key => $array){
            if($key === self::MAX_ID){
                continue;
            }
            if(array_search($request["ID"], $array) !== FALSE){
                unset($array_deleted["hook"][$key]);
                break;
            }
        }
        $this->post->post_content = json_encode($array_deleted);
        wp_update_post($this->post, true);
	}
}