<?php

$fc = new CF7Hooks();

add_filter('essif_options_hooks', function($hooks) use ($fc) {
    return array_merge($hooks, ['context' => ['ContactForm7' => 'ContactForm7']]);
});

// Hooks
add_filter('essif_options_hooks', function($hooks) use ($fc) {
    return array_merge($hooks, $fc->getAllFormTitles());
});

// Credentials
add_filter('essif_options_hooks_id', function($hooks) use ($fc) {
    return array_merge($hooks, $fc->getFormFields(13));
});


class CF7Hooks {

    public function getAllForms() {
        $args = array('post_type' => 'wpcf7_contact_form', 'posts_per_page' => -1);
        $cf7Forms = get_posts( $args );
        return $cf7Forms;
    }

    public function getAllFormTitles() {
//        $target = ['bbb' => 'bbb', 'bar' => 'bar'];
//        $res = ['context' => $context, 'target' => $target];
//        var_dump($res);

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
        return $uniqueFields;
    }

    public function getFormFields($id) {
        $post = get_post($id);
        $fields = $this->extractFields($post);
        $res = ['credential' => $fields];
        return $res;
    }
}