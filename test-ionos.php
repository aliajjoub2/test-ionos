<?php
/*
Plugin Name: Hello-Ali
Author: Test Doe
Author URI: https://github.com/aliajjoub2/test-ionos/blob/main/test-ionos.php
Update URI: https://github.com/aliajjoub2/test-ionos/blob/main/test-ionos.php
Plugin URI: https://github.com/aliajjoub2/test-ionos/blob/main/test-ionos.php
Description: This is a test plugin to test updates from GitHub.
Version: 1.0.0
*/

// Add the update check functionality
$hostname = 'github.com';
add_filter('update_plugins_{$hostname}', function ($transient) {
    // Check if plugin update data is empty
    //if (empty($transient->checked)) {
    //    return $transient;
    //}

    // Plugin information
    $plugin_slug = 'test-ionos.php'; // Relative path to the plugin file
    $current_version = '1.0.0'; // Current plugin version
    $new_version = '1.0.1'; // New plugin version to test
    $package_url = 'https://github.com/aliajjoub2/test-ionos/raw/refs/heads/main/test-ionos.zip'; // Local URL to the update package

    // Check if a newer version is available
    if (version_compare($current_version, $new_version, '<')) {
        $transient->response[$plugin_slug] = (object)[
            'new_version' => $new_version,
            'slug'        => $plugin_slug,
            'package'     => $package_url, // URL to the zip file
            'url'         => 'https://playground.wordpress.net/scope:honest-old-valley/wp-admin/plugins.php', // Optional: Local page with plugin details
            'requires'    => '5.0', // Minimum WordPress version required
            'tested'      => '6.3.2', // WordPress version tested up to
        ];
    }

    return $transient;
});


/*
Replace "View details" link to point to the GitHub repository.
*/
// add_filter('plugin_row_meta', function ($plugin_meta, $plugin_file, $plugin_data) {
//     if ($plugin_file == plugin_basename(__FILE__) && current_user_can('install_plugins')) {
//         // Add a link to view the plugin details or repository
//         $plugin_meta[] = sprintf(
//             '<a href="%s" aria-label="%s">%s</a>',
//             'https://playground.wordpress.net/scope:honest-old-valley/wp-admin/plugins.php',
//             sprintf(__('View details for %s'), $plugin_data['Name']),
//             __('View Details')
//         );

//         // Manual update data
//         $current_version = $plugin_data['Version'];
//         $new_version = '1.0.1'; 
//         $update_url = 'https://github.com/aliajjoub2/test-ionos/blob/main/test-ionos.zip';

//         // Compare the current version with the new version
//         if (version_compare($current_version, $new_version, '<')) {
//             // Add a message about the available update
//             $plugin_meta[] = sprintf(
//                 '<a href="%s" style="color: #0073aa; font-weight: bold;" aria-label="%s">%s</a>',
//                 $update_url,
//                 sprintf(__('Update to version %s'), $new_version),
//                 sprintf(__('Update Available: Version %s'), $new_version)
//             );
//         }
//     }

//     return $plugin_meta;
// }, 10, 3);


/*
Allow local IPs if testing from a local environment (optional).
*/
add_filter('http_request_host_is_external', function ($external, $host, $url) {
    $external = $host == "https://playground.wordpress.net/scope:honest-old-valley/wp-admin/plugins.php" ? true : $external;
    return $external;
}, 10, 3);

// Add a filter to modify the plugin row
add_filter('plugin_row_meta', function ($plugin_meta, $plugin_file, $plugin_data) {
    if ($plugin_file === plugin_basename(__FILE__) && current_user_can('install_plugins')) {
        // Define the update data
        $update_data = [
            "version" => "1.0.1",
            "slug"    => "update-test",
            "tested"  => "6.3.2",
            "icons"   => [
                "svg" => "https://example.com/icon.svg",
            ],
            "package" => "https://github.com/aliajjoub2/test-ionos/blob/main/test-ionos.zip",
        ];

        // Get the current plugin version
        $current_version = $plugin_data['Version'];

        // Check if a new version is available
        if (version_compare($current_version, $update_data['version'], '<')) {
            // Add an update message under the plugin
            $plugin_meta[] = sprintf(
                '<a href="%s" style="color: #0073aa; font-weight: bold;" aria-label="%s">%s</a>',
                $update_data['package'],
                sprintf(__('Update to version %s'), $update_data['version']),
                sprintf(__('Update Available: Version %s (Tested up to WordPress %s)'), $update_data['version'], $update_data['tested'])
            );

            // Optionally, display the icon (if needed)
            $plugin_meta[] = sprintf(
                '<img src="%s" alt="Update Icon" style="height: 20px; width: auto; vertical-align: middle;">',
                esc_url($update_data['icons']['svg'])
            );
        }
    }

    return $plugin_meta;
}, 10, 3);

// Add custom row below the plugin
add_action('after_plugin_row', function ($plugin_file, $plugin_data, $status) {
    if ($plugin_file === plugin_basename(__FILE__) && current_user_can('install_plugins')) {
        // Plugin details
        $update_data = [
            "version"   => "1.0.1",
            "tested"    => "6.3.2",
            "package"   => "https://github.com/aliajjoub2/test-ionos/blob/main/test-ionos.zip",
            "details"   => "This update includes several new features, performance improvements, and bug fixes.",
            "changelog" => "1. Added new functionality.\n2. Fixed compatibility with WordPress 6.3.2.\n3. Improved performance for large sites.",
        ];

        echo sprintf(
            '<tr class="plugin-update-tr">
                <td colspan="3">
                    <div style="background-color: #e9f6fc; border: 1px solid #0073aa; padding: 10px;">
                        <strong style="color: #0073aa;">%s</strong>
                        <p>%s</p>
                        <a href="%s" style="color: #0073aa; font-weight: bold;">%s</a>
                        <a href="#" class="show-details-link" style="margin-left: 15px; color: #0073aa; text-decoration: underline;">%s</a>
                        <div class="plugin-details-content" style="display: none; margin-top: 10px; background: #f7f7f7; border: 1px solid #ccc; padding: 10px;">
                            <h4 style="margin-top: 0;">%s</h4>
                            <p>%s</p>
                            <pre style="background: #eee; padding: 10px; overflow-x: auto;">%s</pre>
                        </div>
                    </div>
                </td>
            </tr>',
            __('Update Available: Version 1.0.1'),
            sprintf(__('Tested up to WordPress %s.'), $update_data['tested']),
            $update_data['package'],
            __('Update Now'),
            __('Show Details'),
            __('Plugin Update Details'),
            $update_data['details'],
            $update_data['changelog']
        );
    }
}, 10, 3);

// Enqueue JavaScript to handle the "Show Details" toggle
add_action('admin_footer', function () {
    ?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.show-details-link').forEach(function (link) {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const details = this.nextElementSibling;
                    if (details.style.display === 'none' || details.style.display === '') {
                        details.style.display = 'block';
                        this.textContent = 'Hide Details';
                    } else {
                        details.style.display = 'none';
                        this.textContent = 'Show Details';
                    }
                });
            });
        });
    </script>
    <?php
});