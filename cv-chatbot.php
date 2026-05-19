<?php
/**
 * Plugin Name: AI CV Chatbot
 * Plugin URI:  https://sandorkardos.com
 * Description: An AI-powered CV chatbot using the Google Gemini API. Visitors ask questions about your background in plain language and get real, context-aware answers.
 * Version:     1.5.8
 * Author:      Sandor Kardos
 * Author URI:  https://sandorkardos.com
 * License:     GPL v2 or later
 * Text Domain: cv-chatbot
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'CV_CHATBOT_VERSION',     '1.5.8' );
define( 'CV_CHATBOT_PLUGIN_URL',  plugin_dir_url( __FILE__ ) );
define( 'CV_CHATBOT_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

require_once CV_CHATBOT_PLUGIN_PATH . 'includes/class-cv-chatbot.php';

function cv_chatbot_init() {
    new CV_Chatbot();
}
add_action( 'plugins_loaded', 'cv_chatbot_init' );

// ── Activation ──────────────────────────────────────────────────────────
register_activation_hook( __FILE__, 'cv_chatbot_activate' );
function cv_chatbot_activate() {
    $defaults = array(
        'cv_chatbot_api_key'      => '',
        'cv_chatbot_name'         => 'Your Name',
        'cv_chatbot_email'        => 'your@email.com',
        'cv_chatbot_phone'        => '',
        'cv_chatbot_linkedin'     => '',
        'cv_chatbot_github'       => '',
        'cv_chatbot_portfolio'    => '',
        'cv_chatbot_blog'         => '',
        'cv_chatbot_linktree'     => '',
        'cv_chatbot_twitter'      => '',
        'cv_chatbot_behance'      => '',
        'cv_chatbot_youtube'      => '',
        'cv_chatbot_instagram'    => '',
        'cv_chatbot_cv_url'       => '',
        'cv_chatbot_cv_content'   => cv_chatbot_get_default_cv(),
        'cv_chatbot_bg_mode'      => 'dark',
        'cv_chatbot_tone_style'   => 'professional',
        'cv_chatbot_persona_mode' => 'assistant',
        'cv_chatbot_lang_detect'  => '1',
    );
    foreach ( $defaults as $key => $value ) {
        add_option( $key, $value );
    }
}

// ── Deactivation ─────────────────────────────────────────────────────────
register_deactivation_hook( __FILE__, 'cv_chatbot_deactivate' );
function cv_chatbot_deactivate() {
    global $wpdb;
    $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_cv_chatbot_%'" );
    $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_cv_chatbot_%'" );
}

// ── Default CV template ──────────────────────────────────────────────────
function cv_chatbot_get_default_cv() {
    return "Replace this text with your actual CV content.

I. Professional Summary
Write a brief summary of your professional background, key skills, and career highlights.

II. Contact Information
Location: Your City, Country
Email: your@email.com

III. Technical Skills
List your skills, tools, and technologies here.

IV. Work Experience
Job Title – Company Name (Year – Year)
- Key responsibility or achievement
- Another key achievement

V. Education
Qualification – Institution (Year – Year)
- Brief description

VI. Projects
Project Name
- What it was and what you built

VII. Additional Information
Anything else that is relevant — availability, languages spoken, interests.";
}
