<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

final readonly class HomeController
{
    public function __invoke(): Response
    {
        return Inertia::render('Index');
    }
}
