<?php
/**
 * Stat: Handles AJAX requests for retrieving statistical data.
 *
 * This class processes AJAX requests to retrieve statistical data based on a specified filter (e.g., today, week, month, year)
 * and types. It calculates and returns the statistics for each requested type.
 *
 * @package YD\Mobile_App
 * @subpackage Admin\Ajax
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Admin\Ajax;

defined( 'ABSPATH' ) || exit;

/**
 * Handles AJAX requests for retrieving statistical data based on the specified filter and types.
 */
final class Stat extends \YD\Admin\Ajax {

	/**
	 * Returns the action name for the AJAX request.
	 *
	 * @return string Action name.
	 */
	protected function get_action_name(): string {
		return 'stat';
	}

	/**
	 * Returns the nonce string for the AJAX request.
	 *
	 * @return string Nonce string.
	 */
	protected function get_wp_nonce(): string {
		return 'stat';
	}

	/**
	 * Returns the validation rules for the incoming AJAX data.
	 *
	 * @return array Validation rules.
	 */
	protected function get_rules(): array {
		return array(
			'filter' => array(
				'type'     => 'enum',
				'values'   => array(
					'today',
					'week',
					'month',
					'year',
				),
				'required' => true,
				'default'  => 'today',
			),
			'types'  => array(
				'type'       => 'array',
				'item_rules' => array( 'type' => 'string' ),
				'required'   => true,
				'default'    => array(),
			),
		);
	}

	/**
	 * Handles the AJAX request for fetching statistical data based on the specified filter and types.
	 * Calculates and returns the statistics for each requested type.
	 *
	 * @return void
	 */
	protected function get_action() {
		$results = array();
		foreach ( $this->data['types'] as $type ) {
			$stat             = \YD\Mobile_App\Admin\Stat::get_stat_by_type( $type );
			$results[ $type ] = null === $stat ? 0 : $stat->calculate( $this->data['filter'] );
		}
		parent::send_success( $results );
	}
}
