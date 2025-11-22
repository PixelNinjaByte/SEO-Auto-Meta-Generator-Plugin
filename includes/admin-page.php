<?php

if (!defined('ABSPATH')) exit;

/*
|--------------------------------------------------------------------------
| Add Admin Menu Page
|--------------------------------------------------------------------------
*/
function ai_seo_register_menu_page() {
    add_menu_page(
        'AI SEO Meta Generator',
        'AI SEO',
        'manage_options',
        'ai-seo-settings',
        'ai_seo_admin_page_html',
        'dashicons-art',
        60
    );
}
add_action('admin_menu', 'ai_seo_register_menu_page');


/*
|--------------------------------------------------------------------------
| Admin Page HTML
|--------------------------------------------------------------------------
*/
function ai_seo_admin_page_html() {
    if (!current_user_can('manage_options')) return;

    if (isset($_POST['ai_seo_api_key'])) {
        update_option('ai_seo_gemini_api_key', sanitize_text_field($_POST['ai_seo_api_key']));
        echo '<div class="updated"><p>API Key saved.</p></div>';
    }
    ?>

    <div class="wrap">
        <h1>AI SEO Meta Generator (Gemini)</h1>

        <h2>🔑 Gemini API Setup</h2>
        <form method="post">
            <label for="ai_seo_api_key">Gemini API Key:</label>
            <input type="text" name="ai_seo_api_key" value="<?php echo esc_attr(get_option('ai_seo_gemini_api_key')); ?>" style="width: 400px;">
            <br><br>
            <button class="button button-primary">Save API Key</button>
        </form>

        <hr>

        <h2>🧪 AI SEO Playground</h2>
        <p>Enter some content below and let Gemini generate an SEO-friendly meta description.</p>

        <form method="post">
            <textarea name="ai_test_content" style="width:100%;height:150px;"><?php echo $_POST['ai_test_content'] ?? ''; ?></textarea>
            <br><br>
            <button name="ai_test_submit" class="button button-primary">Generate Description</button>
        </form>

        <?php if (isset($_POST['ai_test_submit'])): ?>
            <h3>Generated Description:</h3>
            <div style="padding: 15px; background:#fff; border-left:4px solid #2271b1; margin-top:10px;">
                <?php
                $gen = ai_seo_generate_description_with_gemini($_POST['ai_test_content']);
                echo $gen ? esc_html($gen) : "❌ Could not generate description. Check your API Key.";
                ?>
            </div>
        <?php endif; ?>

        <hr>

        <h2>📘 Instructions</h2>
        <ul style="line-height: 1.8;">
            <li>1. Create a Gemini API Key → https://aistudio.google.com/app/api-keys</li>
            <li>2. Save the API key in the form above.</li>
            <li>3. When you publish a post, the plugin automatically:
                <ul>
                    <li>Extracts content</li>
                    <li>Generates an SEO-friendly meta description using Gemini</li>
                    <li>Saves it to the post</li>
                </ul>
            </li>
            <li>4. Use the AI SEO Playground to test prompts manually.</li>
        </ul>

    </div>

    <?php
}
