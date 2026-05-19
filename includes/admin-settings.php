<?php
/**
 * Admin Settings Page - v1.5.8
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// ── Save ────────────────────────────────────────────────────────────
if ( isset( $_POST['submit'] ) && wp_verify_nonce( $_POST['cv_chatbot_nonce'], 'cv_chatbot_settings' ) ) {
    $fields_text = array(
        'cv_chatbot_api_key', 'cv_chatbot_name', 'cv_chatbot_bg_mode', 'cv_chatbot_tone_style',
        'cv_chatbot_phone', 'cv_chatbot_persona_mode', 'cv_chatbot_lang_detect',
    );
    $fields_email = array( 'cv_chatbot_email' );
    $fields_url   = array(
        'cv_chatbot_linkedin', 'cv_chatbot_github', 'cv_chatbot_portfolio', 'cv_chatbot_blog',
        'cv_chatbot_linktree', 'cv_chatbot_twitter', 'cv_chatbot_behance',
        'cv_chatbot_youtube',  'cv_chatbot_instagram', 'cv_chatbot_cv_url',
    );

    foreach ( $fields_text  as $f ) update_option( $f, sanitize_text_field( $_POST[ $f ] ?? '' ) );
    foreach ( $fields_email as $f ) update_option( $f, sanitize_email( $_POST[ $f ] ?? '' ) );
    foreach ( $fields_url   as $f ) update_option( $f, esc_url_raw( $_POST[ $f ] ?? '' ) );

    // CV content — strip HTML tags but keep line breaks
    $cv = isset( $_POST['cv_chatbot_cv_content'] ) ? wp_strip_all_tags( stripslashes( $_POST['cv_chatbot_cv_content'] ) ) : '';
    update_option( 'cv_chatbot_cv_content', $cv );

    echo '<div class="notice notice-success is-dismissible"><p>✅ Settings saved!</p></div>';
}

// ── Load ────────────────────────────────────────────────────────────
$f = function( $key, $default = '' ) { return get_option( $key, $default ); };

$api_key   = $f('cv_chatbot_api_key');
$name      = $f('cv_chatbot_name',      'Your Name');
$email     = $f('cv_chatbot_email',     'your@email.com');
$phone     = $f('cv_chatbot_phone',     '');
$linkedin  = $f('cv_chatbot_linkedin');
$github    = $f('cv_chatbot_github');
$portfolio = $f('cv_chatbot_portfolio');
$blog      = $f('cv_chatbot_blog',      '');
$linktree  = $f('cv_chatbot_linktree');
$twitter   = $f('cv_chatbot_twitter');
$behance   = $f('cv_chatbot_behance');
$youtube   = $f('cv_chatbot_youtube');
$instagram = $f('cv_chatbot_instagram');
$cv_url    = $f('cv_chatbot_cv_url');
$cv        = $f('cv_chatbot_cv_content');
$bg_mode   = $f('cv_chatbot_bg_mode',      'dark');
$tone      = $f('cv_chatbot_tone_style',   'professional');
$persona   = $f('cv_chatbot_persona_mode', 'assistant');
$lang_det  = $f('cv_chatbot_lang_detect',  '1');
?>
<div class="wrap cvcb-wrap">
<h1>🤖 CV Chatbot Settings <span style="font-size:13px;font-weight:400;color:#666;">v1.5.8</span></h1>

<!-- Quick Start -->
<div class="cvcb-box cvcb-info">
    <strong>🚀 Shortcode:</strong> <code>[cv_chatbot]</code> &nbsp;|&nbsp;
    <strong>Custom placeholder:</strong> <code>[cv_chatbot placeholder="Ask me anything..."]</code>
</div>

<form method="post" action="" id="cvcb-form">
<?php wp_nonce_field('cv_chatbot_settings','cv_chatbot_nonce'); ?>

<!-- ── API ── -->
<div class="cvcb-box">
    <h2>🔑 API Configuration</h2>
    <table class="form-table">
        <tr>
            <th><label for="cv_chatbot_api_key">Gemini API Key *</label></th>
            <td>
                <input type="password" id="cv_chatbot_api_key" name="cv_chatbot_api_key"
                       value="<?php echo esc_attr($api_key); ?>" class="regular-text" required />
                <p class="description">
                    Free key at <a href="https://aistudio.google.com/app/apikey" target="_blank">Google AI Studio</a>.
                    Used for chatbot responses and CV parsing.
                </p>
            </td>
        </tr>
    </table>
</div>

<!-- ── Personal ── -->
<div class="cvcb-box">
    <h2>👤 Personal Information</h2>
    <table class="form-table">
        <tr>
            <th><label for="cv_chatbot_name">Your Name *</label></th>
            <td><input type="text" id="cv_chatbot_name" name="cv_chatbot_name"
                       value="<?php echo esc_attr($name); ?>" class="regular-text" required /></td>
        </tr>
        <tr>
            <th><label for="cv_chatbot_email">Email Address *</label></th>
            <td><input type="email" id="cv_chatbot_email" name="cv_chatbot_email"
                       value="<?php echo esc_attr($email); ?>" class="regular-text" required /></td>
        </tr>
        <tr>
            <th><label for="cv_chatbot_phone">Phone Number</label></th>
            <td>
                <input type="text" id="cv_chatbot_phone" name="cv_chatbot_phone"
                       value="<?php echo esc_attr($phone); ?>" class="regular-text"
                       placeholder="+44 7700 000000" />
                <p class="description">Shown as a clickable tel: link when someone asks how to contact you.</p>
            </td>
        </tr>
        <tr>
            <th><label for="cv_chatbot_cv_url">CV Download URL (PDF)</label></th>
            <td>
                <input type="url" id="cv_chatbot_cv_url" name="cv_chatbot_cv_url"
                       value="<?php echo esc_attr($cv_url); ?>" class="regular-text"
                       placeholder="https://yoursite.com/your-cv.pdf" />
                <p class="description">
                    Direct link to your CV PDF. Shown as a download button when someone asks for your CV or resume.<br>
                    Upload your PDF via <a href="<?php echo admin_url('upload.php'); ?>">Media Library</a> and paste the URL here.
                </p>
            </td>
        </tr>
    </table>
</div>

<!-- ── Social Links ── -->
<div class="cvcb-box">
    <h2>🔗 Social &amp; Portfolio Links</h2>
    <p class="description" style="margin:0 0 16px;">
        The AI will recommend the most relevant link based on what the visitor is asking about. Leave blank to skip.
    </p>
    <table class="form-table">
        <?php
        $socials = array(
            'cv_chatbot_linkedin'  => array('LinkedIn',          'linkedin',   'https://linkedin.com/in/your-profile'),
            'cv_chatbot_github'    => array('GitHub',            'github',     'https://github.com/yourusername'),
            'cv_chatbot_portfolio' => array('Portfolio / Website','link',      'https://yourwebsite.com'),
            'cv_chatbot_blog'      => array('Blog',              'edit',       'https://yourwebsite.com/blog'),
            'cv_chatbot_linktree'  => array('Linktree',          'link',       'https://linktr.ee/yourhandle'),
            'cv_chatbot_behance'   => array('Behance',           'palette',    'https://behance.net/yourprofile'),
            'cv_chatbot_twitter'   => array('X / Twitter',       'twitter',    'https://x.com/yourhandle'),
            'cv_chatbot_youtube'   => array('YouTube',           'youtube',    'https://youtube.com/@yourchannel'),
            'cv_chatbot_instagram' => array('Instagram',         'instagram',  'https://instagram.com/yourhandle'),
        );
        $vals = array(
            'cv_chatbot_linkedin'  => $linkedin,
            'cv_chatbot_github'    => $github,
            'cv_chatbot_portfolio' => $portfolio,
            'cv_chatbot_blog'      => $blog,
            'cv_chatbot_linktree'  => $linktree,
            'cv_chatbot_behance'   => $behance,
            'cv_chatbot_twitter'   => $twitter,
            'cv_chatbot_youtube'   => $youtube,
            'cv_chatbot_instagram' => $instagram,
        );
        foreach ( $socials as $key => list($label, $icon, $ph) ):
        ?>
        <tr>
            <th><label for="<?php echo $key; ?>"><?php echo $label; ?></label></th>
            <td>
                <input type="url" id="<?php echo $key; ?>" name="<?php echo $key; ?>"
                       value="<?php echo esc_attr($vals[$key]); ?>"
                       placeholder="<?php echo $ph; ?>" class="regular-text" />
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<!-- ── Appearance ── -->
<div class="cvcb-box">
    <h2>🎨 Appearance &amp; Tone</h2>
    <table class="form-table">

        <!-- BG MODE -->
        <tr>
            <th><label>Website Background</label></th>
            <td>
                <div style="display:flex;gap:14px;flex-wrap:wrap;">
                    <label style="cursor:pointer;">
                        <div class="bg-card <?php echo $bg_mode==='dark'?'bg-card-active':''; ?>"
                             style="background:#0f1a0a;color:#f3f4f6;border:2px solid <?php echo $bg_mode==='dark'?'#d8ff88':'#555'; ?>;border-radius:10px;padding:14px 18px;min-width:155px;text-align:center;">
                            <input type="radio" name="cv_chatbot_bg_mode" value="dark" <?php checked($bg_mode,'dark'); ?>/><br>
                            <strong>🌙 Dark Background</strong><br>
                            <small style="color:#9ca3af;">Light text on dark site</small><br>
                            <div style="margin-top:8px;padding:5px 8px;background:rgba(255,255,255,.05);border-radius:5px;font-size:12px;color:#f3f4f6;">Response text preview</div>
                        </div>
                    </label>
                    <label style="cursor:pointer;">
                        <div class="bg-card <?php echo $bg_mode==='light'?'bg-card-active':''; ?>"
                             style="background:#f9fafb;color:#1f2937;border:2px solid <?php echo $bg_mode==='light'?'#65a30d':'#ddd'; ?>;border-radius:10px;padding:14px 18px;min-width:155px;text-align:center;">
                            <input type="radio" name="cv_chatbot_bg_mode" value="light" <?php checked($bg_mode,'light'); ?>/><br>
                            <strong>☀️ Light Background</strong><br>
                            <small style="color:#6b7280;">Dark text on light site</small><br>
                            <div style="margin-top:8px;padding:5px 8px;background:rgba(0,0,0,.05);border-radius:5px;font-size:12px;color:#1f2937;">Response text preview</div>
                        </div>
                    </label>
                </div>
                <p class="description" style="margin-top:8px;">Automatically sets correct text &amp; input colours.</p>
            </td>
        </tr>

        <!-- PERSONA MODE -->
        <tr>
            <th><label>Chatbot Persona</label></th>
            <td>
                <div style="display:flex;gap:14px;flex-wrap:wrap;">
                    <label style="cursor:pointer;">
                        <div style="background:#1e293b;color:#f1f5f9;border:2px solid <?php echo $persona==='assistant'?'#d8ff88':'#475569'; ?>;border-radius:10px;padding:14px 18px;min-width:180px;">
                            <input type="radio" name="cv_chatbot_persona_mode" value="assistant" <?php checked($persona,'assistant'); ?>><br>
                            <strong style="font-size:13px;">🤖 AI Assistant</strong><br>
                            <small style="color:#94a3b8;font-size:12px;">"I'm [Your Name]'s AI Assistant."<br>Speaks on your behalf.</small>
                        </div>
                    </label>
                    <label style="cursor:pointer;">
                        <div style="background:#1e293b;color:#f1f5f9;border:2px solid <?php echo $persona==='clone'?'#d8ff88':'#475569'; ?>;border-radius:10px;padding:14px 18px;min-width:180px;">
                            <input type="radio" name="cv_chatbot_persona_mode" value="clone" <?php checked($persona,'clone'); ?>><br>
                            <strong style="font-size:13px;">🧬 AI Clone</strong><br>
                            <small style="color:#94a3b8;font-size:12px;">"I'm [Your Name]'s AI Clone."<br>Speaks as if it IS you.</small>
                        </div>
                    </label>
                </div>
                <p class="description" style="margin-top:8px;">Controls how the chatbot introduces itself. Both modes use first person throughout.</p>
            </td>
        </tr>

        <!-- TONE -->
        <tr>
            <th><label for="cv_chatbot_tone_style">Response Tone</label></th>
            <td>
                <select id="cv_chatbot_tone_style" name="cv_chatbot_tone_style" class="regular-text">
                    <option value="sandor"       <?php selected($tone,'sandor');       ?>>Building in Public – Honest, technical, autodidact journey, no fluff</option>
                    <option value="professional" <?php selected($tone,'professional'); ?>>Professional – Polished UK English, confident, subtle wit</option>
                    <option value="casual"       <?php selected($tone,'casual');       ?>>Casual – Friendly, warm, conversational</option>
                    <option value="basic"        <?php selected($tone,'basic');        ?>>Basic – Simple, direct, no jargon</option>
                </select>
                <p class="description"><strong>Building in Public</strong> is recommended — honest, technical but accessible, autodidact narrative. Facts and progress, no fluff.</p>
            </td>
        </tr>

        <!-- LANGUAGE DETECTION -->
        <tr>
            <th><label>Language Detection</label></th>
            <td>
                <label style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;max-width:520px;">
                    <input type="hidden"   name="cv_chatbot_lang_detect" value="0" />
                    <input type="checkbox" name="cv_chatbot_lang_detect" value="1" <?php checked( $lang_det, '1' ); ?>
                           style="margin-top:3px;flex-shrink:0;" />
                    <span>
                        <strong>Detect visitor browser language and offer to switch</strong><br>
                        <span style="color:#666;font-size:12px;line-height:1.5;">
                            When enabled, the chatbot detects the visitor's browser language on their first message.
                            If it's not English, it answers in English first then offers to switch to their language.
                            If the visitor says yes, the whole conversation continues in that language.<br><br>
                            Supported languages: major EU languages (Hungarian, German, French, Spanish, Italian, Polish and more),
                            Arabic, Hebrew, Persian, Turkish, Chinese, Japanese, Korean, Hindi, Swahili, Yoruba, Zulu, and 70+ others.
                        </span>
                    </span>
                </label>
            </td>
        </tr>
    </table>
</div>

<!-- ── CV Content ── -->
<div class="cvcb-box">
    <h2>📄 CV Content</h2>

    <!-- CV Upload / Parse Tool -->
    <div class="cvcb-cv-tool">
        <h3 style="margin:0 0 10px;">✨ AI CV Parser</h3>
        <p style="margin:0 0 12px;color:#555;font-size:13px;">
            Paste raw CV text below and click <strong>Parse &amp; Clean</strong> — the AI will
            reformat it into a clean, structured version optimised for the chatbot.
        </p>
        <textarea id="cvcb-raw-cv" rows="8" placeholder="Paste your raw CV text here (from Word, PDF, LinkedIn etc.)..."
                  style="width:100%;font-family:monospace;font-size:13px;padding:10px;border:1px solid #ddd;border-radius:6px;resize:vertical;"></textarea>
        <div style="margin-top:10px;display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
            <button type="button" id="cvcb-parse-btn" class="button button-secondary">
                ✨ Parse &amp; Clean with AI
            </button>
            <button type="button" id="cvcb-use-parsed-btn" class="button button-primary" style="display:none;">
                ✅ Use This CV
            </button>
            <span id="cvcb-parse-status" style="font-size:13px;color:#666;"></span>
        </div>
        <div id="cvcb-parsed-preview" style="display:none;margin-top:14px;">
            <strong style="font-size:13px;">Parsed preview:</strong>
            <pre id="cvcb-parsed-text" style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;padding:12px;font-size:12px;max-height:250px;overflow-y:auto;white-space:pre-wrap;"></pre>
        </div>
    </div>

    <table class="form-table" style="margin-top:20px;">
        <tr>
            <th><label for="cv_chatbot_cv_content">CV Data (used by AI) *</label></th>
            <td>
                <textarea id="cv_chatbot_cv_content" name="cv_chatbot_cv_content"
                          rows="22" class="large-text code" required
                          style="font-size:13px;"><?php echo esc_textarea($cv); ?></textarea>
                <p class="description">
                    Plain text only — HTML tags are stripped automatically on save.<br>
                    This is what the AI reads to answer visitor questions.
                </p>
            </td>
        </tr>
    </table>
</div>

<?php submit_button('💾 Save Settings'); ?>
</form>

<!-- ── Features ── -->
<div class="cvcb-box">
    <h2>✅ Active Features</h2>
    <ul style="columns:2;gap:20px;">
        <li>✅ Gemini 2.5 Flash (fallback: 1.5 Flash → 1.5 Pro)</li>
        <li>✅ Prompt injection &amp; jailbreak protection</li>
        <li>✅ Rate limiting (15 req/hr per IP)</li>
        <li>✅ 3 tone styles (Basic / Professional / Casual)</li>
        <li>✅ Dark / Light background auto-colours</li>
        <li>✅ Smart contextual social link CTAs</li>
        <li>✅ AI CV Parser (paste → clean structured text)</li>
        <li>✅ Conversation memory (last 5 turns)</li>
        <li>✅ Session-free (transient-based)</li>
        <li>✅ UK English responses</li>
        <li>✅ Max 300 char input guard</li>
        <li>✅ Response length capped at ~200 words</li>
    </ul>
</div>
</div><!-- .cvcb-wrap -->

<style>
.cvcb-wrap h1{margin-bottom:16px;}
.cvcb-box{background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:20px 24px;margin-bottom:20px;}
.cvcb-box h2{font-size:16px;margin:0 0 16px;padding-bottom:10px;border-bottom:2px solid #d8ff88;}
.cvcb-info{background:#f0fdf4;border-color:#bbf7d0;font-size:14px;padding:14px 20px;}
.cvcb-info code{background:#dcfce7;padding:2px 6px;border-radius:4px;}
.cvcb-cv-tool{background:#f8fafc;border:1px dashed #cbd5e1;border-radius:8px;padding:16px 18px;margin-bottom:4px;}
.bg-card{transition:border-color .2s;}
.bg-card:hover{border-color:#d8ff88 !important;}
</style>

<script>
(function(){
    var parseBtn     = document.getElementById('cvcb-parse-btn');
    var useBtn       = document.getElementById('cvcb-use-parsed-btn');
    var rawArea      = document.getElementById('cvcb-raw-cv');
    var status       = document.getElementById('cvcb-parse-status');
    var preview      = document.getElementById('cvcb-parsed-preview');
    var parsedText   = document.getElementById('cvcb-parsed-text');
    var cvField      = document.getElementById('cv_chatbot_cv_content');
    var parsedResult = '';

    parseBtn.addEventListener('click', function(){
        var raw = rawArea.value.trim();
        if(!raw){ status.textContent = '⚠️ Please paste some CV text first.'; return; }

        parseBtn.disabled = true;
        status.textContent = '⏳ Parsing with AI...';
        useBtn.style.display = 'none';
        preview.style.display = 'none';

        var fd = new FormData();
        fd.append('action',  'cv_chatbot_parse_cv');
        fd.append('nonce',   '<?php echo wp_create_nonce("cv_chatbot_admin_nonce"); ?>');
        fd.append('cv_text', raw);

        fetch(ajaxurl, { method:'POST', body:fd })
            .then(function(r){ return r.json(); })
            .then(function(d){
                if(d.success){
                    parsedResult = d.data.parsed;
                    parsedText.textContent = parsedResult;
                    preview.style.display  = 'block';
                    useBtn.style.display   = 'inline-block';
                    status.textContent     = '✅ Parsed! Review below, then click "Use This CV".';
                } else {
                    status.textContent = '❌ ' + (d.data || 'Error. Check API key.');
                }
            })
            .catch(function(){ status.textContent = '❌ Network error. Try again.'; })
            .finally(function(){ parseBtn.disabled = false; });
    });

    useBtn.addEventListener('click', function(){
        cvField.value = parsedResult;
        status.textContent = '✅ CV content updated! Save settings to apply.';
        useBtn.style.display = 'none';
        rawArea.value = '';
        cvField.scrollIntoView({ behavior:'smooth', block:'center' });
    });
})();
</script>

<div style="margin-top:40px;padding:16px 20px;background:#1e1e2e;border-radius:8px;border:1px solid #2a2a3e;text-align:center;">
    <p style="margin:0 0 4px;font-size:13px;color:#94a3b8;">Enjoying the plugin? It took a lot of iterations to get here.</p>
    <p style="margin:0;font-size:13px;color:#64748b;">If it's been useful, you can <a href="https://ko-fi.com/sandorko" target="_blank" rel="noopener" style="color:#d8ff88;text-decoration:none;">buy me a coffee ☕</a> — no pressure at all.</p>
</div>
