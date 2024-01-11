<?php

namespace WPForms\Emails;

/**
 * Class Preview.
 * Handles previewing email templates.
 *
 * @since 1.8.5
 */
class Preview {

	/**
	 * List of preview fields.
	 *
	 * @since 1.8.5
	 *
	 * @var array
	 */
	private $fields = [];

	/**
	 * Current email template.
	 *
	 * @since 1.8.5
	 *
	 * @var string
	 */
	private $current_template;

	/**
	 * Field template.
	 *
	 * @since 1.8.5
	 *
	 * @var string
	 */
	private $field_template;

	/**
	 * Content is plain text type.
	 *
	 * @since 1.8.5
	 *
	 * @var bool
	 */
	private $plain_text;

	/**
	 * Preview nonce name.
	 *
	 * @since 1.8.5
	 *
	 * @var string
	 */
	const PREVIEW_NONCE_NAME = 'wpforms_email_preview';

	/**
	 * Initialize class.
	 *
	 * @since 1.8.5
	 */
	public function init() {

		// Leave if user can't access.
		if ( ! wpforms_current_user_can() ) {
			return;
		}

		// Leave early if nonce verification failed.
		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), self::PREVIEW_NONCE_NAME ) ) {
			return;
		}

		// Leave early if preview is not requested.
		if ( ! isset( $_GET['wpforms_email_preview'], $_GET['wpforms_email_template'] ) ) {
			return;
		}

		$this->current_template = sanitize_key( $_GET['wpforms_email_template'] );
		$this->plain_text       = $this->current_template === 'none';

		$this->preview();
	}

	/**
	 * Preview email template.
	 *
	 * @since 1.8.5
	 */
	private function preview() {

		$template = Notifications::get_available_templates( $this->current_template );

		/**
		 * Filter the email template to be previewed.
		 *
		 * @since 1.8.5
		 *
		 * @param array $template Email template.
		 */
		$template = apply_filters( 'wpforms_emails_preview_template', $template );

		// Redirect to the email settings page if the template is not set.
		if ( ! isset( $template['path'] ) || ! class_exists( $template['path'] ) ) {
			wp_safe_redirect(
				add_query_arg(
					[
						'page' => 'wpforms-settings',
						'view' => 'email',
					],
					admin_url( 'admin.php' )
				)
			);
			exit;
		}

		// Set the email template, i.e. WPForms\Emails\Templates\Classic.
		$template = new $template['path']( '', true );

		// Set the field template.
		// This is used to replace the placeholders in the email template.
		$this->field_template = $template->get_field_template();

		// Set the email template fields.
		$template->set_field( $this->get_placeholder_message() );

		// Get the email template content.
		$content = $template->get();

		// Return if the template is empty.
		if ( ! $content ) {
			return;
		}

		// Echo the email template content.
		echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		exit; // No need to continue. WordPress will die() after this.
	}

	/**
	 * Get preview content.
	 *
	 * @since 1.8.5
	 *
	 * @return string Placeholder message.
	 */
	private function get_placeholder_message() {

		$this->fields = [
			[
				'type'  => 'name',
				'name'  => __( 'Name', 'wpforms-lite' ),
				'value' => 'Sullie Eloso',
			],
			[
				'type'  => 'email',
				'name'  => __( 'Email', 'wpforms-lite' ),
				'value' => 'sullie@wpforms.com',
			],
			[
				'type'  => 'textarea',
				'name'  => __( 'Comment or Message', 'wpforms-lite' ),
				'value' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Odio ut sem nulla pharetra diam sit amet. Sed risus pretium quam vulputate dignissim suspendisse in est ante. Risus ultricies tristique nulla aliquet enim tortor at auctor. Nisl tincidunt eget nullam non nisi est sit amet facilisis. Duis at tellus at urna condimentum mattis pellentesque id nibh. Curabitur vitae nunc sed velit dignissim.\r\n\r\nLeo urna molestie at elementum eu facilisis sed odio. Scelerisque mauris pellentesque pulvinar pellentesque habitant morbi. Volutpat maecenas volutpat blandit aliquam. Libero id faucibus nisl tincidunt. Et malesuada fames ac turpis egestas.",
			],
		];

		// Early return if the template is plain text.
		if ( $this->plain_text ) {
			return $this->process_plain_message();
		}

		return $this->process_html_message();
	}

	/**
	 * Process the HTML email message.
	 *
	 * @since 1.8.5
	 *
	 * @return string
	 */
	private function process_html_message() {

		$message = '';

		foreach ( $this->fields as $field ) {
			$message .= str_replace(
				[ '{field_type}', '{field_name}', '{field_value}', "\r\n" ],
				[ $field['type'], $field['name'], $field['value'], '<br>' ],
				$this->field_template
			);
		}

		return $message;
	}

	/**
	 * Process the plain text email message.
	 *
	 * @since 1.8.5
	 *
	 * @return string
	 */
	private function process_plain_message() {

		$message = '';

		foreach ( $this->fields as $field ) {
			$message .= '--- ' . $field['name'] . " ---\r\n\r\n" . str_replace( [ "\n", "\r" ], '', $field['value'] ) . "\r\n\r\n";
		}

		return nl2br( $message );
	}
}
