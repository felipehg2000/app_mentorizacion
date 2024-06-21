<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BannsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\BrowseController;
use App\Http\Controllers\SyncChatController;
use App\Http\Controllers\TutorshipsController;
use App\Http\Controllers\TasksBoardsController;
/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2023-03-06 23:03:30
 * @Last Modified by: Felipe Hernández González
 * @Last Modified time: 2024-06-20 20:47:54
 * @Description: De este archivo se leerán las rutas a las que el usuario pueda acceder, es decir, el usuario solo puede acceder a las rutas que especifiquemos aquí.
 *               Como buena práctica hay que darle nombre a cada una de las rutas, para que si una de estas cambie no haya que cambiar todos los lugares donde las referenciemos,
 *               para esto usaremos la función name.
 *               El control de estas rutas lo haremos en los diferentes controladores, tendremos que poner el useSpace en la parte superior como podemos ver.
 *               Si la ruta tiene la función GET precedida la función del controlador que maneja dicha dirección mostrará la vista, en caso de que esté predecido por POST la función
 *               del controlador recibirá las entradas de las vistas y los manejará.
 *               Separaremos los distintos espacios de rutas en grupos de controladores, como el grupo de users(*), por ejemplo
 */


Route::get('/', HomeController::class)->name('home');

Route::controller(UsersController::class)->group(function(){
    Route::get  ('users'                , [UsersController::class, 'index'                  ])->name('users.index'                  );
    Route::post ('users'                , [UsersController::class, 'store'                  ])->name('users.store'                  );

    Route::get  ('users/friendship'             , [UsersController::class, 'friendship'             ])->name('users.friendship'             );
    Route::post ('users/friendship_store'       , [UsersController::class, 'friendship_store'       ])->name('users.friendship.store'       );
    Route::get  ('users/actual_friends'         , [UsersController::class, 'actual_friends'         ])->name('users.actual_friends'         );
    Route::post ('users/actual_frineds_store'   , [UsersController::class, 'actual_friends_store'   ])->name('users.actual_friends.store'   );

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
    Route::post ('users/check_password' , [UsersController::class, 'check_password_store'   ])->name('users.check_password.store'   );

    Route::get  ('users/create_admin'   , [UsersController::class, 'create_admin'           ])->name('admin.create'                 );
    Route::post ('users/create_admin'   , [UsersController::class, 'create_admin_store'     ])->name('admin.create.store'           );

    Route::get  ('users/modify_admin'   , [UsersController::class, 'modify_admin'           ])->name('admin.modify'                 );
    Route::post ('users/modify_admin'   , [UsersController::class, 'modify_admin_store'     ])->name('admin.modify.store'           );

    Route::get  ('users/delete_admins'  , [UsersController::class, 'delete_admins'          ])->name('admin.delete'                 );
    Route::post ('users/delete_admins'  , [UsersController::class, 'delete_admins_store'    ])->name('admin.delete.store'           );

    Route::get  ('users/close'          , [UsersController::class, 'close'                  ])->name('users.close'                  );
});

Route::controller(BrowseController::class)->group(function(){
    Route::post ('users/info_inicial'   , [BrowseController::class, 'info_inicial_store'                    ])->name('users.info_inicial.store'               );
    Route::post ('users/rep_req_saw'    , [BrowseController::class, 'ReportRequestSaw'                      ])->name('admin.ReportRequestSaw'                 );
    Route::post ('users/friend_req_saw' , [BrowseController::class, 'FriendRequestsSaw'                     ])->name('users.FriendRequestsSaw'                );
    Route::post ('users/tutoring_saw'   , [BrowseController::class, 'TutoringSaw'                           ])->name('users.TutoringSaw'                      );
    Route::post ('users/tut_modify_not' , [BrowseController::class, 'TutoringModificationsNotification'     ])->name('users.TutoringModificationsNotification');
    Route::post ('users/task_saw'       , [BrowseController::class, 'TasksSaw'                              ])->name('users.TasksSaw'                         );
    Route::post ('users/answer_saw'     , [BrowseController::class, 'AnswersSaw'                            ])->name('users.AnswersSaw'                       );

    Route::get  ('users/tutorial'       , [BrowseController::class, 'tutorial'               ])->name('users.tutorial'               );
    Route::get  ('users/news'           , [BrowseController::class, 'news'                   ])->name('users.news'                   );
    Route::get  ('users/admin_tut'      , [BrowseController::class, 'admin_tut'              ])->name('admin.tutorial'               );
    Route::get  ('users/admin_news'     , [BrowseController::class, 'admin_news'             ])->name('admin.news'                   );
});

Route::controller(SyncChatController::class)->group(function(){
    Route::get  ('users/sync_chat'      , [SyncChatController::class, 'sync_chat'              ])->name('users.sync_chat'              );
    Route::post ('users/sync_chat'      , [SyncChatController::class, 'sync_chat_store'        ])->name('users.sync_chat.store'        );
    Route::post ('users/send_message'   , [SyncChatController::class, 'send_message_store'     ])->name('users.send_message.store'     );
});

Route::controller(TutorshipsController::class)->group(function(){
    Route::get  ('users/tut_request'    , [TutorshipsController::class, 'tut_request'            ])->name('users.tut_request'            );
    Route::post ('users/add_tuto'       , [TutorshipsController::class, 'add_tuto_store'         ])->name('users.add_tuto.store'         );
    Route::post ('users/get_tuto_data'  , [TutorshipsController::class, 'get_tuto_data_store'    ])->name('users.get_tuto_data.store'    );
    Route::post ('users/update_tuto'    , [TutorshipsController::class, 'update_tuto_store'      ])->name('users.update_tuto.store'      );
    Route::get  ('users/tut_access'     , [TutorshipsController::class, 'tut_access'             ])->name('users.tut_access'             );
    Route::post ('users/send_text'      , [TutorshipsController::class, 'send_text_store'        ])->name('users.send_text.store'        );
    Route::post ('users/fin_tuto'       , [TutorshipsController::class, 'fin_tuto_store'         ])->name('users.fin_tuto.store'         );
    Route::post ('users/upload_img_tuto', [TutorshipsController::class, 'upload_img_tuto_store'  ])->name('users.upload_img_tuto.store'  );
    Route::post ('users/decrypt_info'   , [TutorshipsController::class, 'decrypt_info_store'     ])->name('users.decrypt_info.store'     );
});

Route::controller(TasksBoardsController::class)->group(function(){
    Route::get  ('users/task_board'     , [TasksBoardsController::class, 'task_board'             ])->name('users.task_board'             );
    Route::post ('users/task_board'     , [TasksBoardsController::class, 'task_board_store'       ])->name('users.task_board.store'       );
    Route::post ('users/add_task'       , [TasksBoardsController::class, 'add_task_store'         ])->name('users.add_task.store'         );
    Route::post ('users/update_task'    , [TasksBoardsController::class, 'update_task_store'      ])->name('users.update_task.store'      );
    Route::post ('users/delete_task'    , [TasksBoardsController::class, 'delete_task_store'      ])->name('users.delete_task.store'      );

    Route::get  ('users/done_tasks'     , [TasksBoardsController::class, 'done_tasks'             ])->name('users.done_tasks'             );
    Route::post ('users/found_task'     , [TasksBoardsController::class, 'found_task_store'       ])->name('users.found_task.store'       );
    Route::post ('users/found_answers'  , [TasksBoardsController::class, 'found_answers_store'    ])->name('users.found_answers.store'    );
    Route::get  ('users/to_do_tasks'    , [TasksBoardsController::class, 'to_do_tasks'            ])->name('users.to_do_tasks'            );

    Route::post ('users/download_task'  , [TasksBoardsController::class, 'download_task'          ])->name('users.download_task'          );
});

Route::controller(BannsController::class)->group(function(){
    Route::get  ('users/report_requests', [BannsController::class, 'rep_requests'           ])->name('admin.rep_requests'           );
    Route::get  ('users/block_mentores' , [BannsController::class, 'block_mentores'         ])->name('admin.block_mentores'         );
    Route::get  ('users/block_students' , [BannsController::class, 'block_students'         ])->name('admin.block_students'         );
    Route::get  ('users/block_admins'   , [BannsController::class, 'block_admins'           ])->name('admin.block_admins'           );
    Route::post ('users/bann_people'    , [BannsController::class, 'bann_people_store'      ])->name('admin.bann_people.store'      );
    Route::post ('users/create_report'  , [BannsController::class, 'create_report'          ])->name('users.create_repot'           );
});
