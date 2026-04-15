<?php
/**
 * Pakistani Rozgar - Master Snippet V5
 * Paste into Code Snippets plugin and activate on frontend.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_head', 'pr_v5_jobs_search_ui_css', 99 );
function pr_v5_jobs_search_ui_css() {
	?>
	<style id="pr-v5-jobs-search-ui">
		/* WP Job Manager [jobs] search bar redesign (pill style) */
		.job_filters .search_jobs {
			background: #ffffff !important;
			border: 1px solid #d7e3da !important;
			border-radius: 999px !important;
			box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08) !important;
			padding: 8px !important;
			max-width: 980px !important;
			margin: 0 auto 20px !important;
		}

		.job_filters .search_jobs > div,
		.job_filters .search_jobs > fieldset,
		.job_filters .search_jobs .search_keywords,
		.job_filters .search_jobs .search_submit {
			float: none !important;
			width: auto !important;
			margin: 0 !important;
			padding: 0 !important;
			border: 0 !important;
			background: transparent !important;
		}

		.job_filters .search_jobs {
			display: flex !important;
			align-items: center !important;
			gap: 8px !important;
			flex-wrap: wrap !important;
		}

		.job_filters .search_jobs .search_location,
		.job_filters .search_jobs [for="search_location"],
		.job_filters .search_jobs input[name="search_location"] {
			display: none !important;
		}

		.job_filters .search_jobs .search_keywords {
			flex: 1 1 260px !important;
		}

		.job_filters .search_jobs .search_keywords input,
		.job_filters .search_jobs input[name="search_keywords"] {
			width: 100% !important;
			height: 52px !important;
			border: 0 !important;
			box-shadow: none !important;
			border-radius: 999px !important;
			padding: 0 18px !important;
			font-size: 16px !important;
			background: transparent !important;
		}

		.job_filters .search_jobs .search_submit input,
		.job_filters .search_jobs .search_submit button,
		.job_filters .search_jobs input[type="submit"] {
			height: 52px !important;
			border: 0 !important;
			border-radius: 999px !important;
			padding: 0 24px !important;
			background: #149253 !important;
			color: #fff !important;
			font-weight: 700 !important;
			cursor: pointer !important;
			margin: 0 !important;
		}

		.job_filters .search_jobs .search_submit input:hover,
		.job_filters .search_jobs .search_submit button:hover,
		.job_filters .search_jobs input[type="submit"]:hover {
			background: #0f7a45 !important;
		}

		@media (max-width: 767px) {
			.job_filters .search_jobs {
				border-radius: 20px !important;
				padding: 10px !important;
			}

			.job_filters .search_jobs .search_keywords,
			.job_filters .search_jobs .search_submit {
				flex: 1 1 100% !important;
			}
		}
	</style>
	<?php
}

function pr_v5_get_whatsapp_button_data() {
	if ( ! is_singular( 'job_listing' ) ) {
		return array();
	}

	$job_id    = get_the_ID();
	$job_title = wp_strip_all_tags( get_the_title( $job_id ) );
	$job_url   = get_permalink( $job_id );

	if ( ! $job_id || ! $job_url ) {
		return array();
	}

	$raw_message = apply_filters( 'pr_v5_whatsapp_message', sprintf( 'Check out this job: %s %s', $job_title, $job_url ), $job_id, $job_title, $job_url );
	$message     = rawurlencode( (string) $raw_message );
	$wa_url  = 'https://wa.me/?text=' . $message;
	$label   = sprintf( 'Share %s on WhatsApp', $job_title );

	return array(
		'url'   => $wa_url,
		'label' => $label,
		'text'  => 'Share on WhatsApp 📲',
	);
}

function pr_v5_render_whatsapp_button() {
	static $printed_for_jobs = array();
	$job_id = get_the_ID();

	if ( ! $job_id || isset( $printed_for_jobs[ $job_id ] ) ) {
		return;
	}

	$data = pr_v5_get_whatsapp_button_data();
	if ( empty( $data['url'] ) ) {
		return;
	}

	$printed_for_jobs[ $job_id ] = true;
	echo '<div class="pr-whatsapp-wrap"><a class="pr-whatsapp-btn" href="' . esc_url( $data['url'] ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr( $data['label'] ) . '">' . esc_html( $data['text'] ) . '</a></div>';
}

add_action( 'single_job_listing_start', 'pr_v5_render_whatsapp_button', 20 );
add_action( 'single_job_listing_end', 'pr_v5_render_whatsapp_button', 20 );
add_action( 'single_job_listing_meta_end', 'pr_v5_render_whatsapp_button', 20 );
add_action( 'job_listing_meta_end', 'pr_v5_render_whatsapp_button', 20 );

add_action( 'wp_head', 'pr_v5_whatsapp_css', 100 );
function pr_v5_whatsapp_css() {
	?>
	<style id="pr-v5-whatsapp-btn">
		.pr-whatsapp-wrap {
			margin: 16px 0 !important;
		}

		.pr-whatsapp-btn {
			display: inline-flex !important;
			align-items: center !important;
			justify-content: center !important;
			width: 100% !important;
			max-width: 360px !important;
			background: #25d366 !important;
			color: #ffffff !important;
			text-decoration: none !important;
			font-weight: 700 !important;
			padding: 12px 18px !important;
			border-radius: 999px !important;
			border: 0 !important;
		}

		.pr-whatsapp-btn:hover {
			background: #128c7e !important;
		}
	</style>
	<?php
}

add_action( 'wp_footer', 'pr_v5_whatsapp_fallback_injection', 99 );
function pr_v5_whatsapp_fallback_injection() {
	if ( ! is_singular( 'job_listing' ) ) {
		return;
	}

	$data = pr_v5_get_whatsapp_button_data();
	if ( empty( $data['url'] ) ) {
		return;
	}
	$selectors = apply_filters(
		'pr_v5_whatsapp_fallback_selectors',
		array(
			'.application_button',
			'.job_application',
			'.single_job_listing .meta',
		)
	);
	$safe_selectors = array();
	foreach ( (array) $selectors as $selector ) {
		$selector = wp_strip_all_tags( (string) $selector );
		if ( preg_match( '/^[\\w\\s\\.#\\-]+$/', $selector ) ) {
			$safe_selectors[] = $selector;
		}
	}
	if ( empty( $safe_selectors ) ) {
		return;
	}
	?>
	<script>
	(function() {
		const whatsappUrl = <?php echo wp_json_encode( esc_url_raw( $data['url'] ) ); ?>;
		const ariaLabel = <?php echo wp_json_encode( sanitize_text_field( $data['label'] ) ); ?>;
		const buttonText = <?php echo wp_json_encode( sanitize_text_field( $data['text'] ) ); ?>;
		const targetSelectors = <?php echo wp_json_encode( array_values( $safe_selectors ) ); ?>;
		function injectButton() {
			if (document.querySelector('.pr-whatsapp-btn') !== null) {
				return;
			}
			let target = null;
			for (let i = 0; i < targetSelectors.length; i++) {
				target = document.querySelector(targetSelectors[i]);
				if (target) {
					break;
				}
			}
			if (!target) {
				return;
			}
			const wrapper = document.createElement('div');
			wrapper.className = 'pr-whatsapp-wrap pr-whatsapp-wrap-fallback';
			const link = document.createElement('a');
			link.className = 'pr-whatsapp-btn';
			link.href = whatsappUrl;
			link.target = '_blank';
			link.rel = 'noopener noreferrer';
			link.setAttribute('aria-label', ariaLabel);
			link.textContent = buttonText;
			wrapper.appendChild(link);
			target.appendChild(wrapper);
		}
		if (window.jQuery) {
			window.jQuery(function() { injectButton(); });
		} else if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', injectButton);
		} else {
			injectButton();
		}
	})();
	</script>
	<?php
}
