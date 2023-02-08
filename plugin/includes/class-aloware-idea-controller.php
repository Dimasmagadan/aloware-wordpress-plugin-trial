<?php
/**
 *
 * @package Aloware
 */

class Aloware_Idea_Controller {

    private $options_slug;
    private $ideas_per_page;

    public function __construct() {
        $this->options_slug = 'aloware_setting_';
        $this->ideas_per_page = get_option( $this->options_slug . 'ideas_count', 10 );
    }

    public function modify_query( $query ) {
        if ( ! is_admin() && is_post_type_archive( 'idea' ) && $query->is_main_query() ) {
            $query->set( 'posts_per_page', $this->ideas_per_page );
        }
    }

    public function ajax_load_more_ideas() {
        
        // var_dump( $_POST );
        // exit;
        
        $page = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
        $posts_per_page = $this->ideas_per_page;

        $ideas_args = [
            'post_type' => 'idea',
            'posts_per_page' => $posts_per_page,
            'paged' => $page,
        ];

        if ( isset( $_POST['filterByCategory'] ) ) {
            $ideas_args['tax_query'] = [
                [
                    'taxonomy' => 'idea_category',
                    'field' => 'term_id', 
                    'terms' => intval( $_POST['filterByCategory'] ),
                ]
            ];
        }

        $ideas = get_posts( $ideas_args );

        $out = [];
        
        if ( $ideas ) {
            foreach ( $ideas as $key => $idea ) {
                $out[$key]['id'] = $idea->ID;
                $out[$key]['title'] = $idea->post_title;
                $out[$key]['content'] = $idea->post_content;
                if ( has_post_thumbnail( $idea->ID ) ) {
                    $out[$key]['img'] = [
                        'src' => get_post_thumbnail( $idea->ID ),
                        'width' => get_post_thumbnail_width( $idea->ID ),
                        'height' => get_post_thumbnail_height( $idea->ID ),
                        
                    ];
                }
            }
        }

        if ( empty( $out ) ) {
            wp_send_json_error([
                'content' => __( 'No Ideas Found', 'aloware' ),
            ] );
        }

        wp_send_json_success( [
            'content' => $out,
            'nextPage' => count($ideas) == $ideas_args['posts_per_page'],
        ] );
    }

    public function custom_endpoint() {
        add_rewrite_endpoint( 'idea/add', EP_PERMALINK | EP_PAGES );
    }
    
    public function add_new_idea_endpoint( $tpl ) {
        global $wp;

        if ( $wp->request !== 'idea/add' ) {
            return $tpl;
        }
        
        $idea_add_form_template = WP_PLUGIN_DIR . '/aloware/templates/idea-add-form.php';

        if ( ! file_exists( $idea_add_form_template ) ) {
            return $tpl;
        } 

        status_header(200);

        return $idea_add_form_template;
    }

    public function update_title( $title ) {
        global $wp;

        if ( $wp->request === 'idea/add' ) {
            return __( 'Add New Idea', 'aloware' );
        }

        return $title;
    }

    public function add_post_tpl() {
        echo '<script id="aloware-tpl" type="text/template">';
        require_once WP_PLUGIN_DIR . '/aloware/templates/idea-card.php';
        echo '</script>';
    }

    public function add_img_tpl() {
        echo '<script id="aloware-img-tpl" type="text/template">';
        require_once WP_PLUGIN_DIR . '/aloware/templates/idea-image.php';
        echo '</script>';
    }
}
