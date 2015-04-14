<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
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
define('DB_NAME', 'wwwsugbs_prefund');

/** MySQL database username */
define('DB_USER', 'wwwsugbs_prefund');

/** MySQL database password */
define('DB_PASSWORD', '11mv-S3P7p!');

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
define('AUTH_KEY',         'xwiidixxmuuithzxjknnzyjkh3qdsvxbrvzphhsrdnjdjemgcgcmnpb4brvzbe4j');
define('SECURE_AUTH_KEY',  't3i4rokzmwrhmwhhoyihuk76as548ri4p6txwi9h8rpewcjalgv8oslldgwqvhgj');
define('LOGGED_IN_KEY',    'qswzekqpwfagg5tyegaufqvbahmaxzzfqt5auxvagu70bnipsqxfrm8vaekih87y');
define('NONCE_KEY',        'vj1xekzayt3g0cfdr1awezvcgeapqvg13sigytm4gdjcgkigabdhshshtwtaxbx7');
define('AUTH_SALT',        'lagriwq4x1udepguqib3guwucwkconsger7pjqpzfrfree39kjvwtj1pxiq5cwzd');
define('SECURE_AUTH_SALT', 'xyoczydvy8m0em0t7wr7cnsmmhp9ymh5ttgsiq0qh4s0oapfhbh0ty5qgvbj3gkm');
define('LOGGED_IN_SALT',   '8gw3yu1ddc4ko7yenjpgtmz4lt2v2f1aaxt5b2payizwq4gr7pldljw12zczo8qx');
define('NONCE_SALT',       '2n0vcdc8pbkpqljyjhqxnzfbtr2co7t29ddk5p1gfwei3bo9jtznllwiz68gbjlt');

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
