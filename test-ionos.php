<?php
/*
Plugin Name: Hello-Ali
Author: Test Doe
Author URI: https://ali-ajjoub.de
Update URI: https://github.com/aliajjoub2/test-ionos/blob/main/test-ionos.php
Plugin URI: https://ionos.de
Description: This is a test plugin to test updates from GitHub.
Version: 1.0.0
*/

// Add the update check functionality
add_filter('pre_set_site_transient_update_plugins', function ($transient) {
    // Check if plugin update data is empty
    if (empty($transient->checked)) {
        return $transient;
    }

    // Plugin information
    $plugin_slug = 'test-ionos/test-ionos.php'; // Relative path to the plugin file
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
        $plugin_meta[] = sprintf(
            '<a href="%s" aria-label="%s">%s</a>',
            'https://github.com/aliajjoub2/test-ionos/blob/main/test-ionos.php',
            sprintf(__('View GitHub repository for %s'), $plugin_data['Name']),
            __('View GitHub Repository')
        );
    }

    return $plugin_meta;
}, 10, 3);

/*
Allow local IPs if testing from a local environment (optional).
*/
// Uncomment this filter if you're testing from a local server
/*
add_filter('http_request_host_is_external', function ($external, $host, $url) {
    $external = $host == "example.com" ? true : $external;
    return $external;
}, 10, 3);
*/
