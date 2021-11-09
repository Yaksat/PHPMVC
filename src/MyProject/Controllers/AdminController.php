<?php

namespace MyProject\Controllers;

use MyProject\Exceptions\ForbiddenException;
use MyProject\Exceptions\UnauthorizedException;

class AdminController extends AbstractController
{
    public function view()
    {
        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if (!$this->user->isAdmin()) {
            throw new ForbiddenException('Только пользователи с ролью admin могут попасть на эту страницу');
        }

        ;$this->view->renderHtml('admin/adminMain.php');
    }

}