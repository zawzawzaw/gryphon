<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'gryphon');

/** MySQL database username */
define('DB_USER', 'gryphon_manic');

/** MySQL database password */
define('DB_PASSWORD', 'M4n1cDB2015');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'YTn934yAXDn_e}OJ~V<{&=//H*+Yrq-u}TC6te>|mOXG97fYu6|dJ-B+HEse3;nF');
define('SECURE_AUTH_KEY',  'KMG[>TM]!8`D^@B|aZ@Sv:hiy):KVEj*BR8v5F~LE;Rw?<<`Ybxef!7:};O-o=Yv');
define('LOGGED_IN_KEY',    '|jo19p1p+Hs>8yl?gX2=(;Zn-qE+Yx|QTw1p[VpAgV[py9^7aK<}uIDf9my7}p<+');
define('NONCE_KEY',        '.k^g(6+;Q/4q;8-A5LFv5M5n.,jc3o8uY8Nb2+X?&9g$SNE:+4*4p2yd=F@2/OM>');
define('AUTH_SALT',        'pBU=+V2I ^hUC)Z`-?21>#sU=OK`sw|}*PQ?~ZQjs?q$e9I-%])p6K.&4a#.MI!k');
define('SECURE_AUTH_SALT', 'e({hfF{fYwLB_-op8+V.*|*V6w=+a>V(t4<CXc1]Z_%`O2LX lVo`{m]~{@FgQxs');
define('LOGGED_IN_SALT',   'v+~+}#-bMyB*]$F`%quk1Or1>u?.)1]tg:!zKN1|aiL/6Pmz5S+Ns4*j6%KOHO6X');
define('NONCE_SALT',       'm2`qVuaL_y_+`R%ni+aY8wj{_q.^F|L-LAb#/+x^v}BU:tn6;[^-;DVOqOu-|+>d');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
