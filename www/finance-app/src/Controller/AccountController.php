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
        if (isset($_COOKIE['access_token'])) {
            header("Location: http://finance-app/account");
            exit;
        }

        $view = new View();

        $view->generate('Account', 'Authorize/AuthorizeView');
    }

    public static function logoutAction()
    {
        if (isset($_COOKIE['access_token'])) {
            $accountModel = new AccountModel(new MysqlConnection());
            $user = $accountModel->getUserByAccessToken($_COOKIE['access_token']);

            if ($accountModel->setAccessTokenByUsername($user->username, null)) {
                setcookie('access_token', false);
            }
        }

        header("Location: http://finance-app/account/authorize");
    }

    public static function authorizeActionPost()
    {
        $accountModel = new AccountModel(new MysqlConnection());

        $user = $accountModel->getUserByUsername($_POST['username']);

        $accessToken = $accountModel->authorizeUser($user);

        if ($accessToken !== false) {
            header("Location: http://finance-app/account");
            setcookie('access_token', $accessToken);
        }
    }

    public static function getProfile()
    {
        self::checkIsAuthorized();

        $accountModel = new AccountModel(new MysqlConnection());
        $view = new View();

        $user = $accountModel->getUserByAccessToken($_COOKIE['access_token']);

        $view->generate('Account', 'AccountView', ['username' => $user->username, 'balance' => $user->balance]);
    }

    public static function withdrawFromBalanceAction()
    {
        self::checkIsAuthorized();

        $accountModel = new AccountModel(new MysqlConnection());

        $userByAccessToken = $accountModel->getUserByAccessToken($_COOKIE['access_token']);
        $userByUsername = $accountModel->getUserByUsername($_POST['username']);

        if (null !== $userByUsername) {
            $isVerified = $accountModel->verifyUser($userByUsername);

            if ($isVerified !== false && $userByUsername->accessToken === $userByAccessToken->accessToken) {
                $isWithdrawed = $accountModel->withdrawFromBalanceByAccessToken($userByAccessToken->balance - $_POST['amount'], $userByAccessToken->accessToken);

                if ($isWithdrawed) {
                    header("Location: http://finance-app/account");
                    exit;
                }
            }
        }

        echo "NO!";
    }

    public static function checkIsAuthorized()
    {
        $accountModel = new AccountModel(new MysqlConnection());
        $view = new View();

        if (!$accountModel->isAuthorized()) {
            setcookie('access_token', false);
            $view->generate('Account', 'NotAuthorized');

            exit;
        }
    }
};
