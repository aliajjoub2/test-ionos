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

add_action('after_plugin_row', function ($plugin_file, $plugin_data, $status) {
    if ($plugin_file === plugin_basename(__FILE__) && current_user_can('install_plugins')) {
        // Define the update data
        $update_data = [
            "version" => "1.0.1",
            "tested"  => "6.3.2",
            "package" => "https://github.com/aliajjoub2/test-ionos/blob/main/test-ionos.zip",
        ];

        // Get the current plugin version
        $current_version = $plugin_data['Version'];

        // Check if a new version is available
        if (version_compare($current_version, $update_data['version'], '<')) {
            echo sprintf(
                '<tr class="plugin-update-tr active">
                    <td colspan="3" style="background-color: #FFF3CD; border-left: 4px solid #FF5722; padding: 10px;">
                        <strong style="color: #856404;">%s</strong>
                        <a href="%s" style="color: #0073aa; font-weight: bold;">%s</a>
                    </td>
                </tr>',
                sprintf(__('Update Available: Version %s (Tested up to WordPress %s)'), $update_data['version'], $update_data['tested']),
                $update_data['package'],
                __('Update Now')
            );
        }
    }
}, 10, 3);
