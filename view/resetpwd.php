<!--Set new password-->       
<section clas="main-container">
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-md-offset-2">
                <div class="form-block">                                                  
                    <h3>Set new password</h3>
                        <div class="form">
                            <form action="#" method="POST">
                            <span class="error"><?= (array_key_exists('empty_fields', $errors) ? $errors['empty_fields'] : ''); ?></span> 
                            <span class="error"><?= (array_key_exists('empty', $errors) ? $errors['empty'] : ''); ?></span>
                                <div class="form-group">
                                    <input type="password" class="form-control" placeholder="Password" name="newpassword"  value = "<?= $newpassword;?>">
                                    <span class="error"><?= (array_key_exists('pass_str', $errors) ? $errors['pass_str'] : ''); ?></span> 
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" placeholder="Confirm Password" name="newpassword_confirm"  value = "<?= $newpassword_confirm;?>">
                                    <span class="error"><?= (array_key_exists('pass_conf', $errors) ? $errors['pass_conf'] : ''); ?></span>
                                </div>
                                    <button type="submit" class="btn btn-success custom-btn" name="change">Change password</button>
                            </form>
                        </div>
                </div>
            </div>        
        </div>
    </div>
</section>