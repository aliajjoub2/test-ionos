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
add_filter('pre_set_site_transient_update_plugins', function ($update, $plugin_data, $plugin_file, $locales) {
    if ($plugin_file == plugin_basename(__FILE__)) {
        // GitHub repository information
        $github_user = 'aliajjoub2';
        $github_repo = 'test-ionos';

        $update = (object) [
            'new_version' => '1.0.1',
            'slug'        => plugin_basename(__FILE__),
            'package'     => 'https://github.com/aliajjoub2/test-ionos/raw/refs/heads/main/test-ionos.zip',
            'url'         => 'https://github.com/aliajjoub2/test-ionos/raw/refs/heads/main/test-ionos.zip',
            'requires'    => '5.0', // Minimum WordPress version required
            'tested'      => '6.3.2', // WordPress version tested up to
        ];
        return $update;

        // Fetch the latest release information from GitHub API
        // $api_url = "https://api.github.com/repos/{$github_user}/{$github_repo}";
        // $response = wp_remote_get($api_url);

        // if (is_wp_error($response)) {
        //     return $update; // Return existing update data if there's an error
        // }

        // $release_data = json_decode(wp_remote_retrieve_body($response), true);

        // Ensure the response contains the required data
        // if (!empty($release_data['tag_name']) && !empty($release_data['zipball_url'])) {
        //     $latest_version = $release_data['tag_name'];
        //     $current_version = $plugin_data['Version'];

        //     // Compare the current version with the latest version
        //     if (version_compare($current_version, $latest_version, '<')) {
        //         $update = (object) [
        //             'new_version' => '1.0.1',
        //             'slug'        => plugin_basename(__FILE__),
        //             'package'     => $release_data['zipball_url'],
        //             'url'         => $plugin_data['PluginURI'],
        //             'requires'    => '5.0', // Minimum WordPress version required
        //             'tested'      => '6.3.2', // WordPress version tested up to
        //         ];
        //     }
        // }
    }

    // return $update;
}, 10, 4);

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
