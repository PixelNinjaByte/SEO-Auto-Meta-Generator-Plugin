<?php
if (!defined('ABSPATH')) exit;

/*
|--------------------------------------------------------------------------
| Add SEO Fields to WooCommerce Product Edit Page
|--------------------------------------------------------------------------
*/
function ai_seo_add_product_meta_box() {
    add_meta_box(
        'ai_seo_product_meta',
        'Product SEO (Gemini AI)',
        'ai_seo_product_meta_box_html',
        'product',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'ai_seo_add_product_meta_box');


/*
|--------------------------------------------------------------------------
| Meta Box HTML
|--------------------------------------------------------------------------
*/
function ai_seo_product_meta_box_html($post) {

    $seo_title = get_post_meta($post->ID, '_ai_seo_title', true);
    $seo_desc  = get_post_meta($post->ID, '_ai_seo_description', true);
    ?>

    <style>
        .ai-seo-box input, .ai-seo-box textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            font-size: 14px;
        }
        .ai-seo-generate {
            margin-top: 10px;
        }
    </style>

    <div class="ai-seo-box">
        <label><strong>SEO Title</strong></label>
        <input type="text" name="ai_seo_title" value="<?php echo esc_attr($seo_title); ?>">

        <br><br>

        <label><strong>Meta Description</strong></label>
        <textarea name="ai_seo_description" rows="4"><?php echo esc_textarea($seo_desc); ?></textarea>

        <br><br>

        <button type="button"
                class="button ai-seo-generate"
                onclick="aiSeoGenerateProductMeta(<?php echo $post->ID; ?>)">
            Generate With Gemini
        </button>

        <p id="ai-seo-result" style="margin-top:10px;"></p>
    </div>

    <script>
        function aiSeoGenerateProductMeta(productId) {
            const resultDiv = document.getElementById('ai-seo-result');
            resultDiv.innerHTML = "Generating...";

            fetch(ajaxurl, {
                method: "POST",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: "action=ai_seo_generate_product_meta&product_id=" + productId
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    document.querySelector("input[name='ai_seo_title']").value = res.title;
                    document.querySelector("textarea[name='ai_seo_description']").value = res.description;
                    resultDiv.innerHTML = "<b>Generated!</b>";
                } else {
                    resultDiv.innerHTML = "<b>Error:</b> " + res.message;
                }
            });
        }
    </script>

    <?php
}

function ai_seo_save_product_meta($post_id) {

    if (isset($_POST['ai_seo_title'])) {
        update_post_meta($post_id, '_ai_seo_title', sanitize_text_field($_POST['ai_seo_title']));
    }

    if (isset($_POST['ai_seo_description'])) {
        update_post_meta($post_id, '_ai_seo_description', sanitize_textarea_field($_POST['ai_seo_description']));
    }

}
add_action('save_post_product', 'ai_seo_save_product_meta');
