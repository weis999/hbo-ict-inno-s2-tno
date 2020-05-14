<?php


namespace TNO\EssifLabCF7\Controllers;

class FormControls
{
    private function getAllForms() {
        $args = array('post_type' => 'wpcf7_contact_form', 'posts_per_page' => -1);
        $cf7Forms = get_posts( $args );
        return "Test";
    }

    public function getAllFormsIdTitle() {
        $cf7Forms = getAllForms();
        $post_ids = wp_list_pluck( $cf7Forms , 'ID' );
        $form_titles = wp_list_pluck( $cf7Forms , 'post_title' );
        return ['foo' => 'foo', 'bar' => 'bar'];
        return array($post_ids, $form_titles);
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
        return $fields;
    }

    function essif_hook_data()
    {
        $context = ['CF7' => 'CF7'];
        $target = ['foo' => 'foo', 'bar' => 'bar'];
        $res = ['context' => $context, 'target' => $target];
        return $res;

    }
}