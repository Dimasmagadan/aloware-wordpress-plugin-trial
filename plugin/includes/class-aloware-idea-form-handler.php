<?php
/**
 *
 * @package Aloware
 */

class Aloware_Idea_Form_Handler {

    private $errors;

    public function submit_idea() {
        $this->errors = new WP_Error();

        $this->validate_inputs();
        $this->insert_idea();

        if ( ! $this->errors->get_error_codes() ) {
            wp_send_json_success();
        } else {
            wp_send_json_error( [
                'errors' => $this->errors->get_error_messages()
            ] );
        }
    }

    private function validate_inputs() {

        if ( ! current_user_can( 'publish_posts' ) ) {
            $this->errors->add('permission_error', __('You do not have the required permissions to submit an idea', 'aloware'));
        }

        if ( ! isset( $_POST['form_nonce'] ) || ! wp_verify_nonce( $_POST['form_nonce'], 'aloware_idea_form_nonce' ) ) {
            $this->errors->add( 'nonce_error', __( 'Invalid request', 'aloware' ) );
        }

        $idea_title = filter_input( INPUT_POST, 'idea-title', FILTER_SANITIZE_STRING );
        $idea_category = filter_input( INPUT_POST, 'idea-category', FILTER_SANITIZE_STRING );
        $idea_description = filter_input( INPUT_POST, 'idea-description', FILTER_SANITIZE_STRING );
        
        if ( ! $idea_title ) {
            $this->errors->add( 'empty_title', __( 'Idea title is required', 'aloware' ) );
        }

        if ( ! $idea_category ) {
            $this->errors->add( 'empty_category', __( 'Idea category is required', 'aloware' ) );
        }

        if ( ! $idea_description ) {
            $this->errors->add( 'empty_description', __( 'Idea description is required', 'aloware' ) );
        }
    }

    private function upload_thumbnail() {
        if ( ! function_exists( 'wp_handle_upload' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        $file = $_FILES['idea-thumbnail'];
        
        $uploaded_file = wp_handle_upload(
            $file,
            [
                'test_form' => false
            ]
        );

        if ( $uploaded_file && ! isset( $uploaded_file['error'] ) ) {
            $file_type = wp_check_filetype( basename( $uploaded_file['file'] ), null );

            $attachment_data = [
                'post_mime_type' => $file_type['type'],
                'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $uploaded_file['file'] ) ),
                'post_content'   => '',
                'post_status'    => 'inherit',
            ];

            $attachment_id = wp_insert_attachment( $attachment_data, $uploaded_file['file'] );

            
            require_once ABSPATH . 'wp-admin/includes/image.php';
            $attachment_metadata = wp_generate_attachment_metadata( $attachment_id, $uploaded_file['file'] );
            wp_update_attachment_metadata( $attachment_id, $attachment_metadata );

            return $attachment_id;
        } else {
            $this->errors->add( 'upload_error', $uploaded_file['error'] );
        }
    }

    private function insert_idea() {
        if ( ! $this->errors->get_error_codes() ) {
            $idea_id = wp_insert_post( [
                'post_title'   => $_POST['idea-title'],
                'post_content' => $_POST['idea-description'],
                'post_type'    => 'idea',
                'post_status'  => 'pending'
            ] );

            if ( $idea_id ) {

                wp_set_object_terms( $idea_id, $_POST['idea-category'], 'idea_category' );

                $thumbnail_id = $this->upload_thumbnail();
                if ( $thumbnail_id ) {
                    set_post_thumbnail( $idea_id, $thumbnail_id );
                }
            }
        }
    }
}
