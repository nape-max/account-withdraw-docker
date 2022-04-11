<?php

namespace Model;

use Entity\Account;
use Mapper\AccountMapper;
use DatabaseConnection\MysqlConnection;

class AccountModel
{
    private $connection;

    public function __construct(MysqlConnection $connection)
    {
        $this->connection = $connection;
    }

    public function getUserByUsername($username): ?Account
    {
        $statement = $this
            ->connection
            ->getConnection()
            ->prepare("
                SELECT * FROM account WHERE username = :username;
            ");

        $statement->bindValue(':username', $username);
        $statement->execute();

        $result = $statement->fetch();

        $mapper = new AccountMapper();

        $account = $mapper->databaseResultToEntity($result);

        return $account;
    }

    public function getUserByAccessToken($accessToken): ?Account
    {
        $statement = $this
            ->connection
            ->getConnection()
            ->prepare("
                SELECT * FROM account WHERE access_token = :accessToken;
            ");

        $statement->bindValue(':accessToken', $accessToken);
        $statement->execute();

        $result = $statement->fetch();

        $mapper = new AccountMapper();

        $account = $mapper->databaseResultToEntity($result);

        return $account;
    }

    public function setAccessTokenByUsername($username, $accessToken)
    {
        $statement = $this
            ->connection
            ->getConnection()
            ->prepare("
                UPDATE account SET access_token = :accessToken WHERE username = :username;
            ");

        $statement->bindValue(':accessToken', $accessToken);
        $statement->bindValue(':username', $username);
        $statement->execute();

        return true;
    }

    public function authorizeUser(Account $user)
    {
        $isVerified = $this->verifyUser($user);

        if ($isVerified) {
            $accessToken = bin2hex($user->username . random_bytes(36));

            if ($this->setAccessTokenByUsername($user->username, $accessToken)) {
                return $accessToken;
            }
        }

        return false;
    }

    public function verifyUser(Account $user)
    {
        if (true === password_verify($_POST['password'], $user->password)) {
            return true;
        }

        return false;
    }

    public function withdrawFromBalanceByAccessToken($balance, $accessToken)
    {
        $statement = $this
            ->connection
            ->getConnection()
            ->prepare("
                UPDATE account SET balance = :balance WHERE access_token = :accessToken;
            ");

        $statement->bindValue(':balance', $balance);
        $statement->bindValue(':accessToken', $accessToken);
        $statement->execute();

        return true;
    }

    public function isAuthorized()
    {
        if (!isset($_COOKIE['access_token'])) {
            return false;
        }

        $user = $this->getUserByAccessToken($_COOKIE['access_token']);
        if (null === $user) {
            return false;
        }

        return true;
    }
}
