<?php

namespace MyProject\Services;

use MyProject\Models\Users\User;

class EmailSender
{
    public static function send(
        User $receiver,
        string $subject,
        string $templateName,
        array $templateVars = []
    ): void {
        extract($templateVars);

        ob_start();
        require __DIR__ . '/../../../templates/mail/' . $templateName;
        $body = ob_get_contents();
        ob_end_clean();

        mail($receiver->getEmail(), $subject, $body, "From: yaksat995@gmail.com\n" . 'Content-Type: text/html; charset=UTF-8');
    }

}
