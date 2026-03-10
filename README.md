<?php
/**
 * Plugin Name: Gemini CV Chatbot
 * Plugin URI: https://github.com/sanyi8/gemini-cv-chatbot
 * Description: Turn your CV into a conversational AI chatbot powered by Google Gemini. Visitors ask questions about your background in any language and get real answers with context-matched CTAs. Includes prompt injection protection, bot blocking, language detection, persona modes, tone styles, and an AI CV parser.
 * Version: 1.5.8
 * Author: Sandor Kardos
 * Author URI: https://sandorkardos.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: gemini-cv-chatbot
 * Tags: chatbot, AI, CV, resume, portfolio, gemini, multilingual
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'CV_CHATBOT_VERSION',     '1.5.8' );
define( 'CV_CHATBOT_PLUGIN_URL',  plugin_dir_url( __FILE__ ) );
define( 'CV_CHATBOT_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

require_once CV_CHATBOT_PLUGIN_PATH . 'includes/class-cv-chatbot.php';

function cv_chatbot_init() { new CV_Chatbot(); }
add_action( 'plugins_loaded', 'cv_chatbot_init' );

register_activation_hook( __FILE__, 'cv_chatbot_activate' );
function cv_chatbot_activate() {
    $defaults = array(
        'cv_chatbot_api_key'    => '',
        'cv_chatbot_name'       => 'Your Name',
        'cv_chatbot_email'      => 'your.email@example.com',
        'cv_chatbot_phone'      => '',
        'cv_chatbot_linkedin'   => '',
        'cv_chatbot_github'     => '',
        'cv_chatbot_portfolio'  => '',
        'cv_chatbot_blog'       => '',
        'cv_chatbot_linktree'   => '',
        'cv_chatbot_twitter'    => '',
        'cv_chatbot_behance'    => '',
        'cv_chatbot_youtube'    => '',
        'cv_chatbot_instagram'  => '',
        'cv_chatbot_cv_url'     => '',
        'cv_chatbot_cv_content' => 'Please add your CV content in the plugin settings.',
        'cv_chatbot_bg_mode'    => 'dark',
        'cv_chatbot_tone_style' => 'sandor',
        'cv_chatbot_persona_mode' => 'assistant',
        'cv_chatbot_lang_detect'  => '1',
    );
    foreach ( $defaults as $key => $value ) {
        add_option( $key, $value );
    }
}

register_deactivation_hook( __FILE__, 'cv_chatbot_deactivate' );
function cv_chatbot_deactivate() {
    global $wpdb;
    $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_cv_chatbot_%'" );
    $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_cv_chatbot_%'" );
}
