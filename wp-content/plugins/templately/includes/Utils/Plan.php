<?php

namespace Templately\Utils;

class Plan extends Base {
    const ALL     = 1;
    const STARTER = 2;
    const PRO     = 3;

	/**
	 * Get the plan ID.
	 *
	 * @since 2.0.0
	 * @param string $plan
	 *
	 * @return int
	 */
    public static function get( $plan = 'all' ) {
        $plan = strtoupper( $plan );
        return $plan === 'STARTER' ? self::STARTER : ( $plan === 'PRO' ? self::PRO : self::ALL );
    }
}