<?php // used here only for enabling syntax highlighting. Leave this out if it's already included in your plugin file.

// define the actions for the two hooks created, first for logged in users and the next for logged out users


namespace TNO\EssifLabCF7\Controllers;

class Forms
{
    function essif_hook_data()
    {
        $context = ['CF7' => 'CF7'];
        $target = ['foo' => 'foo', 'bar' => 'bar'];
        $res = ['context' => $context, 'target' => $target];
        return $res;

    }
}