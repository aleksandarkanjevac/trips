<?php
namespace core;

class App
{
   /**
   * return wheater user is guest || autenticated
   */
    public static function is_guest()
    {
        return array_key_exists('user', $_SESSION) ? false : true;
    }

    /**
     * return autenticated user id || 0 if user is guest
     */
    public static function user_id()
    {
        if (!self::is_guest()) {
            return 0;
        }

        return $_SESSION['user'];
    }
  
    /**
     * handling logaut user
     */
    public static function logout_user()
    {
        if (!self::is_guest()) {
            unset($_SESSION['user']);
            unset($_SESSION['mssg']);
            header('Location:index.php?r=login');
            self::redirect(['r'=>'login']);
        }
    }

    /**
     * handling login user
     */
    public static function login_user($user_id)
    {
        $_SESSION['user']=$user_id;
        self::redirect();
    }

    /**
     * hendling redirection
     */
    public static function redirect($params = [])
    {
        $get = '';
        if (!empty($params)) {
            $get = '?'. http_build_query($params);
        }
      
        header("Location:index.php{$get}");
    }
}
