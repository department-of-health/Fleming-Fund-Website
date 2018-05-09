<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'wordpress_user');

/** MySQL database password */
define('DB_PASSWORD', 'jellyfish_123');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');


/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'ySQTFg%xWMK+ZyuYxU{zYrVA.6W9e&yrV!^aL`+L||6oGzIog_:Yr]: O}5FZG9 ');
define('SECURE_AUTH_KEY',  'r>Sv,.2x[M3&D2k CU qB1A=_S)Z)!~GHjzGVnfrU&tLivZW@_uH&u)9N+>~m(Bz');
define('LOGGED_IN_KEY',    'xQM->GwqA,(ReG0OR1e[o#<ik(>s|=Gvfp9Hoa.kY|xHB~WO|edhWT%>})*D`c<P');
define('NONCE_KEY',        '1UuWD[{Ue(eGel5@QyRl5{S*z=/D/f}/>itvH1R(dV}s6>u:+qS;<TiL+weu6^4,');
define('AUTH_SALT',        'uG#aOu^IhqHiw{v I</>$h9,#?$PAd3_;G+$I)KdF:>[i]hsYbB[!zABXU_]Es0p');
define('SECURE_AUTH_SALT', 'du}83Vz*Q8ap~xFV}Q4DkE?H4N`[6L=I9Db:bZmt~q$$>DKXGK<.HVJ?~!u@)v(d');
define('LOGGED_IN_SALT',   'Ba^$6Aoay51`U=Fo[=p/*oKk3m%{zH.p1$f#eCnT($c@P{cBNejXQ+g7T? W:B#y');
define('NONCE_SALT',       'XnM%iza9aR~Z%R8h`iSXeRiC@06f;e6gd]j[PiEKO_gcu8|N6!_{?e]]MSC#KAQ ');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
