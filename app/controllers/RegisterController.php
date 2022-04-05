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

class RegisterController
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

    //sign up
    public function index()
    {
        echo $this->templates->render('page_register', ['name' => 'Sign up']);
    }

    public function register()
    {
        $email = $_POST['email'];//Create a mail variable
        $password = $_POST['password'];//Create a password variable
        $user_name = $_POST['user_name'];//Create User Name
        $flash_massages = [

            "success_create_user" => 'We have signed up a new user with the ID',
            "error_check_email" => 'Invalid email address',
            "error_check_password" => 'Invalid password',
            "warning_check_user" => 'User already exists',
            "warning_requests" => 'Too many requests',
        ];

        try {
            $userId = $this->auth->register($email, $password, $user_name, function ($selector, $token) {
                $this->flash->message('Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)', 'success');
            });

            $this->flash->message($flash_massages['success_create_user'] . " " . $userId, 'success');
            header("Location: /login");

        } catch (\Delight\Auth\InvalidEmailException $e) {

            $this->flash->message($flash_massages['error_check_email'], 'error');
            header("Location: /register");

        } catch (\Delight\Auth\InvalidPasswordException $e) {

            $this->flash->message($flash_massages['error_check_password'], 'error');
            header("Location: /register");

        } catch (\Delight\Auth\UserAlreadyExistsException $e) {

            $this->flash->message($flash_massages['warning_check_user'], 'warning');
            header("Location: /register");

        } catch (\Delight\Auth\TooManyRequestsException $e) {

            $this->flash->message($flash_massages['warning_requests'], 'warning');
            header("Location: /register");
        }
    }

    public function email_verify()
    {
        try {
            $this->auth->confirmEmail($_GET['selector'], $_GET['token']);

            echo 'Email address has been verified';
        } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            die('Invalid token');
        } catch (\Delight\Auth\TokenExpiredException $e) {
            die('Token expired');
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            die('Email address already exists');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }
    }
}