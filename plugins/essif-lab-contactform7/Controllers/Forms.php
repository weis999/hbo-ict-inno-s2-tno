<?php


namespace TNO\EssifLabCF7\Controllers;

class Forms
{
    private function getAllForms() {
        $args = array('post_type' => 'wpcf7_contact_form', 'posts_per_page' => -1);
        $cf7Forms = get_posts( $args );
        return $cf7Forms;
    }

    public function getAllFormsIdTitle() {
        $cf7Forms = getAllForms();
        $post_ids = wp_list_pluck( $cf7Forms , 'ID' );
        $form_titles = wp_list_pluck( $cf7Forms , 'post_title' );
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
}