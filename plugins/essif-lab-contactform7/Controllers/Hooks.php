<?php

$fc = new CF7Hooks();

add_filter('essif_options_hooks', function($hooks) use ($fc) {
    return array_merge($hooks, ['context' => ['ContactForm7' => 'ContactForm7']]);
});

add_filter('essif_options_hooks', function($hooks) use ($fc) {
    return array_merge($hooks, $fc->getAllFormTitles());
});

add_filter('essif_options_hooks_total', function($hooks) use ($fc) {
    return array_merge($hooks, $fc->getAllFormsFields());
});

add_filter('essif_options_hooks_id', function($hooks) use ($fc) {
    return array_merge($hooks, $fc->getFormFields(12));
});

/* Req info:
Hook: CF7
Validation policy: ??
Target: Welk CF7?
*/

class CF7Hooks {

    public function getAllForms() {
        $args = array('post_type' => 'wpcf7_contact_form', 'posts_per_page' => -1);
        $cf7Forms = get_posts( $args );
        return $cf7Forms;
    }

    public function getAllFormTitles() {
        $cf7Forms = $this->getAllForms();
        $post_ids = wp_list_pluck( $cf7Forms , 'ID' );
        $form_titles = wp_list_pluck( $cf7Forms , 'post_title' );
        $res = ['target' => $form_titles];
        return $res;
    }

    public function extractFields($post) {
        $post_content = $post->post_content;
        $regex = '/(?<=\[)[^]\s]+(?=])/';
        preg_match_all($regex, $post_content, $fields);
        $uniqueFields = array_unique($fields[0]);
//        print_r($post_content);
//        echo "</br>";
//        print_r($uniqueFields);
//        echo "</br>";
//        echo "</br>";
//        echo "</br>";
        return $uniqueFields;
    }

    public function getFormFields($id) {
        $post = get_post($id);
        $fields = $this->extractFields($post);
        $res = ['credential' => $fields];
        return $res;
    }

    public function getAllFormsFields() {
        $forms = $this->getAllForms();
        $arrayForms = array();
        foreach ($forms as $form) {
            array_push($arrayForms, array($form->post_title, $this->extractFields($form)));
        }

        //print_r($arrayForms);
    }
}