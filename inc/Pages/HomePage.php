<?php

/*
* @package Whoischecker
*/

namespace Inc\Pages;

use \Inc\Base\BaseController;

class HomePage extends BaseController
{
    

    public function register()
    {
        $this->template();
    }

    public function template()
    {
        $this->registerHeaderScripts();
        echo '<title>'.$this->page_titles['home'].'</title>';
        $this->bodyHTML();
        require_once "$this->app_root/views/home.php";
        $this->registerFooterScripts();
    }

}