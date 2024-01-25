<?php

namespace Templately\Utils;

class Database extends Base {

    /**
     * Set transient to options table
     *
     * @since 2.0.0
     *
     * @param string $key
     * @param mixed $value
     * @param int $expiration
     *
     * @return bool
     */
    public static function set_transient( $key, $value, $expiration = DAY_IN_SECONDS ) {
        $key = '_templately_' . trim( $key );
        return set_transient( $key, $value, $expiration );
    }

    /**
     * Get transient data from options table.
     *
     * @since 2.0.0
     * @param string $key
     *
     * @return mixed
     */
    public static function get_transient( $key ) {
        $key = '_templately_' . trim( $key );
        return get_transient( $key );
    }


}