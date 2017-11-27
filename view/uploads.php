<!--Upload form-->
<section clas="main-container">
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-md-offset-2">
                <div class="form-block">                                                  
                    <h3>Upload</h3>
                        <div class="form">
                            <form action="#" method="POST" enctype="multipart/form-data">
                                <div class="form-group has-success">
                                    <label class="custom-file">                                 
                                        <div class="form-group has-success">                                         
                                            <input type="text" class="form-control" placeholder="Map title" name="title" autofocus value="<?=$title?>">
                                            <span class="error"><?= (array_key_exists('title', $errors) ? $errors['title'] : ''); ?></span>
                                        </div>
                                        <div class="form-group has-success">   
                                             <input type="file" id="file" class="custom-file-input" name="uploaded_file">
                                             <span class="error"><?= (array_key_exists('file_empty', $errors) ? $errors['file_empty'] : ''); ?></span>
                                             <span class="error"><?= (array_key_exists('file_size', $errors) ? $errors['file_size'] : ''); ?></span>
                                             <span class="error"><?= (array_key_exists('file_format', $errors) ? $errors['file_format'] : ''); ?></span>
                                        </div>                                        
                                        <span class="custom-file-control"></span>
                                        <br>
                                        <button type="submit" class="btn btn-success" name="upload">Upload</button>
                                    </label>
                                </div>
                            </form>
                        </div>
                </div>
            </div>
        </div>
    </div>
</section>