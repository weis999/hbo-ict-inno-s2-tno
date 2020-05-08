<?php

namespace TNO\EssifLab\Services;

class PostUtil {
    public static function getJsonPostContentAsArray($post = null): array
    {
        $post_content = 'post_content';
        $post = empty($post) ? get_post() : $post;
        $content = is_array($post) && array_key_exists($post_content, $post) ? $post[$post_content] : null;
        $content = empty($content) && is_object($post) && property_exists($post, $post_content) ? $post->{$post_content} : $content;
        $content = json_decode($content, true);

        return empty($content) || !is_array($content) ? [] : $content;
    }
}