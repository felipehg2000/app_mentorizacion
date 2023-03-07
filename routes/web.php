<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;

/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2023-03-06 23:03:30
 * @Last Modified by: Felipe Hernández González
 * @Last Modified time: 2023-03-06 23:09:31
 * @Description: De este archivo se leerán las rutas a las que el usuario pueda acceder, es decir, el usuario solo puede acceder a las rutas que especifiquemos aquí.
 *               Como buena práctica hay que darle nombre a cada una de las rutas, para que si una de estas cambie no haya que cambiar todos los lugares donde las referenciemos,
 *               para esto usaremos la función name.
 *               El control de estas rutas lo haremos en los diferentes controladores, tendremos que poner el useSpace en la parte superior como podemos ver.
 *               Si la ruta tiene la función GET precedida la función del controlador que maneja dicha dirección mostrará la vista, en caso de que esté predecido por POST la función
 *               del controlador recibirá las entradas de las vistas y los manejará.
 *               Separaremos los distintos espacios de rutas en grupos de controladores, como el grupo de users(*), por ejemplo
 */


Route::get('/', HomeController::class);

//(*)
Route::controller(UserController::class)->group(function(){
    Route::get ('users'              , [UsersController::class, 'index'       ])->name('users.index'       );
    Route::post('users'              , [UsersController::class, 'store'       ])->name('users.store'       );
    Route::get ('users/create'       , [UsersController::class, 'create'      ])->name('users.create'      );
    Route::post('users/create'       , [UsersController::class, 'create_store'])->name('users.create.store');
    Route::get ('users/edit'         , [UsersController::class, 'edit'        ])->name('users.edit'        );
    Route::post('users/edit'         , [UsersController::class, 'edit_store'  ])->name('users.edit.store'  );
    Route::get ('users/{loginResult}', [UsersController::class, 'show'        ])->name('users.show'        );
});

