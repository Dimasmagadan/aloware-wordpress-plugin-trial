<?php
/**
 *
 * @package Aloware
 */

class Aloware_Idea_Taxonomy {

    private $taxonomy_name = 'idea_category';
    private $post_type_name = 'idea';
    private $taxonomy_labels = [];
    private $taxonomy_args = [];

    public function __construct() {
        $this->taxonomy_labels = [
            'name'              => _x( 'Idea Categories', 'taxonomy general name', 'aloware' ),
            'singular_name'     => _x( 'Idea Category', 'taxonomy singular name', 'aloware' ),
            'search_items'      => __( 'Search Idea Categories', 'aloware' ),
            'all_items'         => __( 'All Idea Categories', 'aloware' ),
            'parent_item'       => __( 'Parent Idea Category', 'aloware' ),
            'parent_item_colon' => __( 'Parent Idea Category:', 'aloware' ),
            'edit_item'         => __( 'Edit Idea Category', 'aloware' ),
            'update_item'       => __( 'Update Idea Category', 'aloware' ),
            'add_new_item'      => __( 'Add New Idea Category', 'aloware' ),
            'new_item_name'     => __( 'New Idea Category Name', 'aloware' ),
            'menu_name'         => __( 'Idea Categories', 'aloware' ),
        ];

        $this->taxonomy_args = [
            'labels'            => $this->taxonomy_labels,
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => true,
        ];
    }

    public function register_taxonomy() {
        register_taxonomy( $this->taxonomy_name, [$this->post_type_name], $this->taxonomy_args );
    }
}
