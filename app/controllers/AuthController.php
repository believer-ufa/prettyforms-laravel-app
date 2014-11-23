<?php

use PrettyForms\Commands;

/**
 * Контроллер, отвечающий за авторизацию и регистрацию
 */

class AuthController extends BaseController {

    public function __construct()
    {
        // POST-запросы в этот контроллер могут посылать только гости, и только вместе с защитным токеном
        $this->beforeFilter('guest|csrf', [
            'on'   => 'post',
        ]);

        // Страница регистрации и авторизации открывается только гостям
        $this->beforeFilter('guest', [
            'on'   => 'get',
            'only' => ['anyRegister','anyLogin']
        ]);

        // Разлогиниться может только авторизованный пользователь
        $this->beforeFilter('auth', [
            'on'   => 'get',
            'only' => ['getLogout']
        ]);
    }

    public function anyRegister() {
        if (Request::isMethod('post') AND Request::wantsJson() AND Request::ajax()) {
            $user           = new User;
            $user->email    = Input::get('email');
            $user->name     = Input::get('name');
            $user->password = Hash::make(Input::get('password'));
            $user->validateAndSave();

            return Commands::generate([
                'redirect' => '/auth/login'
            ]);

        } else {
            return View::make('auth.register');
        }
    }

    public function anyLogin() {
        if (Request::isMethod('post')) {
            $login_result = Auth::attempt([
                'email' => Input::get('email'),
                'password' => Input::get('password'),
            ], true);

            if ($login_result) {
                return Redirect::to('/');
            } else {
                return Redirect::to('/auth/login?fail')
                    ->withInput(Input::except('password'));
            }

        } else {
            return View::make('auth.login');
        }
    }

    function getLogout() {
        Auth::logout();
        return Redirect::to('/');
    }

}