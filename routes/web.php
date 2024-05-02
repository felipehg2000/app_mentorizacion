<?php

use App\Events\NewMessageEvent;
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
 * @Last Modified time: 2024-05-02 11:55:42
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

    Route::post ('users/info_inicial'   , [UsersController::class, 'info_inicial_store'                    ])->name('users.info_inicial.store'               );
    Route::post ('users/rep_req_saw'    , [UsersController::class, 'ReportRequestSaw'                      ])->name('admin.ReportRequestSaw'                 );
    Route::post ('users/friend_req_saw' , [UsersController::class, 'FriendRequestsSaw'                     ])->name('users.FriendRequestsSaw'                );
    Route::post ('users/tutoring_saw'   , [UsersController::class, 'TutoringSaw'                           ])->name('users.TutoringSaw'                      );
    Route::post ('users/tut_modify_not' , [UsersController::class, 'TutoringModificationsNotification'     ])->name('users.TutoringModificationsNotification');
    Route::post ('users/task_saw'       , [UsersController::class, 'TasksSaw'                              ])->name('users.TasksSaw'                         );
    Route::post ('users/answer_saw'     , [UsersController::class, 'AnswersSaw'                            ])->name('users.AnswersSaw'                       );

    Route::get  ('users/task_board'     , [UsersController::class, 'task_board'             ])->name('users.task_board'             );
    Route::post ('users/task_board'     , [UsersController::class, 'task_board_store'       ])->name('users.task_board.store'       );
    Route::post ('users/add_task'       , [UsersController::class, 'add_task_store'         ])->name('users.add_task.store'         );
    Route::post ('users/update_task'    , [UsersController::class, 'update_task_store'      ])->name('users.update_task.store'      );
    Route::post ('users/delete_task'    , [UsersController::class, 'delete_task_store'      ])->name('users.delete_task.store'      );

    Route::get  ('users/done_tasks'     , [UsersController::class, 'done_tasks'             ])->name('users.done_tasks'             );
    Route::post ('users/found_task'     , [UsersController::class, 'found_task_store'       ])->name('users.found_task.store'       );
    Route::post ('users/found_answers'  , [UsersController::class, 'found_answers_store'    ])->name('users.found_answers.store'    );
    Route::get  ('users/to_do_tasks'    , [UsersController::class, 'to_do_tasks'            ])->name('users.to_do_tasks'            );

    Route::post ('users/download_task'  , [UsersController::class, 'download_task'          ])->name('users.download_task'          );

    Route::get  ('users/sync_chat'      , [UsersController::class, 'sync_chat'              ])->name('users.sync_chat'              );
    Route::post ('users/sync_chat'      , [UsersController::class, 'sync_chat_store'        ])->name('users.sync_chat.store'        );
    Route::post ('users/send_message'   , [UsersController::class, 'send_message_store'     ])->name('users.send_message.store'     );

    Route::get  ('users/tut_request'    , [UsersController::class, 'tut_request'            ])->name('users.tut_request'            );
    Route::post ('users/add_tuto'       , [UsersController::class, 'add_tuto_store'         ])->name('users.add_tuto.store'         );
    Route::post ('users/get_tuto_data'  , [UsersController::class, 'get_tuto_data_store'    ])->name('users.get_tuto_data.store'    );
    Route::post ('users/update_tuto'    , [UsersController::class, 'update_tuto_store'      ])->name('users.update_tuto.store'      );

    Route::get  ('users/tut_access'     , [UsersController::class, 'tut_access'             ])->name('users.tut_access'             );
    Route::post ('users/send_text'      , [UsersController::class, 'send_text_store'        ])->name('users.send_text.store'        );
    Route::post ('users/fin_tuto'       , [UsersController::class, 'fin_tuto_store'         ])->name('users.fin_tuto.store'         );
    Route::post ('users/upload_img_tuto', [UsersController::class, 'upload_img_tuto_store'  ])->name('users.upload_img_tuto.store'  );

    Route::get  ('users/friendship'     , [UsersController::class, 'friendship'             ])->name('users.friendship'             );
    Route::get  ('users/actual_friends' , [UsersController::class, 'actual_friends'         ])->name('users.actual_friends'         );
    Route::post ('users/create_report'  , [UsersController::class, 'create_report'          ])->name('users.create_repot'           );

    Route::get  ('users/tutorial'       , [UsersController::class, 'tutorial'               ])->name('users.tutorial'               );
    Route::get  ('users/news'           , [UsersController::class, 'news'                   ])->name('users.news'                   );

    Route::get  ('users/create'         , [UsersController::class, 'create'                 ])->name('users.create'                 );
    Route::post ('users/create'         , [UsersController::class, 'create_store'           ])->name('users.create.store'           );

    Route::get  ('users/modify'         , [UsersController::class, 'modify'                 ])->name('users.modify'                 );
    Route::post ('users/modify'         , [UsersController::class, 'modify_store'           ])->name('users.modify.store'           );

    Route::get ('users/modify_password' , [UsersController::class, 'modify_password'        ])->name('users.modify_password'        );
    Route::post('users/modify_password' , [UsersController::class, 'modify_password_store'  ])->name('users.modify_password.store'  );

    Route::get ('users/modify_img_perf' , [UsersController::class, 'modify_img_perf'        ])->name('users.modify_img_perf'        );
    Route::post('users/modify_img_perf' , [UsersController::class, 'modify_img_perf_store'  ])->name('users.modify_img_perf.store'  );

    Route::get  ('users/delete'         , [UsersController::class, 'delete'                 ])->name('users.delete'                 );
    Route::post ('users/delete'         , [UsersController::class, 'delete_store'           ])->name('users.delete.store'           );

    Route::get  ('users/report_requests', [UsersController::class, 'rep_requests'           ])->name('admin.rep_requests'           );
    Route::get  ('users/block_mentores' , [UsersController::class, 'block_mentores'         ])->name('admin.block_mentores'         );
    Route::get  ('users/block_students' , [UsersController::class, 'block_students'         ])->name('admin.block_students'         );
    Route::get  ('users/block_admins'   , [UsersController::class, 'block_admins'           ])->name('admin.block_admins'           );
    Route::post ('users/bann_people'    , [UsersController::class, 'bann_people_store'      ])->name('admin.bann_people.store'      );

    Route::get  ('users/admin_tut'      , [UsersController::class, 'admin_tut'              ])->name('admin.tutorial'               );
    Route::get  ('users/admin_news'     , [UsersController::class, 'admin_news'             ])->name('admin.news'                   );

    Route::get  ('users/create_admin'   , [UsersController::class, 'create_admin'           ])->name('admin.create'                 );
    Route::post ('users/create_admin'   , [UsersController::class, 'create_admin_store'     ])->name('admin.create.store'           );

    Route::get  ('users/modify_admin'   , [UsersController::class, 'modify_admin'           ])->name('admin.modify'                 );
    Route::post ('users/modify_admin'   , [UsersController::class, 'modify_admin_store'     ])->name('admin.modify.store'           );

    Route::get  ('users/delete_admins'  , [UsersController::class, 'delete_admins'          ])->name('admin.delete'                 );
    Route::post ('users/delete_admins'  , [UsersController::class, 'delete_admins_store'    ])->name('admin.delete.store'           );

    Route::get  ('users/close'          , [UsersController::class, 'close'                  ])->name('users.close'                  );
});

Route::controller(MentorsController::class)->group(function(){
    Route::get  ('mentors/friendship'    , [MentorsController::class, 'friendship'          ])->name('mentors.friendship'          );
    Route::post ('mentors/friendship'    , [MentorsController::class, 'friendship_store'    ])->name('mentors.friendship.store'    );
    Route::get  ('mentors/actual_friends', [MentorsController::class, 'actual_friends'      ])->name('mentors.actual_fruends'      );
    Route::post ('mentors/actual_friends', [MentorsController::class, 'actual_friends_store'])->name('mentors.actual_fruends.store');
});

Route::controller(StudentsController::class)->group(function(){
    Route::get  ('students/friendship'    , [StudentsController::class, 'friendship'          ])->name('students.friendship'          );
    Route::post ('students/friendship'    , [StudentsController::class, 'friendship_store'    ])->name('students.friendship.store'    );
    Route::get  ('students/actual_friends', [StudentsController::class, 'actual_friends'      ])->name('students.actual_fruends'      );
    Route::post ('students/actual_friends', [StudentsController::class, 'actual_friends_store'])->name('students.actual_fruends.store');
});

