<?php

namespace Controller;

use DatabaseConnection\MysqlConnection;
use View\View;
use Model\AccountModel;

class AccountController
{
    public static function indexAction()
    {
        $view = new View();

        $view->generate('Index', 'MainView');
    }

    public static function authorizeAction()
    {
        $view = new View();

        $view->generate('Account', 'Authorize/AuthorizeView');
    }

    public static function authorizeActionPost()
    {
        $accountModel = new AccountModel(new MysqlConnection());

        $user = $accountModel->getUserByUsername($_POST['username']);

        if (true === password_verify($_POST['password'], $user->password)) {
            $accessToken = bin2hex($user->username . random_bytes(36));

            if ($accountModel->setAccessTokenByUsername($user->username, $accessToken)) {
                header("Location: http://finance-app/account");
                setcookie('access_token', $accessToken);
            }
        }
    }

    public static function getProfile()
    {
        $accountModel = new AccountModel(new MysqlConnection());
        $view = new View();

        if (!$accountModel->isAuthorized()) {
            $view->generate('Account', 'NotAuthorized');

            exit;
        }

        $user = $accountModel->getUserByAccessToken($_COOKIE['access_token']);

        $view->generate('Account', 'AccountView', ['username' => $user->username, 'balance' => $user->balance]);
    }

    public static function withdrawFromBalanceAction()
    {
    }
};
