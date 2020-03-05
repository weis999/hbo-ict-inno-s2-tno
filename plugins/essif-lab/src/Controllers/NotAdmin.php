<?php

namespace LSVH\WordPress\FixContentLinks\Controllers;

defined('ABSPATH') or die();

use LSVH\WordPress\FixContentLinks\Extendables\CoreAbstract;

class NotAdmin extends CoreAbstract
{
    public function fix_post_content($content)
    {
        $path = $this->get_option(self::FIELD_PATH);
        $path = empty($path) ? '' : json_encode(trailingslashit($path));

        $pattern = '/src(?:\s|)=(?:\s|)("(?:\s|)(?:http[^".]*[^"\/]*)?'.$path.'([^"]*)(?:\s|)")/mi';
        preg_match_all($pattern, $content, $matches);

        if (!empty($matches)) {
            $excluded = array_filter(explode("\n", $this->get_option(self::FIELD_EXCLUDE)));

            $wp_uploads = wp_upload_dir();
            $uploads_dir = basename($wp_uploads['basedir']);
            $uploads_url = rtrim($wp_uploads['baseurl'], '/');

            $plugins_url = plugins_url();
            $plugins_dir = basename($plugins_url);

            $themes_url = dirname(get_template_directory_uri());
            $themes_dir = basename($themes_url);

            $content_url = content_url();
            $content_dir = basename($content_url);

            global $post;
            $transient_name = $this->get_domain() . '_' . get_current_user_id() . '_replacements';
            $transient = get_transient($transient_name);
            $transient = empty($transient) || !is_array($transient) ? [] : $transient;
            $transient[$post->ID] = 0;

            foreach (array_unique($matches[1]) as $i => $url) {
                $is_excluded = array_filter(array_filter($excluded, function($x) use ($url) {
                    return strpos($url, $x);
                }));

                if (empty($is_excluded)) {
                    $path = $matches[2][$i];

                    // First check if the link is loading a file from the uploads folder
                    $strpos = strpos($path, $uploads_dir);
                    $new_url = wp_make_link_relative($uploads_url . substr($path, $strpos + strlen($uploads_dir)));

                    // If the file is not in the uploads folder check the plugins folder.
                    if ($strpos === false) {
                        $strpos = strpos($path, $plugins_dir);
                        $new_url = wp_make_link_relative($plugins_url . substr($path, $strpos + strlen($plugins_dir)));
                    }

                    // If the file is not in the plugins folder check the themes folder.
                    if ($strpos === false) {
                        $strpos = strpos($path, $themes_dir);
                        $new_url = wp_make_link_relative($themes_url . substr($path, $strpos + strlen($themes_dir)));
                    }

                    // The file was neither in the uploads, plugins or themes folder, lastly check the wp-content folder.
                    if ($strpos === false) {
                        $strpos = strpos($path, $content_dir);
                        $new_url = wp_make_link_relative($content_url . substr($path, $strpos + strlen($content_dir)));
                    }

                    $new_url = '"'.$new_url.'"';

                    // Replace the old url for the new one.
                    if ($strpos !== false && $url !== $new_url) {
                        $transient[$post->ID] += substr_count($content, $url);
                        $content = str_replace($url, $new_url, $content);
                    }
                }
            }

            set_transient($transient_name, $transient);
        }

        return $content;
    }
}