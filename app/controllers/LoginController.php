<?php

namespace App\controllers;
if (!session_id()) @session_start();

use App\exceptions\AccountIsBlockedException;
use App\exceptions\NotEnoughMoneyException;
use Exception;
use App\QueryBilder;
use League\Plates\Engine;
use PDO;
use Delight\Auth\Auth;
use \Tamtamchik\SimpleFlash\Flash;

class LoginController
{

    private $templates;
    private $qb;
    private $engine;
    private $flash;

    public function __construct(QueryBilder $qb, Engine $engine, Auth $auth, Flash $flash)
    {
        $this->templates = $engine;
        $this->qb = $qb;
        $this->auth = $auth;
        $this->flash = $flash;
    }

    //Sign in
    public function index()
    {
        echo $this->templates->render('page_login', ['name' => 'Sign in']);
    }

    public function login_handler()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $flash_massages = [
            "success_user_logged" => 'User is logged in',
            "error_wrong_email" => 'Wrong email address',
            "error_wrong_password" => 'Wrong password',
            "error_email_verified" => 'Email not verified',
            "warning_many_requests" => 'Too many requests',
        ];
        try {
            $this->auth->login($email, $password);
            $this->flash->message($flash_massages['success_user_logged'], 'success');
            header("Location: /");
        } catch (\Delight\Auth\InvalidEmailException $e) {
            $this->flash->message($flash_massages['error_wrong_email'], 'error');
            header("Location: /login");
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            $this->flash->message($flash_massages['error_wrong_password'], 'error');
            header("Location: /login");
        } catch (\Delight\Auth\EmailNotVerifiedException $e) {
            $this->flash->message($flash_massages['error_email_verified'], 'error');
            header("Location: /login");
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            $this->flash->message($flash_massages['warning_many_requests'], 'warning');
            header("Location: /login");
        }
    }

    public function logout()
    {
        try {
            $this->auth->logOutEverywhereElse();
        } catch (\Delight\Auth\NotLoggedInException $e) {
            $this->flash->message('Not logged in', 'warning');
            header("Location: /login");
        }
        $this->auth->destroySession() . header("Location: /login");

    }
}