<?php

class OutaiGate_Admin_Form
{

    const ID = 'outaigate-admin-forms';

    const NONCE_KEY = 'outaigate_admin';

    protected $views = array(
        'view0' => 'views/view0',
        'alerts' => 'views/alerts'
    );
    const WHITELISTED_KEYS = array(
        'outaigate-admin-data'
    );

    private $default_values = array();
    private $current_page = '';

    public function init()
    {
        add_action('admin_menu', array($this, 'add_menu_page'), 20);

        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));

        add_action('admin_post_outaigate_admin_save', array($this, 'submit_save'));

        $this->add_hook();

    }

    public function get_id()
    {
        return self::ID;
    }

    public function get_nonce_key()
    {
        return self::NONCE_KEY;
    }

    public function get_whitelisted_keys()
    {
        return self::WHITELISTED_KEYS;
    }

    private function get_defaults()
    {
        $defaults = array();
        foreach ($this->get_whitelisted_keys() as $key => $val) {
            $defaults[$val] = get_option($val);
        }
        return $defaults;
    }


    public function add_menu_page()
    {
        add_menu_page(
            esc_html__('outaigate', 'outaigate-admin'),
            esc_html__('outaigate', 'outaigate-admin'),
            'manage_options',
            $this->get_id(),
            array(&$this, 'load_view'),
            'dashicons-plugins-checked',
            75
        );
    }


    function load_view()
    {
        $this->default_values = $this->get_defaults();
        $this->current_page = outaigate_admin_current_view();
        $current_views = isset($this->views[$this->current_page]) ? $this->views[$this->current_page] : $this->views['not-found'];
        $step_data_func_name = $this->current_page . '_data';

        $args = [];
        /**
         * prepare data for view
         */
        if (method_exists($this, $step_data_func_name)) {
            $args = $this->$step_data_func_name();
        }
        /**
         * Default Admin Form Template
         */

        echo '<div class="outaigate-admin-forms ' . esc_html($this->current_page) . '">';

        echo '<div class="container container1">';
        echo '<div class="inner">';

        $this->includeWithVariables(outaigate_admin_template_server_path('views/alerts', false));

        $this->includeWithVariables(outaigate_admin_template_server_path($current_views, false), $args);

        echo '</div>';
        echo '</div>';

        echo '</div> <!-- / outaigate-admin-forms -->';
    }


    function includeWithVariables($filePath, $variables = array(), $print = true)
    {
        $output = NULL;
        if (file_exists($filePath)) {
            // Extract the variables to a local namespace
            extract($variables);

            // Start output buffering
            ob_start();

            // Include the template file
            include $filePath;

            // End buffering and return its contents
            $output = ob_get_clean();
        }
        if ($print) {
            print $output;
        }
        return $output;

    }


    public function admin_enqueue_scripts($hook_suffix)
    {
        if (strpos($hook_suffix, $this->get_id()) === false) {
            return;
        }

        wp_enqueue_style('outaigate-admin-form-bs', plugin_dir_url( __FILE__ ).'../assets/bootstrap.min.css', OutaiGate_ADMIN_VERSION);

        wp_enqueue_script('outaigate-admin-form-bs', plugin_dir_url( __FILE__ ).'../assets/bootstrap.bundle.min.js',
            array('jquery'),
            OutaiGate_ADMIN_VERSION,
            true
        );
    }

    public function recursive_sanitize_text_field($array) {
        foreach ( $array as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = $this->recursive_sanitize_text_field($value);
            }
            else {
                $value = sanitize_text_field( $value );
            }
        }
    
        return $array;
    }

    public function submit_save()
    {

        $nonce = sanitize_text_field($_POST[$this->get_nonce_key()]);
        $action = sanitize_text_field($_POST['action']);

        if (!isset($nonce) || !wp_verify_nonce($nonce, $action)) {
            print 'Sorry, your nonce did not verify.';
            exit;
        }
        if (!current_user_can('manage_options')) {
            print 'You can\'t manage options';
            exit;
        }
        /**
         * whitelist keys that can be updated
         */
        $whitelisted_keys = $this->get_whitelisted_keys();

        $fields_to_update = [];

        foreach ($whitelisted_keys as $key) {
            if (array_key_exists($key, $_POST)) {
                $fields_to_update[$key] = isset($_POST[$key])?$this->recursive_sanitize_text_field($_POST[$key]):"";
            }
        }

        /**
         * Loop through form fields keys and update data in DB (wp_options)
         */

        $this->db_update_options($fields_to_update);

        $redirect_to = sanitize_text_field($_POST['redirectToUrl']);

        if ($redirect_to) {
            add_settings_error('outaigate_msg', 'outaigate_msg_option', __("Changes saved."), 'success');
            set_transient('settings_errors', get_settings_errors(), 30);
            wp_safe_redirect($redirect_to);
            exit;
        }
    }

    private function db_update_options($group)
    {
        foreach ($group as $key => $fields) {
            $db_opts = get_option($key);
            $db_opts = ($db_opts === '') ? array() : $db_opts;

            if(!$db_opts){
                $db_opts = array();
            }

            $updated = array_merge($db_opts, $fields);
            update_option($key, $updated);


        }
    }

    /**
     * Prepare data for views
     */

    private function view0_data()
    {
        $args = [];
        $args['approvalData'] = isset($this->default_values['outaigate-admin-data']['approval']) ? stripslashes($this->default_values['outaigate-admin-data']['approval']) : 'false';
        $args['brandkey'] = isset($this->default_values['outaigate-admin-data']['brandkey']) ? stripslashes($this->default_values['outaigate-admin-data']['brandkey']) : '';;
        $args['channelType'] = isset($this->default_values['outaigate-admin-data']['channelType']) ? stripslashes($this->default_values['outaigate-admin-data']['channelType']) : '';

        $checkedVal = isset($this->default_values['outaigate-admin-data']['is_use']) ? $this->default_values['outaigate-admin-data']['is_use'] : '';
        $checkedAttr = "";
        if ($checkedVal != '') {
            $checkedAttr = "checked";
        }
        $args['is_use'] = $checkedAttr;

        return $args;
    }

    private function add_hook() {
        $this->default_values = $this->get_defaults();

        $checkedVal = isset($this->default_values['outaigate-admin-data']['is_use']) ? $this->default_values['outaigate-admin-data']['is_use'] : '';
        if ($checkedVal != '') {
            add_action( 'wp_head' , array( $this, 'load_scripts' ) );        
        }
		
	}

    public function load_scripts() {
        if(isset($this->default_values['outaigate-admin-data']['brandkey'])){
            $settings = array(
                "brandKey" => $this->default_values['outaigate-admin-data']['brandkey'],
                "channelType" => $this->default_values['outaigate-admin-data']['channelType']
            );
        }

        if($settings['brandKey']!=""){
            wp_register_script('outaigate-plugin-js', plugin_dir_url( __FILE__ ).'../assets/Twc.plugin.js', array(), false, true);
            wp_enqueue_script('outaigate-plugin-js');

            wp_register_script('outaigate-load-js', plugin_dir_url( __FILE__ ).'../assets/outaigate.load.js', array(), false, true);
            wp_localize_script('outaigate-load-js', 'settings', $settings);
            wp_enqueue_script('outaigate-load-js');
        }
	}
}