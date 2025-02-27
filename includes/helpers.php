<?php

/**
 * helpers
 */

function outaigate_admin_current_view()
{
    $page = sanitize_text_field($_GET['page']);
    $current_step = isset($page) ? $page : 'view0';

    if (strpos($current_step, '_') === false) {
        return 'view0';
    }

    return str_replace("outaigate-admin-forms_", "", $current_step);

}

function outaigate_admin_template_server_path($file_path, $include = true, $options = array())
{
    $my_plugin_dir = WP_PLUGIN_DIR . "/" . OutaiGate_ADMIN_DIR . "/";
    if ( is_dir( $my_plugin_dir ) ) {

        $path_to_file = $my_plugin_dir . $file_path . '.php';

        if ($include) {
            include $path_to_file;
        }

        return $path_to_file;
    }


    // view options
    $options = apply_filters('outaigate_admin_locate_template_options', $options, $name);

    $include_dir_path = rtrim(get_stylesheet_directory(), '/')."/outaigate-admin";
    $path_to_file     = rtrim($include_dir_path, '/')."/$name.php";

    if (!is_readable($path_to_file)) {
        $include_dir_path = __DIR__."/views";
    }

    $include_dir_path = apply_filters('outaigate_admin_locate_template_path', $include_dir_path, $name);
    $path_to_file     = rtrim($include_dir_path, '/')."/$name.php";

    if ($include) {
        include $path_to_file;
    }

    return $path_to_file;
}
function outaigate_admin_url($append = '')
{
    return plugins_url($append, __DIR__);
}

function outaigate_admin_view_pagename($step)
{
    $view_url_part = '';
    if($step){
        $view_url_part = '_' . $step;
    }

    return admin_url('admin.php?page=outaigate-admin-forms' . $view_url_part);
}
function outaigate_admin_submit($submit_text, $hide_class = "sr-only"){ ?>
    <div class="form__submit <?php echo esc_html($hide_class) ?>">
        <p class="submit">
            <input type="submit" name="submit5" id="submit5" class="button" value="<?php echo esc_html($submit_text); ?>">
        </p>
    </div>
<?php }

/**
 * @param $message
 * @param $msg_type
 * @return void
 * warning, info, success
 */
function outaigate_admin_message($message, $msg_type = 'info') {
    return "<div id='message' class='alert alert-$msg_type'>$message</div>";
}