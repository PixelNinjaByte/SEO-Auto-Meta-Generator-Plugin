<?php

if (!defined('ABSPATH')) exit;

function ai_seo_get_gemini_api_key() {
    return get_option('ai_seo_gemini_api_key', '');
}

function ai_seo_generate_description_with_gemini($content) {

    $api_key = ai_seo_get_gemini_api_key();

    if (!$api_key) return false;

    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-pro:generateContent?key={$api_key}";

    $prompt = "Generate a concise and SEO-friendly meta description (max 155 characters) for this content:\n\n" . $content;

    $post_fields = json_encode([
        "contents" => [
            [
                "parts" => [
                    ["text" => $prompt]
                ]
            ]
        ]
    ]);

    $response = wp_remote_post($url, [
        'body'    => $post_fields,
        'headers' => [
            'Content-Type' => 'application/json'
        ]
    ]);

    if (is_wp_error($response)) {
        return false;
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    return $body['candidates'][0]['content']['parts'][0]['text'] ?? false;
}
