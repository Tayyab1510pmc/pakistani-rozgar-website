<?php
/**
 * Plugin Name: Pakistani Rozgar Core
 * Plugin URI:  https://pakistanirozgar.com
 * Description: Core plugin for Pakistani Rozgar job portal. Adds modern custom header/footer UI, WhatsApp share/apply buttons, advanced Pakistani city location filters, and SEO OpenGraph meta tags for WP Job Manager.
 * Version:     1.3.0
 * Author:      Pakistani Rozgar
 * Author URI:  https://pakistanirozgar.com
 * License:     GPL-2.0-or-later
 * Text Domain: pakistani-rozgar
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/** Maximum character length for og:description meta tag content. */
define( 'PRZGR_OG_DESC_MAX_LENGTH', 200 );

// =============================================================================
// 1. WHATSAPP SHARE & APPLY BUTTONS
// =============================================================================

/**
 * Output WhatsApp share and apply buttons on single job listing pages.
 * Hooks into WP Job Manager's single_job_listing_end action.
 */
add_action( 'single_job_listing_end', 'przgr_whatsapp_buttons' );

function przgr_whatsapp_buttons() {
    global $post;

    if ( ! $post || 'job_listing' !== $post->post_type ) {
        return;
    }

    $job_title = rawurlencode( get_the_title( $post->ID ) );
    $job_url   = rawurlencode( get_permalink( $post->ID ) );

    // WhatsApp share message: "Check out this job: [Title] – [URL]"
    // Use the already-encoded variables to avoid double-encoding.
    $share_message = 'Check out this job: ' . $job_title . ' %E2%80%93 ' . $job_url;
    $share_url     = 'https://wa.me/?text=' . $share_message;

    echo '<div class="przgr-whatsapp-buttons" style="margin:20px 0;">';

    // Share on WhatsApp button (always visible)
    echo '<a href="' . esc_url( $share_url ) . '" target="_blank" rel="noopener noreferrer" class="przgr-btn przgr-btn-share" style="display:inline-flex;align-items:center;gap:8px;background:#25D366;color:#fff;padding:10px 20px;border-radius:6px;text-decoration:none;font-weight:bold;margin-right:10px;">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.117.549 4.107 1.51 5.84L0 24l6.335-1.51C8.093 23.45 10.017 24 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.846 0-3.576-.498-5.065-1.365l-.363-.215-3.761.896.911-3.664-.236-.375C2.498 15.576 2 13.846 2 12 2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>';
    echo esc_html__( 'Share on WhatsApp', 'pakistani-rozgar' );
    echo '</a>';

    // Apply via WhatsApp button (only if application method is a phone number)
    $apply_method = get_post_meta( $post->ID, '_application', true );
    if ( ! empty( $apply_method ) && przgr_is_phone_number( $apply_method ) ) {
        $phone       = preg_replace( '/[^0-9+]/', '', $apply_method );
        $apply_text  = rawurlencode( 'Hello, I am interested in the job: ' . get_the_title( $post->ID ) . ' (' . get_permalink( $post->ID ) . ')' );
        $apply_url   = 'https://wa.me/' . ltrim( $phone, '+' ) . '?text=' . $apply_text;

        echo '<a href="' . esc_url( $apply_url ) . '" target="_blank" rel="noopener noreferrer" class="przgr-btn przgr-btn-apply" style="display:inline-flex;align-items:center;gap:8px;background:#128C7E;color:#fff;padding:10px 20px;border-radius:6px;text-decoration:none;font-weight:bold;">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.117.549 4.107 1.51 5.84L0 24l6.335-1.51C8.093 23.45 10.017 24 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.846 0-3.576-.498-5.065-1.365l-.363-.215-3.761.896.911-3.664-.236-.375C2.498 15.576 2 13.846 2 12 2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>';
        echo esc_html__( 'Apply via WhatsApp', 'pakistani-rozgar' );
        echo '</a>';
    }

    echo '</div>';
}

/**
 * Detect whether a string looks like a phone number rather than an email/URL.
 *
 * @param string $value The application method value from WP Job Manager.
 * @return bool
 */
function przgr_is_phone_number( $value ) {
    // Strip common formatting characters and check if the result is a plausible phone number
    $cleaned = preg_replace( '/[\s\-().+]/', '', $value );
    return (bool) preg_match( '/^\+?[0-9]{7,15}$/', $cleaned );
}

// =============================================================================
// 2. ADVANCED LOCATION FILTERS (Pakistani cities dropdown)
// =============================================================================

/**
 * List of major Pakistani cities used in the location dropdown.
 */
function przgr_pakistani_cities() {
    return array(
        ''           => __( 'All Locations', 'pakistani-rozgar' ),
        'Lahore'     => __( 'Lahore', 'pakistani-rozgar' ),
        'Karachi'    => __( 'Karachi', 'pakistani-rozgar' ),
        'Islamabad'  => __( 'Islamabad', 'pakistani-rozgar' ),
        'Rawalpindi' => __( 'Rawalpindi', 'pakistani-rozgar' ),
        'Peshawar'   => __( 'Peshawar', 'pakistani-rozgar' ),
        'Quetta'     => __( 'Quetta', 'pakistani-rozgar' ),
        'Multan'     => __( 'Multan', 'pakistani-rozgar' ),
        'Remote'     => __( 'Remote', 'pakistani-rozgar' ),
    );
}

/**
 * Replace the WP Job Manager location text input with a Pakistani cities dropdown.
 *
 * @param string $field_html Original field HTML.
 * @param array  $field      Field configuration array.
 * @return string Modified field HTML.
 */
add_filter( 'job_manager_job_filters_search_jobs_get_location', 'przgr_replace_location_with_dropdown', 10, 2 );

function przgr_replace_location_with_dropdown( $field_html, $field ) {
    $cities          = przgr_pakistani_cities();
    $raw_location    = isset( $_GET['search_location'] ) ? sanitize_text_field( wp_unslash( $_GET['search_location'] ) ) : '';
    // Validate against allowed cities to prevent manipulation of filter parameters.
    $selected_city   = array_key_exists( $raw_location, $cities ) ? $raw_location : '';

    $select_html  = '<select name="search_location" id="search_location" class="job-manager-filter">';
    foreach ( $cities as $value => $label ) {
        $select_html .= '<option value="' . esc_attr( $value ) . '"' . selected( $selected_city, $value, false ) . '>' . esc_html( $label ) . '</option>';
    }
    $select_html .= '</select>';

    return $select_html;
}

/**
 * Inject the location dropdown via widget filter as a fallback for themes that
 * render the search form directly through [jobs] shortcode HTML output.
 * Replaces the rendered <input type="text"> location field with a <select>.
 *
 * @param string $html Shortcode / widget HTML output.
 * @return string Modified HTML.
 */
add_filter( 'the_content', 'przgr_inject_city_select_into_jobs_output', 20 );

function przgr_inject_city_select_into_jobs_output( $html ) {
    if ( ! is_page() && ! is_singular() ) {
        return $html;
    }

    // Only act when the jobs search form is present in the output
    if ( false === strpos( $html, 'search_location' ) ) {
        return $html;
    }

    $cities        = przgr_pakistani_cities();
    $raw_location  = isset( $_GET['search_location'] ) ? sanitize_text_field( wp_unslash( $_GET['search_location'] ) ) : '';
    // Validate against allowed cities to prevent manipulation of filter parameters.
    $selected_city = array_key_exists( $raw_location, $cities ) ? $raw_location : '';

    $options_html = '';
    foreach ( $cities as $value => $label ) {
        $options_html .= '<option value="' . esc_attr( $value ) . '"' . selected( $selected_city, $value, false ) . '>' . esc_html( $label ) . '</option>';
    }

    // Replace the plain text input for location with a select element
    $pattern     = '/<input[^>]*name=["\']search_location["\'][^>]*>/i';
    $replacement = '<select name="search_location" id="search_location" class="job-manager-filter">' . $options_html . '</select>';
    $html        = preg_replace( $pattern, $replacement, $html );

    return $html;
}

// =============================================================================
// 3. SEO & OPENGRAPH TAGS
// =============================================================================

/**
 * Output OpenGraph meta tags in <head> for better link previews on
 * Facebook, WhatsApp, and other social platforms.
 */
add_action( 'wp_head', 'przgr_output_opengraph_tags', 1 );

function przgr_output_opengraph_tags() {
    global $post;

    $og_title       = '';
    $og_description = '';
    $og_image       = '';
    $og_url         = '';
    $og_type        = 'website';

    if ( is_singular( 'job_listing' ) && $post ) {
        // Single job listing page
        $og_title       = get_the_title( $post->ID );
        $og_description = wp_strip_all_tags( get_the_excerpt( $post->ID ) );
        if ( empty( $og_description ) ) {
            $og_description = wp_trim_words( wp_strip_all_tags( $post->post_content ), 25, '...' );
        }
        $og_url  = get_permalink( $post->ID );
        $og_type = 'article';

        // Use the job's featured image if available, otherwise fall back to site logo/default
        if ( has_post_thumbnail( $post->ID ) ) {
            $og_image = get_the_post_thumbnail_url( $post->ID, 'large' );
        } else {
            $og_image = przgr_default_og_image();
        }
    } elseif ( is_front_page() || is_home() ) {
        // Homepage
        $og_title       = get_bloginfo( 'name' ) . ' – ' . get_bloginfo( 'description' );
        $og_description = get_bloginfo( 'description' );
        $og_url         = home_url( '/' );
        $og_image       = przgr_default_og_image();
    } else {
        // Generic pages / archives
        $og_title       = wp_title( '|', false, 'right' ) . get_bloginfo( 'name' );
        $og_description = get_bloginfo( 'description' );
        // Use home_url with current query args for a safe, injection-free current URL.
        $og_url         = home_url( add_query_arg( array() ) );
        $og_image       = przgr_default_og_image();
    }

    // Ensure description is not empty
    if ( empty( $og_description ) ) {
        $og_description = get_bloginfo( 'description' );
    }

    // Trim to a reasonable length
    $og_description = mb_substr( $og_description, 0, PRZGR_OG_DESC_MAX_LENGTH );

    ?>
    <!-- Pakistani Rozgar OpenGraph / SEO Meta Tags -->
    <meta property="og:type"        content="<?php echo esc_attr( $og_type ); ?>" />
    <meta property="og:title"       content="<?php echo esc_attr( $og_title ); ?>" />
    <meta property="og:description" content="<?php echo esc_attr( $og_description ); ?>" />
    <meta property="og:url"         content="<?php echo esc_url( $og_url ); ?>" />
    <?php if ( ! empty( $og_image ) ) : ?>
    <meta property="og:image"       content="<?php echo esc_url( $og_image ); ?>" />
    <?php endif; ?>
    <meta property="og:site_name"   content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
    <meta name="twitter:card"        content="summary_large_image" />
    <meta name="twitter:title"       content="<?php echo esc_attr( $og_title ); ?>" />
    <meta name="twitter:description" content="<?php echo esc_attr( $og_description ); ?>" />
    <?php if ( ! empty( $og_image ) ) : ?>
    <meta name="twitter:image"       content="<?php echo esc_url( $og_image ); ?>" />
    <?php endif; ?>
    <?php
}

/**
 * Return the default OpenGraph image URL.
 * Tries the site's custom logo first, then falls back to a placeholder.
 *
 * @return string Image URL, or empty string if none found.
 */
function przgr_default_og_image() {
    // Try the site's custom logo (set in Customizer)
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    if ( $custom_logo_id ) {
        $logo_src = wp_get_attachment_image_url( $custom_logo_id, 'full' );
        if ( $logo_src ) {
            return $logo_src;
        }
    }

    // Try a site-icon (favicon) as last resort
    $site_icon_id = get_option( 'site_icon' );
    if ( $site_icon_id ) {
        $icon_src = wp_get_attachment_image_url( $site_icon_id, 'full' );
        if ( $icon_src ) {
            return $icon_src;
        }
    }

    return '';
}

// =============================================================================
// 4. PROFESSIONAL HEADER & FOOTER UI SHELL
// =============================================================================

/**
 * Output modern shell styles for custom header/footer.
 */
add_action( 'wp_head', 'przgr_output_shell_styles', 5 );

function przgr_output_shell_styles() {
    if ( is_admin() ) {
        return;
    }
    ?>
    <style id="przgr-shell-styles">
        :root {
            --przgr-brand-green: #0f8a4d;
            --przgr-brand-dark: #1f2937;
            --przgr-shell-text: #374151;
            --przgr-footer-bg: #0f172a;
            --przgr-footer-muted: #94a3b8;
        }
        body {
            padding-top: 78px;
        }
        .przgr-site-shell {
            font-family: inherit;
        }
        .przgr-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 9999;
            background: #fff;
            box-shadow: 0 2px 22px rgba(15, 23, 42, 0.08);
            border-bottom: 1px solid #e5e7eb;
        }
        .przgr-shell-container {
            width: min(1200px, 94%);
            margin: 0 auto;
        }
        .przgr-header-inner {
            min-height: 78px;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .przgr-logo {
            text-decoration: none;
            font-weight: 800;
            line-height: 1.05;
            font-size: clamp(1.1rem, 1.8vw, 1.45rem);
            letter-spacing: -0.02em;
            margin-right: auto;
            white-space: nowrap;
        }
        .przgr-logo__green { color: var(--przgr-brand-green); }
        .przgr-logo__dark { color: var(--przgr-brand-dark); }
        .przgr-nav {
            display: flex;
            align-items: center;
        }
        .przgr-nav-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .przgr-nav a {
            position: relative;
            color: var(--przgr-shell-text);
            text-decoration: none;
            font-weight: 600;
            padding: 8px 4px;
            transition: color .2s ease;
        }
        .przgr-nav a::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -2px;
            width: 100%;
            height: 2px;
            transform: scaleX(0);
            transform-origin: left center;
            background: var(--przgr-brand-green);
            transition: transform .2s ease;
        }
        .przgr-nav a:hover,
        .przgr-nav a:focus-visible {
            color: var(--przgr-brand-green);
        }
        .przgr-nav a:hover::after,
        .przgr-nav a:focus-visible::after {
            transform: scaleX(1);
        }
        .przgr-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-left: 8px;
        }
        .przgr-btn-outline,
        .przgr-btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            border-radius: 999px;
            padding: 10px 16px;
            font-weight: 700;
            font-size: 0.92rem;
            transition: transform .2s ease, box-shadow .2s ease, background-color .2s ease, color .2s ease;
        }
        .przgr-btn-outline {
            border: 1px solid #d1d5db;
            color: var(--przgr-shell-text);
            background: #fff;
        }
        .przgr-btn-primary {
            color: #fff;
            background: linear-gradient(135deg, #16a34a, #0f8a4d);
            box-shadow: 0 8px 18px rgba(22, 163, 74, 0.22);
        }
        .przgr-btn-outline:hover,
        .przgr-btn-primary:hover {
            transform: translateY(-1px);
        }
        .przgr-btn-outline:hover {
            background: #f9fafb;
            color: var(--przgr-brand-green);
        }
        .przgr-btn-primary:hover {
            box-shadow: 0 12px 24px rgba(22, 163, 74, 0.3);
        }
        .przgr-menu-toggle {
            border: 1px solid #d1d5db;
            background: #fff;
            border-radius: 10px;
            width: 42px;
            height: 42px;
            display: none;
            align-items: center;
            justify-content: center;
            color: var(--przgr-shell-text);
            cursor: pointer;
        }
        .przgr-footer {
            background: linear-gradient(180deg, #111827, var(--przgr-footer-bg));
            color: #f8fafc;
            margin-top: 60px;
        }
        .przgr-footer-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 28px;
            padding: 54px 0 40px;
        }
        .przgr-footer h3,
        .przgr-footer h4 {
            margin: 0 0 14px;
            color: #fff;
            font-size: 1.02rem;
        }
        .przgr-footer p {
            color: var(--przgr-footer-muted);
            margin: 0;
            line-height: 1.6;
        }
        .przgr-footer-links {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 8px;
        }
        .przgr-footer-links a {
            color: var(--przgr-footer-muted);
            text-decoration: none;
            transition: color .2s ease, transform .2s ease;
            display: inline-block;
        }
        .przgr-footer-links a:hover {
            color: #fff;
            transform: translateX(3px);
        }
        .przgr-social {
            display: flex;
            gap: 10px;
            margin-top: 16px;
        }
        .przgr-social a {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(148, 163, 184, 0.16);
            color: #fff;
            text-decoration: none;
            transition: background-color .2s ease, transform .2s ease;
        }
        .przgr-social a:hover {
            background: var(--przgr-brand-green);
            transform: translateY(-2px);
        }
        .przgr-footer-bottom {
            border-top: 1px solid rgba(148, 163, 184, 0.2);
            padding: 14px 0 16px;
            text-align: center;
            color: #cbd5e1;
            font-size: 0.9rem;
        }
        @media (max-width: 992px) {
            .przgr-nav-list {
                gap: 12px;
            }
            .przgr-header-actions .przgr-btn-outline {
                display: none;
            }
        }
        @media (max-width: 820px) {
            body {
                padding-top: 72px;
            }
            .przgr-header-inner {
                min-height: 72px;
                flex-wrap: wrap;
            }
            .przgr-menu-toggle {
                display: inline-flex;
                margin-left: auto;
            }
            .przgr-nav {
                display: none;
                width: 100%;
                order: 3;
            }
            .przgr-nav.is-open {
                display: block;
            }
            .przgr-nav-list {
                width: 100%;
                flex-direction: column;
                align-items: flex-start;
                gap: 0;
                border-top: 1px solid #e5e7eb;
                padding: 12px 0 4px;
            }
            .przgr-nav-list li {
                width: 100%;
            }
            .przgr-nav-list a {
                display: block;
                width: 100%;
                padding: 10px 0;
            }
            .przgr-header-actions {
                margin-left: 0;
            }
            .przgr-footer-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                padding: 42px 0 30px;
            }
        }
        @media (max-width: 580px) {
            .przgr-header-actions {
                width: 100%;
                order: 2;
            }
            .przgr-header-actions .przgr-btn-primary {
                width: 100%;
            }
            .przgr-footer-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>
    <?php
}

/**
 * Render custom header shell.
 */
add_action( 'wp_body_open', 'przgr_render_custom_header', 5 );

function przgr_render_custom_header() {
    if ( is_admin() ) {
        return;
    }
    ?>
    <div class="przgr-site-shell" aria-hidden="false">
        <header class="przgr-header" role="banner">
            <div class="przgr-shell-container">
                <div class="przgr-header-inner">
                    <a class="przgr-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <span class="przgr-logo__green"><?php esc_html_e( 'Pakistani', 'pakistani-rozgar' ); ?></span>
                        <span class="przgr-logo__dark"><?php esc_html_e( 'Rozgar', 'pakistani-rozgar' ); ?></span>
                    </a>
                    <button class="przgr-menu-toggle" aria-label="<?php esc_attr_e( 'Toggle navigation menu', 'pakistani-rozgar' ); ?>" aria-expanded="false" aria-controls="przgr-navigation">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M2 4h16v2H2V4zm0 5h16v2H2V9zm0 5h16v2H2v-2z"/></svg>
                    </button>
                    <nav id="przgr-navigation" class="przgr-nav" aria-label="<?php esc_attr_e( 'Primary navigation', 'pakistani-rozgar' ); ?>">
                        <?php przgr_render_header_menu(); ?>
                    </nav>
                    <div class="przgr-header-actions">
                        <a class="przgr-btn-outline" href="<?php echo esc_url( wp_login_url() ); ?>"><?php esc_html_e( 'Login / Register', 'pakistani-rozgar' ); ?></a>
                        <a class="przgr-btn-primary" href="<?php echo esc_url( home_url( '/post-a-job/' ) ); ?>"><?php esc_html_e( 'Post a Job', 'pakistani-rozgar' ); ?></a>
                    </div>
                </div>
            </div>
        </header>
    </div>
    <?php
}

/**
 * Render menu in the custom header using wp_nav_menu when available.
 */
function przgr_render_header_menu() {
    if ( has_nav_menu( 'primary' ) ) {
        wp_nav_menu(
            array(
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'przgr-nav-list',
                'fallback_cb'    => '__return_empty_string',
                'depth'          => 1,
            )
        );
        return;
    }

    $default_links = array(
        __( 'Home', 'pakistani-rozgar' )        => home_url( '/' ),
        __( 'Browse Jobs', 'pakistani-rozgar' ) => home_url( '/jobs/' ),
        __( 'Categories', 'pakistani-rozgar' )  => home_url( '/job-category/' ),
        __( 'About', 'pakistani-rozgar' )       => home_url( '/about/' ),
        __( 'Contact', 'pakistani-rozgar' )     => home_url( '/contact-us/' ),
    );

    echo '<ul class="przgr-nav-list">';
    foreach ( $default_links as $label => $url ) {
        echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
    }
    echo '</ul>';
}

/**
 * Render custom footer shell.
 */
add_action( 'wp_footer', 'przgr_render_custom_footer', 5 );

function przgr_render_custom_footer() {
    if ( is_admin() ) {
        return;
    }

    $privacy_url  = get_privacy_policy_url();
    $social_links = przgr_get_social_links();
    ?>
    <footer class="przgr-footer" role="contentinfo">
        <div class="przgr-shell-container">
            <div class="przgr-footer-grid">
                <div>
                    <h3><?php esc_html_e( 'Pakistani Rozgar', 'pakistani-rozgar' ); ?></h3>
                    <p><?php esc_html_e( 'Pakistan’s modern career platform connecting talented candidates with trusted employers nationwide.', 'pakistani-rozgar' ); ?></p>
                    <?php if ( ! empty( $social_links ) ) : ?>
                        <div class="przgr-social">
                            <?php foreach ( $social_links as $network => $url ) : ?>
                                <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( ucfirst( $network ) ); ?>">
                                    <?php echo esc_html( przgr_social_abbr( $network ) ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div>
                    <h4><?php esc_html_e( 'For Candidates', 'pakistani-rozgar' ); ?></h4>
                    <ul class="przgr-footer-links">
                        <li><a href="<?php echo esc_url( home_url( '/jobs/' ) ); ?>"><?php esc_html_e( 'Browse Jobs', 'pakistani-rozgar' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/job-category/' ) ); ?>"><?php esc_html_e( 'Categories', 'pakistani-rozgar' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/saved-jobs/' ) ); ?>"><?php esc_html_e( 'Saved Jobs', 'pakistani-rozgar' ); ?></a></li>
                    </ul>
                </div>
                <div>
                    <h4><?php esc_html_e( 'For Employers', 'pakistani-rozgar' ); ?></h4>
                    <ul class="przgr-footer-links">
                        <li><a href="<?php echo esc_url( home_url( '/post-a-job/' ) ); ?>"><?php esc_html_e( 'Post a Job', 'pakistani-rozgar' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/pricing/' ) ); ?>"><?php esc_html_e( 'Pricing', 'pakistani-rozgar' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/employer-dashboard/' ) ); ?>"><?php esc_html_e( 'Dashboard', 'pakistani-rozgar' ); ?></a></li>
                    </ul>
                </div>
                <div>
                    <h4><?php esc_html_e( 'Legal', 'pakistani-rozgar' ); ?></h4>
                    <ul class="przgr-footer-links">
                        <?php if ( ! empty( $privacy_url ) ) : ?>
                            <li><a href="<?php echo esc_url( $privacy_url ); ?>"><?php esc_html_e( 'Privacy Policy', 'pakistani-rozgar' ); ?></a></li>
                        <?php else : ?>
                            <li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'pakistani-rozgar' ); ?></a></li>
                        <?php endif; ?>
                        <li><a href="<?php echo esc_url( home_url( '/terms-and-conditions/' ) ); ?>"><?php esc_html_e( 'Terms', 'pakistani-rozgar' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>"><?php esc_html_e( 'Contact Us', 'pakistani-rozgar' ); ?></a></li>
                    </ul>
                </div>
            </div>
            <div class="przgr-footer-bottom">
                &copy; 2026 <?php esc_html_e( 'Pakistani Rozgar. All rights reserved.', 'pakistani-rozgar' ); ?>
            </div>
        </div>
    </footer>
    <?php
}

/**
 * Get footer social links and allow overrides via filter.
 *
 * @return array<string, string>
 */
function przgr_get_social_links() {
    $defaults = array(
        'facebook'  => home_url( '/' ),
        'linkedin'  => home_url( '/' ),
        'instagram' => home_url( '/' ),
    );

    $links = apply_filters( 'przgr_social_links', $defaults );

    if ( ! is_array( $links ) ) {
        return $defaults;
    }

    $sanitized = array();
    foreach ( $links as $network => $url ) {
        $network = sanitize_key( $network );
        if ( empty( $network ) ) {
            continue;
        }
        $url = esc_url_raw( $url );
        if ( empty( $url ) ) {
            continue;
        }
        $sanitized[ $network ] = $url;
    }

    return ! empty( $sanitized ) ? $sanitized : $defaults;
}

/**
 * Social icon abbreviations for compact footer buttons.
 *
 * @param string $network Network slug.
 * @return string
 */
function przgr_social_abbr( $network ) {
    $map = array(
        'facebook'  => 'f',
        'linkedin'  => 'in',
        'instagram' => 'ig',
        'x'         => 'x',
        'twitter'   => 'x',
        'youtube'   => 'yt',
    );

    return isset( $map[ $network ] ) ? $map[ $network ] : substr( $network, 0, 2 );
}

/**
 * Toggle mobile navigation.
 */
add_action( 'wp_footer', 'przgr_output_shell_scripts', 99 );

function przgr_output_shell_scripts() {
    if ( is_admin() ) {
        return;
    }
    ?>
    <script id="przgr-shell-script">
        (function () {
            var toggle = document.querySelector('.przgr-menu-toggle');
            var nav = document.querySelector('.przgr-nav');
            if (!toggle || !nav) {
                return;
            }
            toggle.addEventListener('click', function () {
                var expanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', expanded ? 'false' : 'true');
                nav.classList.toggle('is-open');
            });
        })();
    </script>
    <?php
}
