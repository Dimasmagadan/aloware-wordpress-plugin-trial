<?php
/**
 *
 * @package Aloware
 */

class Aloware_Idea_Post_Type {
    private $post_type_name = 'idea';

    public function register_post_type() {
        $labels = [
            'name'               => _x( 'Ideas', 'post type general name', 'aloware' ),
            'singular_name'      => _x( 'Idea', 'post type singular name', 'aloware' ),
            'menu_name'          => _x( 'Ideas', 'admin menu', 'aloware' ),
            'name_admin_bar'     => _x( 'Idea', 'add new on admin bar', 'aloware' ),
            'add_new'            => _x( 'Add New', 'Idea', 'aloware' ),
            'add_new_item'       => __( 'Add New Idea', 'aloware' ),
            'new_item'           => __( 'New Idea', 'aloware' ),
            'edit_item'          => __( 'Edit Idea', 'aloware' ),
            'view_item'          => __( 'View Idea', 'aloware' ),
            'all_items'          => __( 'All Ideas', 'aloware' ),
            'search_items'       => __( 'Search Ideas', 'aloware' ),
            'parent_item_colon'  => __( 'Parent Ideas:', 'aloware' ),
            'not_found'          => __( 'No Ideas found.', 'aloware' ),
            'not_found_in_trash' => __( 'No Ideas found in Trash.', 'aloware' ),
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => true,
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => [ 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ],
        ];

        register_post_type( $this->post_type_name, $args );
    }
}
