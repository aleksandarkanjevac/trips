<?php
namespace core;

use core\exceptions\BaseException;
use core\exceptions\NotValidEmailException;
use core\exceptions\NotValidPasswordException;
use core\exceptions\NotValidFileException;
use core\exceptions\NotValidDbException;

class Controller
{
    use View;
//index page action
    public function index()
    {
        if (App::is_guest()) {
            $this->login();
        } else {
            $this->trips();
        }
    }
//login page action
    public function login()
    {
        if (!App::is_guest()) {
            App::redirect();
        }
        $errors = [];
        if (isset($_SESSION['mssg'])) {
            $mssg = $_SESSION['mssg'];
        } else {
            $mssg = '';
        }
        $email = '';
        $password = '';
        if (isset($_POST['login'])) {
            try {
                $email = trim($_POST['email']);
                $password = trim($_POST['password']);
                $user = \core\Validation::loginValidation($email, $password);
                if (!empty($user)) {
                    App::login_user($user->id);
                }
            } catch (BaseException $e) {
                switch ($e->getCode()) {
                    case \core\exceptions\NotValidEmailException::CODE_DB_DEFAULT:
                        $errors['empty_fields'] = $e->getCustomMessage();
                        break;
                    case \core\exceptions\NotValidEmailException::CODE_EMAIL_FORMAT:
                        $errors['wrong_email'] = $e->getCustomMessage();
                        break;
                    case \core\exceptions\NotValidPasswordException::CODE_PWD_DEFAULT:
                        $errors['wrong_pwd'] = $e->getCustomMessage();
                        break;
                    default:
                        $errors['empty'] = $e->getMessage();
                }
            }
        }
        $this->render_page('login', ['errors'=>$errors,'email'=>$email,'password'=>$password,'mssg'=>$mssg]);
    }

//registration page action
    public function register()
    {
        $errors = [];
        $name = '';
        $email = '';
        $password = '';
        $password_confirm = '';
        if (isset($_POST['register'])) {
            try {
                $name = \core\Validation::checkData($_POST['full_name']);
                $email = \core\Validation::checkData($_POST['email']);
                $password =\core\Validation::checkData( $_POST['password']);
                $password_confirm =  \core\Validation::checkData($_POST['password_confirm']);
                $email =  \core\Validation::checkEmail($email);
                $pwd = \core\Validation::checkPwd($password, $password_confirm);
        
                $user = new \core\models\Users();
                $user->setAttr(['name' => $name,'email' => $email, 'password' => $pwd,'status'=>'0']);
                $user->save();
        
                if ($user->save()) {
                    App::login_user($user->id);
                }
            } catch (BaseException $e) {
                switch ($e->getCode()) {
                    case \core\exceptions\NotValidEmailException::CODE_DB_DEFAULT:
                        $errors['empty_fields'] = $e->getCustomMessage();
                        break;
                    case \core\exceptions\NotValidEmailException::CODE_EMAIL_EXIST:
                        $errors['email_used'] = $e->getCustomMessage();
                        break;
                    case \core\exceptions\NotValidPasswordException::CODE_PWD_FORMAT:
                        $errors['pass_str'] = $e->getCustomMessage();
                        break;
                    case \core\exceptions\NotValidPasswordException::CODE_PWD_EXIST:
                        $errors['pass_conf'] = $e->getCustomMessage();
                        break;
                    default:
                        $errors['empty'] = $e->getMessage();
                }
            }
        }
        $this->render_page('registration', ['errors'=>$errors,'name'=>$name,'email'=>$email,'password'=>$password,'password_confirm'=>$password_confirm]);
    }

//trips page action
    public function trips()
    {
        if (App::is_guest()) {
            App::redirect(['r'=>'login']);
        }

        //PAGINATION
        $limit = 10;//should be in some config.
        $page = array_key_exists('page',$_GET) ? (int)$_GET['page'] : 0;
        $page = ($page < 0) ? 0 : $page;
        $total = Db::query('SELECT count(id) as total FROM '. \core\models\Maps::table().' WHERE user_id ='.$_SESSION['user']);
        $total = !empty($total) ? $total['total'] : 0;

        if (($page*$limit) >= $total) {
            $page = 0;
        }
        $pages = ceil(($total/$limit)-1);
        $total_limit = (($page*$limit) + $limit);
        $total_limit = $total_limit > $total ? $total : $total_limit;
        $listing_info = 'Listing '.($page*$limit).' - '.$total_limit.' of '.$total.' trips';

        $maps=\core\models\Maps::selectByAttributes($filter = '*', ['user_id' => $_SESSION['user']], true, false,['pagination'=>['page'=>$page,'limit'=>$limit]]);
        
        $this->render_page('trips', ['maps'=>$maps,'id' => $_SESSION['user'],'pages'=>$pages,'listing_info'=>$listing_info,'page'=>$page]);
    }

//map page action
    public function map()
    {
        if (App::is_guest()) {
            App::redirect(['r'=>'login']);
        }

        $id = array_key_exists('id', $_GET) ? $_GET['id'] : null;
        if ($id === null || !is_numeric($id)) {
            $this->error(404, 'Nepravilan id');
        }
        $map = \core\models\Maps::findByPk($id);
        if (!$map) {
            $this->error(404, 'Nepravilan id');
        }
        $this->render_page('map', ['map'=>$map]);
    }

//upload maps page action
    public function uploads()
    {
        if (App::is_guest()) {
            App::redirect(['r'=>'login']);
        }
        
        $errors = [];
        $title = '';
        if (isset($_POST['upload'])) {
            try {
                $title = \core\Validation::checkData($_POST['title']);
                $file = \core\Validation::fileValidation(['size'=>$_FILES['uploaded_file']['size'],'type'=>$_FILES['uploaded_file']['type'],'name'=>$_FILES['uploaded_file']['name']]);
                $file_name = \core\Validation::checkData($file);

                $name = pathinfo($file_name, PATHINFO_FILENAME);
                $map_identificator = md5($name."_".time()).".gpx";

                $target = "../uploads/".basename($map_identificator);
                move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $target);

                $id=$_SESSION['user'];
                //save in db
                $map = new \core\models\Maps();
                $map->setAttr(['user_id' => $id,'title'=>$title, 'map_name' => $file_name, 'map_identificator' => $map_identificator]);
                $map->save();
                App::redirect(['r'=>'trips']);
            } catch (BaseException $e) {
                switch ($e->getCode()) {
                    case \core\exceptions\NotValidFileException::CODE_FILE_FORMAT:
                        $errors['file_size'] = $e->getCustomMessage();
                        break;
                    case \core\exceptions\NotValidFileException::CODE_FILE_EXIST:
                        $errors['file_format'] = $e->getCustomMessage();
                        break;
                    case \core\exceptions\NotValidFileException::CODE_FILE_DEFAULT:
                        $errors['file_empty'] = $e->getCustomMessage();
                        break;
                    case \core\exceptions\NotValidEmailException::CODE_DB_DEFAULT:
                        $errors['title'] = $e->getCustomMessage();
                        break;
                    default:
                        $errors['empty'] = $e->getMessage();
                }
            }
        }
        
        $this->render_page('uploads', ['errors'=>$errors,'title'=>$title]);
    }

//error page action
    public function error($code, $message = 'Error')
    {
        $this->render_page('error', ['code'=>$code,'message'=>$message]);
        exit;
    }

//forgot password page action
    public function forgot()
    {
        $errors = [];
        $mssg = '';
        if (isset($_POST['reset'])) {
            try {
                $user =  \core\Validation::resetPwd($_POST['email']);
                $user->remember_token = \core\Validation::tokenGenerator();
                $user->save();

                $to = $user->email;
                $subject = 'Password update request';
                $content = 'Dear '.$user->name.'<br> To reset your password please visit the following link: <a href="http://trips.dev/index.php?r=resetpwd&t='.$user->remember_token.'"></a><br>Regards,<br>Trips.dev ';
                mail($to, $subject, $content);

                $mssg='You successfuly started change password procces. Please check your email';
            } catch (BaseException $e) {
                switch ($e->getCode()) {
                    case \core\exceptions\NotValidEmailException::CODE_DB_DEFAULT:
                        $errors['empty_fields'] = $e->getCustomMessage();
                        break;
                    case \core\exceptions\NotValidEmailException::CODE_EMAIL_FORMAT:
                        $errors['email_format'] = $e->getCustomMessage();
                        break;
                    case \core\exceptions\NotValidPasswordException::CODE_EMAIL_EXIST:
                        $errors['email_exist'] = $e->getCustomMessage();
                        break;
                    default:
                        $errors['empty'] = $e->getMessage();
                }
            }
        }
        $this->render_page('forgot', ['errors'=>$errors,'mssg'=>$mssg]);
    }


//reset password page action
    public function resetpwd()
    {
        if (!isset($_GET['t'])) {
            App::redirect(['r'=>'login']);
        }

        $user = \core\models\Users::selectByAttributes($filter = '*', ['remember_token' => $_GET['t']], false, true);
        if (!$user) {
            App::redirect(['r'=>'login']);
        }
       
        $errors = [];
        $newpassword = '';
        $newpassword_confirm = '';
        if (isset($_POST['change'])) {
            try {
                $newpassword =\core\Validation::checkData($_POST['newpassword']);
                $newpassword_confirm = \core\Validation::checkData($_POST['newpassword_confirm']);

                $password = \core\Validation::checkPwd($newpassword, $newpassword_confirm);
                $user->password = $password;
                $user->remember_token = null;
                if(!$user->save()){
                    throw new BaseException('Password is not changed. Please try again.');
                }
                
                $_SESSION['mssg'] = 'You are successfuly change your password. Please login in with new credentials.';
                App::redirect(['r'=>'login']);
            } catch (BaseException $e) {
                switch ($e->getCode()) {
                    case \core\exceptions\NotValidDbException::CODE_DB_DEFAULT:
                        $errors['empty_fields'] = $e->getCustomMessage();
                        break;
                    case \core\exceptions\ NotValidPasswordException::CODE_PWD_FORMAT:
                        $errors['pass_str'] = $e->getCustomMessage();
                        break;
                    case \core\exceptions\ NotValidPasswordException::CODE_PWD_EXIST:
                        $errors['pass_conf'] = $e->getCustomMessage();
                        break;
                    default:
                        $errors['empty'] = $e->getMessage();
                }
            }
        }

        $this->render_page('resetpwd', ['errors'=>$errors,'newpassword'=>$newpassword,'newpassword_confirm'=>$newpassword_confirm]);
    }

//logout page action
    public function logout()
    {
        if (App::is_guest()) {
            App::redirect(['r'=>'login']);
        }

        App::logout_user();
    }

//get map action
    public function getMap()
    {
        if (App::is_guest()) {
            App::redirect(['r'=>'login']);
        }

        $id =$_GET['id'];
        $id = array_key_exists('id', $_GET) ? $_GET['id'] : null;
        if ($id === null || !is_numeric($id)) {
            $this->error(404, 'Wrong id');
        }
        $map = \core\models\Maps::findByPk($id);
        if (!$map) {
            $this->error(404, 'Wrong id');
        }

        if (file_exists("../uploads/{$map->map_identificator}")) {
            header ("Content-Type: text/xml");
           
            $stream = fopen("../uploads/{$map->map_identificator}", 'rb');
           
            while (!feof($stream)) {
                echo fread($stream, 1024);
            }
            fclose($stream);
            exit;
        }
    }

    //action handle delete trips from db & map file from folder
    public function delete()
    {

        if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 404 Bad Request!');
            exit();
        }

        $map = \core\models\Maps::findByPk($_POST['id']);
        unlink('../uploads/'.$map->map_identificator);
        if (!$map || !$map->delete()) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 404 Bad Request!');
            exit();
        }

    }
}
