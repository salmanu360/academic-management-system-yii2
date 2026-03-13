<!DOCTYPE html>
<html lang="en">
    
<head>
        <title>Login</title><meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="<?php echo yii::$app->request->baseUrl;?>/css/login/bootstrap.min.css" />
        <link rel="stylesheet" href="<?php echo yii::$app->request->baseUrl;?>/css/login/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="<?php echo yii::$app->request->baseUrl;?>/css/login/maruti-login.css" />
        <link rel="stylesheet" href="<?php echo yii::$app->request->baseUrl;?>/css/font-awesome.min.css" />
    </head>
    <body>
        <div id="loginbox">            
             <?= $content; ?>
            
        </div>
        
        <script src="<?php echo yii::$app->request->baseUrl;?>/js/login/jquery.min.js"></script>  
        <script src="<?php echo yii::$app->request->baseUrl;?>/js/login/maruti.login.js"></script> 
    </body>

</html>