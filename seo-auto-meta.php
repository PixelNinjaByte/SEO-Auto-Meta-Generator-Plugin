<?php
/**
 * Plugin Name:       AI SEO Meta Generator (Gemini)
 * Plugin URI:        https://github.com/PixelNinjaByte/SEO-Auto-Meta-Generator-Plugin
 * Description:       Uses Google Gemini AI to automatically generate SEO meta descriptions and Open Graph tags.
 * Version:           1.0
 * Author:            Kasina Yuvaraj, PixelNinjaByte
 * License:           MIT
 * Text Domain:       ai-seo-meta
 */

if (!defined('ABSPATH')) exit;

// Includes
require_once plugin_dir_path(__FILE__) . 'includes/gemini-client.php';
require_once plugin_dir_path(__FILE__) . 'includes/meta-generator.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-page.php';
