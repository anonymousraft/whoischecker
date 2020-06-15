<?php

/*
* @package Whoischecker
*/

namespace Inc\Pages;

use \Inc\Base\BaseController;

class HomePage extends BaseController
{


    public function initiate()
    {
        if(!$this->checkConnection())
        {
            echo '<p align="center" style="color:red;">You are not connected to Internet</p>';
            die();
        }

        $this->template();

    }

    public function template()
    {
        $this->registerHeaderScripts();
        echo '<title>' . $this->page_titles['home'] . '</title>';
        $this->bodyHTML();
        require_once "$this->app_root/views/home.php";
        $this->registerFooterScripts();
    }

    private function checkConnection()
    {
        $connected = @fsockopen("google.com", 80);

        if ($connected) {
            $is_conn = true;
            fclose($connected);
        } else {
            $is_conn = false;
        }
        return $is_conn;
    }
}