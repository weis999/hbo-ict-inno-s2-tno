<?php

namespace TNO\EssifLab\Presentation\Components;

use TNO\EssifLab\Contracts\Abstracts\Component;
use TNO\EssifLab\Contracts\Interfaces\Core;

class FormControl extends Component {
	private $name = '';

	private $value = '';

	private $href = '';

	private $type = '';

	private $tag = 'input';

	private $class = '';

	private $placeholder = '';

	private $disabled = false;

	private $options = [];

	private $label = '';

	private $children = '';

	public function __construct(Core $plugin, $args = []) {
		parent::__construct($plugin);

		foreach ($args as $key => $value) {
			if (property_exists($this, $key) && gettype($this->{$key}) === gettype($value)) {
				$this->{$key} = $value;
			}
		}

		wp_enqueue_script("jquery");
		$this->loadJS();
	}

	public function render(): string {
		return join('', $this->getFormControl());
	}

	public function getFormControl(): array {
		return [
			'label' => $this->renderLabel(),
			'input' => $this->renderInput(),
		];
	}

	protected function renderLabel() {
		$attrs = self::generateElementAttrs(['for' => $this->name]);

		return empty($this->label) ? '' : "<label$attrs>$this->label</label>";
	}

	protected function renderInput() {
		$renderedChildren = $this->renderInputChildren();
		$renderedAttributes = $this->renderInputAttributes();

		return $this->renderInputTag($renderedChildren, $renderedAttributes);
	}

	protected function renderInputChildren(): string {
		switch ($this->tag) {
			case 'input':
				return '';

			case 'select':
				return $this->renderSelectChildren();

			default:
				return $this->children;
		}
	}

	protected function renderSelectChildren(): string {
		$output = '';
		if (is_array($this->options)) {
			$output = ! empty($this->placeholder) ? '<option value="">'.$this->placeholder.'...</option>' : '';
			foreach ($this->options as $key => $value) {
				$attrs = self::generateElementAttrs(['value' => $key]);
				$output .= "<option$attrs>$value</option>";
			}
		}

		$this->unsetValue();

		return $output;
	}

	protected function renderInputTag($children, $attributes) {
	    if($children == "Delete"){
//            var_dump("tag", $this->tag, "attributes", $attributes, "children", $children);
//            die();
            preg_match('/name="(.*?)"/', $attributes, $name);
//            $attributes .= "onclick='deleteCustomPost(".$name.")'";
            $attributes = "class='button-link' onclick='deleteCustomPost(\"".$name[1]."\")'";
//            echo "onclick='deleteCustomPost(".$name[1].")'";
//            var_dump("name", $name[1]);
        }
		return '<'.$this->tag.$attributes.(empty($children) ? '/>' : '>'.$children.'</'.$this->tag.'>');
	}

	protected function renderInputAttributes() {
		$excluded = [
			'tag',
			'options',
			'label',
			'placeholder',
			'plugin',
			'children'
		];

		$attributes = array_filter(get_object_vars($this), function ($key) use ($excluded) {
			return array_search($key, $excluded) === false;
		}, ARRAY_FILTER_USE_KEY);

		return self::generateElementAttrs($attributes);
	}

	private function unsetValue() {
		$this->value = '';
	}

	private function loadJS(){
        echo '<script type="text/javascript">
                function deleteCustomPost(postName){
                    postNameExtracted = /essif-lab_hook/.exec(postName);
                    if(/essif-lab_hook/.test(postName)){
                        jQuery.post(
                            ajaxurl,
                            {
                                "action": "essif_delete_hooks",
                                "name": postNameExtracted[0]
                            },
                            (response) => location.reload()
                        );
//                        jQuery.post("api", {"action": "delete", "name": postNameExtracted[0]}, (response) => alert("ajax call performed"));
                    }
                }
            </script>';
    }
}