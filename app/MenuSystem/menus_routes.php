<?php

use App\MenuSystem\Http\MenuController;
use Illuminate\Support\Facades\Route;
Route::group(['middleware' => 'web'], function () {
    Route::get('/menus/manage', [MenuController::class, 'manage'])
        ->middleware('auth')
        ->middleware('can:any_menus_permission')
    ;

    Route::get('/menus/index', [MenuController::class, 'index'])
        ->middleware('auth')
        ->middleware('can:admin.access.menus')
    ;

    Route::get('/menus/{menu}', [MenuController::class, 'show'])
        ->middleware('auth')->whereNumber('menu');

    Route::get('/menus/create', [MenuController::class, 'create'])
        ->middleware('can:admin.access.menus.create')
    ;

    Route::get('/menus/edit/{menu}', [MenuController::class, 'edit'])
        ->middleware('can:admin.access.menus.edit')
        ->whereNumber('menu')
    ;

    Route::post('/menus', [MenuController::class, 'store'])
        ->middleware('can:admin.access.menus.create')
    ;

    Route::patch('/menus/{menu}', [MenuController::class, 'update'])
        ->middleware('can:admin.access.menus.edit')->whereNumber('menu')
    ;

    Route::put('/menus', [MenuController::class, 'update'])
        ->middleware('can:admin.access.menus.edit')
    ;

    Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])
        ->middleware('can:admin.access.menus.delete')
        ->whereNumber('menu')
;
});
