<?php

/**
 * Plugin Name: Phosworks GTM
 * Plugin URI: https://phosworks.com
 * Description: Simple plugin to add Google Tag Manager to your site
 * Version: 1.0.0
 * Author: Daniel Melin @ Phosworks
 * Author URI: https://phosworks.com
 * License: GPL2
 */

function pw_gtm_add_admin_menu()
{
    add_submenu_page(
        'options-general.php', // Parent slug
        'GTM', // Page title
        'GTM', // Menu title
        'manage_options', // Capability
        'pw-gtm', // Menu slug
        'pw_gtm_render_admin_page' // Callback function
    );
}
add_action('admin_menu', 'pw_gtm_add_admin_menu');

function pw_gtm_render_admin_page()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['submit'])) {
        update_option('pw_gtm_tag_id', sanitize_text_field($_POST['pw_gtm_tag_id']));
    }

    $pw_gtm_tag_id = get_option('pw_gtm_tag_id');
?>
    <div class="wrap">
        <h1>Google Tag Manager</h1>
        <form method="post" action="">
            <label for="pw_gtm_tag_id">Tag ID:</label>
            <input type="text" id="pw_gtm_tag_id" name="pw_gtm_tag_id" value="<?php echo esc_attr($pw_gtm_tag_id); ?>" placeholder="GTM-xxxxxx" />
            <?php submit_button(); ?>
        </form>
    </div>
<?php
}

add_action('wp_head', function () {
    $pw_gtm_tag_id = get_option('pw_gtm_tag_id');
    if (!$pw_gtm_tag_id) {
        return;
    }
?>
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', '<?= $pw_gtm_tag_id ?>');
    </script>
    <!-- End Google Tag Manager -->
<?php
}, 10, 1);

add_action('wp_body_open', function () {
    $pw_gtm_tag_id = get_option('pw_gtm_tag_id');
    if (!$pw_gtm_tag_id) {
        return;
    }
?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?= $pw_gtm_tag_id ?>"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
<?php
}, 10, 1);
