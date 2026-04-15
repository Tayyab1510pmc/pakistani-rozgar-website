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
        body div.job_listings .job_filters { background: transparent !important; padding: 0 !important; margin-bottom: 50px !important; border: none !important; }
        body div.job_listings .job_filters .search_jobs { display: flex !important; background: #ffffff !important; padding: 8px 8px 8px 25px !important; border-radius: 60px !important; box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important; max-width: 700px !important; margin: 0 auto !important; align-items: center !important; border: 2px solid #e2e8f0 !important; }
        body div.job_listings .job_filters .search_jobs div.search_location, body div.job_listings .job_filters .search_jobs div.search_categories, body div.job_listings .showing_jobs { display: none !important; }
        body div.job_listings .job_filters .search_jobs div.search_keywords { flex: 1 !important; width: 100% !important; margin: 0 !important; padding: 0 !important; }
        body div.job_listings .job_filters .search_jobs input[name="search_keywords"] { border: none !important; box-shadow: none !important; background: transparent !important; font-size: 1.2rem !important; padding: 10px !important; width: 100% !important; outline: none !important; color: #333 !important; line-height: 1.5 !important; }
        body div.job_listings .job_filters .search_jobs .search_submit { margin: 0 !important; padding: 0 !important; width: auto !important; }
        body div.job_listings .job_filters .search_jobs .search_submit input { background: #149253 !important; color: white !important; border-radius: 50px !important; padding: 15px 35px !important; font-size: 1.1rem !important; border: none !important; cursor: pointer !important; }
        body ul.job_listings li.job_listing { background: #fff !important; border: 1px solid #e2e8f0 !important; border-radius: 16px !important; padding: 25px !important; margin-bottom: 20px !important; }
        body .job_listing .application_button { background: #149253 !important; border-radius: 8px !important; margin-top: 15px !important; width: 100% !important; text-align: center !important; color: white !important; padding: 15px !important; font-weight: bold !important; border: none !important; }
        .pr-footer { background: #0f172a !important; color: #94a3b8 !important; padding: 80px 0 30px; margin-top: 60px; font-family: sans-serif; }
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
    echo '<footer class="pr-footer"><div class="pr-container"><h3>Pakistani Rozgar</h3></div></footer>';
}

// 4. WHATSAPP BUTTON & SEO
add_action('single_job_listing_end', 'pr_inject_whatsapp_btn', 5);
add_action('single_job_listing_meta_after', 'pr_inject_whatsapp_btn', 5);
function pr_inject_whatsapp_btn() {
    global $post;
    if(!is_singular('job_listing')) return;
    $wa_link = "https://api.whatsapp.com/send?text=" . urlencode("Apply for this job: " . get_the_title($post->ID) . " " . get_permalink($post->ID));
    echo '<div style="margin-top: 25px; width: 100%; clear: both;"><a href="' . esc_url($wa_link) . '" target="_blank" style="display: block; background: #25D366 !important; color: white !important; padding: 18px !important; text-align: center !important; border-radius: 8px !important; font-weight: bold !important; text-decoration: none !important; font-size: 1.2rem !important;">📲 Share to WhatsApp</a></div>';
}