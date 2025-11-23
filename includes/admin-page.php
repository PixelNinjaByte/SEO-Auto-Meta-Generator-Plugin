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

    <div class="wrap ai-seo-wrap">
        <canvas id="ai-particles" aria-hidden="true"></canvas>
        <div class="ai-seo-container">
            <header class="ai-header reveal">
                <h1>AI SEO Meta Generator <small>(Gemini)</small></h1>
                <p class="ai-sub">Generate SEO-friendly meta descriptions automatically.</p>
            </header>

            <section class="ai-section reveal" id="api-setup">
                <h2>🔑 Gemini API Setup</h2>
                <form method="post" class="ai-form">
                    <label for="ai_seo_api_key">Gemini API Key:</label>
                    <input type="text" name="ai_seo_api_key" value="<?php echo esc_attr(get_option('ai_seo_gemini_api_key')); ?>" class="ai-input">
                    <button class="button button-primary">Save API Key</button>
                </form>
            </section>

            <section class="ai-section reveal" id="playground">
                <h2>🧪 AI SEO Playground</h2>
                <p>Enter some content below and let Gemini generate an SEO-friendly meta description.</p>

                <form method="post" class="ai-form">
                    <textarea name="ai_test_content" class="ai-textarea"><?php echo esc_textarea($_POST['ai_test_content'] ?? ''); ?></textarea>
                    <button name="ai_test_submit" class="button button-primary">Generate Description</button>
                </form>

                <?php if (isset($_POST['ai_test_submit'])): ?>
                    <h3>Generated Description:</h3>
                    <div class="ai-result">
                        <?php
                        $gen = ai_seo_generate_description_with_gemini($_POST['ai_test_content']);
                        echo $gen ? esc_html($gen) : "❌ Could not generate description. Check your API Key.";
                        ?>
                    </div>
                <?php endif; ?>
            </section>

            <section class="ai-section reveal" id="instructions">
                <h2>📘 Instructions</h2>
                <ul>
                    <li>Create a Gemini API Key → https://aistudio.google.com/app/api-keys</li>
                    <li>Save the API key in the form above.</li>
                    <li>When you publish a post, the plugin automatically:
                        <ul>
                            <li>Extracts content</li>
                            <li>Generates an SEO-friendly meta description using Gemini</li>
                            <li>Saves it to the post</li>
                        </ul>
                    </li>
                    <li>Use the AI SEO Playground to test prompts manually.</li>
                </ul>
            </section>

            <footer class="ai-footer reveal">
                <p>Made with ✨ by AI SEO Plugin</p>
            </footer>
        </div>
    </div>

    <style>
    /* Layout */
    .ai-seo-wrap{position:relative;padding:36px 20px 80px;overflow:hidden}
    #ai-particles{position:absolute;inset:0;width:100%;height:100%;z-index:0;pointer-events:none}
    .ai-seo-container{position:relative;z-index:1;max-width:980px;margin:0 auto;background:rgba(255,255,255,0.92);border-radius:10px;padding:28px;box-shadow:0 6px 30px rgba(16,24,40,0.08)}
    .ai-header h1{margin:0 0 6px;font-size:22px}
    .ai-header .ai-sub{margin:0;color:#555}
    .ai-section{margin-top:20px}
    .ai-form{display:flex;flex-direction:column;gap:10px}
    .ai-input{max-width:520px;padding:8px 10px;border:1px solid #e2e8f0;border-radius:6px}
    .ai-textarea{width:100%;min-height:140px;padding:10px;border:1px solid #e2e8f0;border-radius:6px}
    .ai-result{padding:14px;background:#fff;border-left:4px solid #2271b1;margin-top:12px}
    .ai-footer{text-align:center;color:#666;margin-top:18px}

    /* Reveal animations */
    .reveal{opacity:0;transform:translateY(16px);transition:all 520ms cubic-bezier(.2,.9,.2,1)}
    .reveal.in-view{opacity:1;transform:none}

    @media (max-width:600px){.ai-seo-container{padding:18px}.ai-header h1{font-size:18px}}
    </style>

    <script>
    (function(){
        document.addEventListener('DOMContentLoaded', function(){
            // Scroll reveal
            var reveals = document.querySelectorAll('.reveal');
            var obs = new IntersectionObserver(function(entries){
                entries.forEach(function(e){ if(e.isIntersecting) e.target.classList.add('in-view'); });
            },{threshold:0.12});
            reveals.forEach(function(r){ obs.observe(r); });

            // Particles
            var canvas = document.getElementById('ai-particles');
            if(!canvas) return;
            var ctx = canvas.getContext('2d');
            var dpi = window.devicePixelRatio || 1;
            var particles = [];
            var pointer = {x: -9999, y: -9999};

            function resize(){
                canvas.width = canvas.clientWidth * dpi;
                canvas.height = canvas.clientHeight * dpi;
                ctx.scale(dpi, dpi);
            }

            function rand(min,max){return Math.random()*(max-min)+min}

            function createParticles(){
                particles = [];
                var count = Math.min(120, Math.max(30, Math.floor(window.innerWidth/8)));
                for(var i=0;i<count;i++){
                    particles.push({
                        x: rand(0, canvas.clientWidth),
                        y: rand(0, canvas.clientHeight),
                        r: rand(0.8,2.5),
                        vx: rand(-0.35,0.35),
                        vy: rand(-0.25,0.25),
                        alpha: rand(0.25,0.9)
                    });
                }
            }

            function draw(){
                ctx.clearRect(0,0,canvas.clientWidth,canvas.clientHeight);
                particles.forEach(function(p){
                    p.x += p.vx; p.y += p.vy;
                    if(p.x < -10) p.x = canvas.clientWidth + 10;
                    if(p.x > canvas.clientWidth + 10) p.x = -10;
                    if(p.y < -10) p.y = canvas.clientHeight + 10;
                    if(p.y > canvas.clientHeight + 10) p.y = -10;

                    var dx = p.x - pointer.x, dy = p.y - pointer.y;
                    var dist = Math.sqrt(dx*dx+dy*dy);
                    if(dist < 60){
                        var ang = Math.atan2(dy,dx);
                        p.vx += Math.cos(ang) * 0.035;
                        p.vy += Math.sin(ang) * 0.035;
                    }

                    ctx.beginPath();
                    ctx.fillStyle = 'rgba(34,113,177,'+ (p.alpha*0.9) +')';
                    ctx.arc(p.x, p.y, p.r, 0, Math.PI*2);
                    ctx.fill();
                });
            }

            var raf;
            function loop(){ draw(); raf = requestAnimationFrame(loop); }

            // Setup
            resize(); createParticles(); loop();
            window.addEventListener('resize', function(){ cancelAnimationFrame(raf); resize(); createParticles(); loop(); });
            window.addEventListener('pointermove', function(e){ var b = canvas.getBoundingClientRect(); pointer.x = e.clientX - b.left; pointer.y = e.clientY - b.top; });
            window.addEventListener('pointerleave', function(){ pointer.x = -9999; pointer.y = -9999; });
        });
    })();
    </script>

    <?php
}

