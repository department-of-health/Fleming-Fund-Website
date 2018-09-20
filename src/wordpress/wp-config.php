<?php
//Begin Really Simple SSL Load balancing fix
if ((isset($_ENV["HTTPS"]) && ("on" == $_ENV["HTTPS"]))
  || (isset($_SERVER["HTTP_X_FORWARDED_SSL"]) && (strpos($_SERVER["HTTP_X_FORWARDED_SSL"], "1") !== false))
  || (isset($_SERVER["HTTP_X_FORWARDED_SSL"]) && (strpos($_SERVER["HTTP_X_FORWARDED_SSL"], "on") !== false))
  || (isset($_SERVER["HTTP_CF_VISITOR"]) && (strpos($_SERVER["HTTP_CF_VISITOR"], "https") !== false))
  || (isset($_SERVER["HTTP_CLOUDFRONT_FORWARDED_PROTO"]) && (strpos($_SERVER["HTTP_CLOUDFRONT_FORWARDED_PROTO"], "https") !== false))
  || (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && (strpos($_SERVER["HTTP_X_FORWARDED_PROTO"], "https") !== false))) {
  $_SERVER["HTTPS"] = "on";
}
//END Really Simple SSL

define('DB_HOST',     $_ENV['DB_HOST']);
define('DB_NAME',     $_ENV['DB_NAME']);
define('DB_USER',     $_ENV['DB_USER']);
define('DB_PASSWORD', $_ENV['DB_PASSWORD']);

define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');


define('AUTH_KEY',          $_ENV['AUTH_KEY']);
define('SECURE_AUTH_KEY',   $_ENV['SECURE_AUTH_KEY']);
define('LOGGED_IN_KEY',     $_ENV['LOGGED_IN_KEY']);
define('NONCE_KEY',         $_ENV['NONCE_KEY']);
define('AUTH_SALT',         $_ENV['AUTH_SALT']);
define('SECURE_AUTH_SALT',  $_ENV['SECURE_AUTH_SALT']);
define('LOGGED_IN_SALT',    $_ENV['LOGGED_IN_SALT']);
define('NONCE_SALT',        $_ENV['NONCE_SALT']);

if (isset($_ENV["WP_CONTENT_URL"])) {
  define('WP_CONTENT_URL', $_ENV["WP_CONTENT_URL"]);
}

if (isset($_SERVER["HTTP_X_AMZ_CF_ID"])) {
  # From CloudFront
  # Use the CloudFront domain instead of the origin
  if (isset($_ENV["CLOUDFRONT_HOME"])) {
    define('WP_HOME', $_ENV["CLOUDFRONT_HOME"]);
    define('WP_SITEURL', $_ENV["CLOUDFRONT_HOME"]);
  } else {
    define('WP_HOME', 'https://www.flemingfund.org');
    define('WP_SITEURL', 'https://www.flemingfund.org');
  }
} elseif (isset($_ENV["WP_HOME"])) {
  define('WP_HOME', $_ENV["WP_HOME"]);
  define('WP_SITEURL', $_ENV["WP_HOME"]);
}

$table_prefix = 'wp_';
define('WP_DEBUG', (isset($_ENV["FLEM_ENV"]) && ("local-dev" == $_ENV["FLEM_ENV"])));
if (!defined('ABSPATH'))
  define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');
