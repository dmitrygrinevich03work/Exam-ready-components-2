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

class UsersController
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

    public function index()
    {
        if (!$this->auth->isLoggedIn()) {
            header("Location: /login");
        }
        if ($this->auth->hasRole(\Delight\Auth\Role::ADMIN)) {
            echo $this->templates->render('create_user', ['name' => 'Create User']);
        } else {
            header("Location: /");
        }
    }

    public function create_user()
    {
        $flash_massages = [

            "success_create_user" => 'We have signed up a new user with the ID',
            "error_create_email" => 'Invalid email address',
            "error_create_password" => 'Invalid password',
            "warning_create_user" => 'User already exists',
        ];
        try {
            $userId = $this->auth->admin()->createUser($_POST['email'], $_POST['password'], $_POST['user_name']);
            $this->flash->message($flash_massages['success_create_user'] . " " . $userId, 'success');
            header("Location: /");
        } catch (\Delight\Auth\InvalidEmailException $e) {
            $this->flash->message($flash_massages['error_create_email'], 'error');
            header("Location: /");
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            $this->flash->message($flash_massages['error_create_password'], 'error');
            header("Location: /");
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            $this->flash->message($flash_massages['warning_create_user'], 'warning');
            header("Location: /");
        }

        $this->add_information($userId);
        $this->set_status($userId);
        $this->add_social_media($userId);


    }

    //edit
    public function edit()
    {
        $user_id = $_GET['id']; // Get user ID
        $get_user_id = $this->auth->getUserId(); // Authorized user ID
        $select_user = $this->qb->getOne(['*'], 'users', $user_id); //Select user

        if ($this->auth->isLoggedIn()) {
            if ($this->auth->hasRole(\Delight\Auth\Role::ADMIN)) {
                echo $this->templates->render('edit', ['user' => $select_user]);//View edit
            } else {
                if ($get_user_id == $_GET['id']) {
                    echo $this->templates->render('edit', ['user' => $select_user]);//View edit
                } else {
                    $this->flash->message("You can always only your profile", 'success');
                    header("Location: /");
                }
            }
        } else {
            header("Location: /login");
        }
    }

    public function update_info_edit()
    {
        if (isset($_POST['submit'])) {
            $this->qb->update(['username' => $_POST['user_name'], 'work' => $_POST['work'], 'phone' => $_POST['phone'], 'address' => $_POST['address']], $_POST['id'], 'users');
            $this->flash->message("Profile updated successfully", 'success');
            header("Location: /");
        }
    }

    public function add_information($userId)
    {
        $this->qb->update(['work' => $_POST['work'], 'phone' => $_POST['phone'], 'address' => $_POST['address']], $userId, 'users');
    }

    public function set_status($userId)
    {
        $this->qb->update(['status_online' => $_POST['status_online']], $userId, 'users');
    }

    public function add_social_media($userId)
    {
        $this->qb->update(['vk_social_media' => $_POST['vk_social_media'], 'telegram_social_media' => $_POST['telegram_social_media'], 'instagram_social_media' => $_POST['instagram_social_media']], $userId, 'users');
    }

    //Page Profile
    public function page_profile()
    {
        $user_id = $this->auth->getUserId(); // Authorized user ID
        $select_user = $this->qb->getOne(['*'], 'users', $user_id); //Select user

        if ($this->auth->isLoggedIn()) {
            echo $this->templates->render('page_profile', ['user' => $select_user]);
        } else {
            header("Location: /login");
        }
    }

    //Security
    public function security()
    {
        $user_id = $_GET['id']; // Get user ID
        $get_user_id = $this->auth->getUserId(); // Authorized user ID
        $select_user = $this->qb->getOne(['*'], 'users', $user_id); //Select user

        if ($this->auth->isLoggedIn()) {
            if ($this->auth->hasRole(\Delight\Auth\Role::ADMIN)) {
                echo $this->templates->render('security', ['user' => $select_user]);//View edit
                //Переходим на форму редактирования
            } else {
                if ($get_user_id == $_GET['id']) {
                    // Якщо твій аккаунт то переходимо на форму
                    echo $this->templates->render('security', ['user' => $select_user]);//View edit
                } else {
                    $this->flash->message("Can eventually only your profile", 'success');
                    header("Location: /");
                }
            }
        } else {
            header("Location: /login");
        }
    }


    public function update_security()
    {
        $email = $_POST['email'];
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];

        $userId = $this->auth->getUserId();//Получили ід користувача
        $select_email_from_db = $this->qb->select_email('email', 'users', $_POST['email']);//Получили почту з бази данних
        $select_email_auth_user = $this->auth->getEmail();//Получили почту авторизованого користувача
        $role_admin = $this->auth->admin()->doesUserHaveRole($userId, \Delight\Auth\Role::ADMIN);//Получили роль адміністратора авторизованого користувача

        if (trim($_POST['email'] == '')) {
            header("Location: /");
        } else {
            if (!$select_email_from_db == $email || $email == $select_email_auth_user || $role_admin) {//Якщо почта вільна то обновлюємо данні
                if (trim($_POST['old_password'] == '' && $_POST['new_password'] == '')) {
                    $this->qb->update(['email' => $_POST['email']], $_POST['id'], 'users');
                } else {
                    try {
                        if ($userId == $_POST['id']) {
                            $this->auth->changePassword($old_password, $new_password);
                            $this->qb->update(['email' => $_POST['email']], $_POST['id'], 'users');
                        } else if ($role_admin) {
                            $this->qb->update(['email' => $_POST['email']], $_POST['id'], 'users');
                            $this->auth->admin()->changePasswordForUserById($_POST['id'], $new_password);
                        }
                        $this->flash->message("profile updated", 'success');
                        header("Location: /");
                    } catch (\Delight\Auth\NotLoggedInException $e) {
                        $this->flash->message("Not logged in", 'warning');
                        header("Location: /");
                    } catch (\Delight\Auth\InvalidPasswordException $e) {
                        $this->flash->message("Invalid password(s)", 'warning');
                        header("Location: /");
                    } catch (\Delight\Auth\TooManyRequestsException $e) {
                        $this->flash->message("Too many requests", 'warning');
                        header("Location: /");
                    }
                }
            } else {
                $this->flash->message("Email busy!", 'warning');
                header("Location: /security");
            }
        }
    }

    //Status
    public function status()
    {
        $user_id = $_GET['id'];
        $get_user_id = $this->auth->getUserId();
        $select_user = $this->qb->getOne(['*'], 'users', $user_id);
        $select_all_status = $this->qb->getAll('status');

        if ($this->auth->isLoggedIn()) {
            if ($this->auth->hasRole(\Delight\Auth\Role::ADMIN)) {
                echo $this->templates->render('status', ['user' => $select_user, 'select_all_status' => $select_all_status]);
            } else {
                if ($get_user_id == $_GET['id']) {
                    echo $this->templates->render('status', ['user' => $select_user, 'select_all_status' => $select_all_status]);
                } else {
                    $this->flash->message("Можливо редагувати тільки свій профіль!", 'success');
                    header("Location: /");
                }
            }
        } else {
            header("Location: /login");
        }
    }

    public function update_status()
    {
        $userId = $this->auth->getUserId();//Получили ід користувача
        $role_admin = $this->auth->hasRole(\Delight\Auth\Role::ADMIN);

        if ($userId == $_POST['id'] || $role_admin) {
            $this->qb->update(['status' => $_POST['select_status']], $_POST['id'], 'users');
            $this->flash->message("Профіль успішно оновлено!", 'success');
            header("Location: /page_profile");
        } else {
            $this->flash->message("Ви не можете змінити статус!", 'warning');
            header("Location: /status");
        }

    }

    //media
    public function media()
    {
        $user_id = $_GET['id'];
        $get_user_id = $this->auth->getUserId();
        $select_user = $this->qb->getOne(['*'], 'users', $user_id);

        if ($this->auth->isLoggedIn()) {
            if ($this->auth->hasRole(\Delight\Auth\Role::ADMIN)) {
                echo $this->templates->render('/page_media', ['user' => $select_user]);
            } else {
                if ($get_user_id == $_GET['id']) {
                    echo $this->templates->render('/page_media', ['user' => $select_user]);
                } else {
                    $this->flash->message("Можливо редагувати тільки свій профіль!", 'warning');
                    header("Location: /");
                }
            }
        } else {
            header("Location: /login");
        }
    }

    //upload media
    public function upload_media()
    {

        $userId = $this->auth->getUserId();//Получили ід користувача
        $role_admin = $this->auth->hasRole(\Delight\Auth\Role::ADMIN);
        $tmp_name = $_FILES['image']['tmp_name'];
        $name = $_FILES['image']['name'];

        if ($userId == $_POST['id'] || $role_admin) {
            if (isset($_POST['submit'])) {
                if (isset($_FILES['image'])) {
                    $name_image = uniqid() . $name;
                    move_uploaded_file($tmp_name, '../img/' . $name_image);
                    $this->qb->update(['image' => $name_image], $_POST['id'], 'users');
                    $this->flash->message("Профіль успішно оновлено!", 'success');
                    header("Location: /page_profile");
                }
            }
        } else {
            $this->flash->message("Ви не можете оновити профіль!", 'warning');
            header("Location: /page_profile");
        }
    }

    public function delete_user()
    {
        $user_id = $_GET['id'];
        $get_user_id = $this->auth->getUserId();
        $select_user = $this->qb->getOne(['*'], 'users', $user_id);

        if ($this->auth->isLoggedIn()) {
            if ($this->auth->hasRole(\Delight\Auth\Role::ADMIN)) {
                try {
                    unlink('../img/' . $select_user['image']);//Удаляю аватарку с диска
                    $this->auth->admin()->deleteUserById($user_id);
                    $this->flash->message("Користувач видаленний!", 'warning');
                    header("Location: /");
                } catch (\Delight\Auth\UnknownIdException $e) {
                    die('Unknown ID');
                }
            } else {
                if ($get_user_id == $user_id) {
                    try {
                        unlink('../img/' . $select_user['image']);//Удаляю аватарку с диска
                        $this->auth->admin()->deleteUserById($user_id);
                        session_destroy();
                        header("Location: /register");
                    } catch (\Delight\Auth\UnknownIdException $e) {
                        die('Unknown ID');
                    }
                } else {
                    $this->flash->message("Можливо редагувати тільки свій профіль!", 'warning');
                    header("Location: /");
                }
            }
        } else {
            header("Location: /login");
        }
    }
}