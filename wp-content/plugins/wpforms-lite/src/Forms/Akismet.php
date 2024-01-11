<?php

namespace WPForms\Forms;

use Akismet as AkismetPlugin;

/**
 * Class Akismet.
 *
 * @since 1.7.6
 */
class Akismet {

	/**
	 * Is the Akismet plugin installed?
	 *
	 * @since 1.7.6
	 *
	 * @return bool
	 */
	public static function is_installed() {

		return file_exists( WP_PLUGIN_DIR . '/akismet/akismet.php' );
	}

	/**
	 * Is the Akismet plugin activated?
	 *
	 * @since 1.7.6
	 *
	 * @return bool
	 */
	public static function is_activated() {

		return is_callable( [ 'Akismet', 'get_api_key' ] ) && is_callable( [ 'Akismet', 'http_post' ] );
	}

	/**
	 * Has the Akismet plugin been configured wih a valid API key?
	 *
	 * @since 1.7.6
	 *
	 * @return bool
	 */
	public static function is_configured() {

		// Akismet will only allow an API key to be saved if it is a valid key.
		// We can assume that if there is an API key saved, it is valid.
		return self::is_activated() && ! empty( AkismetPlugin::get_api_key() );
	}

	/**
	 * Get the list of field types that are allowed to be sent to Akismet.
	 *
	 * @since 1.7.6
	 *
	 * @return array List of field types that are allowed to be sent to Akismet
	 */
	private function get_field_type_allowlist() {

		$field_type_allowlist = [
			'text',
			'textarea',
			'name',
			'email',
			'phone',
			'address',
			'url',
			'richtext',
		];

		/**
		 * Filters the field types that are allowed to be sent to Akismet.
		 *
		 * @since 1.7.6
		 *
		 * @param array $field_type_allowlist Field types allowed to be sent to Akismet.
		 */
		return (array) apply_filters( 'wpforms_forms_akismet_get_field_type_allowlist', $field_type_allowlist );
	}

	/**
	 * Get the entry data to be sent to Akismet.
	 *
	 * @since 1.7.6
	 *
	 * @param array $fields Field data for the current form.
	 * @param array $entry  Entry data.
	 *
	 * @return array $entry_data Entry data to be sent to Akismet.
	 */
	private function get_entry_data( $fields, $entry ) {

		$field_type_allowlist = $this->get_field_type_allowlist();
		$entry_data           = [];
		$entry_content        = [];

		foreach ( $fields as $field_id => $field ) {
			$field_type = $field['type'];

			if ( ! in_array( $field_type, $field_type_allowlist, true ) ) {
				continue;
			}

			$field_content = $this->get_field_content( $field, $entry, $field_id );

			if ( ! isset( $entry_data[ $field_type ] ) && in_array( $field_type, [ 'name', 'email', 'url' ], true ) ) {
				$entry_data[ $field_type ] = $field_content;

				continue;
			}

			$entry_content[] = $field_content;
		}

		$entry_data['content'] = implode( ' ', $entry_content );

		return $entry_data;
	}

	/**
	 * Get field content.
	 *
	 * @since 1.8.5
	 *
	 * @param array $field    Field data.
	 * @param array $entry    Entry data.
	 * @param int   $field_id Field ID.
	 *
	 * @return string
	 */
	private function get_field_content( $field, $entry, $field_id ) {

		if ( ! isset( $entry['fields'][ $field_id ] ) ) {
			return '';
		}

		if ( ! is_array( $entry['fields'][ $field_id ] ) ) {
			return (string) $entry['fields'][ $field_id ];
		}

		if ( ! empty( $field['type'] ) && $field['type'] === 'email' && ! empty( $entry['fields'][ $field_id ]['primary'] ) ) {
			return (string) $entry['fields'][ $field_id ]['primary'];
		}

		return implode( ' ', $entry['fields'][ $field_id ] );
	}

	/**
	 * Is the entry marked as spam by Akismet?
	 *
	 * @since 1.7.6
	 *
	 * @param array $form_data Form data for the current form.
	 * @param array $entry     Entry data for the current entry.
	 *
	 * @return bool
	 */
	private function entry_is_spam( $form_data, $entry ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		$entry_data = $this->get_entry_data( $form_data['fields'], $entry );
		$request    = [
			'blog'                 => get_option( 'home' ),
			'user_ip'              => wpforms_is_collecting_ip_allowed( $form_data ) ? wpforms_get_ip() : null,
			// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			'user_agent'           => isset( $_SERVER['HTTP_USER_AGENT'] ) ? wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) : null,
			'referrer'             => wp_get_referer() ? wp_get_referer() : null,
			// phpcs:enable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			'permalink'            => wpforms_current_url(),
			'comment_type'         => 'contact-form',
			'comment_author'       => isset( $entry_data['name'] ) ? $entry_data['name'] : '',
			'comment_author_email' => isset( $entry_data['email'] ) ? $entry_data['email'] : '',
			'comment_author_url'   => isset( $entry_data['url'] ) ? $entry_data['url'] : '',
			'comment_content'      => isset( $entry_data['content'] ) ? $entry_data['content'] : '',
			'blog_lang'            => get_locale(),
			'blog_charset'         => get_bloginfo( 'charset' ),
			'user_role'            => AkismetPlugin::get_user_roles( get_current_user_id() ),
			'honypot_field_name'   => 'wpforms["hp"]',
		];

		// If we are on a form preview page, tell Akismet that this is a test submission.
		if ( wpforms()->get( 'preview' )->is_preview_page() ) {
			$request['is_test'] = true;
		}

		$response = AkismetPlugin::http_post( build_query( $request ), 'comment-check' );

		return ! empty( $response ) && isset( $response[1] ) && 'true' === trim( $response[1] );
	}

	/**
	 * Validate entry.
	 *
	 * @since 1.7.6
	 *
	 * @param array $form_data Form data for the current form.
	 * @param array $entry     Entry data for the current entry.
	 *
	 * @return string|bool
	 */
	public function validate( array $form_data, array $entry ) {

		// If Akismet is turned on in form settings, is activated, is configured and the entry is spam.
		if (
			! empty( $form_data['settings']['akismet'] ) &&
			self::is_configured() &&
			$this->entry_is_spam( $form_data, $entry )
		) {
			// This string is being logged not printed, so it does not need to be translatable.
			return esc_html__( 'Anti-spam verification failed, please try again later.', 'wpforms-lite' );
		}

		return false;
	}
}
