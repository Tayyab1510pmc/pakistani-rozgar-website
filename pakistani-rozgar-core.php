<?php
/**
 * Plugin Name: Pakistani Rozgar Core System
 * Plugin URI: https://pakistanirozgar.com
 * Description: Premium responsive UI system for Pakistani Rozgar with universal layout, homepage sections, auto pages, and WhatsApp apply CTA.
 * Version: 7.0.0
 * Author: Tayyab
 * Author URI: https://pakistanirozgar.com
 * Text Domain: pakistani-rozgar
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'pr_get_contact_whatsapp_number' ) ) {
    function pr_get_contact_whatsapp_number() {
        // Replace this sample via the `pr_contact_whatsapp_number` filter in your site/plugin.
        $number = apply_filters( 'pr_contact_whatsapp_number', '923001234567' );
        return preg_replace( '/[^0-9]/', '', (string) $number );
    }
}

if ( ! function_exists( 'pr_get_page_by_title' ) ) {
    function pr_get_page_by_title( $title ) {
        $pages = get_posts(
            array(
                'post_type'      => 'page',
                'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
                'title'          => $title,
                'posts_per_page' => 1,
            )
        );

        return ! empty( $pages ) ? $pages[0] : null;
    }
}

if ( ! function_exists( 'pr_auto_create_website_pages' ) ) {
    add_action( 'admin_init', 'pr_auto_create_website_pages' );

    function pr_auto_create_website_pages() {
        if ( get_option( 'pr_pages_created' ) || get_option( 'pr_v6_pages_created' ) || get_option( 'pr_v7_pages_created' ) ) {
            return;
        }

        $pages = array(
            'Home'        => '[pr_homepage]',
            'Browse Jobs' => '[jobs show_filters="true" show_categories="true" per_page="12"]',
            'About Us'    => '[pr_about_page]',
            'Contact Us'  => '[pr_contact_page]',
        );

        foreach ( $pages as $title => $content ) {
            $page = pr_get_page_by_title( $title );
            if ( ! $page ) {
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

        $home_page = pr_get_page_by_title( 'Home' );
        if ( $home_page ) {
            update_option( 'show_on_front', 'page' );
            update_option( 'page_on_front', (int) $home_page->ID );
        }

        update_option( 'pr_pages_created', true );
        update_option( 'pr_v7_pages_created', true );
    }
}

if ( ! function_exists( 'pr_unbreakable_premium_css' ) ) {
    add_action( 'wp_head', 'pr_unbreakable_premium_css', 9999 );

    function pr_unbreakable_premium_css() {
        echo '<style id="pr-unbreakable-premium-css">
:root{--pr-primary:#149253;--pr-primary-dark:#0f7c45;--pr-whatsapp:#25D366;--pr-text:#0f172a;--pr-text-muted:#64748b;--pr-bg:#f8fafc;--pr-surface:#ffffff;--pr-border:#e2e8f0;--pr-shadow:0 12px 40px rgba(15,23,42,.08);--pr-radius:16px}
html,body{margin:0!important;padding:0!important;background:var(--pr-bg)!important;color:var(--pr-text)!important;font-family:Inter,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif!important}
#masthead,#colophon,.site-header,.site-footer,.ast-main-header-wrap,.ast-above-header-wrap,.ast-below-header-wrap,.ast-footer-overlay,.ast-footer-copyright,.elementor-location-header,.elementor-location-footer,.entry-header{display:none!important}
.ast-theme-transparent-header #masthead{display:none!important}
.elementor-page .site,.site,.ast-container,.site-content,.content-area,.site-main,.elementor-section-wrap,.elementor-location-single,.wp-site-blocks{max-width:100%!important;width:100%!important;margin:0!important;padding:0!important}
body.admin-bar .pr-universal-header{top:32px}
@media(max-width:782px){body.admin-bar .pr-universal-header{top:46px}}
.pr-hide-title .entry-title,.pr-hide-title .page-title{display:none!important}
.pr-universal-header{position:sticky;top:0;z-index:99999;background:rgba(255,255,255,.95)!important;backdrop-filter:blur(10px);border-bottom:1px solid var(--pr-border)}
.pr-wrap{width:min(1200px,92vw);margin:0 auto}
.pr-header-inner{display:flex;gap:1rem;justify-content:space-between;align-items:center;padding:14px 0}
.pr-logo{display:flex;align-items:center;gap:.6rem;font-weight:800;font-size:1.15rem;color:var(--pr-text)!important;text-decoration:none!important}
.pr-logo-badge{width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,var(--pr-primary),#31c07a);display:grid;place-items:center;color:#fff;font-weight:800;box-shadow:0 8px 24px rgba(20,146,83,.35)}
.pr-nav{display:flex;align-items:center;gap:1.1rem}
.pr-nav a{color:#334155!important;text-decoration:none!important;font-weight:600;font-size:.95rem}
.pr-nav a:hover{color:var(--pr-primary)!important}
.pr-cta-btn{display:inline-flex;align-items:center;justify-content:center;padding:.72rem 1.2rem;border-radius:999px;background:var(--pr-primary)!important;color:#fff!important;text-decoration:none!important;font-weight:700;box-shadow:0 8px 24px rgba(20,146,83,.28)}
.pr-main-shell{padding-top:20px}
.pr-homepage{padding-bottom:1.2rem}
.pr-hero{position:relative;overflow:hidden;border-radius:24px;background:linear-gradient(135deg,#0b1f2d 0%,#134e35 40%,#149253 100%);box-shadow:var(--pr-shadow);padding:clamp(2rem,5vw,4rem) clamp(1.2rem,4vw,3rem);color:#fff}
.pr-hero h1{margin:0;font-size:clamp(1.9rem,4.5vw,3.4rem);line-height:1.08;letter-spacing:-.02em}
.pr-hero p{max-width:760px;margin:.9rem 0 1.2rem;color:rgba(255,255,255,.88);font-size:clamp(.98rem,1.8vw,1.12rem)}
.pr-hero .job_listings{margin-top:1rem!important}
.pr-hero .job_filters{background:rgba(255,255,255,.07)!important;padding:0!important;border:none!important}
.pr-hero .job_filters .search_jobs{display:flex!important;flex-wrap:wrap;gap:.75rem;align-items:center;background:#fff!important;border-radius:999px!important;padding:.5rem!important;border:1px solid #dbeafe!important;box-shadow:0 10px 30px rgba(2,6,23,.18)!important;max-width:980px;margin:0 auto!important}
.pr-hero .job_filters .search_jobs>div{margin:0!important;flex:1 1 220px!important}
.pr-hero .job_filters input,.pr-hero .job_filters select{width:100%!important;border:none!important;box-shadow:none!important;background:transparent!important;padding:.65rem .9rem!important;font-size:.98rem!important;color:#0f172a!important}
.pr-hero .job_filters .search_submit{flex:0 0 auto!important;width:auto!important}
.pr-hero .job_filters .search_submit input{border:none!important;background:var(--pr-primary)!important;color:#fff!important;border-radius:999px!important;padding:.85rem 1.4rem!important;font-weight:700!important;cursor:pointer!important}
.pr-hero .job_filters .search_submit input:hover{background:var(--pr-primary-dark)!important}
.pr-categories{padding:1.4rem 0 1.1rem}
.pr-section-title{margin:0 0 .9rem;font-size:1.4rem;color:#0f172a;letter-spacing:-.01em}
.pr-category-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:1rem}
.pr-category-card{display:flex;gap:.8rem;align-items:center;background:var(--pr-surface);border:1px solid var(--pr-border);border-radius:14px;padding:1rem;text-decoration:none!important;color:#0f172a!important;box-shadow:0 8px 20px rgba(15,23,42,.05);transition:transform .2s ease,box-shadow .2s ease,border-color .2s ease}
.pr-category-card:hover{transform:translateY(-4px);border-color:#bfdbfe;box-shadow:0 16px 30px rgba(15,23,42,.12)}
.pr-cat-icon{width:44px;height:44px;border-radius:12px;background:#ecfdf3;display:grid;place-items:center;color:var(--pr-primary)}
.pr-cat-label{font-weight:700}
.pr-jobs-block{padding:.6rem 0 1rem}
.pr-jobs-block .job_listings{margin:0!important}
ul.job_listings{list-style:none!important;padding:0!important}
ul.job_listings li.job_listing{display:grid!important;grid-template-columns:auto 1fr;gap:1rem!important;align-items:start;background:var(--pr-surface)!important;border:1px solid var(--pr-border)!important;border-radius:18px!important;padding:1.1rem!important;margin:0 0 .9rem!important;box-shadow:0 8px 26px rgba(2,6,23,.06);transition:transform .2s ease,box-shadow .2s ease,border-color .2s ease}
ul.job_listings li.job_listing:hover{transform:translateY(-3px);border-color:#cbd5e1;box-shadow:0 16px 34px rgba(2,6,23,.12)}
ul.job_listings li.job_listing a{display:grid!important;grid-template-columns:auto 1fr;gap:1rem!important;align-items:center;width:100%}
ul.job_listings li.job_listing img.company_logo,ul.job_listings li.job_listing .company_logo{width:66px!important;height:66px!important;object-fit:contain!important;border-radius:12px!important;background:#f8fafc;padding:.4rem;border:1px solid #e2e8f0}
ul.job_listings li.job_listing .position h3{margin:0!important;font-size:1.05rem!important;line-height:1.35!important;color:#0f172a!important;font-weight:700!important}
ul.job_listings li.job_listing .company{color:#334155!important;font-weight:600}
ul.job_listings li.job_listing .location{color:var(--pr-text-muted)!important}
ul.job_listings li.job_listing .meta{display:flex!important;flex-wrap:wrap;gap:.45rem;align-items:center}
ul.job_listings li.job_listing .meta .job-type,ul.job_listings li.job_listing .meta li{background:#ecfdf3!important;color:var(--pr-primary-dark)!important;border-radius:999px!important;padding:.25rem .6rem!important;font-size:.78rem!important;border:none!important}
.pr-about-card,.pr-contact-card{background:#fff;border:1px solid var(--pr-border);border-radius:20px;padding:1.4rem;box-shadow:var(--pr-shadow)}
.pr-contact-boxes{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;margin-top:1rem}
.pr-contact-box{background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:1rem}
.pr-form-placeholder{margin-top:1rem;padding:1rem;border-radius:12px;background:#f8fafc;border:1px dashed #94a3b8}
.pr-form-shortcode{display:block;margin:.6rem 0;padding:.7rem .8rem;border-radius:8px;background:#0f172a;color:#e2e8f0;font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;word-break:break-word}
.pr-footer{margin-top:2rem;background:#020617;color:#cbd5e1;padding:2.4rem 0 1rem}
.pr-footer-grid{display:grid;grid-template-columns:1.2fr 1fr 1fr 1fr;gap:1.1rem}
.pr-footer h4{color:#fff;margin:0 0 .7rem;font-size:1rem}
.pr-footer p,.pr-footer a{color:#cbd5e1;text-decoration:none!important;font-size:.92rem;line-height:1.6}
.pr-footer a:hover{color:#86efac!important}
.pr-footer-bottom{margin-top:1.1rem;padding-top:1rem;border-top:1px solid rgba(148,163,184,.24);font-size:.86rem;color:#94a3b8}
.pr-whatsapp-wrap{margin-top:1rem;clear:both}
.pr-whatsapp-apply{display:flex!important;align-items:center;justify-content:center;gap:.55rem;width:100%;text-decoration:none!important;background:var(--pr-whatsapp)!important;color:#fff!important;border:none!important;border-radius:12px!important;padding:.92rem 1rem!important;font-size:1rem!important;font-weight:700!important;box-shadow:0 10px 24px rgba(37,211,102,.32)}
.pr-whatsapp-apply:hover{filter:brightness(.95)}
@media(max-width:1024px){.pr-category-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.pr-footer-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(max-width:820px){.pr-nav{display:none}.pr-header-inner{padding:10px 0}.pr-main-shell{padding-top:14px}.pr-hero{border-radius:18px}.pr-hero .job_filters .search_jobs{border-radius:18px!important}.pr-hero .job_filters .search_submit{width:100%!important;flex:1 1 100%!important}.pr-hero .job_filters .search_submit input{width:100%!important}.pr-contact-boxes{grid-template-columns:1fr}}
@media(max-width:640px){.pr-wrap{width:94vw}.pr-category-grid,.pr-footer-grid{grid-template-columns:1fr}.pr-category-card{padding:.9rem}.pr-logo{font-size:1rem}.pr-logo-badge{width:34px;height:34px}ul.job_listings li.job_listing,ul.job_listings li.job_listing a{grid-template-columns:1fr!important}ul.job_listings li.job_listing img.company_logo,ul.job_listings li.job_listing .company_logo{width:54px!important;height:54px!important}.pr-whatsapp-apply{font-size:.96rem!important}}
</style>';
    }
}

if ( ! function_exists( 'pr_universal_header' ) ) {
    add_action( 'wp_body_open', 'pr_universal_header', 1 );
    add_action( 'astra_header', 'pr_universal_header', 1 );

    function pr_universal_header() {
        if ( is_admin() ) {
            return;
        }
        static $done = false;
        if ( $done ) {
            return;
        }
        $done = true;

        $browse_url  = esc_url( home_url( '/browse-jobs/' ) );
        $home_url    = esc_url( home_url( '/' ) );
        $about_url   = esc_url( home_url( '/about-us/' ) );
        $contact_url = esc_url( home_url( '/contact-us/' ) );

        echo '<header class="pr-universal-header">';
        echo '<div class="pr-wrap pr-header-inner">';
        echo '<a class="pr-logo" href="' . $home_url . '"><span class="pr-logo-badge">PR</span><span>Pakistani Rozgar</span></a>';
        echo '<nav class="pr-nav">';
        echo '<a href="' . $home_url . '">Home</a>';
        echo '<a href="' . $browse_url . '">Browse Jobs</a>';
        echo '<a href="' . $about_url . '">About</a>';
        echo '<a href="' . $contact_url . '">Contact</a>';
        echo '</nav>';
        echo '<a class="pr-cta-btn" href="' . $browse_url . '">Post / Browse Jobs</a>';
        echo '</div>';
        echo '</header>';
    }
}

if ( ! function_exists( 'pr_universal_footer' ) ) {
    add_action( 'wp_footer', 'pr_universal_footer', 2 );

    function pr_universal_footer() {
        if ( is_admin() ) {
            return;
        }
        static $done = false;
        if ( $done ) {
            return;
        }
        $done = true;

        $year = gmdate( 'Y' );
        $contact_whatsapp = pr_get_contact_whatsapp_number();

        echo '<footer class="pr-footer">';
        echo '<div class="pr-wrap">';
        echo '<div class="pr-footer-grid">';
        echo '<div><h4>Pakistani Rozgar</h4><p>Pakistan-focused job portal connecting candidates with verified opportunities in government, private, tech, banking, and remote sectors.</p></div>';
        echo '<div><h4>Quick Links</h4><p><a href="' . esc_url( home_url( '/' ) ) . '">Home</a><br><a href="' . esc_url( home_url( '/browse-jobs/' ) ) . '">Browse Jobs</a><br><a href="' . esc_url( home_url( '/about-us/' ) ) . '">About Us</a></p></div>';
        echo '<div><h4>Legal</h4><p><a href="' . esc_url( home_url( '/privacy-policy/' ) ) . '">Privacy Policy</a><br><a href="' . esc_url( home_url( '/terms-and-conditions/' ) ) . '">Terms &amp; Conditions</a><br><a href="' . esc_url( home_url( '/contact-us/' ) ) . '">Support</a></p></div>';
        echo '<div><h4>Contact</h4><p>Email: <a href="mailto:hello@pakistanirozgar.com">hello@pakistanirozgar.com</a><br>WhatsApp: <a href="https://wa.me/' . esc_attr( $contact_whatsapp ) . '" target="_blank" rel="noopener">+' . esc_html( $contact_whatsapp ) . '</a><br>Location: Pakistan</p></div>';
        echo '</div>';
        echo '<div class="pr-footer-bottom">&copy; ' . esc_html( $year ) . ' Pakistani Rozgar. All rights reserved.</div>';
        echo '</div>';
        echo '</footer>';
    }
}

if ( ! function_exists( 'pr_homepage_shortcode' ) ) {
    add_shortcode( 'pr_homepage', 'pr_homepage_shortcode' );

    function pr_homepage_shortcode() {
        $categories = array(
            array(
                'label' => 'Government Jobs',
                'icon'  => '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 10l9-5 9 5"/><path d="M5 10v8h14v-8"/><path d="M3 18h18"/></svg>',
                'url'   => home_url( '/browse-jobs/?search_keywords=Government' ),
            ),
            array(
                'label' => 'IT & Software',
                'icon'  => '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8"/></svg>',
                'url'   => home_url( '/browse-jobs/?search_keywords=Software' ),
            ),
            array(
                'label' => 'Banking Jobs',
                'icon'  => '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 10h18"/><path d="M7 14h.01M11 14h6"/><rect x="2" y="5" width="20" height="14" rx="2"/></svg>',
                'url'   => home_url( '/browse-jobs/?search_keywords=Bank' ),
            ),
            array(
                'label' => 'Remote Jobs',
                'icon'  => '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a15 15 0 010 18"/></svg>',
                'url'   => home_url( '/browse-jobs/?search_keywords=Remote' ),
            ),
        );

        ob_start();
        echo '<div class="pr-wrap pr-main-shell pr-homepage pr-hide-title">';
        echo '<section class="pr-hero">';
        echo '<h1>Find Your Dream Job in Pakistan</h1>';
        echo '<p>Discover verified opportunities across Pakistan with fast search filters, polished job cards, and a clean mobile-first experience built for serious applicants.</p>';
        echo do_shortcode( '[jobs show_filters="true" show_categories="true" show_pagination="false" per_page="0"]' );
        echo '</section>';

        echo '<section class="pr-categories">';
        echo '<h2 class="pr-section-title">Browse by Popular Categories</h2>';
        echo '<div class="pr-category-grid">';
        foreach ( $categories as $category ) {
            echo '<a class="pr-category-card" href="' . esc_url( $category['url'] ) . '">';
            echo '<span class="pr-cat-icon" aria-hidden="true">' . wp_kses(
                $category['icon'],
                array(
                    'svg'    => array(
                        'viewBox'      => true,
                        'width'        => true,
                        'height'       => true,
                        'fill'         => true,
                        'stroke'       => true,
                        'stroke-width' => true,
                    ),
                    'path'   => array(
                        'd' => true,
                    ),
                    'rect'   => array(
                        'x'      => true,
                        'y'      => true,
                        'width'  => true,
                        'height' => true,
                        'rx'     => true,
                    ),
                    'circle' => array(
                        'cx' => true,
                        'cy' => true,
                        'r'  => true,
                    ),
                )
            ) . '</span>';
            echo '<span class="pr-cat-label">' . esc_html( $category['label'] ) . '</span>';
            echo '</a>';
        }
        echo '</div>';
        echo '</section>';

        echo '<section class="pr-jobs-block">';
        echo '<h2 class="pr-section-title">Latest Jobs</h2>';
        echo do_shortcode( '[jobs show_filters="false" show_pagination="true" per_page="12"]' );
        echo '</section>';
        echo '</div>';

        return ob_get_clean();
    }
}

if ( ! function_exists( 'pr_about_page_shortcode' ) ) {
    add_shortcode( 'pr_about_page', 'pr_about_page_shortcode' );

    function pr_about_page_shortcode() {
        ob_start();
        echo '<div class="pr-wrap pr-main-shell pr-hide-title"><div class="pr-about-card">';
        echo '<h1 class="pr-section-title">About Pakistani Rozgar</h1>';
        echo '<p>Pakistani Rozgar is a dedicated employment platform helping job seekers find opportunities that match their skills and career goals. We focus on clarity, speed, and trust so candidates can apply confidently.</p>';
        echo '<p>Employers can publish jobs, candidates can discover relevant listings quickly, and everyone benefits from a responsive, professional experience across desktop and mobile devices.</p>';
        echo '</div></div>';
        return ob_get_clean();
    }
}

if ( ! function_exists( 'pr_contact_page_shortcode' ) ) {
    add_shortcode( 'pr_contact_page', 'pr_contact_page_shortcode' );

    function pr_contact_page_shortcode() {
        $contact_whatsapp = pr_get_contact_whatsapp_number();
        ob_start();
        echo '<div class="pr-wrap pr-main-shell pr-hide-title"><div class="pr-contact-card">';
        echo '<h1 class="pr-section-title">Contact Us</h1>';
        echo '<p>Need hiring support or have a question? Reach our team anytime.</p>';
        echo '<div class="pr-contact-boxes">';
        echo '<div class="pr-contact-box"><strong>Email Support</strong><br><a href="mailto:hello@pakistanirozgar.com">hello@pakistanirozgar.com</a></div>';
        echo '<div class="pr-contact-box"><strong>WhatsApp</strong><br><a href="https://wa.me/' . esc_attr( $contact_whatsapp ) . '" target="_blank" rel="noopener">+' . esc_html( $contact_whatsapp ) . '</a></div>';
        echo '</div>';
        echo '<div class="pr-form-placeholder">';
        echo '<strong>Contact Form Area</strong>';
        echo '<p>If you use WPForms, paste this shortcode on this page or keep this shortcode in the page content:</p>';
        echo '<code class="pr-form-shortcode">[wpforms id="YOUR_FORM_ID" title="false"]</code>';
        echo '<p>Fallback: If no form plugin is installed, users can still contact you by email: <a href="mailto:hello@pakistanirozgar.com">hello@pakistanirozgar.com</a></p>';
        echo '</div>';
        echo '</div></div>';
        return ob_get_clean();
    }
}

if ( ! function_exists( 'pr_inject_whatsapp_btn' ) ) {
    add_action( 'single_job_listing_end', 'pr_inject_whatsapp_btn', 20 );
    add_action( 'single_job_listing_meta_after', 'pr_inject_whatsapp_btn', 20 );

    function pr_inject_whatsapp_btn() {
        if ( ! is_singular( 'job_listing' ) ) {
            return;
        }

        static $done = false;
        if ( $done ) {
            return;
        }
        $done = true;

        $post_id = get_the_ID();
        if ( ! $post_id ) {
            return;
        }

        $message = sprintf(
            /* translators: 1: job title 2: job url */
            __( 'I want to apply for this job: %1$s %2$s', 'pakistani-rozgar' ),
            get_the_title( $post_id ),
            get_permalink( $post_id )
        );

        $wa_link = 'https://api.whatsapp.com/send?text=' . rawurlencode( $message );

        echo '<div class="pr-whatsapp-wrap">';
        echo '<a class="pr-whatsapp-apply" href="' . esc_url( $wa_link ) . '" target="_blank" rel="noopener noreferrer">';
        echo '<svg width="20" height="20" viewBox="0 0 32 32" aria-hidden="true"><path fill="currentColor" d="M16.01 3.2C8.94 3.2 3.2 8.9 3.2 16c0 2.48.7 4.86 2.02 6.94L3.02 29l6.23-2.1A12.71 12.71 0 0 0 16.01 28.8c7.07 0 12.79-5.73 12.79-12.8 0-7.1-5.72-12.8-12.79-12.8Zm0 23.25a10.4 10.4 0 0 1-5.3-1.45l-.38-.22-3.7 1.25 1.2-3.61-.24-.37A10.44 10.44 0 0 1 5.58 16c0-5.74 4.68-10.42 10.43-10.42 5.76 0 10.42 4.68 10.42 10.42 0 5.76-4.66 10.45-10.42 10.45Zm5.71-7.8c-.3-.15-1.8-.9-2.08-1-.28-.1-.49-.15-.69.15-.2.3-.78 1-.95 1.2-.18.2-.35.22-.66.07a8.45 8.45 0 0 1-2.48-1.53 9.36 9.36 0 0 1-1.72-2.14c-.18-.3-.02-.46.13-.61.14-.14.3-.35.45-.52.15-.18.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.69-1.67-.95-2.29-.25-.6-.5-.5-.69-.51h-.58c-.2 0-.51.08-.78.37-.26.3-1 1-.99 2.42 0 1.42 1.02 2.79 1.16 2.98.15.2 2.03 3.11 4.94 4.36.69.29 1.23.47 1.65.6.69.22 1.31.19 1.8.12.55-.08 1.8-.74 2.06-1.46.25-.72.25-1.34.17-1.46-.07-.12-.28-.2-.58-.35Z"/></svg>';
        echo esc_html__( 'Apply via WhatsApp', 'pakistani-rozgar' );
        echo '</a>';
        echo '</div>';
    }
}
