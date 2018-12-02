<?php
define('EXTRA_FOLDER', '{{extra_folder}}');
define('CRM_CORE_FOLDER_PATH', '{{crm_core_path}}');

$_SSS['app']['base_dir'] = getcwd();
$_SSS['app']['tenant_folder'] = '{{folder_name}}'; //folder name
$_SSS['app']['asset_folder'] = 'uploads'; //folder name with a trailing slash only if there is an asset folder else leave blank

$_SSS['app']['tenant_url'] = 'http://'.$_SERVER["SERVER_NAME"].'/'.EXTRA_FOLDER.$_SSS['app']['tenant_folder'];
///The above config variable are defined to create the basic paths and URLs required for the app to work, which are defined just below


/* Main Paths and urls */
if(! defined('APP_TENANTPATH')){
    define('APP_TENANTPATH', $_SSS['app']['base_dir'].'/');//this will be used by ci_app config files to load this config file
    define('APP_TENANTURL', $_SSS['app']['tenant_url']);//this the path that will show on the browser and will be used for accessing the controllers and methods
    define('APP_ASSETURL', $_SSS['app']['tenant_url'].'/'.$_SSS['app']['asset_folder']);//this will be required to access tenant specific assets
}

define('APP_DB_HOSTNAME','{{db_host_name}}');
/* The username used to connect to the database */
define('APP_DB_USERNAME','{{db_user_name}}');
/* The password used to connect to the database */
define('APP_DB_PASSWORD','{{db_password}}');
/* The name of the database you want to connect to */
define('APP_DB_NAME','{{db_name}}');
/*active user count according to subscription plan*/
define('APP_ACTIVE_USER_COUNT','{{active_user_count}}');
/*tenant subscription plan expiration date*/
define('APP_EXPIRES_AT','{{expires_at}}');
/*is app active or not*/
define('APP_ACTIVE_STATUS','{{active_status}}');

?>
