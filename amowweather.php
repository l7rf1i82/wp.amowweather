<?php
/**
 * @package Amoweather
 */
/*
Plugin Name: Amoweather: Widgets
Plugin URI: https://amoweather.com/
Description: download current weather information, weather history, display a floating weather widget with current temperature based on the user's location on each public page. User's location can be obtained from the browser or determined from the user's IP address
Version: 1.0
Requires at least: 6.0
Requires PHP: 7.4
Author: Aleksandr Gorchakov
Author URI: https://github.com/l7rf1i82
License: GPLv2 or later
Text Domain: amoweather
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2023-2024 Automattic, Inc.
*/

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/dashboard.php';
require_once __DIR__ . '/install.php';
require_once __DIR__ . '/credentials.php';

const KELVIN_ZERO = 273.15;

function public_action()
{
    global $wpdb;
    $client      = new \GuzzleHttp\Client();

    $response    = $client->request('GET', "https://api.ipdata.co?api-key=" . IPDATA_API_KEY);
    $body        = json_decode($response->getBody());

    $location    = $body->city;
    $latitude    = $body->latitude;
    $longitude   = $body->longitude;

    $response    = $client->request('GET', "https://api.openweathermap.org/data/2.5/weather?lat=$latitude&lon=$longitude&appid=" . OPENWEATHERMAP_API_KEY);
    $body        = json_decode($response->getBody());

    $temperature = ceil($body->main->feels_like - KELVIN_ZERO);
    $time        = $_REQUEST['time'];

    // save to db
    $time           = esc_sql( $time );
    $location       = esc_sql( $location );
    $temperature    = esc_sql( $temperature );

    $wpdb->insert($wpdb->prefix . 'amoweather', [ 'time' => $time, 'location' => $location, 'temperature' => $temperature, ]);

    echo json_encode([
        'location'    => $location,
        'temperature' => $temperature,
    ]);
    wp_die();
}
function dashboard_action()
{
    if(isset($_POST['checked']))
    {
        update_option('amoweather_toggle_public_widget', (int)$_POST['checked']);
    }
    if(isset($_POST['save-css']))
    {
        file_put_contents(__DIR__ . '/style.css', $_POST['value']);
    }

    echo json_encode(['message' => 'saved']); wp_die();
}

function public_action_data_init()
{
    wp_localize_script( 'amoweather-script-js', 'public_action_data',
        [
            'url'     => admin_url('admin-ajax.php'),
            'action'  => 'public_action',
            'toggle_public_widget' => get_option('amoweather_toggle_public_widget') == 1,
        ]
    );
}

function admin_action_scripts()
{
    wp_enqueue_style( 'amoweather-admin-css', plugins_url( 'style-admin.css', __FILE__ ) );
    wp_enqueue_script( 'amoweather-script-admin-js', plugins_url( '/script-admin.js?v=1.0.4', __FILE__ ));
}
function public_action_scripts()
{
    wp_enqueue_script( 'amoweather-time-js', plugins_url( '/node_modules/moment/moment.js', __FILE__ ));

    wp_enqueue_style( 'amoweather-css', plugins_url( 'style.css', __FILE__ ) );
    wp_enqueue_script( 'amoweather-script-js', plugins_url( '/script.js?v=1.0.2', __FILE__ ));
}

add_action( 'wp_ajax_my_action', 'dashboard_action' );
add_action( 'wp_ajax_nopriv_public_action', 'public_action' );

add_action( 'wp_ajax_public_action', 'public_action' );

add_action( 'admin_enqueue_scripts', 'admin_action_scripts' );
add_action( 'wp_enqueue_scripts', 'public_action_scripts', 99 );
add_action( 'wp_enqueue_scripts', 'public_action_data_init', 99 );

register_activation_hook( __FILE__, 'amoweather_db_table_init' );
