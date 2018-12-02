<?php

namespace App\Http\Traits;

use DB;
use App\Http\InstallingUtil\PasswordHash;
use App\Http\InstallingUtil\SqlScriptParser;

/**
 * this will contain all functionalitis required to generate crm folder for each company
 *
 * @author Anirban Saha
 */
trait CrmFolderGenerator
{
    /**
     * contain all tenant folder path
     * @var string
     */
    private $tenant_path;

    /**
     * contain current tenant folder path
     * @var string
     */
    private $new_tenant_folder;

    /**
     * contain generic config file path
     * @var string
     */
    private $config_path;

    /**
     * contain the folder path where all required files are placed
     * @var string
     */
    private $util_path;

    /**
     * contain new db connection
     * @var object
     */
    private $new_db_connection;

    /**
     * contain main db connection ie. grant privilage
     * @var object
     */
    private $main_db_connection;

    /**
     * contains all errors occured on runtime
     * @var string
     */
    private $error;

    /**
     * this will set all required path into respective variable
     *
     * @return self
     */
    private function setAllPath()
    {
        $this->tenant_path = realpath(app_path('/../../'));
        $this->util_path = realpath(app_path('/Http/InstallingUtil/'));
        $this->config_path = $this->util_path.'/generic-config.php';
        return $this;
    }

    /**
     * create new tenant folder in respective path
     *
     * @return bool true if created otherwise false
     */
    public function createCrmFolder()
    {
        if($this->setAllPath()->setConnection()->createDb() && $this->isNewDbUserCorrect()) {
            $this->createGenericFolderAndFiles()->dumpDatabase()->writeHtaccess();
            $this->closeConnection();
            return true;
        }
        return false;
    }

    /**
     * set main db connection
     *
     * @return self
     */
    private function setConnection()
    {
        $this->main_db_connection                 = @mysqli_connect(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'), env('DB_DATABASE'));
        return $this;
    }

    /**
     * this will create new database for new tenant
     *
     * @return bool  true on success otherwise false
     */
    private function createDb()
    {
        $sql = "CREATE DATABASE ".$this->request->db_name;
        $db_created =         $this->main_db_connection->query($sql);
        if($db_created) {
            $sql = "CREATE USER '". $this->request->db_user ."'@'". $this->request->db_host ."' IDENTIFIED BY '".$this->request->db_password."';";
            $user_created = $this->main_db_connection->query($sql);
            if($user_created) {
                $sql = "GRANT ALL PRIVILEGES ON `".$this->request->db_name."`.* TO '".$this->request->db_user."'@'".$this->request->db_host."' WITH GRANT OPTION;";
                $permission = $this->main_db_connection->query($sql);
                return true;
            } else {
                $this->error[] = 'Can not create user. Please username';
                $sql = "DROP DATABASE ".$_POST['database'];
                $this->main_db_connection->query($sql);
                return false;
            }

        } else {
            $this->error = 'Can not create database. Please change db name';
        }
        return false;
    }

    /**
     * check new mysql user credentials and database
     *
     * @return boolean true if correct otherwise false
     */
    private function isNewDbUserCorrect()
    {
        $link                  = @mysqli_connect($this->request->db_host, $this->request->db_user, $this->request->db_password, $this->request->db_name);
        if (!$link) {
          $this->error = "Could not connect new database. Something went wrong";
          return false;
        }
        $this->new_db_connection = $link;
        return true;
    }

    /**
     * create all required files and folder in new tenant folder
     *
     * @return self
     */
    private function createGenericFolderAndFiles()
    {
        $foldername     = trim(str_ireplace(' ', ' ', $this->request->tenant_folder));
        $new_folder     = $this->tenant_path.'/'.$foldername;
        $this->new_tenant_folder = $new_folder;
        @mkdir($new_folder, 0777, true);
        @chmod($new_folder, 0777);
        @mkdir($new_folder.'/uploads', 0777, true);
        @chmod($new_folder.'/uploads', 0777);
        @symlink(realpath(app_path(env('CRM_ASSETS_PATH_FROM_APP'))),$this->new_tenant_folder.'/assets');
        @copy($this->util_path.'/generic-index.php', $new_folder.'/index.php');
        @chmod($new_folder.'/index.php', 0777);
        return $this;
    }

    /**
     * this will write config file in new tenant
     *
     * @param $subscriber company model
     *
     * @return bool|self false if failed| otherwise self
     */
    private function writeConfig($subscriber)
    {
        $foldername     = trim($subscriber->tenant_folder);
        $hostname       = trim($subscriber->db_host);
        $database       = trim($subscriber->db_name);
        $username       = trim($subscriber->db_user);
        $password       = trim($subscriber->db_password);
        $expires_at     = trim($subscriber->expires_at);
        $active_status  = trim($subscriber->status);
        $config_path    = $this->config_path;
        $active_user_count = trim($subscriber->active_user_count);

        @chmod($config_path, FILE_WRITE_MODE);

        $config_file = file_get_contents($config_path);
        $config_file = trim($config_file);

        $config_file = str_replace("{{extra_folder}}", env('EXTRA_FOLDER'), $config_file);
        $config_file = str_replace("{{crm_core_path}}", env('CRM_CORE_FOLDER_PATH'), $config_file);
        $config_file = str_replace("{{folder_name}}", $foldername, $config_file);
        $config_file = str_replace("{{db_host_name}}", $hostname, $config_file);
        $config_file = str_replace("{{db_user_name}}", $username, $config_file);
        $config_file = str_replace("{{db_password}}", $password, $config_file);
        $config_file = str_replace("{{db_name}}", $database, $config_file);
        $config_file = str_replace("{{active_user_count}}", $active_user_count, $config_file);
        $config_file = str_replace("{{expires_at}}", $expires_at, $config_file);
        $config_file = str_replace("{{active_status}}", $active_status, $config_file);
        $new_config_file_path = $this->new_tenant_folder.'/config.php';
        if (!$fp = fopen($new_config_file_path, FOPEN_WRITE_CREATE_DESTRUCTIVE)) {
            throw new \Exception($new_config_file_path .' is not writable', 1);
            return false;
        }

        flock($fp, LOCK_EX);
        fwrite($fp, $config_file, strlen($config_file));
        flock($fp, LOCK_UN);
        fclose($fp);
        @chmod($new_config_file_path, 0777);

        return $this;
    }

    /**
     * this will dump database in new created tenant databse
     *
     * @return self
     */
    private function dumpDatabase()
    {
        $parser = new SqlScriptParser();

        $sqlStatements = $parser->parse($this->util_path.'/database.sql');

        foreach ($sqlStatements as $statement) {
            $distilled = $parser->removeComments($statement);
            if (!empty($distilled)) {
                $this->new_db_connection->query($distilled);
            }
        }

        $hasher      = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
        $password    = $hasher->HashPassword($this->request->password);
        $email       = $this->request->email;
        $firstname   = $this->request->first_name;
        $lastname    = $this->request->last_name;

        $datecreated = date('Y-m-d H:i:s');

        $timezone = $this->request->timezone;
        $sql      = "UPDATE tbloptions SET value='$timezone' WHERE name='default_timezone'";
        $this->new_db_connection->query($sql);

        $di = time();
        $sql      = "UPDATE tbloptions SET value='$di' WHERE name='di'";
        $this->new_db_connection->query($sql);

        $sql = "INSERT INTO tblstaff (firstname, lastname, password, email, datecreated, admin, active) VALUES('$firstname', '$lastname', '$password', '$email', '$datecreated', 1, 1)";
        $this->new_db_connection->query($sql);
        return $this;
    }

    /**
     * this will write htaccess file in new tenant
     *
     * @return self
     */
    private function writeHtaccess()
    {
        if (!file_exists($this->new_tenant_folder.'/.htaccess') && is_writable($this->new_tenant_folder)) {
            fopen($this->new_tenant_folder.'/.htaccess', 'w');
            $fp = fopen($this->new_tenant_folder.'/.htaccess', 'a+');
            if ($fp) {
                fwrite($fp, 'RewriteEngine on'.PHP_EOL.'RewriteCond $1 !^(index\.php|resources|robots\.txt)'.PHP_EOL.'RewriteCond %{REQUEST_FILENAME} !-f'.PHP_EOL.'RewriteCond %{REQUEST_FILENAME} !-d'.PHP_EOL.'RewriteRule ^(.*)$ index.php?/$1 [L,QSA]'.PHP_EOL.'AddDefaultCharset utf-8');
                fclose($fp);
            }
        }
        return $this;
    }

    public function updateConfigDetails($subscriber)
    {
        if($this->setAllPath()) {
            $this->new_tenant_folder = $this->tenant_path.'/'.$subscriber->tenant_folder;
            $this->writeConfig($subscriber);
            return true;
        }
        return false;
    }

    /**
     * close all db connection
     *
     * @return void
     */
    private function closeConnection()
    {
        @mysqli_close($this->new_db_connection);
        @mysqli_close($this->main_db_connection);
    }
}
