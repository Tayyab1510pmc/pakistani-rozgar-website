<?php
/**
 * Plugin Name: Pakistani Rozgar Core System
 * Plugin URI: https://pakistanirozgar.com
 * Description: The ultimate mega-system for Pakistani Rozgar. Includes Universal Header/Footer, WhatsApp Apply buttons, Auto-Pages, and Custom Search Pill.
 * Version: 6.0.0
 * Author: Tayyab
 * Author URI: https://pakistanirozgar.com
 * Text Domain: pakistani-rozgar
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// 1. AUTO-CREATE ALL REQUIRED PAGES
add_action('admin_init', 'pr_auto_create_website_pages');
function pr_auto_create_website_pages() {
    if (get_option('pr_v6_pages_created')) return;
    $pages = array(
        'Home'         => '[pr_homepage]',
        'Browse Jobs'  => '[jobs]',
        'About Us'     => '[pr_about_page]',
        'Contact Us'   => '[pr_contact_page]'
    );
    foreach ($pages as $title => $content) {
        if (!get_page_by_title($title)) {
            wp_insert_post(array('post_title' => $title, 'post_content' => $content, 'post_status' => 'publish', 'post_type' => 'page'));
        }
    }
    $home = get_page_by_title('Home');
    if ($home) { update_option('show_on_front', 'page'); update_option('page_on_front', $home->ID); }
    update_option('pr_v6_pages_created', true);
}

// 2. CSS (OVERRIDES ELEMENTOR & ASTRA)
add_action('wp_head', 'pr_unbreakable_premium_css', 9999);
function pr_unbreakable_premium_css() {
    echo '<style>
        header.site-header, footer.site-footer, #masthead, #colophon, .ast-main-header-wrap, .ast-footer-wrapper, .entry-header { display: none !important; }
        body { font-family: "Inter", -apple-system, sans-serif !important; background-color: #f4f7f6 !important; color: #334155 !important; margin: 0; padding: 0; }
        .pr-container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .pr-header { background: #ffffff !important; box-shadow: 0 4px 20px rgba(0,0,0,0.05) !important; position: sticky; top: 0; z-index: 9999; padding: 15px 0; }
        .pr-header-inner { display: flex; justify-content: space-between; align-items: center; }
        .pr-logo { font-size: 1.8rem !important; font-weight: 900 !important; color: #1e293b !important; text-decoration: none !important; }
        .pr-logo span { color: #149253 !important; }
        .pr-nav { display: flex; gap: 30px; align-items: center; }
        .pr-nav a { color: #475569 !important; text-decoration: none !important; font-weight: 600 !important; }
        .pr-btn { background: #149253 !important; color: white !important; padding: 12px 28px !important; border-radius: 50px !important; font-weight: bold !important; text-decoration: none !important; display: inline-block; }
        .pr-btn:hover { background: #0e6b3d !important; color: #fff !important; }
        body div.job_listings .job_filters { background: transparent !important; padding: 0 !important; margin-bottom: 50px !important; border: none !important; }
        body div.job_listings .job_filters .search_jobs { display: flex !important; background: #ffffff !important; padding: 8px 8px 8px 25px !important; border-radius: 60px !important; box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important; max-width: 700px !important; margin: 0 auto !important; align-items: center !important; border: 2px solid #e2e8f0 !important; }
        body div.job_listings .job_filters .search_jobs div.search_location, body div.job_listings .job_filters .search_jobs div.search_categories, body div.job_listings .showing_jobs { display: none !important; }
        body div.job_listings .job_filters .search_jobs div.search_keywords { flex: 1 !important; width: 100% !important; margin: 0 !important; padding: 0 !important; }
        body div.job_listings .job_filters .search_jobs input[name="search_keywords"] { border: none !important; box-shadow: none !important; background: transparent !important; font-size: 1.2rem !important; padding: 10px !important; width: 100% !important; outline: none !important; color: #333 !important; line-height: 1.5 !important; }
        body div.job_listings .job_filters .search_jobs .search_submit { margin: 0 !important; padding: 0 !important; width: auto !important; }
        body div.job_listings .job_filters .search_jobs .search_submit input { background: #149253 !important; color: white !important; border-radius: 50px !important; padding: 15px 35px !important; font-size: 1.1rem !important; border: none !important; cursor: pointer !important; }
        body ul.job_listings li.job_listing { background: #fff !important; border: 1px solid #e2e8f0 !important; border-radius: 16px !important; padding: 25px !important; margin-bottom: 20px !important; }
        body .job_listing .application_button { background: #149253 !important; border-radius: 8px !important; margin-top: 15px !important; width: 100% !important; text-align: center !important; color: white !important; padding: 15px !important; font-weight: bold !important; border: none !important; }
        .pr-home-hero { background: linear-gradient(135deg, #149253, #0a4f2d); padding: 140px 20px 70px; text-align: center; margin-bottom: 50px; }
        .pr-home-hero h1 { color: #fff; font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 900; margin: 0 0 15px 0; }
        .pr-home-hero p { color: #e2f5ec; font-size: 1.3rem; max-width: 700px; margin: 0 auto 35px; }
        .pr-section-heading { text-align: center; font-size: 2rem; color: #1e293b; margin: 30px 0; font-weight: 800; }
        .pr-categories-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 18px; margin: 35px auto 15px; }
        .pr-category-card { display: block; background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; text-decoration: none !important; color: #1e293b !important; padding: 20px 15px; box-shadow: 0 10px 24px rgba(0,0,0,0.06); transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .pr-category-card:hover { transform: translateY(-4px); box-shadow: 0 16px 30px rgba(20,146,83,0.2); }
        .pr-category-icon { font-size: 1.8rem; display: block; margin-bottom: 10px; }
        .pr-category-name { font-size: 1rem; font-weight: 700; display: block; }
        .pr-featured-wrap { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 25px; box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06); margin-bottom: 40px; }
        .pr-featured-top { display: flex; justify-content: space-between; align-items: center; gap: 15px; margin-bottom: 10px; }
        .pr-featured-badge { background: #ecfdf3; color: #149253; border: 1px solid #c8f0db; border-radius: 999px; padding: 6px 12px; font-size: 12px; font-weight: 700; }
        .pr-footer { background: #0f172a !important; color: #94a3b8 !important; padding: 80px 0 30px; margin-top: 60px; font-family: sans-serif; }
        .pr-footer-grid { display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr; gap: 30px; }
        .pr-footer h3, .pr-footer h4 { color: #fff !important; margin: 0 0 16px; }
        .pr-footer a { color: #cbd5e1 !important; text-decoration: none !important; display: block; margin-bottom: 10px; }
        .pr-footer a:hover { color: #fff !important; }
        .pr-footer-social { display: flex; gap: 10px; margin-top: 15px; }
        .pr-footer-social a { width: 36px; height: 36px; border-radius: 50%; background: #1e293b; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 0; }
        .pr-footer-social svg { width: 16px; height: 16px; fill: #e2e8f0; }
        .pr-wa-btn { display: flex; align-items: center; justify-content: center; gap: 10px; background: #25D366 !important; color: #fff !important; padding: 18px !important; text-align: center !important; border-radius: 8px !important; font-weight: bold !important; text-decoration: none !important; font-size: 1.2rem !important; box-shadow: 0 4px 10px rgba(37,211,102,0.3) !important; }
        .pr-wa-btn svg { width: 22px; height: 22px; fill: currentColor; }
        @media (max-width: 991px) {
            .pr-categories-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .pr-footer-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 768px) {
            .pr-nav { display: none !important; }
            body div.job_listings .job_filters .search_jobs { flex-direction: column !important; padding: 15px !important; border-radius: 20px !important; }
            body div.job_listings .job_filters .search_jobs .search_submit input { width: 100% !important; margin-top: 10px !important; }
            .pr-categories-grid, .pr-footer-grid { grid-template-columns: 1fr; }
            .pr-featured-top { flex-direction: column; align-items: flex-start; }
        }
    </style>';  
}

// 3. HEADER & FOOTER
add_action('wp_body_open', 'pr_universal_header'); add_action('astra_header', 'pr_universal_header');
function pr_universal_header() {
    static $done = false; if($done) return; $done = true;
    echo '<header class="pr-header"><div class="pr-container pr-header-inner"><a href="/" class="pr-logo">Pakistani<span>Rozgar</span></a><nav class="pr-nav"><a href="/">Home</a><a href="/browse-jobs/">Browse Jobs</a><a href="/contact-us/">Contact</a></nav><a href="/browse-jobs/" class="pr-btn">View All Jobs</a></div></header>'; 
}
add_action('wp_footer', 'pr_universal_footer', 1);
function pr_universal_footer() {
    echo '<footer class="pr-footer"><div class="pr-container"><div class="pr-footer-grid"><div><h3>Pakistani <span style="color:#22c55e;">Rozgar</span></h3><p>Your trusted portal for verified jobs across Pakistan.</p><div class="pr-footer-social"><a href="https://facebook.com" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><svg viewBox="0 0 24 24"><path d="M22 12.07C22 6.51 17.52 2 12 2S2 6.51 2 12.07c0 5.02 3.66 9.18 8.44 9.93v-7.03h-2.54v-2.9h2.54V9.84c0-2.52 1.49-3.91 3.78-3.91 1.1 0 2.25.2 2.25.2v2.47h-1.27c-1.25 0-1.64.78-1.64 1.58v1.89h2.79l-.45 2.9h-2.34V22c4.78-.75 8.44-4.91 8.44-9.93z"></path></svg></a><a href="https://instagram.com" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><svg viewBox="0 0 24 24"><path d="M7.8 2h8.4A5.8 5.8 0 0 1 22 7.8v8.4A5.8 5.8 0 0 1 16.2 22H7.8A5.8 5.8 0 0 1 2 16.2V7.8A5.8 5.8 0 0 1 7.8 2zm0 1.9A3.9 3.9 0 0 0 3.9 7.8v8.4a3.9 3.9 0 0 0 3.9 3.9h8.4a3.9 3.9 0 0 0 3.9-3.9V7.8a3.9 3.9 0 0 0-3.9-3.9H7.8zm8.95 1.45a1.15 1.15 0 1 1 0 2.3 1.15 1.15 0 0 1 0-2.3zM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm0 1.9a3.1 3.1 0 1 0 0 6.2 3.1 3.1 0 0 0 0-6.2z"></path></svg></a><a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><svg viewBox="0 0 24 24"><path d="M20.45 20.45h-3.56v-5.57c0-1.33-.03-3.04-1.85-3.04-1.85 0-2.13 1.45-2.13 2.95v5.66H9.35V9h3.42v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.61 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.07 2.07 0 1 1 0-4.14 2.07 2.07 0 0 1 0 4.14zM7.12 20.45H3.56V9h3.56v11.45z"></path></svg></a></div></div><div><h4>Candidates</h4><a href="/browse-jobs/">Browse Jobs</a><a href="/browse-jobs/?search_keywords=Remote">Remote Jobs</a><a href="/browse-jobs/?search_keywords=Government">Government Jobs</a></div><div><h4>Company</h4><a href="/about-us/">About Us</a><a href="/contact-us/">Contact Us</a><a href="/browse-jobs/">Featured Jobs</a></div><div><h4>Resources</h4><a href="/privacy-policy/">Privacy Policy</a><a href="/terms-and-conditions/">Terms & Conditions</a><a href="/contact-us/">Support</a></div></div><div style="border-top:1px solid #1e293b;margin-top:35px;padding-top:20px;text-align:center;">&copy; ' . date('Y') . ' Pakistani Rozgar. All rights reserved.</div></div></footer>';
}

// 4. WHATSAPP BUTTON & SEO
add_action('single_job_listing_end', 'pr_inject_whatsapp_btn', 5);
add_action('single_job_listing_meta_after', 'pr_inject_whatsapp_btn', 5);
function pr_inject_whatsapp_btn() {
    global $post;
    if(!is_singular('job_listing')) return;
    $wa_link = "https://api.whatsapp.com/send?text=" . urlencode("Apply for this job: " . get_the_title($post->ID) . " " . get_permalink($post->ID));
    echo '<div style="margin-top:25px;width:100%;clear:both;"><a class="pr-wa-btn" href="' . esc_url($wa_link) . '" target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 32 32" aria-hidden="true"><path d="M16 3.2c-7.1 0-12.8 5.7-12.8 12.7 0 2.3.6 4.5 1.8 6.5L3.2 29l6.9-1.8a12.9 12.9 0 0 0 5.9 1.5h.1c7 0 12.7-5.7 12.7-12.7S23 3.2 16 3.2zm0 23.3c-1.9 0-3.8-.5-5.4-1.5l-.4-.2-4.1 1.1 1.1-4-.3-.4a10.5 10.5 0 0 1-1.6-5.6c0-5.8 4.8-10.6 10.7-10.6s10.6 4.8 10.6 10.6c0 5.9-4.8 10.6-10.6 10.6zm5.8-7.9c-.3-.2-1.8-.9-2-1-.3-.1-.5-.2-.8.2-.2.3-.8 1-1 1.2-.2.2-.4.2-.7.1-.3-.2-1.4-.5-2.6-1.6-1-1-1.6-2.1-1.8-2.4-.2-.3 0-.5.1-.7.1-.1.3-.3.5-.5.2-.2.2-.3.3-.5.1-.2 0-.4 0-.6-.1-.2-.7-1.7-1-2.3-.2-.5-.5-.5-.7-.5h-.6c-.2 0-.6.1-.9.4-.3.3-1.2 1.2-1.2 2.8 0 1.6 1.2 3.2 1.3 3.4.2.2 2.3 3.6 5.7 5 .8.4 1.5.6 2 .7.9.3 1.7.2 2.3.1.7-.1 1.8-.8 2-1.6.3-.8.3-1.5.2-1.6 0-.1-.2-.2-.5-.4z"></path></svg><span>Share to WhatsApp</span></a></div>';
}

// 5. HOMEPAGE + CATEGORY/FEATURED SHORTCODES
function pr_get_home_categories() {
    return array(
        array('name' => 'Government Jobs', 'icon' => '🏛️', 'query' => 'Government'),
        array('name' => 'IT Jobs', 'icon' => '💻', 'query' => 'IT'),
        array('name' => 'Remote Jobs', 'icon' => '🌍', 'query' => 'Remote'),
        array('name' => 'Banking Jobs', 'icon' => '🏦', 'query' => 'Banking'),
    );
}

add_shortcode('pr_categories', 'pr_categories_shortcode');
function pr_categories_shortcode() {
    $categories = pr_get_home_categories();
    ob_start(); ?>
    <section class="pr-categories">
        <h2 class="pr-section-heading">Popular Job Categories</h2>
        <div class="pr-categories-grid">
            <?php foreach ($categories as $category) : ?>
                <a class="pr-category-card" href="<?php echo esc_url('/browse-jobs/?search_keywords=' . rawurlencode($category['query'])); ?>">
                    <span class="pr-category-icon"><?php echo esc_html($category['icon']); ?></span>
                    <span class="pr-category-name"><?php echo esc_html($category['name']); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_shortcode('pr_featured_jobs', 'pr_featured_jobs_shortcode');
function pr_featured_jobs_shortcode() {
    ob_start(); ?>
    <section class="pr-featured-wrap">
        <div class="pr-featured-top">
            <h2 class="pr-section-heading" style="margin:0;">Featured Jobs</h2>
            <span class="pr-featured-badge">Premium Listings</span>
        </div>
        <?php echo do_shortcode('[jobs featured="true" per_page="6" show_filters="false"]'); ?>
    </section>
    <?php
    return ob_get_clean();
}

add_shortcode('pr_homepage', 'pr_homepage_shortcode');
function pr_homepage_shortcode() {
    ob_start(); ?>
    <section class="pr-home-hero">
        <h1>Find Your Dream Job</h1>
        <p>Search verified opportunities in Government, IT, Banking, and remote roles across Pakistan.</p>
        <?php echo do_shortcode('[jobs show_filters="true" show_pagination="false" per_page="0"]'); ?>
        <div class="pr-container">
            <?php echo do_shortcode('[pr_categories]'); ?>
        </div>
    </section>
    <div class="pr-container">
        <?php echo do_shortcode('[pr_featured_jobs]'); ?>
        <h2 class="pr-section-heading">Recent Opportunities</h2>
        <?php echo do_shortcode('[jobs per_page="6" show_filters="false"]'); ?>
        <div style="text-align:center; margin:40px 0 80px;"><a href="/browse-jobs/" class="pr-btn">Browse All Jobs</a></div>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode('pr_about_page', 'pr_about_page_shortcode');
function pr_about_page_shortcode() {
    return '<div class="pr-container" style="padding:80px 20px;max-width:800px;text-align:center;min-height:50vh;"><h1 style="color:#149253;font-size:3rem;font-weight:900;">About Us</h1><p style="font-size:1.2rem;line-height:1.8;color:#475569;margin-top:20px;">We built Pakistani Rozgar to be a trusted platform connecting talent with verified employers across Pakistan.</p></div>';
}

add_shortcode('pr_contact_page', 'pr_contact_page_shortcode');
function pr_contact_page_shortcode() {
    return '<div class="pr-container" style="padding:80px 20px;max-width:600px;min-height:50vh;"><h1 style="color:#149253;font-size:3rem;text-align:center;font-weight:900;">Contact Us</h1><div style="background:white;padding:40px;border-radius:16px;box-shadow:0 10px 30px rgba(0,0,0,0.05);margin-top:30px;"><p style="margin-bottom:20px;color:#475569;text-align:center;">Email us at <strong>support@pakistanirozgar.com</strong> and we will get back to you within 24 hours.</p></div></div>';
}
