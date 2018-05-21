<?php
define('DB_HOST', $_ENV['RDS_HOSTNAME']);
define('DB_NAME', $_ENV['RDS_DB_NAME']);

define('DB_USER', $_ENV['RDS_USERNAME']);
define('DB_PASSWORD', $_ENV['RDS_PASSWORD']);

define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');


define('AUTH_KEY',         'ySQTFg%xWMK+ZyuYxU{zYrVA.6W9e&yrV!^aL`+L||6oGzIog_:Yr]: O}5FZG9 ');
define('SECURE_AUTH_KEY',  'r>Sv,.2x[M3&D2k CU qB1A=_S)Z)!~GHjzGVnfrU&tLivZW@_uH&u)9N+>~m(Bz');
define('LOGGED_IN_KEY',    'xQM->GwqA,(ReG0OR1e[o#<ik(>s|=Gvfp9Hoa.kY|xHB~WO|edhWT%>})*D`c<P');
define('NONCE_KEY',        '1UuWD[{Ue(eGel5@QyRl5{S*z=/D/f}/>itvH1R(dV}s6>u:+qS;<TiL+weu6^4,');
define('AUTH_SALT',        'uG#aOu^IhqHiw{v I</>$h9,#?$PAd3_;G+$I)KdF:>[i]hsYbB[!zABXU_]Es0p');
define('SECURE_AUTH_SALT', 'du}83Vz*Q8ap~xFV}Q4DkE?H4N`[6L=I9Db:bZmt~q$$>DKXGK<.HVJ?~!u@)v(d');
define('LOGGED_IN_SALT',   'Ba^$6Aoay51`U=Fo[=p/*oKk3m%{zH.p1$f#eCnT($c@P{cBNejXQ+g7T? W:B#y');
define('NONCE_SALT',       'XnM%iza9aR~Z%R8h`iSXeRiC@06f;e6gd]j[PiEKO_gcu8|N6!_{?e]]MSC#KAQ ');
// define('AUTH_KEY',         $_ENV['AUTH_KEY']);
// define('SECURE_AUTH_KEY',  $_ENV['SECURE_AUTH_KEY']);
// define('LOGGED_IN_KEY',    $_ENV['LOGGED_IN_KEY']);
// define('NONCE_KEY',        $_ENV['NONCE_KEY']);
// define('AUTH_SALT',        $_ENV['AUTH_SALT']);
// define('SECURE_AUTH_SALT', $_ENV['SECURE_AUTH_SALT']);
// define('LOGGED_IN_SALT',   $_ENV['LOGGED_IN_SALT']);
// define('NONCE_SALT',       $_ENV['NONCE_SALT']);


$table_prefix  = 'wp_';
define('WP_DEBUG', false);
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');
