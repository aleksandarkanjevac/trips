
<!--Forgot password form-->       
<section clas="main-container">
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-md-offset-2">
                <div class="form-block">                                                  
                    <h2>Reset password</h2>
                        <div class="form">
                            <form action="#" method="POST"> 
                            <span class="mssg"><?=$mssg?></span> 
                            <span class="error"><?= (array_key_exists('empty_fields', $errors) ? $errors['empty_fields'] : ''); ?></span>                                 
                                <div class="form-group">                                    
                                    <input type="email" class="form-control"  placeholder="Enter your Email" name="email">
                                    <span class="error"><?= (array_key_exists('email_format', $errors) ? $errors['email_format'] : ''); ?></span>
                                    <span class="error"><?= (array_key_exists('email_exist', $errors) ? $errors['email_exist'] : ''); ?></span>
                                </div>
                                <button type="submit" class="btn btn-success custom-btn" name="reset">Reset</button>
                            </form>
                        </div>
                </div>
            </div>        
        </div>
    </div>
</section>

