<?php
/**
 * Plugin Name: Pakistani Rozgar Core System
 * Plugin URI: https://pakistanirozgar.com
 * Description: The ultimate mega-system for Pakistani Rozgar. Includes Universal Header/Footer, WhatsApp Apply buttons, Auto-Pages, and Custom Search Pill.
 * Version: 1.0.0
 * Author: Tayyab
 * Author URI: https://pakistanirozgar.com
 * Text Domain: pakistani-rozgar
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// ==========================================
// 1. AUTO-CREATE ALL REQUIRED PAGES
// ==========================================
add_action( 'admin_init', 'pr_auto_create_website_pages' );
function pr_auto_create_website_pages() {
    if ( get_option( 'pr_pages_created' ) || get_option( 'pr_v6_pages_created' ) ) {
        return;
    }

    $pages = array(
        'Home'        => '[pr_homepage]',
        'Browse Jobs' => '[jobs]',
        'About Us'    => '[pr_about_page]',
        'Contact Us'  => '[pr_contact_page]',
    );

    foreach ( $pages as $title => $content ) {
        if ( ! pr_get_page_by_title( $title ) ) {
            wp_insert_post(
                array(
                    'post_title'   => $title,
                    'post_content' => $content,
                    'post_status'  => 'publish',
                    'post_type'    => 'page',
                )
            );
        }
    }

    $home = pr_get_page_by_title( 'Home' );

    if ( $home ) {
        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', $home->ID );
    }

    update_option( 'pr_pages_created', true );
    update_option( 'pr_v6_pages_created', true );
}

function pr_get_page_by_title( $title ) {
    $pages = get_posts(
        array(
            'post_type'      => 'page',
            'title'          => $title,
            'post_status'    => 'any',
            'posts_per_page' => 1,
        )
    );

    return ! empty( $pages ) ? $pages[0] : null;
}

// ==========================================
// 2. UNBREAKABLE CSS (OVERRIDES ELEMENTOR & ASTRA)
// ==========================================
add_action( 'wp_head', 'pr_unbreakable_premium_css', 9999 );
function pr_unbreakable_premium_css() {
    ?>
    <style>
        /* Hide Default Theme Clutter */
        header.site-header, footer.site-footer, #masthead, #colophon, .ast-main-header-wrap, .ast-footer-wrapper, .entry-header { display: none !important; }
        body { font-family: 'Inter', -apple-system, sans-serif !important; background-color: #f4f7f6 !important; color: #334155 !important; margin: 0; padding: 0; }
        .pr-container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }

        /* Universal Header */
        .pr-header { background: #ffffff !important; box-shadow: 0 4px 20px rgba(0,0,0,0.05) !important; position: sticky; top: 0; z-index: 9999; padding: 15px 0; }
        .pr-header-inner { display: flex; justify-content: space-between; align-items: center; }
        .pr-logo { font-size: 1.8rem !important; font-weight: 900 !important; color: #1e293b !important; text-decoration: none !important; }
        .pr-logo span { color: #149253 !important; }
        .pr-nav { display: flex; gap: 30px; align-items: center; }
        .pr-nav a { color: #475569 !important; text-decoration: none !important; font-weight: 600 !important; }
        .pr-nav a:hover { color: #149253 !important; }
        .pr-btn { background: #149253 !important; color: white !important; padding: 12px 28px !important; border-radius: 50px !important; font-weight: bold !important; text-decoration: none !important; display: inline-block; transition: 0.3s; }
        .pr-btn:hover { background: #0e6b3d !important; transform: translateY(-2px); }

        /* --- THE UNBREAKABLE PILL SEARCH BAR --- */
        body div.job_listings .job_filters { background: transparent !important; padding: 0 !important; margin-bottom: 50px !important; border: none !important; }
        body div.job_listings .job_filters .search_jobs {
            display: flex !important;
            background: #ffffff !important;
            padding: 8px 8px 8px 25px !important;
            border-radius: 60px !important;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
            max-width: 700px !important;
            margin: 0 auto !important;
            align-items: center !important;
            border: 2px solid #e2e8f0 !important;
        }

        /* Hide extra dropdowns */
        body div.job_listings .job_filters .search_jobs div.search_location,
        body div.job_listings .job_filters .search_jobs div.search_categories,
        body div.job_listings .showing_jobs { display: none !important; }

        /* The Keyword Input Box */
        body div.job_listings .job_filters .search_jobs div.search_keywords { flex: 1 !important; width: 100% !important; margin: 0 !important; padding: 0 !important; }
        body div.job_listings .job_filters .search_jobs input[name="search_keywords"] {
            border: none !important; box-shadow: none !important; background: transparent !important;
            font-size: 1.2rem !important; padding: 10px !important; width: 100% !important; outline: none !important;
            color: #333 !important; line-height: 1.5 !important;
        }

        /* The Submit Button */
        body div.job_listings .job_filters .search_jobs .search_submit { margin: 0 !important; padding: 0 !important; width: auto !important; }
        body div.job_listings .job_filters .search_jobs .search_submit input {
            background: #149253 !important; color: white !important; border-radius: 50px !important;
            padding: 15px 35px !important; font-size: 1.1rem !important; border: none !important;
            box-shadow: 0 4px 15px rgba(20,146,83,0.3) !important; cursor: pointer !important;
        }

        /* Job Cards */
        body ul.job_listings li.job_listing { background: #fff !important; border: 1px solid #e2e8f0 !important; border-radius: 16px !important; padding: 25px !important; margin-bottom: 20px !important; box-shadow: 0 4px 6px rgba(0,0,0,0.02) !important; }
        body ul.job_listings li.job_listing:hover { border-left: 6px solid #149253 !important; box-shadow: 0 15px 25px rgba(20,146,83,0.1) !important; }
        body .job_listing .application_button { background: #149253 !important; border-radius: 8px !important; margin-top: 15px !important; width: 100% !important; text-align: center !important; color: white !important; padding: 15px !important; font-weight: bold !important; border: none !important; }

        /* Universal Footer */
        .pr-footer { background: #0f172a !important; color: #94a3b8 !important; padding: 80px 0 30px; margin-top: 60px; font-family: sans-serif; }
        .pr-footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 40px; }
        .pr-footer h3, .pr-footer h4 { color: #fff !important; margin-bottom: 20px; font-weight: 700; }
        .pr-footer a { color: #94a3b8 !important; text-decoration: none !important; display: block; margin-bottom: 12px; }

        @media (max-width: 768px) {
            .pr-nav { display: none !important; }
            body div.job_listings .job_filters .search_jobs { flex-direction: column !important; padding: 15px !important; border-radius: 20px !important; }
            body div.job_listings .job_filters .search_jobs .search_submit input { width: 100% !important; margin-top: 10px !important; }
            .pr-footer-grid { grid-template-columns: 1fr; text-align: center; }
        }
    </style>
    <?php
}

// ==========================================
// 3. UNIVERSAL HEADER & FOOTER
// ==========================================
add_action( 'wp_body_open', 'pr_universal_header' );
add_action( 'astra_header', 'pr_universal_header' );
function pr_universal_header() {
    static $done = false;

    if ( $done ) {
        return;
    }

    $done = true;

    echo '<header class="pr-header"><div class="pr-container pr-header-inner"><a href="' . esc_url( home_url( '/' ) ) . '" class="pr-logo">Pakistani<span>Rozgar</span></a><nav class="pr-nav"><a href="' . esc_url( home_url( '/' ) ) . '">Home</a><a href="' . esc_url( home_url( '/browse-jobs/' ) ) . '">Browse Jobs</a><a href="' . esc_url( home_url( '/about-us/' ) ) . '">About Us</a><a href="' . esc_url( home_url( '/contact-us/' ) ) . '">Contact</a></nav><a href="' . esc_url( home_url( '/browse-jobs/' ) ) . '" class="pr-btn">View All Jobs</a></div></header>';
}

add_action( 'wp_footer', 'pr_universal_footer', 1 );
function pr_universal_footer() {
    $privacy_url = function_exists( 'get_privacy_policy_url' ) ? get_privacy_policy_url() : '';
    if ( empty( $privacy_url ) ) {
        $privacy_url = home_url( '/privacy-policy/' );
    }

    echo '<footer class="pr-footer"><div class="pr-container pr-footer-grid"><div><h3>Pakistani<span>Rozgar</span></h3><p>' . esc_html__( 'Your trusted portal for verified careers across Pakistan.', 'pakistani-rozgar' ) . '</p></div><div><h4>' . esc_html__( 'Candidates', 'pakistani-rozgar' ) . '</h4><a href="' . esc_url( home_url( '/browse-jobs/' ) ) . '">' . esc_html__( 'All Jobs', 'pakistani-rozgar' ) . '</a></div><div><h4>' . esc_html__( 'Platform', 'pakistani-rozgar' ) . '</h4><a href="' . esc_url( home_url( '/about-us/' ) ) . '">' . esc_html__( 'About Us', 'pakistani-rozgar' ) . '</a><a href="' . esc_url( home_url( '/contact-us/' ) ) . '">' . esc_html__( 'Contact Us', 'pakistani-rozgar' ) . '</a></div><div><h4>' . esc_html__( 'Legal', 'pakistani-rozgar' ) . '</h4><a href="' . esc_url( $privacy_url ) . '">' . esc_html__( 'Privacy Policy', 'pakistani-rozgar' ) . '</a></div></div><div class="pr-container" style="border-top: 1px solid #1e293b; margin-top: 40px; padding-top: 20px; text-align: center;">&copy; ' . esc_html( gmdate( 'Y' ) ) . ' ' . esc_html__( 'Pakistani Rozgar. All rights reserved.', 'pakistani-rozgar' ) . '</div></footer>';
}

// ==========================================
// 4. UNBREAKABLE WHATSAPP BUTTON & SEO
// ==========================================
// Hook 1: Standard WP Job Manager Hook
add_action( 'single_job_listing_end', 'pr_inject_whatsapp_btn', 5 );
add_action( 'single_job_listing_meta_after', 'pr_inject_whatsapp_btn', 5 );
function pr_inject_whatsapp_btn() {
    global $post;

    if ( ! is_singular( 'job_listing' ) ) {
        return;
    }

    $wa_link = 'https://api.whatsapp.com/send?text=' . urlencode( 'Apply for this job: ' . get_the_title( $post->ID ) . ' ' . get_permalink( $post->ID ) );

    echo '<div style="margin-top: 25px; width: 100%; clear: both;"><a href="' . esc_url( $wa_link ) . '" target="_blank" style="display: block; background: #25D366 !important; color: white !important; padding: 18px !important; text-align: center !important; border-radius: 8px !important; font-weight: bold !important; text-decoration: none !important; font-size: 1.2rem !important; box-shadow: 0 4px 10px rgba(37,211,102,0.3) !important;">📲 Share to WhatsApp</a></div>';
}

// Hook 2: Unbreakable JavaScript Injection (If Elementor kills the PHP hooks)
add_action( 'wp_head', 'pr_job_og_meta' );
function pr_job_og_meta() {
    if ( is_singular( 'job_listing' ) ) {
        echo '<meta property="og:title" content="' . esc_attr( get_the_title() ) . ' - Pakistani Rozgar" />';
    }
}

add_action( 'wp_footer', 'pr_inject_whatsapp_btn_js_fallback', 99 );
function pr_inject_whatsapp_btn_js_fallback() {
    if ( is_singular( 'job_listing' ) ) {
        global $post;
        $wa_link = 'https://api.whatsapp.com/send?text=' . urlencode( 'Apply for this job: ' . get_the_title( $post->ID ) . ' ' . get_permalink( $post->ID ) );
        ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var appArea = document.querySelector('.job_application');
                if(!appArea) appArea = document.querySelector('.single_job_listing');
                if(appArea) {
                    var waBtn = document.createElement('a');
                    waBtn.href = "<?php echo esc_js( $wa_link ); ?>";
                    waBtn.target = "_blank";
                    waBtn.innerHTML = "📲 Share to WhatsApp";
                    waBtn.style.cssText = "display: block; background: #25D366 !important; color: white !important; padding: 18px !important; text-align: center !important; border-radius: 8px !important; font-weight: bold !important; text-decoration: none !important; font-size: 1.2rem !important; box-shadow: 0 4px 10px rgba(37,211,102,0.3) !important; margin-top: 25px; width: 100%;";
                    appArea.appendChild(waBtn);
                }
            });
        </script>
        <?php
    }
}

// ==========================================
// 5. BEAUTIFUL PAGE SHORTCODES
// ==========================================
add_shortcode(
    'pr_homepage',
    function() {
        ob_start();
        ?>
    <div style="background: linear-gradient(135deg, #149253, #0a4f2d); padding: 140px 20px; text-align: center; margin-bottom: 50px;">
        <h1 style="color: white; font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 900; margin: 0 0 15px 0;">Find Your Dream Job</h1>
        <p style="color: #e2f5ec; font-size: 1.3rem; max-width: 600px; margin: 0 auto 40px;">Search 1,000+ verified jobs in Government, IT, and Banking.</p>
            <?php echo do_shortcode( '[jobs show_filters="true" show_pagination="false" per_page="0"]' ); ?>
    </div>
    <div class="pr-container">
        <h2 style="text-align: center; font-size: 2.5rem; color: #1e293b; margin-bottom: 40px; font-weight: 800;">Recent Opportunities</h2>
            <?php echo do_shortcode( '[jobs per_page="6" show_filters="false"]' ); ?>
        <div style="text-align: center; margin-top: 50px; margin-bottom: 80px;"><a href="<?php echo esc_url( home_url( '/browse-jobs/' ) ); ?>" class="pr-btn" style="padding: 15px 40px !important; font-size: 1.1rem !important;">Browse All Jobs</a></div>
    </div>
        <?php
        return ob_get_clean();
    }
);

add_shortcode(
    'pr_about_page',
    function() {
        return '<div class="pr-container" style="padding: 80px 20px; max-width: 800px; text-align: center; min-height: 50vh;"><h1 style="color: #149253; font-size: 3rem; font-weight: 900;">About Us</h1><p style="font-size: 1.2rem; line-height: 1.8; color: #475569; margin-top: 20px;">We built Pakistani Rozgar to be the most trusted job platform in the country. Our goal is to connect top talent with verified employers, eliminating scams and making the job hunt incredibly simple.</p></div>';
    }
);

add_shortcode(
    'pr_contact_page',
    function() {
        return '<div class="pr-container" style="padding: 80px 20px; max-width: 600px; min-height: 50vh;"><h1 style="color: #149253; font-size: 3rem; text-align: center; font-weight: 900;">Contact Us</h1><div style="background: white; padding: 40px; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); margin-top: 30px;"><p style="margin-bottom: 20px; color: #475569; text-align: center;">Email us at <strong>support@pakistanirozgar.com</strong> and we will get back to you within 24 hours.</p></div></div>';
    }
);
