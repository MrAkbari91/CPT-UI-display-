<?php

namespace WPForms\Integrations\Stripe\Api\Webhooks;

use WPForms\Integrations\Stripe\Helpers;
use RuntimeException;

/**
 * Webhook charge.succeeded class.
 *
 * @since 1.8.4
 */
class ChargeSucceeded extends Base {

	/**
	 * Handle the Webhook's data.
	 *
	 * @since 1.8.4
	 *
	 * @throws RuntimeException If payment not found or not updated.
	 *
	 * @return bool
	 */
	public function handle() {

		$this->delay();

		$this->set_payment();

		if ( ! $this->db_payment ) {
			return false;
		}

		if ( $this->db_payment->status !== 'processed' ) {
			return false;
		}

		$currency  = strtoupper( $this->data->currency );
		$db_amount = wpforms_format_amount( $this->db_payment->total_amount );
		$amount    = wpforms_format_amount( $this->data->amount_captured / Helpers::get_decimals_amount( $currency ) );

		if ( $amount !== $db_amount || ! $this->data->paid ) {
			return false;
		}

		if ( ! $this->is_previous_statuses_matched() ) {
			return false;
		}

		$updated_payment = wpforms()->get( 'payment' )->update(
			$this->db_payment->id,
			[
				'status'           => 'completed',
				'date_updated_gmt' => gmdate( 'Y-m-d H:i:s' ),
			]
		);

		if ( ! $updated_payment ) {
			throw new RuntimeException( 'Payment not updated' );
		}

		wpforms()->get( 'payment_meta' )->add_log(
			$this->db_payment->id,
			'Stripe payment was completed.'
		);

		return true;
	}
}
