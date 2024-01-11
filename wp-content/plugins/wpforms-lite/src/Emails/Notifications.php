<?php

namespace WPForms\Emails;

use WPForms_WP_Emails;
use WPForms\Tasks\Actions\EntryEmailsTask;

/**
 * Class Notifications.
 * Used to send email notifications.
 *
 * @since 1.8.5
 */
class Notifications extends Mailer {

	/**
	 * List of submitted fields.
	 *
	 * @since 1.8.5
	 *
	 * @var array
	 */
	public $fields = [];

	/**
	 * Form data.
	 *
	 * @since 1.8.5
	 *
	 * @var array
	 */
	public $form_data = [];

	/**
	 * Entry id.
	 *
	 * @since 1.8.5
	 *
	 * @var int
	 */
	public $entry_id;

	/**
	 * Notification ID that is currently being processed.
	 *
	 * @since 1.8.5
	 *
	 * @var int
	 */
	public $notification_id = '';

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
	protected $field_template;

	/**
	 * Default email template name.
	 *
	 * @since 1.8.5
	 *
	 * @var string
	 */
	const DEFAULT_TEMPLATE = 'classic';

	/**
	 * Plain/Text email template name.
	 *
	 * @since 1.8.5
	 *
	 * @var string
	 */
	const PLAIN_TEMPLATE = 'none';

	/**
	 * Legacy email template name.
	 *
	 * @since 1.8.5
	 *
	 * @var string
	 */
	const LEGACY_TEMPLATE = 'default';

	/**
	 * This method will initialize the class.
	 *
	 * Maybe use the old class for backward compatibility.
	 * The old class might be removed in the future.
	 *
	 * @since 1.8.5
	 *
	 * @param string $template Email template name.
	 *
	 * @return $this|WPForms_WP_Emails
	 */
	public function init( $template = '' ) {

		// Assign the current template.
		$this->current_template = Helpers::get_current_template_name( $template );

		// If the old class doesn't exist, return the current class.
		// The old class might be removed in the future.
		if ( ! class_exists( 'WPForms_WP_Emails' ) ) {
			return $this;
		}

		// In case user is still using the old "Legacy" default template, use the old class.
		// Use the old class if the current template is "Legacy".
		if ( $this->current_template === self::LEGACY_TEMPLATE ) {
			return new WPForms_WP_Emails();
		}

		// Plain text and other html templates will use the current class.
		return $this;
	}

	/**
	 * Maybe send an email right away or schedule it.
	 *
	 * @since 1.8.5
	 *
	 * @return bool Whether the email was sent successfully.
	 */
	public function send() {

		// Leave the method if the arguments are empty.
		// We will be looking for 3 arguments: $to, $subject, $message.
		// The primary reason for this method not to take any direct arguments is to make it compatible with the parent class.
		if ( empty( func_get_args() ) || count( func_get_args() ) < 3 ) {
			return false;
		}

		// Don't send anything if emails have been disabled.
		if ( $this->is_email_disabled() ) {
			return false;
		}

		// Set the arguments.
		list( $to, $subject, $message ) = func_get_args();

		// Don't send if email address is invalid.
		if ( ! is_email( $to ) ) {
			return false;
		}

		/**
		 * Fires before the email is sent.
		 *
		 * The filter has been ported from "class-emails.php" to maintain backward compatibility
		 * and avoid unintended breaking changes where these hooks may have been used.
		 *
		 * @since 1.8.5.2
		 *
		 * @param Notifications $this An instance of the "Notifications" class.
		 */
		do_action( 'wpforms_email_send_before', $this ); // phpcs:ignore WPForms.PHP.ValidateHooks.InvalidHookName

		// Set the attachments to an empty array.
		// We will set the attachments later in the filter.
		$attachments = [];

		/**
		 * Filter the email data before sending.
		 *
		 * The filter has been ported from "class-emails.php" to maintain backward compatibility
		 * and avoid unintended breaking changes where these hooks may have been used.
		 *
		 * @since 1.8.5
		 *
		 * @param array         $data Email data.
		 * @param Notifications $this An instance of the "Notifications" class.
		 */
		$data = (array) apply_filters( // phpcs:ignore WPForms.PHP.ValidateHooks.InvalidHookName
			'wpforms_emails_send_email_data',
			[
				'to'          => $to,
				'subject'     => $subject,
				'message'     => $message,
				'headers'     => $this->get_headers(),
				'attachments' => $attachments,
			],
			$this
		);

		// Set the recipient email address.
		$this->to_email( $data['to'] );

		// Set the email subject.
		$this->subject( $this->process_subject( $data['subject'] ) );

		// Process the email template.
		$this->process_email_template( $data['message'] );

		// Set the attachments to the email.
		$this->__set( 'attachments', $data['attachments'] );

		/**
		 * Filter whether to send the email in the same process.
		 *
		 * The filter has been ported from "class-emails.php" to maintain backward compatibility
		 * and avoid unintended breaking changes where these hooks may have been used.
		 *
		 * @since 1.8.5
		 *
		 * @param bool   $send_same_process Whether to send the email in the same process.
		 * @param array  $fields            List of submitted fields.
		 * @param array  $entry             Entry data.
		 * @param array  $form_data         Form data.
		 * @param int    $entry_id          Entry ID.
		 * @param string $type              Email type.
		 */
		$send_same_process = (bool) apply_filters( // phpcs:ignore WPForms.PHP.ValidateHooks.InvalidHookName, WPForms.Comments.ParamTagHooks.InvalidParamTagsQuantity
			'wpforms_tasks_entry_emails_trigger_send_same_process',
			false,
			$this->fields,
			! empty( wpforms()->get( 'entry' ) ) ? wpforms()->get( 'entry' )->get( $this->entry_id ) : [],
			$this->form_data,
			$this->entry_id,
			'entry'
		);

		// Send the email immediately.
		if ( $send_same_process || ! empty( $this->form_data['settings']['disable_entries'] ) ) {
			$results = parent::send();
		} else {
			$results = (bool) ( new EntryEmailsTask() )
				->params(
					$this->__get( 'to_email' ),
					$this->__get( 'subject' ),
					$this->get_message(),
					$this->get_headers(),
					$this->get_attachments()
				)
				->register();
		}

		/**
		 * Fires after the email has been sent.
		 *
		 * The filter has been ported from "class-emails.php" to maintain backward compatibility
		 * and avoid unintended breaking changes where these hooks may have been used.
		 *
		 * @since 1.8.5.2
		 *
		 * @param Notifications $this An instance of the "Notifications" class.
		 */
		do_action( 'wpforms_email_send_after', $this ); // phpcs:ignore WPForms.PHP.ValidateHooks.InvalidHookName

		return $results;
	}

	/**
	 * Process the email template.
	 *
	 * @since 1.8.5
	 *
	 * @param string $message Email message.
	 */
	private function process_email_template( $message ) {

		$template = self::get_available_templates( $this->current_template );

		// Return if the template is not set.
		// This can happen if the template is not found or if the template class doesn't exist.
		if ( ! isset( $template['path'] ) || ! class_exists( $template['path'] ) ) {
			return;
		}

		// Set the email template, i.e. WPForms\Emails\Templates\Classic.
		$this->template( new $template['path']( '', false, $this->current_template ) );

		// Set the field template.
		$this->field_template = $this->template->get_field_template();

		// Set the email template fields.
		$this->template->set_field( $this->process_message( $message ) );

		$content = $this->template->get();

		// Return if the template is empty.
		if ( ! $content ) {
			return;
		}

		$this->message( $content );
	}

	/**
	 * Format and process the email subject.
	 *
	 * @since 1.8.5
	 *
	 * @param string $subject Email subject.
	 *
	 * @return string
	 */
	private function process_subject( $subject ) {

		$subject = $this->process_tag( $subject );
		$subject = trim( str_replace( [ "\r\n", "\r", "\n" ], ' ', $subject ) );

		return wpforms_decode_string( $subject );
	}

	/**
	 * Process the email message.
	 *
	 * @since 1.8.5
	 *
	 * @param string $message Email message.
	 *
	 * @return string
	 */
	private function process_message( $message ) {

		$message = $this->process_tag( $message );
		$message = str_replace( '{all_fields}', $this->process_field_values(), $message );

		/**
		 * Filter and modify the email message content before sending.
		 * This filter allows customizing the email message content for notifications.
		 *
		 * @since 1.8.5
		 *
		 * @param string        $message  The email message to be sent out.
		 * @param string        $template The email template name.
		 * @param Notifications $this     The instance of the "Notifications" class.
		 */
		$message = apply_filters( 'wpforms_emails_notifications_message', $message, $this->current_template, $this );

		// Leave early if the template is set to plain text.
		if ( Helpers::is_plain_text_template( $this->current_template ) ) {
			return $message;
		}

		return make_clickable( str_replace( "\r\n", '<br/>', $message ) );
	}

	/**
	 * Process the field values.
	 *
	 * @since 1.8.5
	 *
	 * @return string
	 */
	private function process_field_values() {

		// If fields are empty, return an empty message.
		if ( empty( $this->fields ) ) {
			return '';
		}

		// If no message was generated, create an empty message.
		$default_message = esc_html__( 'An empty form was submitted.', 'wpforms-lite' );

		/**
		 * Filter whether to display empty fields in the email.
		 *
		 * @since 1.8.5
		 * @deprecated 1.8.5.2
		 *
		 * @param bool $show_empty_fields Whether to display empty fields in the email.
		 */
		$show_empty_fields = apply_filters_deprecated( // phpcs:disable WPForms.Comments.ParamTagHooks.InvalidParamTagsQuantity
			'wpforms_emails_notifications_display_empty_fields',
			[ false ],
			'1.8.5.2 of the WPForms plugin',
			'wpforms_email_display_empty_fields'
		);

		/** This filter is documented in /includes/emails/class-emails.php */
		$show_empty_fields = apply_filters( // phpcs:ignore WPForms.PHP.ValidateHooks.InvalidHookName
			'wpforms_email_display_empty_fields',
			false
		);

		// Process either plain text or HTML message based on the template type.
		if ( Helpers::is_plain_text_template( $this->current_template ) ) {
			$message = $this->process_plain_message( $show_empty_fields );
		} else {
			$message = $this->process_html_message( $show_empty_fields );
		}

		return empty( $message ) ? $default_message : $message;
	}

	/**
	 * Process the plain text email message.
	 *
	 * @since 1.8.5
	 *
	 * @param bool $show_empty_fields Whether to display empty fields in the email.
	 *
	 * @return string
	 */
	private function process_plain_message( $show_empty_fields = false ) {

		$message = '';

		foreach ( $this->fields as $field ) {

			if ( ! $show_empty_fields && ( ! isset( $field['value'] ) || (string) $field['value'] === '' ) ) {
				continue;
			}

			$field_name = isset( $field['name'] ) ? $field['name'] : '';
			$field_val  = empty( $field['value'] ) && ! is_numeric( $field['value'] ) ? esc_html__( '(empty)', 'wpforms-lite' ) : $field['value'];

			// Set a default field name if empty.
			if ( empty( $field_name ) && $field_name !== null ) {
				$field_name = $this->get_default_field_name( $field['id'] );
			}

			$message    .= '--- ' . $field_name . " ---\r\n\r\n";
			$field_value = wpforms_decode_string( $field_val ) . "\r\n\r\n";

			/**
			 * Filter the field value before it is added to the email message.
			 *
			 * @since 1.8.5
			 *
			 * @param string $field_value Field value.
			 * @param array  $field       Field data.
			 * @param array  $form_data   Form data.
			 */
			$message .= apply_filters( 'wpforms_emails_notifications_plaintext_field_value', $field_value, $field, $this->form_data );
		}

		return $message;
	}

	/**
	 * Process the HTML email message.
	 *
	 * @since 1.8.5
	 *
	 * @param bool $show_empty_fields Whether to display empty fields in the email.
	 *
	 * @return string
	 */
	private function process_html_message( $show_empty_fields = false ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity

		$message = '';

		/**
		 * Filter the list of field types to display in the email.
		 *
		 * @since 1.8.5
		 * @deprecated 1.8.5.2
		 *
		 * @param array $other_fields List of field types.
		 * @param array $form_data    Form data.
		 */
		$other_fields = apply_filters_deprecated( // phpcs:disable WPForms.Comments.ParamTagHooks.InvalidParamTagsQuantity
			'wpforms_emails_notifications_display_other_fields',
			[ [], $this->form_data ],
			'1.8.5.2 of the WPForms plugin',
			'wpforms_email_display_other_fields'
		);

		/** This filter is documented in /includes/emails/class-emails.php */
		$other_fields = apply_filters( // phpcs:ignore WPForms.PHP.ValidateHooks.InvalidHookName
			'wpforms_email_display_other_fields',
			[],
			$this
		);

		foreach ( $this->form_data['fields'] as $field_id => $field ) {
			$field_type = ! empty( $field['type'] ) ? $field['type'] : '';

			// Check if the field is empty in $this->fields.
			if ( empty( $this->fields[ $field_id ] ) ) {
				// Check if the field type is in $other_fields, otherwise skip.
				if ( empty( $other_fields ) || ! in_array( $field_type, $other_fields, true ) ) {
					continue;
				}

				// Handle specific field types.
				list( $field_name, $field_val ) = $this->process_special_field_values( $field );
			} else {
				// Handle fields that are not empty in $this->fields.
				if ( ! $show_empty_fields && ( ! isset( $this->fields[ $field_id ]['value'] ) || (string) $this->fields[ $field_id ]['value'] === '' ) ) {
					continue;
				}

				$field_name = isset( $this->fields[ $field_id ]['name'] ) ? $this->fields[ $field_id ]['name'] : '';
				$field_val  = empty( $this->fields[ $field_id ]['value'] ) && ! is_numeric( $this->fields[ $field_id ]['value'] ) ? '<em>' . esc_html__( '(empty)', 'wpforms-lite' ) . '</em>' : $this->fields[ $field_id ]['value'];
			}

			// Set a default field name if empty.
			if ( empty( $field_name ) && $field_name !== null ) {
				$field_name = $this->get_default_field_name( $field_id );
			}

			/** This filter is documented in src/SmartTags/SmartTag/FieldHtmlId.php.*/
			$field_val = apply_filters( // phpcs:ignore WPForms.PHP.ValidateHooks.InvalidHookName
				'wpforms_html_field_value',
				$field_val,
				isset( $this->fields[ $field_id ] ) ? $this->fields[ $field_id ] : $field,
				$this->form_data,
				'email-html'
			);

			// Append the field item to the message.
			$message .= str_replace(
				[ '{field_type}', '{field_name}', '{field_value}' ],
				[ $field_type, $field_name, $field_val ],
				$this->field_template
			);
		}

		return $message;
	}

	/**
	 * Process a smart tag.
	 *
	 * @since 1.8.5
	 *
	 * @param string $input Smart tag.
	 *
	 * @return string
	 */
	private function process_tag( $input = '' ) {

		return wpforms_process_smart_tags( $input, $this->form_data, $this->fields, $this->entry_id );
	}

	/**
	 * Process special field types.
	 * This is used for fields such as Page Break, HTML, Content, etc.
	 *
	 * @since 1.8.5
	 *
	 * @param array $field Field data.
	 *
	 * @return array
	 */
	private function process_special_field_values( $field ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity

		$field_name = null;
		$field_val  = null;

		// Use a switch-case statement to handle specific field types.
		switch ( $field['type'] ) {
			case 'divider':
				$field_name = ! empty( $field['label'] ) ? str_repeat( '&mdash;', 3 ) . ' ' . $field['label'] . ' ' . str_repeat( '&mdash;', 3 ) : null;
				$field_val  = ! empty( $field['description'] ) ? $field['description'] : '';
				break;

			case 'pagebreak':
				// Skip if position is 'bottom'.
				if ( ! empty( $field['position'] ) && $field['position'] === 'bottom' ) {
					break;
				}

				$title      = ! empty( $field['title'] ) ? $field['title'] : esc_html__( 'Page Break', 'wpforms-lite' );
				$field_name = str_repeat( '&mdash;', 6 ) . ' ' . $title . ' ' . str_repeat( '&mdash;', 6 );
				break;

			case 'html':
				// Skip if the field is conditionally hidden.
				if ( $this->is_field_conditionally_hidden( $field['id'] ) ) {
					break;
				}

				$field_name = ! empty( $field['name'] ) ? $field['name'] : esc_html__( 'HTML / Code Block', 'wpforms-lite' );
				$field_val  = $field['code'];
				break;

			case 'content':
				// Skip if the field is conditionally hidden.
				if ( $this->is_field_conditionally_hidden( $field['id'] ) ) {
					break;
				}

				$field_name = esc_html__( 'Content', 'wpforms-lite' );
				$field_val  = $field['content'];
				break;

			default:
				$field_name = '';
				$field_val  = '';
				break;
		}

		return [ $field_name, $field_val ];
	}

	/**
	 * Checks if conditional_logic is enabled and a field is conditionally hidden in the form.
	 *
	 * @since 1.8.5
	 *
	 * @param int $field_id The ID of the field to check.
	 *
	 * @return bool
	 */
	private function is_field_conditionally_hidden( $field_id ) {

		return ! empty( $this->form_data['fields'][ $field_id ]['conditionals'] ) && ! wpforms_conditional_logic_fields()->field_is_visible( $this->form_data, $field_id );
	}

	/**
	 * Get the email reply to address.
	 * This method has been overridden to add support for the Reply-to Name.
	 *
	 * @since 1.8.5
	 *
	 * @return string
	 */
	public function get_reply_to_address() {

		$reply_to      = $this->__get( 'reply_to' );
		$reply_to_name = false;

		if ( ! empty( $reply_to ) ) {

			// Optional custom format with a Reply-to Name specified: John Doe <john@doe.com>
			// - starts with anything,
			// - followed by space,
			// - ends with <anything> (expected to be an email, validated later).
			$regex   = '/^(.+) (<.+>)$/';
			$matches = [];

			if ( preg_match( $regex, $reply_to, $matches ) ) {
				$reply_to_name = $this->sanitize( $matches[1] );
				$reply_to      = trim( $matches[2], '<> ' );
			}

			$reply_to = $this->process_tag( $reply_to );

			if ( ! is_email( $reply_to ) ) {
				$reply_to      = false;
				$reply_to_name = false;
			}
		}

		if ( $reply_to_name ) {
			$reply_to = "$reply_to_name <{$reply_to}>";
		}

		/**
		 * Filter the email reply-to address.
		 *
		 * @since 1.8.5
		 *
		 * @param string $reply_to Email reply-to address.
		 * @param object $this     Instance of the Notifications class.
		 */
		return apply_filters( 'wpforms_emails_notifications_get_reply_to_address', $reply_to, $this );
	}

	/**
	 * Sanitize the string.
	 * This method has been overridden to add support for processing smart tags.
	 *
	 * @since 1.8.5
	 *
	 * @param string $input String to sanitize and process for smart tags.
	 *
	 * @return string
	 */
	public function sanitize( $input = '' ) {

		return wpforms_decode_string( $this->process_tag( $input ) );
	}

	/**
	 * Get the email content type.
	 * This method has been overridden to better declare email template assigned to each notification.
	 *
	 * @since 1.8.5.2
	 *
	 * @return string
	 */
	public function get_content_type() {

		$content_type = 'text/html';

		if ( Helpers::is_plain_text_template( $this->current_template ) ) {
			$content_type = 'text/plain';
		}

		/**
		 * Filter the email content type.
		 *
		 * @since 1.8.5.2
		 *
		 * @param string        $content_type The email content type.
		 * @param Notifications $this         An instance of the "Notifications" class.
		 */
		$content_type = apply_filters( 'wpforms_emails_notifications_get_content_type', $content_type, $this );

		// Set the content type.
		$this->__set( 'content_type', $content_type );

		// Return the content type.
		return $content_type;
	}

	/**
	 * Check if all emails are disabled.
	 *
	 * @since 1.8.5
	 *
	 * @return bool
	 */
	public function is_email_disabled() {

		/**
		 * Filter to control email disabling.
		 *
		 * The "Notifications" class is designed to mirror the properties and methods
		 * provided by the "WPForms_WP_Emails" class for backward compatibility.
		 *
		 * @since 1.8.5
		 *
		 * @param bool          $is_disabled Whether to disable all emails.
		 * @param Notifications $this        An instance of the "Notifications" class.
		 */
		return (bool) apply_filters( // phpcs:ignore WPForms.PHP.ValidateHooks.InvalidHookName
			'wpforms_disable_all_emails',
			false,
			$this
		);
	}

	/**
	 * Get the default field name as a fallback.
	 *
	 * @since 1.8.5
	 *
	 * @param int $field_id Field ID.
	 *
	 * @return string
	 */
	private function get_default_field_name( $field_id ) {

		return sprintf( /* translators: %1$d - field ID. */
			esc_html__( 'Field ID #%1$d', 'wpforms-lite' ),
			absint( $field_id )
		);
	}

	/**
	 * Get the list of available email templates.
	 *
	 * Given a template name, this method will return the template data.
	 * If no template name is provided, all available templates will be returned.
	 *
	 * Templates will go through a conditional check to make sure they are available for the current plugin edition.
	 *
	 * @since 1.8.5
	 *
	 * @param string $template Template name. If empty, all available templates will be returned.
	 *
	 * @return array
	 */
	public static function get_available_templates( $template = '' ) {

		$templates = self::get_all_templates();

		// Filter the list of available email templates based on the edition of WPForms.
		if ( ! wpforms()->is_pro() ) {
			$templates = array_filter(
				$templates,
				static function ( $instance ) {

					return ! $instance['is_pro'];
				}
			);
		}

		return isset( $templates[ $template ] ) ? $templates[ $template ] : $templates;
	}

	/**
	 * Get the list of all email templates.
	 *
	 * Given the name of a template, this method will return the template data.
	 * If the template is not found, all available templates will be returned.
	 *
	 * @since 1.8.5
	 *
	 * @param string $template Template name. If empty, all templates will be returned.
	 *
	 * @return array
	 */
	public static function get_all_templates( $template = '' ) {

		$templates = [
			'classic' => [
				'name'   => esc_html__( 'Classic', 'wpforms-lite' ),
				'path'   => __NAMESPACE__ . '\Templates\Classic',
				'is_pro' => false,
			],
			'compact' => [
				'name'   => esc_html__( 'Compact', 'wpforms-lite' ),
				'path'   => __NAMESPACE__ . '\Templates\Compact',
				'is_pro' => false,
			],
			'modern'  => [
				'name'   => esc_html__( 'Modern', 'wpforms-lite' ),
				'path'   => 'WPForms\Pro\Emails\Templates\Modern',
				'is_pro' => true,
			],
			'elegant' => [
				'name'   => esc_html__( 'Elegant', 'wpforms-lite' ),
				'path'   => 'WPForms\Pro\Emails\Templates\Elegant',
				'is_pro' => true,
			],
			'tech'    => [
				'name'   => esc_html__( 'Tech', 'wpforms-lite' ),
				'path'   => 'WPForms\Pro\Emails\Templates\Tech',
				'is_pro' => true,
			],
			'none'    => [
				'name'   => esc_html__( 'Plain Text', 'wpforms-lite' ),
				'path'   => __NAMESPACE__ . '\Templates\Plain',
				'is_pro' => false,
			],
		];

		// Make sure the current user can preview templates.
		if ( wpforms_current_user_can() ) {
			// Add a preview key to each template.
			foreach ( $templates as $key => &$tmpl ) {
				$tmpl['preview'] = wp_nonce_url(
					add_query_arg(
						[
							'wpforms_email_preview'  => '1',
							'wpforms_email_template' => $key,
						],
						admin_url()
					),
					Preview::PREVIEW_NONCE_NAME
				);
			}

			// Make sure to unset the reference to avoid unintended changes later.
			unset( $tmpl );
		}

		return isset( $templates[ $template ] ) ? $templates[ $template ] : $templates;
	}
}
