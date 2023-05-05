<?php

namespace WML\Classes;

/**
 * Capture Mail
 *
 * This class is to manage capture mail & save to databse.
 *
 */
class Capture_Mail {

	/**
	 * This function fire on wp_mail filter in bootstrap file.
	 *
	 * This function will save the email data to database table.
	 *
	 * @access public
	 * @since 0.3
	 * @param array @var $mail_info this info comes with filter.
	 * @return array unmodified $mail_info
	 */
	public static function log_email( $mail_info ) {
		global $wpdb;
		$table_name         = $wpdb->prefix . 'wml_entries';
		$attachment_present = ( is_array( $mail_info['attachments'] ) && count( $mail_info['attachments'] ) > 0 ) ? 'true' : 'false';
		if ( is_array( $mail_info['to'] ) ) {
			$mail_to = implode( ', ', $mail_info['to'] );
		} else {
			$mail_to = $mail_info['to'];
			$parts   = explode( ',', $mail_to );
			$mail_to = implode( ', ', $parts );
		}
		// Log into the database
		$wpdb->insert(
			$table_name,
			[
				'to_email'     => $mail_to,
				'subject'      => $mail_info['subject'],
				'message'      => $mail_info['message'],
				'headers'      => $mail_info['headers'],
				'attachments'  => $attachment_present,
				'sent_date'    => current_time( 'mysql', $gmt = 0 ),
				'captured_gmt' => current_time( 'mysql', $gmt = 1 ),
			]
		);

		// return unmodifiyed array
		return $mail_info;
	}
}
