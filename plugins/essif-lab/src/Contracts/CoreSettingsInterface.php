<?php

namespace LSVH\WordPress\FixContentLinks\Contracts;

defined('ABSPATH') or die();

interface CoreSettingsInterface
{
    const FIELD_TYPE = 'type';
    const FIELD_PATH = 'path';
    const FIELD_EXCLUDE = 'exclude';
}