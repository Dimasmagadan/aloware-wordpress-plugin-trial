<?php
/**
 *
 * @package Aloware
 */

class Aloware_Plugin {
    /**
     * The instance of the class.
     *
     * @var Aloware_Plugin
     */
    private static $instance;

    /**
     * The instance of the custom post type class.
     *
     * @var Aloware_Idea_Post_Type
     */
    private $post_type;

    /**
     * The instance of the custom taxonomy class.
     *
     * @var Aloware_Idea_Taxonomy
     */
    private $taxonomy;

    /**
     * The instance of the admin settings class.
     *
     * @var Aloware_Settings
     */
    private $settings;

    /**
     * Returns the instance of the class.
     *
     * @return Aloware_Plugin
     */
    public static function get_instance() {
        if ( ! self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Constructor.
     */
    private function __construct() {
        $this->include_dependencies();
        $this->post_type = new Aloware_Idea_Post_Type();
        $this->taxonomy  = new Aloware_Idea_Taxonomy();
        $this->settings  = new Aloware_Settings();
        $this->archive  = new Aloware_Archive_Template();
        $this->controller  = new Aloware_Idea_Controller();
        $this->submit  = new Aloware_Idea_Form_Handler();
        $this->setup_actions();
        $this->setup_filters();
    }

    /**
     * Includes the required dependencies.
     */
    private function include_dependencies() {
        require_once plugin_dir_path(__FILE__) . 'class-aloware-idea-post-type.php';
        require_once plugin_dir_path(__FILE__) . 'class-aloware-idea-taxonomy.php';
        require_once plugin_dir_path(__FILE__) . 'class-aloware-settings.php';
        require_once plugin_dir_path(__FILE__) . 'class-aloware-archive-template.php';
        require_once plugin_dir_path(__FILE__) . 'class-aloware-idea-controller.php';
        require_once plugin_dir_path(__FILE__) . 'class-aloware-idea-form-handler.php';
    }

    /**
     * Sets up the plugin's actions.
     */
    private function setup_actions() {
        add_action('init', [ $this->post_type, 'register_post_type' ] );
        add_action('init', [ $this->taxonomy, 'register_taxonomy' ] );

        add_action('admin_menu', [ $this->settings, 'add_page' ] );
        add_action('admin_init', [ $this->settings, 'register_settings' ] );

        add_action('wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

        add_action( 'pre_get_posts', [ $this->controller, 'modify_query' ] );

        add_action( 'wp_ajax_aloware_load_more_ideas', [ $this->controller, 'ajax_load_more_ideas' ] );
        add_action( 'wp_ajax_nopriv_aloware_load_more_ideas', [ $this->controller, 'ajax_load_more_ideas' ] );

        add_action( 'wp_ajax_aloware_add_idea', [ $this->submit, 'submit_idea' ] );
        add_action( 'wp_ajax_nopriv_aloware_add_idea', [ $this->submit, 'submit_idea' ] );

        add_action( 'init', [ $this->controller, 'custom_endpoint' ] );

        add_action( 'wp_footer', [ $this->controller, 'add_post_tpl' ] );
        add_action( 'wp_footer', [ $this->controller, 'add_img_tpl' ] );
	}

	/**
	 * Sets up the plugin's filters.
	 */
	private function setup_filters() {
        add_filter( 'template_include', [ $this->archive, 'modify_template_output' ] );
        add_filter( 'template_include', [ $this->controller, 'add_new_idea_endpoint' ] );
        add_filter( 'wp_title', [ $this->controller, 'update_title' ] );
	}

	/**
	 * Enqueues the plugin's scripts and styles.
	 */
	public function enqueue_scripts() {
        // no time for speed optimisations, loading everywhere
        // for real time project I should have some check on template type/page type/url
        // if ( is_singular( $this->post_type ) ) {
            wp_enqueue_style( 'aloware-tailwind', 'https://cdn.jsdelivr.net/npm/tailwindcss@1.7.3/dist/tailwind.min.css', [], '1.7.3');

            wp_enqueue_script(
                'aloware-idea',
                plugins_url( 'assets/js/aloware-idea.js', dirname( __FILE__ ) ),
                [],
                '1.0.0',
                true
            );
            wp_localize_script(
                'aloware-idea',
                'aloware',
                [
                    'ajax_url' => admin_url( 'admin-ajax.php' ),
                    'nonce' => wp_create_nonce( 'aloware_idea_form_nonce' ),
                ]
            );
        // }
	}
}
