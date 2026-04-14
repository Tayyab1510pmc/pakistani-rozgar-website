<?php
/**
 * Plugin Name:  Pakistani Rozgar Core
 * Plugin URI:   https://pakistanirozgar.com
 * Description:  The all-in-one core plugin for PakistaniRozgar.com. Auto-creates job categories, demo jobs, pages, and a navigation menu on activation. Injects premium CSS and provides shortcodes for Elementor layouts.
 * Version:      1.0.0
 * Author:       PakistaniRozgar Team
 * Author URI:   https://pakistanirozgar.com
 * License:      GPL-2.0-or-later
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  pakistani-rozgar-core
 *
 * @package PakistaniRozgarCore
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Prevent direct file access.
}

// ---------------------------------------------------------------------------
// 1. ACTIVATION HOOK
// ---------------------------------------------------------------------------

register_activation_hook( __FILE__, 'pr_activate_plugin' );

function pr_activate_plugin() {
    pr_create_job_categories();
    pr_create_demo_jobs();
    pr_create_pages();
    pr_create_nav_menu();

    // Flush rewrite rules so custom post type permalinks work immediately.
    flush_rewrite_rules();
}

// ---------------------------------------------------------------------------
// 2. JOB CATEGORIES
// ---------------------------------------------------------------------------

function pr_create_job_categories() {
    $categories = array(
        array(
            'name' => 'Government Jobs',
            'slug' => 'government-jobs',
            'description' => 'Federal & provincial government vacancies including FPSC, PPSC and NTS.',
        ),
        array(
            'name' => 'IT & Software',
            'slug' => 'it-software',
            'description' => 'Web development, mobile apps, data science and tech roles.',
        ),
        array(
            'name' => 'Banking & Finance',
            'slug' => 'banking-finance',
            'description' => 'Banking, insurance, accounting and financial services positions.',
        ),
        array(
            'name' => 'Armed Forces',
            'slug' => 'armed-forces',
            'description' => 'Pakistan Army, Navy, Air Force and paramilitary recruitment.',
        ),
        array(
            'name' => 'Remote Jobs',
            'slug' => 'remote-jobs',
            'description' => 'Work-from-home and fully remote opportunities across all sectors.',
        ),
    );

    foreach ( $categories as $cat ) {
        if ( ! term_exists( $cat['name'], 'job_listing_category' ) ) {
            wp_insert_term(
                $cat['name'],
                'job_listing_category',
                array(
                    'slug'        => $cat['slug'],
                    'description' => $cat['description'],
                )
            );
        }
    }
}

// ---------------------------------------------------------------------------
// 3. DEMO JOBS
// ---------------------------------------------------------------------------

function pr_create_demo_jobs() {
    $jobs = array(
        array(
            'title'       => 'Junior Software Engineer – Remote',
            'content'     => '<p>We are looking for a passionate <strong>Junior Software Engineer</strong> to join our growing team. You will work on building scalable web applications using modern JavaScript frameworks.</p><h3>Requirements</h3><ul><li>BS/MS in Computer Science or equivalent</li><li>Proficiency in HTML, CSS, JavaScript (React or Vue preferred)</li><li>Basic understanding of REST APIs and Git</li></ul><h3>What We Offer</h3><ul><li>Competitive salary (PKR 60,000 – 90,000/month)</li><li>100% remote work</li><li>Annual bonus and learning allowance</li></ul>',
            'category'    => 'it-software',
            'type'        => 'full-time',
            'location'    => 'Remote – Pakistan',
            'company'     => 'TechSol Pakistan',
            'company_url' => 'https://example.com',
        ),
        array(
            'title'       => 'FPSC Assistant Director – Finance Division',
            'content'     => '<p>The Federal Public Service Commission invites applications from qualified candidates for the post of <strong>Assistant Director (Finance)</strong> in BS-17.</p><h3>Eligibility</h3><ul><li>Master\'s degree in Finance, Economics or Business Administration</li><li>Age: 22–30 years (relaxable for government employees)</li></ul><h3>Salary</h3><p>As per government pay scale BPS-17 (approximately PKR 45,000 – 55,000/month plus allowances).</p>',
            'category'    => 'government-jobs',
            'type'        => 'full-time',
            'location'    => 'Islamabad',
            'company'     => 'Federal Public Service Commission (FPSC)',
            'company_url' => 'https://fpsc.gov.pk',
        ),
        array(
            'title'       => 'Branch Operations Manager – HBL',
            'content'     => '<p><strong>Habib Bank Limited (HBL)</strong> is seeking a dynamic Branch Operations Manager to oversee branch banking operations and deliver excellence in customer service.</p><h3>Key Responsibilities</h3><ul><li>Manage day-to-day branch operations and compliance</li><li>Lead, train and evaluate branch staff</li><li>Achieve deposit growth and cross-sell targets</li></ul><h3>Benefits</h3><ul><li>Attractive salary package</li><li>Provident fund and gratuity</li><li>Medical and life insurance</li></ul>',
            'category'    => 'banking-finance',
            'type'        => 'full-time',
            'location'    => 'Karachi',
            'company'     => 'Habib Bank Limited (HBL)',
            'company_url' => 'https://hbl.com',
        ),
        array(
            'title'       => 'Pakistan Army – Commissioned Officer (Regular Commission)',
            'content'     => '<p>Pakistan Army invites applications from eligible male/female graduates for <strong>Regular Commission</strong> through the Inter Services Selection Board (ISSB).</p><h3>Eligibility</h3><ul><li>Age: 17–22 years</li><li>Education: Intermediate (FSc/ICS) for Cadet College / Graduation for PMA Long Course</li><li>Medical fitness as per Army standards</li></ul>',
            'category'    => 'armed-forces',
            'type'        => 'full-time',
            'location'    => 'All Pakistan',
            'company'     => 'Pakistan Army',
            'company_url' => 'https://joinpakarmy.gov.pk',
        ),
        array(
            'title'       => 'Senior React Developer – Full Remote',
            'content'     => '<p>A fast-growing SaaS startup is looking for a <strong>Senior React Developer</strong> to lead frontend development. This is a 100% remote role with an international salary.</p><h3>Requirements</h3><ul><li>4+ years of React.js experience</li><li>Strong understanding of Redux, Hooks, and TypeScript</li><li>Experience with RESTful and GraphQL APIs</li><li>Excellent English communication skills</li></ul><h3>Compensation</h3><p>USD 1,500 – 2,500/month (paid in PKR equivalent or direct USD).</p>',
            'category'    => 'remote-jobs',
            'type'        => 'full-time',
            'location'    => 'Remote – Worldwide',
            'company'     => 'GlobalTech Inc.',
            'company_url' => 'https://example.com',
        ),
        array(
            'title'       => 'Data Analyst – E-commerce Startup',
            'content'     => '<p>We are looking for a detail-oriented <strong>Data Analyst</strong> to transform raw data into actionable business insights for our growing e-commerce platform.</p><h3>Responsibilities</h3><ul><li>Analyse sales, marketing and customer data using Python/SQL</li><li>Build dashboards in Power BI or Tableau</li><li>Present findings to leadership in clear, concise reports</li></ul><h3>Package</h3><ul><li>PKR 70,000 – 110,000/month</li><li>Flexible hybrid work (2 days office, 3 days remote)</li></ul>',
            'category'    => 'it-software',
            'type'        => 'full-time',
            'location'    => 'Lahore (Hybrid)',
            'company'     => 'ShopEase Pakistan',
            'company_url' => 'https://example.com',
        ),
    );

    foreach ( $jobs as $job ) {
        // Avoid creating duplicate demo jobs on repeated activation.
        $existing = get_posts(
            array(
                'post_type'      => 'job_listing',
                'post_status'    => 'publish',
                'title'          => $job['title'],
                'posts_per_page' => 1,
            )
        );

        if ( ! empty( $existing ) ) {
            continue;
        }

        $post_id = wp_insert_post(
            array(
                'post_title'   => $job['title'],
                'post_content' => $job['content'],
                'post_status'  => 'publish',
                'post_type'    => 'job_listing',
            )
        );

        if ( ! is_wp_error( $post_id ) ) {
            // Assign job category.
            $term = get_term_by( 'slug', $job['category'], 'job_listing_category' );
            if ( $term ) {
                wp_set_object_terms( $post_id, $term->term_id, 'job_listing_category' );
            }

            // Assign job type.
            $type_term = get_term_by( 'slug', $job['type'], 'job_listing_type' );
            if ( $type_term ) {
                wp_set_object_terms( $post_id, $type_term->term_id, 'job_listing_type' );
            }

            // Set WP Job Manager meta fields.
            update_post_meta( $post_id, '_job_location',   $job['location'] );
            update_post_meta( $post_id, '_company_name',   $job['company'] );
            update_post_meta( $post_id, '_company_website', $job['company_url'] );
            update_post_meta( $post_id, '_filled',          0 );
            update_post_meta( $post_id, '_featured',        0 );

            // Set expiry 90 days from now.
            update_post_meta( $post_id, '_job_expires', date( 'Y-m-d', strtotime( '+90 days' ) ) );
        }
    }
}

// ---------------------------------------------------------------------------
// 4. PAGES
// ---------------------------------------------------------------------------

function pr_create_pages() {
    $pages = array(
        array(
            'title'   => 'Home',
            'slug'    => 'home',
            'content' => '[pr_homepage_layout]',
        ),
        array(
            'title'   => 'Browse Jobs',
            'slug'    => 'browse-jobs',
            'content' => '[jobs show_filters="true" show_pagination="true" per_page="12"]',
        ),
        array(
            'title'   => 'About Us',
            'slug'    => 'about-us',
            'content' => '<h2>About PakistaniRozgar.com</h2>
<p>PakistaniRozgar.com is Pakistan\'s most trusted online job portal, connecting thousands of talented professionals with top employers across government, private, and remote sectors every day.</p>
<p>Our mission is simple: <strong>to bridge the gap between talent and opportunity</strong> in Pakistan. Whether you are a fresh graduate looking for your first job, or a seasoned professional seeking the next step in your career, PakistaniRozgar.com is your one-stop destination.</p>
<h3>Why Choose Us?</h3>
<ul>
  <li>✅ Verified job listings from trusted employers</li>
  <li>✅ Government, private, banking, IT and remote opportunities</li>
  <li>✅ Free job alerts delivered straight to your inbox</li>
  <li>✅ Mobile-friendly and easy to use</li>
</ul>',
        ),
        array(
            'title'   => 'Contact Us',
            'slug'    => 'contact-us',
            'content' => '<h2>Get In Touch</h2>
<p>Have a question, partnership enquiry, or feedback? We would love to hear from you.</p>
<ul>
  <li>📧 <strong>Email:</strong> info@pakistanirozgar.com</li>
  <li>🌐 <strong>Website:</strong> https://pakistanirozgar.com</li>
  <li>📍 <strong>Address:</strong> Lahore, Punjab, Pakistan</li>
</ul>
<p>You can also use the contact form below to send us a message directly.</p>
[contact-form-7 id="contact-form" title="Contact form"]',
        ),
    );

    $created_ids = array();

    foreach ( $pages as $page ) {
        $existing = get_page_by_path( $page['slug'] );

        if ( $existing ) {
            $created_ids[ $page['slug'] ] = $existing->ID;
            continue;
        }

        $page_id = wp_insert_post(
            array(
                'post_title'   => $page['title'],
                'post_name'    => $page['slug'],
                'post_content' => $page['content'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
            )
        );

        if ( ! is_wp_error( $page_id ) ) {
            $created_ids[ $page['slug'] ] = $page_id;
        }
    }

    // Set Home page as the WordPress static front page.
    if ( isset( $created_ids['home'] ) ) {
        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', $created_ids['home'] );
    }

    // Set Browse Jobs as the Posts page (used by WP Job Manager as job archive).
    if ( isset( $created_ids['browse-jobs'] ) ) {
        update_option( 'job_manager_jobs_page_id', $created_ids['browse-jobs'] );
    }
}

// ---------------------------------------------------------------------------
// 5. NAVIGATION MENU
// ---------------------------------------------------------------------------

function pr_create_nav_menu() {
    $menu_name = 'Main Menu';

    if ( ! wp_get_nav_menu_object( $menu_name ) ) {
        $menu_id = wp_create_nav_menu( $menu_name );

        $menu_pages = array(
            array( 'slug' => 'home',       'label' => 'Home' ),
            array( 'slug' => 'browse-jobs', 'label' => 'Browse Jobs' ),
            array( 'slug' => 'about-us',   'label' => 'About Us' ),
            array( 'slug' => 'contact-us', 'label' => 'Contact Us' ),
        );

        foreach ( $menu_pages as $mp ) {
            $page = get_page_by_path( $mp['slug'] );
            if ( $page ) {
                wp_update_nav_menu_item(
                    $menu_id,
                    0,
                    array(
                        'menu-item-title'     => $mp['label'],
                        'menu-item-object'    => 'page',
                        'menu-item-object-id' => $page->ID,
                        'menu-item-type'      => 'post_type',
                        'menu-item-status'    => 'publish',
                    )
                );
            }
        }

        // Assign the menu to the primary location if the theme supports it.
        $locations = get_theme_mod( 'nav_menu_locations' );
        if ( is_array( $locations ) ) {
            foreach ( array( 'primary', 'main', 'primary-menu', 'header-menu' ) as $location ) {
                if ( array_key_exists( $location, $locations ) ) {
                    $locations[ $location ] = $menu_id;
                }
            }
            set_theme_mod( 'nav_menu_locations', $locations );
        }
    }
}

// ---------------------------------------------------------------------------
// 6. PREMIUM CSS INJECTION
// ---------------------------------------------------------------------------

add_action( 'wp_enqueue_scripts', 'pr_enqueue_styles' );

function pr_enqueue_styles() {
    wp_add_inline_style(
        'wp-block-library', // Attach to a style WordPress always loads.
        pr_get_premium_css()
    );
}

function pr_get_premium_css() {
    return '
/* ============================================================
   PakistaniRozgar.com – Premium WP Job Manager Stylesheet
   Inspired by Rozee.pk & Indeed.com – Mobile-first & responsive
   ============================================================ */

/* ---- Global variables ---- */
:root {
    --pr-green:       #149253;
    --pr-green-dark:  #0d703f;
    --pr-green-light: #e8f5ee;
    --pr-text:        #111827;
    --pr-muted:       #6b7280;
    --pr-border:      #e5e7eb;
    --pr-radius:      14px;
    --pr-shadow:      0 4px 16px rgba(20,146,83,0.08);
    --pr-shadow-hover:0 16px 32px rgba(20,146,83,0.14);
}

/* ---- Job listing cards ---- */
ul.job_listings {
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
}

ul.job_listings li.job_listing {
    background: #ffffff;
    border: 1px solid var(--pr-border);
    border-radius: var(--pr-radius);
    padding: 0 !important;
    margin: 0 !important;
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    overflow: hidden;
}

ul.job_listings li.job_listing:hover {
    transform: translateY(-5px);
    box-shadow: var(--pr-shadow-hover);
    border-color: var(--pr-green);
}

ul.job_listings li.job_listing a {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 22px 24px;
    text-decoration: none !important;
    color: inherit !important;
}

/* Company logo / avatar */
ul.job_listings li.job_listing .company_logo {
    width: 56px;
    height: 56px;
    min-width: 56px;
    border-radius: 10px;
    border: 1px solid var(--pr-border);
    object-fit: contain;
    background: var(--pr-green-light);
    padding: 4px;
}

/* Text block */
ul.job_listings li.job_listing .position {
    flex: 1;
    min-width: 0;
}

ul.job_listings li.job_listing .position h3 {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--pr-text) !important;
    margin: 0 0 4px !important;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

ul.job_listings li.job_listing .position .company {
    font-size: 0.9rem;
    color: var(--pr-muted);
    margin: 0 0 8px !important;
}

/* Meta badges (location, type) */
ul.job_listings li.job_listing ul.meta {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 6px 10px !important;
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
}

ul.job_listings li.job_listing ul.meta li {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 0.8rem;
    color: var(--pr-muted);
    background: #f9fafb;
    border: 1px solid var(--pr-border);
    border-radius: 20px;
    padding: 3px 10px;
}

ul.job_listings li.job_listing ul.meta li.job-type {
    background: var(--pr-green-light);
    border-color: rgba(20,146,83,0.2);
    color: var(--pr-green);
    font-weight: 600;
}

/* Featured ribbon */
ul.job_listings li.job_listing.featured {
    border-color: var(--pr-green);
    position: relative;
}

ul.job_listings li.job_listing.featured::before {
    content: "★ Featured";
    position: absolute;
    top: 12px;
    right: 12px;
    background: var(--pr-green);
    color: #fff;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    letter-spacing: 0.03em;
}

/* ---- Search / filter bar ---- */
.job_filters {
    background: #ffffff;
    border: 1px solid var(--pr-border);
    border-radius: var(--pr-radius);
    padding: 24px;
    margin-bottom: 28px;
    box-shadow: var(--pr-shadow);
}

.job_filters .search_jobs {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: flex-end;
}

.job_filters .search_jobs > div {
    flex: 1 1 220px;
}

.job_filters .search_jobs label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--pr-text);
    margin-bottom: 6px;
}

.job_filters .search_jobs input[type="text"],
.job_filters .search_jobs select {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid var(--pr-border);
    border-radius: 8px;
    font-size: 0.95rem;
    background: #fcfcfc;
    color: var(--pr-text);
    transition: border-color 0.25s;
    box-sizing: border-box;
}

.job_filters .search_jobs input[type="text"]:focus,
.job_filters .search_jobs select:focus {
    border-color: var(--pr-green);
    outline: none;
    background: #fff;
}

.job_filters .search_jobs .search_submit input,
.job_filters .search_jobs button[type="submit"] {
    width: 100%;
    padding: 13px 28px;
    background: var(--pr-green);
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.25s, transform 0.15s;
}

.job_filters .search_jobs .search_submit input:hover,
.job_filters .search_jobs button[type="submit"]:hover {
    background: var(--pr-green-dark);
    transform: scale(1.02);
}

/* ---- Single job listing page ---- */
.single-job_listing .job-manager-job-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    list-style: none;
    padding: 0;
    margin: 0 0 24px;
}

.single-job_listing .job-manager-job-meta li {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.9rem;
    color: var(--pr-muted);
    background: #f9fafb;
    border: 1px solid var(--pr-border);
    border-radius: 20px;
    padding: 5px 14px;
}

/* ---- Apply button ---- */
.job_apply .application_button,
a.application_button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--pr-green);
    color: #ffffff !important;
    padding: 14px 36px;
    border-radius: 50px;
    font-size: 1.05rem;
    font-weight: 700;
    text-decoration: none !important;
    transition: background 0.25s, transform 0.15s;
    border: none;
    cursor: pointer;
}

.job_apply .application_button:hover,
a.application_button:hover {
    background: var(--pr-green-dark);
    transform: scale(1.02);
}

/* ---- Pagination ---- */
.job-manager-pagination ul {
    display: flex;
    list-style: none;
    gap: 6px;
    padding: 0;
    margin: 36px 0 0;
    justify-content: center;
}

.job-manager-pagination ul li a,
.job-manager-pagination ul li span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    border: 1px solid var(--pr-border);
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--pr-text);
    text-decoration: none;
    transition: background 0.2s, color 0.2s, border-color 0.2s;
}

.job-manager-pagination ul li a:hover {
    background: var(--pr-green-light);
    border-color: var(--pr-green);
    color: var(--pr-green);
}

.job-manager-pagination ul li.active span {
    background: var(--pr-green);
    border-color: var(--pr-green);
    color: #ffffff;
}

/* ---- Responsive ---- */
@media (max-width: 640px) {
    ul.job_listings {
        grid-template-columns: 1fr;
    }

    .job_filters .search_jobs {
        flex-direction: column;
    }

    .job_filters .search_jobs > div {
        flex: 1 1 100%;
    }
}
';
}

// ---------------------------------------------------------------------------
// 7. SHORTCODES
// ---------------------------------------------------------------------------

add_shortcode( 'pr_homepage_layout', 'pr_homepage_layout_shortcode' );

function pr_homepage_layout_shortcode( $atts ) {
    ob_start();
    ?>
<style>
/* ===== Homepage Layout Shortcode Styles ===== */
#pr-home-layout {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #333;
    background-color: #f4f7f6;
    margin: 0;
}

/* Hero */
.pr-hero {
    position: relative;
    background: linear-gradient(
        135deg,
        rgba(20,146,83,0.96),
        rgba(10,79,45,0.93)
    ),
    url('https://images.unsplash.com/photo-1497215728101-856f4ea42174?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
    padding: clamp(80px, 10vw, 140px) 20px clamp(110px, 13vw, 170px);
    text-align: center;
    border-radius: 0 0 clamp(30px, 5vw, 60px) clamp(30px, 5vw, 60px);
}

.pr-hero h1 {
    color: #ffffff !important;
    font-size: clamp(2rem, 5vw, 3.8rem);
    font-weight: 800;
    margin: 0 auto 18px;
    line-height: 1.15;
    max-width: 860px;
    text-shadow: 0 4px 12px rgba(0,0,0,0.25);
}

.pr-hero p {
    color: #d1f0e1 !important;
    font-size: clamp(1rem, 2.5vw, 1.3rem);
    max-width: 660px;
    margin: 0 auto;
}

/* Floating search box */
.pr-search-wrap {
    max-width: 1020px;
    margin: -70px auto 0;
    background: #fff;
    padding: clamp(20px, 4vw, 32px);
    border-radius: 16px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.09);
    position: relative;
    z-index: 10;
    width: 92%;
    box-sizing: border-box;
}

.pr-search-wrap h3 {
    color: #1a1a1a;
    font-size: 1.2rem;
    font-weight: 700;
    margin: 0 0 18px;
}

/* Container */
.pr-wrap {
    max-width: 1180px;
    margin: 0 auto;
    padding: clamp(50px, 7vw, 80px) 20px;
}

/* Section header */
.pr-sh {
    text-align: center;
    margin-bottom: 46px;
}

.pr-sh h2 {
    font-size: clamp(1.8rem, 4vw, 2.6rem);
    color: #111827;
    font-weight: 800;
    margin: 0 0 8px;
}

.pr-sh p {
    color: #6b7280;
    font-size: 1.05rem;
    margin: 0;
}

/* Category grid */
.pr-cat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
    gap: 22px;
}

.pr-cat-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    padding: 32px 18px;
    text-align: center;
    text-decoration: none !important;
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: all 0.28s cubic-bezier(0.4,0,0.2,1);
}

.pr-cat-card:hover {
    transform: translateY(-7px);
    box-shadow: 0 18px 28px rgba(20,146,83,0.1);
    border-color: #149253;
}

.pr-cat-icon {
    width: 66px;
    height: 66px;
    background: #e8f5ee;
    color: #149253;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.9rem;
    margin-bottom: 18px;
    transition: background 0.28s, color 0.28s;
}

.pr-cat-card:hover .pr-cat-icon {
    background: #149253;
    color: #fff;
}

.pr-cat-card h4 {
    color: #111827;
    font-size: 1.15rem;
    font-weight: 700;
    margin: 0 0 6px;
}

.pr-cat-card span {
    color: #6b7280;
    font-size: 0.88rem;
}

/* Jobs section */
.pr-jobs-bg {
    background: #fff;
    padding: clamp(50px, 7vw, 80px) 20px;
    border-top: 1px solid #e5e7eb;
}

/* CTA button */
.pr-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #149253;
    color: #fff !important;
    padding: 15px 38px;
    border-radius: 50px;
    font-size: 1.05rem;
    font-weight: 700;
    text-decoration: none !important;
    margin-top: 36px;
    transition: background 0.25s, transform 0.15s;
}

.pr-btn:hover {
    background: #0d703f;
    transform: scale(1.02);
}

/* Stats bar */
.pr-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    margin-top: 30px;
}

.pr-stat {
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 12px;
    padding: 14px 28px;
    text-align: center;
    backdrop-filter: blur(4px);
}

.pr-stat strong {
    display: block;
    color: #fff;
    font-size: 1.8rem;
    font-weight: 800;
    line-height: 1;
}

.pr-stat span {
    color: #d1f0e1;
    font-size: 0.85rem;
    margin-top: 4px;
    display: block;
}

@media (max-width: 600px) {
    .pr-cat-grid { grid-template-columns: 1fr 1fr; }
    .pr-stat { flex: 1 1 140px; }
}

@media (max-width: 380px) {
    .pr-cat-grid { grid-template-columns: 1fr; }
}
</style>

<div id="pr-home-layout">

    <!-- HERO -->
    <div class="pr-hero">
        <h1>Pakistan&#039;s #1 Job Portal.<br>Your Future Starts Here.</h1>
        <p>Discover thousands of government, private, banking, IT &amp; remote jobs across Pakistan.</p>

        <div class="pr-stats">
            <div class="pr-stat">
                <strong>10,000+</strong>
                <span>Active Jobs</span>
            </div>
            <div class="pr-stat">
                <strong>500+</strong>
                <span>Top Employers</span>
            </div>
            <div class="pr-stat">
                <strong>250K+</strong>
                <span>Job Seekers</span>
            </div>
            <div class="pr-stat">
                <strong>Daily</strong>
                <span>New Listings</span>
            </div>
        </div>
    </div>

    <!-- SEARCH BOX -->
    <div class="pr-search-wrap">
        <h3>&#128269; Search Jobs in Pakistan</h3>
        <?php echo do_shortcode('[jobs show_filters="true" show_pagination="false" per_page="0"]'); ?>
    </div>

    <!-- CATEGORIES -->
    <div class="pr-wrap">
        <div class="pr-sh">
            <h2>Explore by Category</h2>
            <p>Pakistan&#039;s highest-demand sectors in one click.</p>
        </div>

        <div class="pr-cat-grid">
            <a href="<?php echo esc_url( home_url( '/jobs/?job_categories=government-jobs' ) ); ?>" class="pr-cat-card">
                <div class="pr-cat-icon"><i class="fas fa-landmark"></i></div>
                <h4>Government Jobs</h4>
                <span>FPSC, PPSC, NTS</span>
            </a>
            <a href="<?php echo esc_url( home_url( '/jobs/?job_categories=it-software' ) ); ?>" class="pr-cat-card">
                <div class="pr-cat-icon"><i class="fas fa-laptop-code"></i></div>
                <h4>IT &amp; Software</h4>
                <span>Web, App, Data</span>
            </a>
            <a href="<?php echo esc_url( home_url( '/jobs/?job_categories=banking-finance' ) ); ?>" class="pr-cat-card">
                <div class="pr-cat-icon"><i class="fas fa-building-columns"></i></div>
                <h4>Banking &amp; Finance</h4>
                <span>Auditing, Accounting</span>
            </a>
            <a href="<?php echo esc_url( home_url( '/jobs/?job_categories=armed-forces' ) ); ?>" class="pr-cat-card">
                <div class="pr-cat-icon"><i class="fas fa-shield-halved"></i></div>
                <h4>Armed Forces</h4>
                <span>Army, Navy, PAF</span>
            </a>
            <a href="<?php echo esc_url( home_url( '/jobs/?job_categories=remote-jobs' ) ); ?>" class="pr-cat-card">
                <div class="pr-cat-icon"><i class="fas fa-house-laptop"></i></div>
                <h4>Remote Jobs</h4>
                <span>Work From Home</span>
            </a>
        </div>
    </div>

    <!-- LATEST JOBS -->
    <div class="pr-jobs-bg">
        <div class="pr-wrap" style="padding-top:0;padding-bottom:0;">
            <div class="pr-sh">
                <h2>Latest Opportunities</h2>
                <p>Fresh jobs added today – apply before they are gone.</p>
            </div>

            <?php echo do_shortcode('[jobs per_page="6" show_filters="false"]'); ?>

            <div style="text-align:center;">
                <a href="<?php echo esc_url( home_url( '/jobs/' ) ); ?>" class="pr-btn">
                    Browse All Jobs <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

</div>
    <?php
    return ob_get_clean();
}
