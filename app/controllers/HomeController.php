<?php

namespace App\controllers;

session_start();

use App\exceptions\AccountIsBlockedException;
use App\exceptions\NotEnoughMoneyException;
use Exception;
use App\QueryBilder;
use League\Plates\Engine;
use PDO;
use Delight\Auth\Auth;

// $db = new QueryBilder();
// // $posts = $db->getAll('posts');//Загрузить все записи с БД
// // $db->insert(['title' => 'New Post2'], 'posts');//Добавить новую запись в БД
// // $db->update(['title' => 'New Post2'], 7, 'posts');//Обновляем запись в БД
// // $db->delete('posts', 6);//Удалить запись с БД
// $posts = $db->getOne(['id' , 'title'], 'posts', 5);//Вывод одной записи с БД

class HomeController
{

    private $templates;
    private $qb;
    private $engine;

    public function __construct(QueryBilder $qb, Engine $engine, Auth $auth)
    {
        $this->templates = $engine;
        $this->qb = $qb;
        $this->auth = $auth;
        // d($this->templates);die();
    }

    public function index()
    {
        if ($this->auth->isLoggedIn()) {
            $select_user = $this->qb->getAll('users');
            $get_user_id = $this->auth->getUserId();
            $is_admin = $this->auth->hasRole(\Delight\Auth\Role::ADMIN);
            echo $this->templates->render('homepage', ['users' => $select_user, 'authorized_user_id' => $get_user_id, 'is_admin' => $is_admin]);
        } else {
            header("Location: /login");
        }

    }

    // public function about(){
    // 	echo $this->templates->render('about', ['name' => 'About']);
    // }
}
