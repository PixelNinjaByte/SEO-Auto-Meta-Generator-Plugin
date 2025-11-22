<?php

if (!defined('ABSPATH')) exit;

/*
|--------------------------------------------------------------------------
| Auto-Generate Meta Description Using Gemini
|--------------------------------------------------------------------------
*/
function ai_seo_output_meta_description() {

    if (is_singular()) {
        global $post;

        $existing = get_post_meta($post->ID, '_ai_seo_description', true);

        if ($existing) {
            $description = $existing;
        } else {
            $content = wp_strip_all_tags($post->post_content);

            // Generate with Gemini
            $description = ai_seo_generate_description_with_gemini($content);

            if ($description) {
                update_post_meta($post->ID, '_ai_seo_description', $description);
            }
        }

        if ($description) {
            echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
        }
    }
}
add_action('wp_head', 'ai_seo_output_meta_description', 2);
