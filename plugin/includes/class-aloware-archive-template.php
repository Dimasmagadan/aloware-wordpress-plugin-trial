<?php
/**
 *
 * @package Aloware
 */

class Aloware_Archive_Template {

    private $post_type;

    public function __construct() {
        $this->post_type = 'idea';
    }

    public function modify_template_output( $template) {
        
        if (!is_post_type_archive( $this->post_type )) {
            return $template;
        }
        
        $new_template = WP_PLUGIN_DIR . '/aloware/templates/archive-idea.php';

        if ( ! file_exists($new_template)) {
            return $template;
        }

        return $new_template;
    }
}
