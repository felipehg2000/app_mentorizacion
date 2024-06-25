<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-06-21 16:17:20
 * @Last Modified by: Felipe Hernández González
 * @Last Modified time: 2024-06-21 16:35:35
 * @Description: Controlador padre, todos los controladores descienden de este, las funciones auxiliares que se repitan en más de
 *               un controlador se colocarán en este para que sean accesible desde toda la aplicación.
 */

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * @param {Frase o palabra que queremos cifrar} clave
     * @return {Texto cifrado asociado a la clave que nos han pasado por parametro}
     */
    public function cifrate_private_key ($clave){
        $key  = 'clave_de_cifrado_de_32_caracteres';

        return openssl_encrypt($clave, 'aes-256-ecb', $key);
    }

    /**
     * @param {Texto o palabra que queremos descifrar} request->message
     * @return {Si no hay un usuario logueado devolveremos la vista de sesión cerrada}
     *         {Si hay un usuario logueado devolveremos true y el texto descifrado a la petición ajax}
     */
    public function decrypt_info_store (Request $request){
        if (!Auth::check()){
            return view('users.close');
        }
        $key  = 'clave_de_cifrado_de_32_caracteres';
        $message = openssl_decrypt($request->message, 'aes-256-ecb', $key);

        return response()->json(['success' => true,
                                 'message'   => $message]);
    }
}
