<?php
namespace core;

use core\exceptions\NotValidEmailException;
use core\exceptions\NotValidPasswordException;
use core\exceptions\NotValidFileException;
use core\exceptions\NotValidDbException;

class Validation
{
    //validation and cleaning data
    public static function checkData($data, $allowEmpty = false)
    {
        
        if (!$allowEmpty && empty($data)) {
            throw new NotValidDbException('', NotValidDbException::CODE_DB_DEFAULT);
        }
        
            $data = strtolower(htmlspecialchars(trim($data)));
               
            return $data;
    }
    
    //pwd validation, pwd length & pwd match
    public static function checkPwd($password1, $password2)
    {
       
        if (strlen($password1) < 6) {
            throw new NotValidPasswordException('', NotValidPasswordException::CODE_PWD_FORMAT);
        }
        
        if ($password1 !== $password2) {
            throw new NotValidPasswordException('', NotValidPasswordException::CODE_PWD_EXIST);
        }
        
        return password_hash($password1, PASSWORD_DEFAULT);
    }
        
    //Check if email exist in db
    public static function checkEmail($email)
    {
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new NotValidEmailException('', NotValidEmailException::CODE_EMAIL_FORMAT);
        }

        $check = \core\models\Users::selectByAttributes($filter = 'email', ['email' => $email], false, false);
       
        if (!empty($check)) {
            throw new NotValidEmailException('', NotValidEmailException::CODE_EMAIL_EXIST);
        } else {
            return $email;
        }
    }

    public static function loginValidation($email, $password)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new NotValidEmailException('', NotValidEmailException::CODE_EMAIL_FORMAT);
        }

        $check = \core\models\Users::selectByAttributes($filter = '*', ['email' => $email], false, true);

        if (empty($check)) {
             throw new NotValidEmailException('', NotValidEmailException::CODE_EMAIL_FORMAT);
        }
 
        if (!password_verify($password, $check->password)) {
             throw new \core\exceptions\NotValidPasswordException('',NotValidPasswordException::CODE_PWD_DEFAULT);
        }
        
        return $check;
    }

    public static function resetPwd($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new NotValidEmailException('', NotValidEmailException::CODE_EMAIL_FORMAT);
        }

        $check = \core\models\Users::selectByAttributes($filter = '*', ['email' => $email], false, true);
       
        if (empty($check)) {
            throw new NotValidEmailException('', NotValidEmailException::CODE_EMAIL_FORMAT);
        } else {
            return $check;
        }

    }


    public static function fileValidation(array $file)
    {
       
        $max_size = 3000000;
        $acceptable_formats = ['application/octet-stream'];
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (($file['size']===0)) {
            throw new NotValidFileException('',NotValidFileException::CODE_FILE_DEFAULT);
        }

        if (($file['size'] >= $max_size)) {
            throw new NotValidFileException('',NotValidFileException::CODE_FILE_FORMAT);
        }
        if ($extension !== 'gpx') {
            
            throw new NotValidFileException('',NotValidFileException::CODE_FILE_EXIST);
        }
        return $file['name'];

    }
    
    //generate remember token
    public static function tokenGenerator()
    {
        return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRSTUVWXYZ', ceil(64/strlen($x)))), 1, 64);
    }
}
