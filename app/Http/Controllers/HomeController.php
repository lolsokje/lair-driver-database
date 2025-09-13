<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Filament\Pages\Dashboard;
use Symfony\Component\HttpFoundation\RedirectResponse;

final readonly class HomeController
{
    public function __invoke(): RedirectResponse
    {
        return redirect(Dashboard::getUrl());
    }
}
