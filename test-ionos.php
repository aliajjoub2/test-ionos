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
add_filter('update_plugins_github.com/aliajjoub2/test-ionos/blob/main/test-ionos.php', function ($transient) {
    // Check if plugin update data is empty
    //if (empty($transient->checked)) {
    //    return $transient;
    //}

    // Plugin information
    $plugin_slug = 'test-ionos'; // Relative path to the plugin file
    $current_version = '1.0.0'; // Current plugin version
    $new_version = '1.0.1'; // New plugin version to test
    $package_url = 'https://github.com/aliajjoub2/test-ionos/raw/refs/heads/main/test-ionos.zip'; // Local URL to the update package

    // Check if a newer version is available
    if (version_compare($current_version, $new_version, '<')) {
        $transient->response[$plugin_slug] = (object)[
            'new_version' => $new_version,
            'slug'        => $plugin_slug,
            'package'     => $package_url, // URL to the zip file
            'url'         => 'http://localhost/test-ionos', // Optional: Local page with plugin details
            'requires'    => '5.0', // Minimum WordPress version required
            'tested'      => '6.3.2', // WordPress version tested up to
        ];
    }

    return $transient;
});


/*
Replace "View details" link to point to the GitHub repository.
*/
add_filter('plugin_row_meta', function ($plugin_meta, $plugin_file, $plugin_data) {
    if ($plugin_file == plugin_basename(__FILE__) && current_user_can('install_plugins')) {
        // Add a link to view the plugin details or repository
        $plugin_meta[] = sprintf(
            '<a href="%s" aria-label="%s">%s</a>',
            'https://example.com/plugin-details',
            sprintf(__('View details for %s'), $plugin_data['Name']),
            __('View Details')
        );

        // Manual update data
        $current_version = $plugin_data['Version'];
        $new_version = '1.0.1'; 
        $update_url = 'https://github.com/aliajjoub2/test-ionos/blob/main/test-ionos.zip';

        // Compare the current version with the new version
        if (version_compare($current_version, $new_version, '<')) {
            // Add a message about the available update
            $plugin_meta[] = sprintf(
                '<a href="%s" style="color: #0073aa; font-weight: bold;" aria-label="%s">%s</a>',
                $update_url,
                sprintf(__('Update to version %s'), $new_version),
                sprintf(__('Update Available: Version %s'), $new_version)
            );
        }
    }

    return $plugin_meta;
}, 10, 3);


/*
Allow local IPs if testing from a local environment (optional).
*/
add_filter('http_request_host_is_external', function ($external, $host, $url) {
    $external = $host == "example.com" ? true : $external;
    return $external;
}, 10, 3);

