<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\MentorsController;
use App\Http\Controllers\StudentsController;
/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2023-03-06 23:03:30
 * @Last Modified by: Felipe Hernández González
 * @Last Modified time: 2024-02-25 13:17:22
 * @Description: De este archivo se leerán las rutas a las que el usuario pueda acceder, es decir, el usuario solo puede acceder a las rutas que especifiquemos aquí.
 *               Como buena práctica hay que darle nombre a cada una de las rutas, para que si una de estas cambie no haya que cambiar todos los lugares donde las referenciemos,
 *               para esto usaremos la función name.
 *               El control de estas rutas lo haremos en los diferentes controladores, tendremos que poner el useSpace en la parte superior como podemos ver.
 *               Si la ruta tiene la función GET precedida la función del controlador que maneja dicha dirección mostrará la vista, en caso de que esté predecido por POST la función
 *               del controlador recibirá las entradas de las vistas y los manejará.
 *               Separaremos los distintos espacios de rutas en grupos de controladores, como el grupo de users(*), por ejemplo
 */


Route::get('/', HomeController::class)->name('home');

//(*)
Route::controller(UsersController::class)->group(function(){
    Route::get  ('users'                , [UsersController::class, 'index'                  ])->name('users.index'                  );
    Route::post ('users'                , [UsersController::class, 'store'                  ])->name('users.store'                  );

    Route::get  ('users/task_board'     , [UsersController::class, 'task_board'             ])->name('users.task_board'             );
    Route::post ('users/task_board'     , [UsersController::class, 'task_board_store'       ])->name('users.task_board.store'       );
    Route::post ('users/add_task'       , [UsersController::class, 'add_task_store'         ])->name('users.add_task.store'         );
    Route::post ('users/update_task'    , [UsersController::class, 'update_task_store'      ])->name('users.update_task.store'      );
    Route::post ('users/delete_task'    , [UsersController::class, 'delete_task_store'      ])->name('users.delete_task.store'      );

    Route::get  ('users/sync_chat'      , [UsersController::class, 'sync_chat'              ])->name('users.sync_chat'              );
    Route::post ('users/sync_chat'      , [UsersController::class, 'sync_chat_store'        ])->name('users.sync_chat.store'        );
    Route::post ('users/send_message'   , [UsersController::class, 'send_message_store'     ])->name('users.send_message.store'     );

    Route::get  ('users/friendship'     , [UsersController::class, 'friendship'             ])->name('users.friendship'             );
    Route::get  ('users/actual_friends' , [UsersController::class, 'actual_friends'         ])->name('users.actual_friends'         );

    Route::get  ('users/create'         , [UsersController::class, 'create'                 ])->name('users.create'                 );
    Route::post ('users/create'         , [UsersController::class, 'create_store'           ])->name('users.create.store'           );

    Route::get  ('users/modify'         , [UsersController::class, 'modify'                 ])->name('users.modify'                 );
    Route::post ('users/modify'         , [UsersController::class, 'modify_store'           ])->name('users.modify.store'           );

    Route::post ('users/check_password' , [UsersController::class, 'check_password_store'   ])->name('users.check_password.store'   );

    Route::get  ('users/delete'         , [UsersController::class, 'delete'                 ])->name('users.delete'                 );
    Route::post ('users/delete'         , [UsersController::class, 'delete_store'           ])->name('users.delete.store'           );

    Route::get  ('users/close'          , [UsersController::class, 'close'                  ])->name('users.close'                  );

});

Route::controller(MentorsController::class)->group(function(){
    Route::get  ('mentors'               , [MentorsController::class, 'index'               ])->name('mentors.index'               );
    Route::get  ('mentors/friendship'    , [MentorsController::class, 'friendship'          ])->name('mentors.friendship'          );
    Route::post ('mentors/friendship'    , [MentorsController::class, 'friendship_store'    ])->name('mentors.friendship.store'    );
    Route::get  ('mentors/actual_friends', [MentorsController::class, 'actual_friends'      ])->name('mentors.actual_fruends'      );
    Route::post ('mentors/actual_friends', [MentorsController::class, 'actual_friends_store'])->name('mentors.actual_fruends.store');
});

Route::controller(StudentsController::class)->group(function(){
    Route::get  ('students'               , [StudentsController::class, 'index'               ])->name('students.index'               );
    Route::get  ('students/friendship'    , [StudentsController::class, 'friendship'          ])->name('students.friendship'          );
    Route::post ('students/friendship'    , [StudentsController::class, 'friendship_store'    ])->name('students.friendship.store'    );
    Route::get  ('students/actual_friends', [StudentsController::class, 'actual_friends'      ])->name('students.actual_fruends'      );
    Route::post ('students/actual_friends', [StudentsController::class, 'actual_friends_store'])->name('students.actual_fruends.store');
});

