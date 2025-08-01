<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

arch('controllers must be readonly')
    ->expect('App\Http\Controllers')
    ->toBeReadonly()
    ->toExtendNothing()
    ->toHaveMethod('__invoke');

arch('models must be final')
    ->expect('App\Models')
    ->toExtend(Model::class)
    ->toBeFinal()
    ->ignoring(User::class);

arch('user model')
    ->expect(User::class)
    ->toExtend(Authenticatable::class)
    ->toBeFinal();

arch('strict types are used')
    ->expect('App')
    ->toUseStrictTypes()
    ->toUseStrictEquality();

arch('Illuminate\Http\Response not used')
    ->expect(Response::class)
    ->toBeUsedInNothing();

arch('Illuminate\Http\RedirectResponse not used')
    ->expect(RedirectResponse::class)
    ->toBeUsedInNothing();

arch()->preset()->php();
arch()->preset()->security();
