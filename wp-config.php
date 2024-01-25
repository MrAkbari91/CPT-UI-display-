<?php
define( 'WP_CACHE', true ); // Added by WP Rocket

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wpcptui' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'egL5UaI4KpaLtI:%[F-#gb/#/Rv=4H2L>U&!zpnPX{s,3PEYtsSM!eorD7iBF4dL' );
define( 'SECURE_AUTH_KEY',  '?[4UPO22}tj>@]EB/omIc4z(4rpD[yo-S74GZDL8B8HNS:;5)wlC_[Q(}+^@aD0 ' );
define( 'LOGGED_IN_KEY',    'S9cj6<a3Zf/-~mL5!V5_|T][<F0$G$(2<R0),;B]HG&Xf{`].g>zD[8<d`ijF6Rp' );
define( 'NONCE_KEY',        '>.3(}TwIy~U91CwE/Oi$e~5R6D.VH,J[gM0(5|f~Ejkb8JNuL0/}L76PJ3}5}pm-' );
define( 'AUTH_SALT',        'vu#kR1kaUW-<O9GIKPTOzCha+Iqvw)xb/IpFf%xVH9O5>xXDI7bR#T0XMy4;1pIR' );
define( 'SECURE_AUTH_SALT', 'gB;o<nDq/MHLV~ck8Z!#X;^43W_KB0{ EL7E^#Lm(u6?v3@Z,RO|>v1l1Qa7tRce' );
define( 'LOGGED_IN_SALT',   '`!y]FNL=;Lh|^f3~RYc!^zZg:yM#x{}a#}`q{qeVL* b%-0vubQu}Gtc-Yt%9Tb&' );
define( 'NONCE_SALT',       '4>NKo{Ym3gI|qki]4),[N<:jr3sFV-#llhDnQv/#4%oB$e(fPqBoE0!=vg|S]YRN' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
