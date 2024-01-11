<?php

namespace WPForms\Admin\Settings;

use WPForms\Emails\Helpers;
use WPForms\Emails\Notifications;
use WPForms\Admin\Education\Helpers as EducationHelpers;

/**
 * Email setting page.
 * Settings will be accessible via “WPForms” → “Settings” → “Email”.
 *
 * @since 1.8.5
 */
class Email {

	/**
	 * Content is plain text type.
	 *
	 * @since 1.8.5
	 *
	 * @var bool
	 */
	private $plain_text;

	/**
	 * Initialize class.
	 *
	 * @since 1.8.5
	 */
	public function init() {

		$this->hooks();
	}

	/**
	 * Hooks.
	 *
	 * @since 1.8.5
	 */
	private function hooks() {

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
		add_filter( 'wpforms_update_settings', [ $this, 'maybe_update_settings' ] );
		add_filter( 'wpforms_settings_tabs', [ $this, 'register_settings_tabs' ], 5 );
		add_filter( 'wpforms_settings_defaults', [ $this, 'register_settings_fields' ], 5 );
	}

	/**
	 * Enqueue scripts and styles.
	 * Static resources are enqueued only on the "Email" settings page.
	 *
	 * @since 1.8.5
	 */
	public function enqueue_assets() {

		// Leave if the current page is not the "Email" settings page.
		if ( ! $this->is_settings_page() ) {
			return;
		}

		$min = wpforms_get_min_suffix();

		wp_enqueue_script(
			'wpforms-admin-email-settings',
			WPFORMS_PLUGIN_URL . "assets/js/components/admin/email/settings{$min}.js",
			[ 'jquery', 'wpforms-admin', 'choicesjs' ],
			WPFORMS_VERSION,
			true
		);
	}

	/**
	 * Maybe update settings.
	 *
	 * @since 1.8.5
	 *
	 * @param array $settings Admin area settings list.
	 *
	 * @return array
	 */
	public function maybe_update_settings( $settings ) {

		// Leave if the current page is not the "Email" settings page.
		if ( ! $this->is_settings_page() ) {
			return $settings;
		}

		// Backup the Pro version background color setting to the free version.
		// This is needed to keep the background color when the Pro version is deactivated.
		if ( wpforms()->is_pro() && ! Helpers::is_legacy_html_template() ) {
			$settings['email-background-color'] = sanitize_hex_color( $settings['email-color-scheme']['email_background_color'] );

			return $settings;
		}

		// Backup the free version background color setting to the Pro version.
		// This is needed to keep the background color when the Pro version is activated.
		$settings['email-color-scheme']['email_background_color'] = sanitize_hex_color( $settings['email-background-color'] );

		return $settings;
	}

	/**
	 * Register "Email" settings tab.
	 *
	 * @since 1.8.5
	 *
	 * @param array $tabs Admin area tabs list.
	 *
	 * @return array
	 */
	public function register_settings_tabs( $tabs ) {

		$payments = [
			'email' => [
				'form'   => true,
				'name'   => esc_html__( 'Email', 'wpforms-lite' ),
				'submit' => esc_html__( 'Save Settings', 'wpforms-lite' ),
			],
		];

		return wpforms_array_insert( $tabs, $payments, 'general' );
	}

	/**
	 * Register "Email" settings fields.
	 *
	 * @since 1.8.5
	 *
	 * @param array $settings Admin area settings list.
	 *
	 * @return array
	 */
	public function register_settings_fields( $settings ) {

		$education_args   = [ 'action' => 'upgrade' ];
		$has_eduction     = ! wpforms()->is_pro() ? 'education-modal' : '';
		$style_overrides  = Helpers::get_current_template_style_overrides();
		$preview_link     = $this->get_current_template_preview_link();
		$this->plain_text = Helpers::is_plain_text_template();
		$has_legacy       = Helpers::is_legacy_html_template() ? 'legacy-template' : '';

		// After initializing the color picker, the helper icon from 1Password and LastPass appears inside the input field.
		// These data attributes disable the extension form from appearing.
		$color_scheme_data = [
			'1p-ignore' => 'true', // 1Password ignore.
			'lp-ignore' => 'true', // LastPass ignore.
		];

		// Add Email settings.
		$settings['email'] = [
			'email-heading'           => [
				'id'       => 'email-heading',
				'content'  => $this->get_heading_content(),
				'type'     => 'content',
				'no_label' => true,
				'class'    => [ 'section-heading', 'no-desc' ],
			],
			'email-template'          => [
				'id'      => 'email-template',
				'name'    => esc_html__( 'Template', 'wpforms-lite' ),
				'class'   => [ 'wpforms-email-template', 'wpforms-card-image-group' ],
				'type'    => 'email_template',
				'default' => Notifications::DEFAULT_TEMPLATE,
				'options' => Helpers::get_email_template_choices(),
				'value'   => Helpers::get_current_template_name(),
			],
			'email-header-image'      => [
				'id'          => 'email-header-image',
				'name'        => esc_html__( 'Header Image', 'wpforms-lite' ),
				'desc'        => esc_html__( 'Upload or choose a logo to be displayed at the top of email notifications.', 'wpforms-lite' ),
				'class'       => [ 'wpforms-email-header-image', 'hide-for-template-none', $this->get_external_header_image_class() ],
				'type'        => 'image',
				'is_hidden'   => $this->plain_text,
				'show_remove' => true,
			],
			'email-header-image-size' => [
				'id'        => 'email-header-image-size',
				'no_label'  => true,
				'type'      => 'select',
				'class'     => 'wpforms-email-header-image-size',
				'is_hidden' => true,
				'choicesjs' => false,
				'default'   => 'medium',
				'options'   => [
					'small'  => esc_html__( 'Small', 'wpforms-lite' ),
					'medium' => esc_html__( 'Medium', 'wpforms-lite' ),
					'large'  => esc_html__( 'Large', 'wpforms-lite' ),
				],
			],
			'email-color-scheme'      => [
				'id'              => 'email-color-scheme',
				'name'            => esc_html__( 'Color Scheme', 'wpforms-lite' ),
				'class'           => [ 'hide-for-template-none', $has_eduction, $has_legacy ],
				'type'            => 'color_scheme',
				'is_hidden'       => $this->plain_text,
				'education_badge' => $has_eduction ? EducationHelpers::get_badge( 'Pro' ) : '',
				'data_attributes' => $has_eduction ? array_merge( [ 'name' => esc_html__( 'Color Scheme', 'wpforms-lite' ) ], $education_args ) : [],
				'colors'          => [
					'email_background_color' => [
						'name' => esc_html__( 'Background', 'wpforms-lite' ),
						'data' => array_merge(
							[
								'fallback-color' => $style_overrides['email_background_color'],
							],
							$color_scheme_data
						),
					],
					'email_body_color'       => [
						'name' => esc_html__( 'Body', 'wpforms-lite' ),
						'data' => array_merge(
							[
								'fallback-color' => $style_overrides['email_body_color'],
							],
							$color_scheme_data
						),
					],
					'email_text_color'       => [
						'name' => esc_html__( 'Text', 'wpforms-lite' ),
						'data' => array_merge(
							[
								'fallback-color' => $style_overrides['email_text_color'],
							],
							$color_scheme_data
						),
					],
					'email_links_color'      => [
						'name' => esc_html__( 'Links', 'wpforms-lite' ),
						'data' => array_merge(
							[
								'fallback-color' => $style_overrides['email_links_color'],
							],
							$color_scheme_data
						),
					],
				],
			],
			'email-typography'        => [
				'id'              => 'email-typography',
				'name'            => esc_html__( 'Typography', 'wpforms-lite' ),
				'desc'            => esc_html__( 'Choose the style that’s applied to all text in email notifications.', 'wpforms-lite' ),
				'class'           => [ 'hide-for-template-none', $has_eduction, $has_legacy ],
				'education_badge' => $has_eduction ? EducationHelpers::get_badge( 'Pro' ) : '',
				'data_attributes' => $has_eduction ? array_merge( [ 'name' => esc_html__( 'Typography', 'wpforms-lite' ) ], $education_args ) : [],
				'type'            => 'select',
				'is_hidden'       => $this->plain_text,
				'choicesjs'       => true,
				'default'         => 'sans-serif',
				'options'         => [
					'sans-serif' => esc_html__( 'Sans Serif', 'wpforms-lite' ),
					'serif'      => esc_html__( 'Serif', 'wpforms-lite' ),
				],
			],
			'email-preview'           => [
				'id'        => 'email-preview',
				'type'      => 'content',
				'is_hidden' => empty( $preview_link ),
				'content'   => $preview_link,
			],
			'sending-heading'         => [
				'id'       => 'sending-heading',
				'content'  => '<h4>' . esc_html__( 'Sending', 'wpforms-lite' ) . '</h4>',
				'type'     => 'content',
				'no_label' => true,
				'class'    => [ 'section-heading', 'no-desc' ],
			],
			'email-async'             => [
				'id'     => 'email-async',
				'name'   => esc_html__( 'Optimize Email Sending', 'wpforms-lite' ),
				'desc'   => sprintf(
					wp_kses( /* translators: %1$s - WPForms.com Email settings documentation URL. */
						__( 'Send emails asynchronously, which can make processing faster but may delay email delivery by a minute or two. <a href="%1$s" target="_blank" rel="noopener noreferrer" class="wpforms-learn-more">Learn More</a>', 'wpforms-lite' ),
						[
							'a' => [
								'href'   => [],
								'target' => [],
								'rel'    => [],
								'class'  => [],
							],
						]
					),
					esc_url( wpforms_utm_link( 'https://wpforms.com/docs/a-complete-guide-to-wpforms-settings/#email', 'Settings - Email', 'Optimize Email Sending Documentation' ) )
				),
				'type'   => 'toggle',
				'status' => true,
			],
			'email-carbon-copy'       => [
				'id'     => 'email-carbon-copy',
				'name'   => esc_html__( 'Carbon Copy', 'wpforms-lite' ),
				'desc'   => esc_html__( 'Enable the ability to CC: email addresses in the form notification settings.', 'wpforms-lite' ),
				'type'   => 'toggle',
				'status' => true,
			],
		];

		// Add background color control if the Pro version is not active or Legacy template is selected.
		$settings['email'] = $this->maybe_add_background_color_control( $settings['email'], $style_overrides['email_background_color'] );

		// Maybe add the Legacy template notice.
		$settings['email'] = $this->maybe_add_legacy_notice( $settings['email'] );

		return $settings;
	}

	/**
	 * Maybe add the legacy template notice.
	 *
	 * @since 1.8.5
	 *
	 * @param array $settings Email settings.
	 *
	 * @return array
	 */
	private function maybe_add_legacy_notice( $settings ) {

		if ( ! $this->is_settings_page() || ! Helpers::is_legacy_html_template() ) {
			return $settings;
		}

		$content  = '<div class="notice-info"><p>';
		$content .= sprintf(
			wp_kses( /* translators: %1$s - WPForms.com Email settings legacy template documentation URL. */
				__( 'Some style settings are not available when using the Legacy template. <a href="%1$s" target="_blank" rel="noopener noreferrer">Learn More</a>', 'wpforms-lite' ),
				[
					'a' => [
						'href'   => [],
						'target' => [],
						'rel'    => [],
					],
				]
			),
			esc_url( wpforms_utm_link( 'https://wpforms.com/docs/customizing-form-notification-emails/#legacy-template', 'Settings - Email', 'Legacy Template' ) )
		);
		$content .= '</p></div>';

		// Add the background color control after the header image.
		return wpforms_array_insert(
			$settings,
			[
				'email-legacy-notice' => [
					'id'      => 'email-legacy-notice',
					'content' => $content,
					'type'    => 'content',
					'class'   => 'wpforms-email-legacy-notice',
				],
			],
			'email-template'
		);
	}

	/**
	 * Get Email settings heading content.
	 *
	 * @since 1.8.5
	 *
	 * @return string
	 */
	private function get_heading_content() {

		return wpforms_render( 'admin/settings/email-heading' );
	}

	/**
	 * Get current email template hyperlink.
	 *
	 * @since 1.8.5
	 *
	 * @return string
	 */
	private function get_current_template_preview_link() {

		// Leave if the user has the legacy template is set or the user doesn't have the capability.
		if ( ! wpforms_current_user_can() || Helpers::is_legacy_html_template() ) {
			return '';
		}

		$template_name    = Helpers::get_current_template_name();
		$current_template = Notifications::get_available_templates( $template_name );

		// Return empty string if the current template is not found.
		// Leave early if the preview link is empty.
		if ( ! isset( $current_template['path'] ) || ! class_exists( $current_template['path'] ) || empty( $current_template['preview'] ) ) {
			return '';
		}

		return sprintf(
			wp_kses( /* translators: %1$s - Email template preview URL. */
				__( '<a href="%1$s" target="_blank" rel="noopener">Preview Email Template</a>', 'wpforms-lite' ),
				[
					'a' => [
						'href'   => true,
						'target' => true,
						'rel'    => true,
					],
				]
			),
			esc_url( $current_template['preview'] )
		);
	}

	/**
	 * Maybe add the background color control to the email settings.
	 * This is only available in the free version.
	 *
	 * @since 1.8.5
	 *
	 * @param array  $settings       Email settings.
	 * @param string $fallback_color Fallback color.
	 *
	 * @return array
	 */
	private function maybe_add_background_color_control( $settings, $fallback_color = '#e9eaec' ) {

		// Leave as is if the Pro version is active and no legacy template available.
		if ( ! Helpers::is_legacy_html_template() && wpforms()->is_pro() ) {
			return $settings;
		}

		// Add the background color control after the header image.
		return wpforms_array_insert(
			$settings,
			[
				'email-background-color' => [
					'id'        => 'email-background-color',
					'name'      => esc_html__( 'Background Color', 'wpforms-lite' ),
					'desc'      => esc_html__( 'Customize the background color of the email template.', 'wpforms-lite' ),
					'class'     => 'email-background-color',
					'type'      => 'color',
					'is_hidden' => $this->plain_text,
					'default'   => '#e9eaec',
					'data'      => [
						'fallback-color' => $fallback_color,
						'1p-ignore'      => 'true', // 1Password ignore.
						'lp-ignore'      => 'true', // LastPass ignore.
					],
				],
			],
			'email-header-image'
		);
	}

	/**
	 * Gets the class for the header image control.
	 *
	 * This is used to determine if the header image is external.
	 * Legacy header image control was allowing external URLs.
	 *
	 * @since 1.8.5
	 *
	 * @return string
	 */
	private function get_external_header_image_class() {

		$header_image_url = wpforms_setting( 'email-header-image', '' );

		// If the header image URL is empty, return an empty string.
		if ( empty( $header_image_url ) ) {
			return '';
		}

		$site_url = home_url(); // Get the current site's URL.

		// Get the hosts of the site URL and the header image URL.
		$site_url_host         = wp_parse_url( $site_url, PHP_URL_HOST );
		$header_image_url_host = wp_parse_url( $header_image_url, PHP_URL_HOST );

		// Check if the header image URL host is different from the site URL host.
		if ( $header_image_url_host && $site_url_host && $header_image_url_host !== $site_url_host ) {
			return 'has-external-image-url';
		}

		return ''; // If none of the conditions match, return an empty string.
	}

	/**
	 * Determine if the current page is the "Email" settings page.
	 *
	 * @since 1.8.5
	 *
	 * @return bool
	 */
	private function is_settings_page() {

		return wpforms_is_admin_page( 'settings', 'email' );
	}
}
