<?php
/**
 *
 * @package Aloware
 */

class Aloware_Settings {
    private $page_title;
    private $menu_title;
    private $capability;
    private $slug;
    private $callback;
    private $icon;
    private $position;
    private $options_group;
    private $options_slug;
    private $options_section;
    
    public function __construct() {
        $this->page_title  = __( 'Aloware Settings', 'aloware' );
        $this->menu_title  = __( 'Aloware Settings', 'aloware' );
        $this->capability  = 'manage_options';
        $this->slug        = 'aloware_settings';
        $this->callback    = [ $this, 'render_page' ];
        $this->icon        = 'dashicons-admin-generic';
        $this->position    = null;
        $this->options_group = 'aloware_settings';
        $this->options_slug  = 'aloware_setting_';
        $this->options_section = 'ideas_section';
    }

    public function add_page() {
        add_options_page(
            $this->page_title,
            $this->menu_title,
            $this->capability,
            $this->options_group,
            $this->callback,
            $this->position
        );
    }

    public function render_page() {
        if ( ! current_user_can( $this->capability ) ) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields( $this->options_group );
                do_settings_sections( $this->options_group );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function register_settings() {

        register_setting(
            $this->options_group,
            $this->options_slug . 'ideas_count',
            [
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
                'default'           => 10,
            ]
        );

        register_setting(
            $this->options_group,
            $this->options_slug . 'ideas_comments',
            [
                'type'              => 'boolean',
                'sanitize_callback' => 'boolval',
                'default'           => true,
            ]
        );

        register_setting(
            $this->options_group,
            $this->options_slug . 'cookies_time',
            [
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
                'default'           => 30,
            ]
        );

        add_settings_section(
            $this->options_section,
            __( 'Ideas Settings', 'aloware' ),
            [ $this, 'render_ideas_section' ],
            $this->slug
        );

        add_settings_field(
            $this->options_slug . 'ideas_count',
            __( 'Number of Ideas', 'aloware' ),
            [ $this, 'render_ideas_count' ],
            $this->slug,
            $this->options_section
        );

        add_settings_field(
            $this->options_slug .  'ideas_comments',
            __( 'Allow Comments', 'aloware' ),
            [ $this, 'render_ideas_comments' ],
            $this->slug,
            $this->options_section
        );

        add_settings_field(
            $this->options_slug .  'cookies_time',
            __( 'Voting Cookie Time (in hours)', 'aloware' ),
            [ $this, 'render_cookies_time' ],
            $this->slug,
            $this->options_section
        );
    }


    public function render_ideas_section() {
        echo '<p>' . __( 'Settings for Ideas custom post type', 'aloware' ) . '</p>';
    }

    public function render_ideas_count() {
        $attr_name = $this->options_slug . 'ideas_count';
        
        $ideas_count = get_option( $attr_name, 10);
        echo '<input type="number" name="' . esc_attr( $attr_name ) . '" id="' . esc_attr( $attr_name ) . '" value="' . esc_attr( $ideas_count ) . '" />';
    }

    public function render_ideas_comments() {
        $attr_name = $this->options_slug . 'ideas_comments';
        
        $ideas_comments = get_option( $this->options_slug . 'ideas_comments', 1);
        echo '<input type="checkbox" name="' . esc_attr( $attr_name ) . '" id="' . esc_attr( $attr_name ) . '" value="1" ' . checked(1, $ideas_comments, false) . ' />';
    }

    public function render_cookies_time() {
        $attr_name = $this->options_slug . 'cookies_time';
        
        $cookies_time = get_option( $this->options_slug . 'cookies_time', 30);
        echo '<input type="number" name="' . esc_attr( $attr_name ) . '" id="' . esc_attr( $attr_name ) . '" value="' . esc_attr( $cookies_time ) . '" />';
    }
}
