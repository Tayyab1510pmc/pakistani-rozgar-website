<?php
/**
 * Plugin Name: Pakistani Rozgar Core
 * Plugin URI:  https://pakistanirozgar.com
 * Description: Core plugin for Pakistani Rozgar job portal. Adds WhatsApp share/apply buttons, advanced Pakistani city location filters, and SEO OpenGraph meta tags for WP Job Manager.
 * Version:     1.2.0
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
