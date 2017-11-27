
<!--Registration form-->       
<section clas="main-container">
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-md-offset-2">
                <div class="form-block">                                                  
                    <h3>Register</h3>
                        <div class="form">
                            <form action="#" method="POST">
                            <span class="error"><?= (array_key_exists('empty_fields', $errors) ? $errors['empty_fields'] : ''); ?></span> 
                                <div class="form-group has-success">                                         
                                    <input type="text" class="form-control" placeholder="Full Name" name="full_name"  autofocus value = "<?= $name;?>">
                                </div>
                                <div class="form-group has-success">
                                    <input type="email" class="form-control" placeholder="Email" name="email"  value = "<?= $email;?>">
                                    <span class="error"><?= (array_key_exists('email_used', $errors) ? $errors['email_used'] : ''); ?></span>
                                </div>
                                <div class="form-group has-success">
                                    <input type="password" class="form-control" placeholder="Password" name="password"  value = "<?= $password;?>">
                                    <span class="error"><?= (array_key_exists('pass_str', $errors) ? $errors['pass_str'] : ''); ?></span> 
                                </div>
                                <div class="form-group has-success">
                                    <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirm"  value = "<?= $password_confirm;?>">
                                    <span class="error"><?= (array_key_exists('pass_conf', $errors) ? $errors['pass_conf'] : ''); ?></span>
                                </div>
                                    <button type="submit" class="btn btn-success custom-btn" name="register">Register</button>
                            </form>
                        </div>
                </div>
            </div>        
        </div>
    </div>
</section>