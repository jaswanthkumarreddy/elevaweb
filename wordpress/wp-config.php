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
define('DB_NAME', 'eleva_2017');

/** MySQL database username */
define('DB_USER', 'pbls_usr');

/** MySQL database password */
define('DB_PASSWORD', 'pbls_pwd_admin');

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
define('AUTH_KEY',         'iH5[U_4lZN&jg#BN%%0T>#0`V2*alT}0fKU0A0@iGWcGwlS0TIFS)`sQ!%t1s+Sz');
define('SECURE_AUTH_KEY',  'Fbej22];YHXWL49aZhE3o 1E|7H1TxeS|,CB-]Nw (xlI!<PPFW4xR*$>W_owT(U');
define('LOGGED_IN_KEY',    'GTe20)tvRxDo<5s)I)@0d*/RO1KR)A0L42 i$VcIQkT$J*OAxZ,DBoK0{BVz$EH9');
define('NONCE_KEY',        'I+V&l26 I*V?Z; ygxlv@*c|:SB4e+tWOE!)jmf(i=bE:8fPS&91`7L{5B@)( HW');
define('AUTH_SALT',        's K(NYi@._5[gkwfH$S>/~`27Pl_eK<j;BG!)X<UB:U8pl44z:{JwfDwO^ `2 ,B');
define('SECURE_AUTH_SALT', '7uRV|$mAtG1yG5sC=D[Y6]>vPaRjISp2Q,#*!4/KNkfAE<.ziP9KybE 3@{Y*m1 ');
define('LOGGED_IN_SALT',   'XE9~tT?r,V@,{5}M0egMYMolNW?akl*4hgTIDKz={^$bp &Y$L:v=n.p-B mNY|R');
define('NONCE_SALT',       '}i<m=+=c~c|K$(]GMN (byo:*rtXVu(zXiNt C=!a|k$-x&U`Qt.j)4)@!u<*M$?');

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
