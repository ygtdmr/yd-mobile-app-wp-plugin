<?php
/**
 * YD Mobile App Plugin Uninstallation
 *
 * This script is executed when the YD Mobile App plugin is uninstalled from WordPress.
 * It includes the necessary steps for the plugin uninstallation process.
 *
 * @package  YD\Mobile_App
 * @author   Yigit Demir
 * @since    1.0.0
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

require_once __DIR__ . '/src/class-mobile-app.php';

\YD\Mobile_App::uninstall();
