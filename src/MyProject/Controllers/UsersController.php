<?php

namespace MyProject\Controllers;

use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Exceptions\UnauthorizedException;
use MyProject\Exceptions\UploadException;
use MyProject\Models\Users\User;
use MyProject\Models\Users\UserActivationService;
use MyProject\Models\Users\UsersAuthService;
use MyProject\Services\EmailSender;

class UsersController extends AbstractController
{
    public function login()
    {
        /* Если пользователь уже залогинен, то кидаем на главную */
        if ($this->user!==null){
            header('Location: /');
            exit();
        }

        if (!empty($_POST)){
            try {
                $user = User::login($_POST);
                UsersAuthService::createToken($user);
                header('Location: /');
                exit();
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/login.php', ['error' => $e->getMessage()]);
                return;
            }


        }
        $this->view->renderHtml('users/login.php');
    }

    public function logout()
    {
        setcookie('token','',time()-3600,'/');
        header('Location: /');
    }

    public function signUp()
    {
        if (!empty($_POST)){
            try {
                $user = User::signUp($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/signUp.php', ['error' => $e->getMessage()]);
                return;
            }

            if ($user instanceof User) {
                $code = UserActivationService::createActivationCode($user);

                EmailSender::send($user, 'Активация', 'userActivation.php',[
                    'userId' => $user->getId(),
                    'code' => $code
                ]);

                $this->view->renderHtml('users/signUpSuccessful.php');
                return;
            }
        }
        $this->view->renderHtml('users/signUp.php');
    }

    public function activate (int $userId, string $activationCode)
    {
        $user = User::getById($userId);

        if ($user === null) {
            $this->view->renderHtml('users/notActivate.php', ['error' => 'Пользователь не найден']);
            return;
        }

        if ($user->IsConfirmed()) {
            $this->view->renderHtml('users/notActivate.php', ['error' => 'Пользователь уже активирован']);
            return;
        }

        $isCodeValid = UserActivationService::checkActivationCode($user, $activationCode);
        if($isCodeValid) {
            $user->activate();
            $this->view->renderHtml('users/activationSuccessful.php');
        } else {
            $this->view->renderHtml('users/notActivate.php', ['error' => 'Неверный код активации']);
        }
    }

    public function avatar (): void
    {
        if ($this->user === null){
            throw new UnauthorizedException();
        }

        if (!empty($_FILES['userfile'])) {
            try {
                $this->user->setAvatar($_FILES['userfile']);
                $this->view->renderHtml('users/avatar.php', ['userFileName' => $_FILES['userfile']['name']]);
                return;
            } catch (UploadException $e) {
                $this->view->renderHtml('users/avatar.php', ['error' => $e->getMessage()]);
                return;
            }
        }

        $this->view->renderHtml('users/avatar.php');
    }

    public function defaultAvatar (): void
    {
        if ($this->user === null){
            throw new UnauthorizedException();
        }

        $this->user->defaultAvatar();
        $this->view->renderHtml('users/avatar.php');
    }
}
