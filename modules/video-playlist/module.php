<?php
namespace WprAddons\Modules\VideoPlaylist;

use WprAddons\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Module extends Module_Base {

    public function get_widgets() {
        return [
            'Wpr_Video_Playlist',
        ];
    }

    public function get_name() {
        return 'wpr-video-playlist';
    }
}