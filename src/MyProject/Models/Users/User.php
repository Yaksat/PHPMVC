<?php

namespace MyProject\Models\Users;

use MyProject\Exceptions\UploadException;
use MyProject\Models\ActiveRecordEntity;
use MyProject\Exceptions\InvalidArgumentException;

class User extends ActiveRecordEntity
{
    /** @var string */
    protected $nickname;

    /** @var string */
    protected $email;

    /** @var int */
    protected $isConfirmed;

    /** @var string */
    protected $role;

    /** @var string */
    protected $passwordHash;

    /** @var string */
    protected $authToken;

    /** @var string */
    protected $createdAt;

    /** @var string */
    protected $avatar;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @return int
     */
    public function IsConfirmed(): int
    {
        return $this->isConfirmed;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    protected static function getTableName(): string
    {
        return 'users';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public static function signUp(array $userData): User
    {
        if (empty($userData['nickname'])) {
            throw new InvalidArgumentException('Не передан nickname');
        }

        if (!preg_match('/^[a-zA-Z0-9]+$/', $userData['nickname'])) {
            throw new InvalidArgumentException('Nickname может состоять только из символов латинского алфавита и цифр');
        }

        if (empty($userData['email'])) {
            throw new InvalidArgumentException('Не передан email');
        }

        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email некорректен');
        }

        if (empty($userData['password'])) {
            throw new InvalidArgumentException('Не передан password');
        }

        if (mb_strlen($userData['password']) < 8) {
            throw new InvalidArgumentException('Пароль должен быть не менее 8 символов');
        }

        if (static::findOneByColumn ('nickname', $userData['nickname']) !== null) {
            throw new InvalidArgumentException('Пользователь с таким nickname уже существует');
        }

        if (static::findOneByColumn ('email', $userData['email']) !== null) {
            throw new InvalidArgumentException('Пользователь с таким email уже существует');
        }

        $user = new User();
        $user->nickname = $userData['nickname'];
        $user->email = $userData['email'];
        $user->passwordHash = password_hash($userData['password'], PASSWORD_DEFAULT);
        $user->isConfirmed = false;
        $user->role = 'user';
        $user->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
        $user->save();

        return $user;
    }

    public function activate(): void
    {
        $this->isConfirmed = true;
        $this->save();
        UserActivationService::deleteActivationCode($this->getId());
    }

    public static function login(array $loginData): User
    {
        if (empty($loginData['email'])) {
            throw new InvalidArgumentException('Не передан email');
        }

        if (empty($loginData['password'])) {
            throw new InvalidArgumentException('Не передан password');
        }

        $user = User::findOneByColumn('email', $loginData['email']);

        if ($user === null) {
            throw new InvalidArgumentException('Пользователь с таким email не найден');
        }

        if (!password_verify($loginData['password'], $user->getPasswordHash())){
            throw new InvalidArgumentException('Неправильный пароль');
        }

        if (!$user->isConfirmed){
            throw new InvalidArgumentException('Пользователь не подтвержден');
        }

        $user->refreshAuthToken();
        $user->save();

        return $user;
    }

    private function refreshAuthToken()
    {
        $this->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
    }

    public function setAvatar (array $file): string
    {
        if ($file['error'] === UPLOAD_ERR_OK) {
            $srcFileName = time() . $file['name'];
            $newFilePath = __DIR__ . '/../../../../uploads/' . $srcFileName;

            $allowedExtensions = ['jpg', 'png', 'gif'];
            $extension = pathinfo($srcFileName, PATHINFO_EXTENSION);

            if (!in_array($extension, $allowedExtensions)) {
                throw new UploadException('Загрузка файлов с таким расширением запрещена');
            } elseif (file_exists($newFilePath)) {
                throw new UploadException('Файл с таким именем уже существует');
            } elseif (!move_uploaded_file($file['tmp_name'], $newFilePath)) {
                throw new UploadException('Ошибка при загрузке файла');
            } else {
                //'http://phpmvc/uploads/'. $srcFileName
                $this->avatar =  $srcFileName;
                $this->save();
               // return 'Файл ' . $file['name'] . ' загружен';
                return $newFilePath;
            }
        } else {
            switch ($file['error']) {
                case UPLOAD_ERR_INI_SIZE:
                    throw new UploadException('The uploaded file exceeds the upload_max_filesize directive in php.ini');
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    throw new UploadException('Размер файла должен быть не больше 90КБ');
                    break;
                case UPLOAD_ERR_PARTIAL:
                    throw new UploadException('The uploaded file was only partially uploaded');
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new UploadException('No file was uploaded');
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    throw new UploadException('Missing a temporary folder');
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    throw new UploadException('Failed to write file to disk');
                    break;
                case UPLOAD_ERR_EXTENSION:
                    throw new UploadException('File upload stopped by extension');
                    break;

                default:
                    throw new UploadException('Unknown upload error');
                    break;
            }
        }
    }
}