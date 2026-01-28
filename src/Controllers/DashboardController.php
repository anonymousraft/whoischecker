<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Kernel\View;

class DashboardController extends BaseController
{
    public function index(): void
    {
        View::render('dashboard', [
            'page_title' => 'WHOIS Intelligence Suite 5.0'
        ]);
    }

    public function bulk(): void
    {
        View::render('dashboard', [
            'page_title' => 'Bulk Analysis Engine'
        ]);
    }

    public function dns(): void
    {
        View::render('dashboard', [
            'page_title' => 'DNS Intelligence'
        ]);
    }
}
