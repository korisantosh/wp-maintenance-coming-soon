<?php
/*
Plugin Name: Maintenance Mode & Coming Soon with Countdown, Email and Social Links
Plugin URI: https://yourwebsite.com
Description: A simple plugin to enable maintenance mode and coming soon page with countdown and customizable design.
Version: 1.1
Author: Your Name
Author URI: https://yourwebsite.com
License: GPL2
Text Domain: wp-maintenance-coming-soon
*/

// Enqueue necessary styles and scripts for the admin page
function mmcs_enqueue_admin_scripts() {
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_media();

    // wp_enqueue_script('jquery-ui-accordion');
    // wp_enqueue_style('jquery-ui-css', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css'); // jQuery UI CSS for accordion styling
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_style('mmcs-admin-style', plugin_dir_url(__FILE__) . 'admin-style.css');


    wp_enqueue_script('mmcs-admin-script', plugin_dir_url(__FILE__) . 'admin.js', array('wp-color-picker', 'jquery', 'jquery-ui-accordion'), false, true);

}
add_action('admin_enqueue_scripts', 'mmcs_enqueue_admin_scripts');

// Plugin activation hook
function mmcs_activate() {
    add_option('mmcs_maintenance_mode', 'off');
}
register_activation_hook(__FILE__, 'mmcs_activate');

// Plugin deactivation hook
function mmcs_deactivate() {
    delete_option('mmcs_maintenance_mode');
}
register_deactivation_hook(__FILE__, 'mmcs_deactivate');

// Add admin menu for plugin settings
function mmcs_admin_menu() {
    add_menu_page(
        esc_html__('Maintenance Mode Settings', 'maintenance-mode-coming-soon'),
        esc_html__('Maintenance Mode', 'maintenance-mode-coming-soon'),
        'manage_options',
        'mmcs-settings',
        'mmcs_settings_page',
        'dashicons-admin-tools',
        100
    );
}
add_action('admin_menu', 'mmcs_admin_menu');

// Admin page content with accordion format
function mmcs_settings_page() {
    // Check user capabilities and verify nonce
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['mmcs_config_nonce_field']) && wp_verify_nonce($_POST['mmcs_config_nonce_field'], 'mmcs_save_config_settings')) {
      mmcs_save_config_settings();
    }

    if (isset($_POST['mmcs_design_nonce_field']) && wp_verify_nonce($_POST['mmcs_design_nonce_field'], 'mmcs_save_design_settings')) {
      mmcs_save_design_settings();
    }

    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Maintenance Mode & Coming Soon Settings', 'maintenance-mode-coming-soon'); ?></h1>
        <div id="mmcs-accordion" class="accordion accordion-mmcp">
            <h2 class="accordion-heading"><span class="dashicons dashicons-admin-generic"></span> <?php esc_html_e('Configuration', 'maintenance-mode-coming-soon'); ?> <span class="arrow-icon dashicons dashicons-arrow-down-alt2"></span></h2>
            <div>
                <form method="post" action="">
                    <?php wp_nonce_field('mmcs_save_config_settings', 'mmcs_config_nonce_field'); ?>
                    <?php settings_fields('mmcs_settings_group'); ?>
                    <?php do_settings_sections('mmcs-settings'); ?>
                    <?php submit_button(); ?>
                </form>
            </div>

            <h2 class="accordion-heading"><span class="dashicons dashicons-art"></span> <?php esc_html_e('Design', 'maintenance-mode-coming-soon'); ?> <span class="arrow-icon dashicons dashicons-arrow-down-alt2"></span></h2>
            <div>
                <form method="post" action="">
                    <?php wp_nonce_field('mmcs_save_design_settings', 'mmcs_design_nonce_field'); ?>
                    <?php do_settings_sections('mmcs-design'); ?>
                    <?php submit_button(); ?>
                </form>
            </div>

            <h2 class="accordion-heading"><span class="dashicons dashicons-editor-help"></span> <?php esc_html_e('Support', 'maintenance-mode-coming-soon'); ?> <span class="arrow-icon dashicons dashicons-arrow-down-alt2"></span></h2>
            <div>
                <p><?php esc_html_e('For more support and customization, send an email to', 'maintenance-mode-coming-soon'); ?> 
                <a href="mailto:mail@wordpressmaintainace.com"><?php esc_html_e('mail@wordpressmaintainace.com', 'maintenance-mode-coming-soon'); ?></a></p>
            </div>
        </div>
    </div>
    <?php
}

// Save settings with proper sanitization
function mmcs_save_config_settings() {
    if (isset($_POST['mmcs_maintenance_mode'])) {
        update_option('mmcs_maintenance_mode', sanitize_text_field($_POST['mmcs_maintenance_mode']));
    }
    else {
        update_option('mmcs_maintenance_mode', 'off');
    }

    if (isset($_POST['mmcs_launch_date'])) {
        update_option('mmcs_launch_date', sanitize_text_field($_POST['mmcs_launch_date']));
    }
}

function mmcs_save_design_settings() {
    if (isset($_POST['mmcs_logo'])) {
        update_option('mmcs_logo', esc_url_raw($_POST['mmcs_logo']));
    }

    if (isset($_POST['mmcs_image'])) {
      update_option('mmcs_image', esc_url_raw($_POST['mmcs_image']));
    }

    if (isset($_POST['mmcs_heading'])) {
        update_option('mmcs_heading', sanitize_text_field($_POST['mmcs_heading']));
    }

    if (isset($_POST['mmcs_subheading'])) {
        update_option('mmcs_subheading', sanitize_text_field($_POST['mmcs_subheading']));
    }

    if (isset($_POST['mmcs_description'])) {
        update_option('mmcs_description', sanitize_textarea_field($_POST['mmcs_description']));
    }

    if (isset($_POST['mmcs_template'])) {
        update_option('mmcs_template', sanitize_text_field($_POST['mmcs_template']));
    }
}

// Initialize settings with escaping and sanitization
function mmcs_settings_init() {
    // Configuration section
    register_setting('mmcs_settings_group', 'mmcs_maintenance_mode');
    register_setting('mmcs_settings_group', 'mmcs_launch_date');

    add_settings_section('mmcs_general_settings', esc_html__('General Settings', 'maintenance-mode-coming-soon'), null, 'mmcs-settings');

    add_settings_field('mmcs_maintenance_mode_field', esc_html__('Enable Maintenance Mode', 'maintenance-mode-coming-soon'), 'mmcs_toggle_field', 'mmcs-settings', 'mmcs_general_settings');
    add_settings_field('mmcs_launch_date_field', esc_html__('Launch Date', 'maintenance-mode-coming-soon'), 'mmcs_launch_date_field', 'mmcs-settings', 'mmcs_general_settings');

    // Design section
    register_setting('mmcs_settings_group', 'mmcs_logo');
    register_setting('mmcs_settings_group', 'mmcs_image');
    register_setting('mmcs_settings_group', 'mmcs_heading');
    register_setting('mmcs_settings_group', 'mmcs_subheading');
    register_setting('mmcs_settings_group', 'mmcs_description');
    register_setting('mmcs_settings_group', 'mmcs_template');

    add_settings_section('mmcs_design_settings', esc_html__('Design Settings', 'maintenance-mode-coming-soon'), null, 'mmcs-design');

    add_settings_field('mmcs_logo_field', esc_html__('Logo', 'maintenance-mode-coming-soon'), 'mmcs_logo_field', 'mmcs-design', 'mmcs_design_settings');
    add_settings_field('mmcs_image_field', esc_html__('Content Image', 'maintenance-mode-coming-soon'), 'mmcs_image_field', 'mmcs-design', 'mmcs_design_settings');
    add_settings_field('mmcs_heading_field', esc_html__('Heading', 'maintenance-mode-coming-soon'), 'mmcs_heading_field', 'mmcs-design', 'mmcs_design_settings');
    add_settings_field('mmcs_subheading_field', esc_html__('Subheading', 'maintenance-mode-coming-soon'), 'mmcs_subheading_field', 'mmcs-design', 'mmcs_design_settings');
    add_settings_field('mmcs_description_field', esc_html__('Description', 'maintenance-mode-coming-soon'), 'mmcs_description_field', 'mmcs-design', 'mmcs_design_settings');
    add_settings_field('mmcs_template_field', esc_html__('Select Template', 'maintenance-mode-coming-soon'), 'mmcs_template_field', 'mmcs-design', 'mmcs_design_settings');
}
add_action('admin_init', 'mmcs_settings_init');

// Fields for Configuration section
function mmcs_toggle_field() {
    $checked = get_option('mmcs_maintenance_mode') === 'on' ? 'checked' : '';
    echo "<input type='checkbox' name='mmcs_maintenance_mode' value='on' $checked>";
}

function mmcs_launch_date_field() {
    $launch_date = esc_attr(get_option('mmcs_launch_date', ''));
    echo "<input type='datetime-local' name='mmcs_launch_date' value='$launch_date'>";
}

// Fields for Design section
function mmcs_logo_field() {
    $logo_url = esc_attr(get_option('mmcs_logo', ''));
    echo "<input type='text' id='mmcs-logo' name='mmcs_logo' value='$logo_url'>";
    echo "<button type='button' class='button' id='mmcs-logo-upload'>" . esc_html__('Select Logo', 'maintenance-mode-coming-soon') . "</button>";
}

function mmcs_image_field() {
  $logo_url = esc_attr(get_option('mmcs_image', ''));
  echo "<input type='text' id='mmcs-image' name='mmcs_image' value='$logo_url'>";
  echo "<button type='button' class='button' id='mmcs-image-upload'>" . esc_html__('Select Image', 'maintenance-mode-coming-soon') . "</button>";
}


function mmcs_heading_field() {
    $heading = esc_attr(get_option('mmcs_heading', ''));
    echo "<input type='text' name='mmcs_heading' value='$heading'>";
}

function mmcs_subheading_field() {
    $subheading = esc_attr(get_option('mmcs_subheading', ''));
    echo "<input type='text' name='mmcs_subheading' value='$subheading'>";
}

function mmcs_description_field() {
    $description = esc_textarea(get_option('mmcs_description', ''));
    echo "<textarea name='mmcs_description'>$description</textarea>";
}

function mmcs_template_field() {
    $template = esc_attr(get_option('mmcs_template', 'template1'));
    echo "<select name='mmcs_template'>
            <option value='template1' " . selected($template, 'template1', false) . ">" . esc_html__('Template 1', 'maintenance-mode-coming-soon') . "</option>
            <option value='template2' " . selected($template, 'template2', false) . ">" . esc_html__('Template 2', 'maintenance-mode-coming-soon') . "</option>
            <option value='template3' " . selected($template, 'template3', false) . ">" . esc_html__('Template 3', 'maintenance-mode-coming-soon') . "</option>
          </select>";
}

// Enforce Maintenance Mode on Frontend
function mmcs_maintenance_mode() {
    if (!is_user_logged_in() && get_option('mmcs_maintenance_mode') === 'on') {
        status_header(503);
        include plugin_dir_path(__FILE__) . 'maintenance-template.php';
        exit();
    }
}
add_action('template_redirect', 'mmcs_maintenance_mode');

// Enqueue scripts for logo media selector
function mmcs_admin_script() {
    ?>
    <script>
        jQuery(document).ready(function($){
            $('#mmcs-logo-upload').click(function(e) {
                e.preventDefault();
                var image = wp.media({
                    title: '<?php esc_html_e('Select Logo', 'maintenance-mode-coming-soon'); ?>',
                    multiple: false
                }).open()
                .on('select', function(e){
                    var uploaded_image = image.state().get('selection').first();
                    var image_url = uploaded_image.toJSON().url;
                    $('#mmcs-logo').val(image_url);
                });
            });
            $('#mmcs-image-upload').click(function(e) {
                e.preventDefault();
                var image = wp.media({
                    title: '<?php esc_html_e('Select Image', 'maintenance-mode-coming-soon'); ?>',
                    multiple: false
                }).open()
                .on('select', function(e){
                    var uploaded_image = image.state().get('selection').first();
                    var image_url = uploaded_image.toJSON().url;
                    $('#mmcs-image').val(image_url);
                });
            });
        });
    </script>
    <?php
}
add_action('admin_footer', 'mmcs_admin_script');

// Include Maintenance Page Template
function mmcs_template_include($template) {
    if (!is_user_logged_in() && get_option('mmcs_maintenance_mode') === 'on') {
        return plugin_dir_path(__FILE__) . 'maintenance-template.php';
    }
    return $template;
}
add_filter('template_include', 'mmcs_template_include');

function render_logo( $logo_url ) {
    if ( $logo_url ) {
        echo '<div class="logo">';
        echo '<img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr__( 'Logo', 'maintenance-mode-coming-soon' ) . '">';
        echo '</div>';
    }
}

function render_image( $mmcs_image ) {
    if ( $mmcs_image ) {
        echo '<div class="image">';
        echo '<img src="' . esc_url( $mmcs_image ) . '" alt="' . esc_attr__( 'Image', 'maintenance-mode-coming-soon' ) . '">';
        echo '</div>';
    }
}

function render_text_element( $element, $tag ) {
    if ( ! empty( $element ) ) {
        echo '<' . esc_html( $tag ) . '>' . esc_html( $element ) . '</' . esc_html( $tag ) . '>';
    }
}

// Maintenance Page Template Content
function mmcs_maintenance_template_content() {
    $launch_date = esc_attr(get_option('mmcs_launch_date'));
    $logo_url = esc_url(get_option('mmcs_logo'));
    $mmcs_image = esc_url(get_option('mmcs_image'));

    $heading = esc_html(get_option('mmcs_heading'));
    $subheading = esc_html(get_option('mmcs_subheading'));
    $description = esc_html(get_option('mmcs_description'));
    $template = esc_attr(get_option('mmcs_template', 'template1'));
    $counter_js_url = plugin_dir_url(__FILE__) . 'counter.js';

    ?>
    
    
    <div class="maintenance-page template-<?php echo esc_attr($template); ?>">
        <?php if ('template1' === $template) : ?>
            <?php render_image( $mmcs_image ); ?>
            <?php render_text_element( $heading, 'h1' ); ?>
            <?php render_text_element( $subheading, 'h2' ); ?>
            <?php render_text_element( $description, 'p' ); ?>

            <?php if ($launch_date) : ?>
                <div class="countdown" id="countdown" data-launch-date="<?php echo esc_attr($launch_date); ?>"></div>
                <script type="text/javascript" src="<?php echo $counter_js_url; ?>"></script>
            <?php endif; ?>
            <?php render_logo( $logo_url ); ?>
        <!-- End Template 1 -->
        <?php elseif ('template2' === $template) : ?>
            <?php render_logo( $logo_url ); ?>
            <?php render_image( $mmcs_image ); ?>
            <?php render_text_element( $heading, 'h1' ); ?>
            <?php render_text_element( $subheading, 'h2' ); ?>
            <?php render_text_element( $description, 'p' ); ?>
            <?php if ($launch_date) : ?>
                <div class="countdown" id="countdown" data-launch-date="<?php echo esc_attr($launch_date); ?>"></div>
                <script type="text/javascript" src="<?php echo $counter_js_url; ?>"></script>
            <?php endif; ?>
        <!-- End Template 1 -->
        <?php elseif ('template3' === $template) : ?>
            <?php render_logo( $logo_url ); ?>
            <?php if ($launch_date) : ?>
                <div class="countdown" id="countdown" data-launch-date="<?php echo esc_attr($launch_date); ?>"></div>
                <script type="text/javascript" src="<?php echo $counter_js_url; ?>"></script>
            <?php endif; ?>
            <?php render_text_element( $heading, 'h1' ); ?>
            <?php render_text_element( $subheading, 'h2' ); ?>
            <?php render_text_element( $description, 'p' ); ?>
            <?php render_image( $mmcs_image ); ?>
        <?php endif; ?>
    </div>
    <?php
}