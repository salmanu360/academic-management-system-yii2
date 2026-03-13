    <?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\Breadcrumbs;
    use app\assets\AppAsset;
    use app\components\widgets\Flash;
    use app\components\widgets\Title;
    use app\models\Branch;
    use app\models\EmployeeInfo;
    AppAsset::register($this);
    $controller =  Yii::$app->controller->id;
    $action     = Yii::$app->controller->action->id;
    $user = Yii::$app->user->identity;
    $directoryAsset = Yii::$app->request->baseUrl;
    $colors=\app\models\Colors::find()->one();
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <style> .sidebar-menu ul li a{color:#<?php echo $colors->sidebartextcolor; ?>;}</style>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <link rel="shortcut icon" href="<?=$directoryAsset?>/img/krypotons.png">
        <?php $this->head();
        if(!empty(Yii::$app->common->getBranchDetail()->logo)){
        $logo = '/uploads/school-logo/'.Yii::$app->common->getBranchDetail()->logo;
    }else{
        $logo = '/img/logo.png';
    }
    $studentInfo = \app\models\StudentInfo::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
    $file_name = Yii::$app->user->identity->Image;
    $file_path = Yii::getAlias('@webroot').'/uploads/';

    if(!empty($file_name) && file_exists($file_path.$file_name)) {
        $web_path = Yii::getAlias('@web').'/uploads/';
        $imageName = Yii::$app->user->identity->Image;

    }else{
        $web_path = Yii::getAlias('@web').'/img/';
        if($studentInfo) {
            if ($studentInfo->gender_type == 1) {
                $imageName = 'male.jpg';
            } else {
                $imageName = 'female.png';

            }
        }else{
            $imageName = 'male.jpg';
        }
    }
    /*username*/
    $return_name="";
    if(Yii::$app->user->identity->first_name){
        $return_name .= ucfirst(Yii::$app->user->identity->first_name);
    }if(Yii::$app->user->identity->middle_name){
        $return_name .=  ' '. ucfirst(Yii::$app->user->identity->middle_name);
    }if(Yii::$app->user->identity->last_name){
        $return_name .= ' '.ucfirst(Yii::$app->user->identity->last_name);
    }
        if($controller =='student' || $controller == 'fee' || $controller == 'transport-allocation'){
            $this->registerJsFile(Yii::getAlias('@web').'/js/student.js',['depends' => [yii\web\JqueryAsset::className()]]); 
        }
        if($controller =='fee' || $controller == 'fee-group' || $controller == 'fee-submission' ){
            $this->registerJsFile(Yii::getAlias('@web').'/js/fee.js',['depends' => [yii\web\JqueryAsset::className()]]);
        }
        if($controller =='exams' || $controller =='reports' || $controller =='fee'){
            $this->registerJsFile(Yii::getAlias('@web').'/js/exams.js',['depends' => [yii\web\JqueryAsset::className()]]);
        }
        if($controller =='reports' || 'receivable'){
            $this->registerJsFile(Yii::getAlias('@web').'/js/reports.js',['depends' => [yii\web\JqueryAsset::className()]]);
        }
        if($controller =='book-issue'){
            $this->registerJsFile(Yii::getAlias('@web').'/js/reports.js',['depends' => [yii\web\JqueryAsset::className()]]);
        }
        if($controller =='expenses'){
            $this->registerJsFile(Yii::getAlias('@web').'/js/reports.js',['depends' => [yii\web\JqueryAsset::className()]]);
        }
        if($controller =='book-issue' || 'fine-detail'){
            $this->registerJsFile(Yii::getAlias('@web').'/js/student.js',['depends' => [yii\web\JqueryAsset::className()]]);
        }if($controller =='visitors'){
            $this->registerJsFile(Yii::getAlias('@web').'/js/reports.js',['depends' => [yii\web\JqueryAsset::className()]]);
            $this->registerJsFile(Yii::getAlias('@web').'/js/student.js',['depends' => [yii\web\JqueryAsset::className()]]);
        }
        if($controller =='class-timetable' || $controller =='exams' || $controller =='reports' || $controller =='general'){
            $this->registerJsFile(Yii::getAlias('@web').'/js/general.js',['depends' => [yii\web\JqueryAsset::className()]]);
        }if($controller =='employee'){
            $this->registerJsFile(Yii::getAlias('@web').'/js/calendar.js',['depends' => [yii\web\JqueryAsset::className()]]);
        }if($controller =='fee-group'){
            $this->registerJsFile(Yii::getAlias('@web').'/js/general.js',['depends' => [yii\web\JqueryAsset::className()]]);
        }if($controller =='messages'){
            $this->registerJsFile(Yii::getAlias('@web').'/js/general.js',['depends' => [yii\web\JqueryAsset::className()]]);
        }if($controller =='assigments'){
            $this->registerJsFile(Yii::getAlias('@web').'/js/general.js',['depends' => [yii\web\JqueryAsset::className()]]);
        }
        if($controller =='branch'){
            if(yii::$app->user->identity->fk_role_id !=2){
                header("Location: ".Url::to(['site/login'],true));
                exit;
            }
        }
        if($controller =='branch-reports'){
            if(yii::$app->user->identity->fk_role_id !=2){
                header("Location: ".Url::to(['site/login'],true));
                exit;
            }else{
                $this->registerJsFile(Yii::getAlias('@web').'/js/branch-reports.js',['depends' => [yii\web\JqueryAsset::className()]]);
            }
        }

         ?> 
    </head> 
    <!-- <body class="hold-transition skin-blue fixed sidebar-mini <?//=$controller.'-'.$action?>"> -->
     <body class="hold-transition skin-blue sidebar-fixed sidebar-mini"> 

    <?php $this->beginBody() ?>
    <div class="wrapper" style="background-color:#<?php echo $colors->siderbarbackgroud; ?>">

        <header class="main-header">
         <?php $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";?>
            <div class="hidden-xs">
            <a style="background:#<?php echo $colors->headerbackgroud?>" href="<?php echo $actual_link;?>" class="logo">
                            <span class="logo-mini" style="width: 50px;"><b>K</b>ES</span>
                             <span class="logo-lg" style="color:#<?php echo $colors->sidebartextcolor; ?>;">
            Kryptons <small style="font-size: 14px;">Education System</small>

            </span>
             </a>
             </div>

            <nav class="navbar navbar-static-top" style="background:#<?php echo $colors->headerbackgroud;?>">

                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                    <?php if(Yii::$app->user->identity->fk_role_id == 5){?>
                <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Settings">
                    <i class="fa fa-wrench"></i>
                </a>
               <ul class="dropdown-menu">
                   <li class="header" style="text-align: center">Settings</li>
                    <li class="header">
                        <a href="<?= Url::to(['/settings']) ?>"><i class="fa fa-circle-o"></i><?php echo Yii::t('app','Main Settings') ?></a>
                        <a href="<?= Url::to(['/exam-grading']) ?>"><i class="fa fa-circle-o"></i><?php echo Yii::t('app','Exam Grading') ?></a>
                    </li>
                </ul>
            </li>
               <?php } ?>
                    <?php if(Yii::$app->user->identity->fk_role_id == 1 || Yii::$app->user->identity->fk_role_id == 7){ ?>
                <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Settings">
                    <span class="hidden-xs">Basic Settings</span> <span class="caret"></span>

                </a>
                <ul class="dropdown-menu">
                   <li class="header" style="text-align: center;background-color:#<?php echo $colors->headerbackgroud; ?>;color: white">Basic Settings</li>
                    <li class="header">
                        <a href="<?= Url::to(['/session']) ?>"><i class="fa fa-circle-o"></i><?php echo Yii::t('app','Manage Sessions') ?></a>
                        <a href="<?= Url::to(['/class']) ?>"><i class="fa fa-circle-o"></i><?php echo Yii::t('app','Manage Classes/Group/section') ?></a>
                       <!--  <a href="<?//= Url::to(['/class/bulk-classes']) ?>"><i class="fa fa-circle-o"></i><?php //echo Yii::t('app','Create Bulk Classes') ?></a> -->
                        <a href="<?= Url::to(['/subjects']) ?>"><i class="fa fa-circle-o"></i><?php echo Yii::t('app','Manage Subjects') ?></a>
                        <a href="<?= Url::to(['/working-days/create']) ?>"><i class="fa fa-circle-o"></i><?php echo Yii::t('app','Working days') ?></a>
                        <a href="<?= Url::to(['/profession']) ?>"><i class="fa fa-circle-o"></i><?php echo Yii::t('app','Manage Profession') ?></a>
                        <a href="<?= Url::to(['/leave-settings']) ?>"><i class="fa fa-circle-o"></i><?php echo Yii::t('app','Allowed Leaves') ?></a>
                        <a href="<?= Url::to(['/shift']) ?>"><i class="fa fa-circle-o"></i><?php echo Yii::t('app','Shift Management') ?></a>
                        <a href="<?= Url::to(['/religion']) ?>"><i class="fa fa-circle-o"></i><?php echo Yii::t('app','Religion Management') ?></a>
                        <a href="<?= Url::to(['/degree']) ?>"><i class="fa fa-circle-o"></i><?php echo Yii::t('app','Degree Management') ?></a>
                        <a href="<?= Url::to(['/exam-grading']) ?>"><i class="fa fa-circle-o"></i><?php echo Yii::t('app','Exam Grading') ?></a>
                        <a href="<?= Url::to(['/message-control']) ?>"><i class="fa fa-circle-o"></i><?php echo Yii::t('app','Message Control') ?></a>
                        <a href="<?= Url::to(['/sms/index']) ?>"><i class="fa fa-circle-o"></i><?php echo Yii::t('app','SMS') ?></a>
                    </li>
                </ul>
               </li>
                <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Settings">
                    <i class="fa fa-wrench"></i>
                </a>
                <ul class="dropdown-menu">
                   <li class="header alert" style="text-align: center;background-color:#<?php echo $colors->headerbackgroud; ?>;color: white">System Settings</li>
                    <li class="header">
                     <a href="<?= Url::to(['/settings/signature']) ?>"><i class="fa fa-circle-o"></i><?php echo Yii::t('app','Signature') ?></a>
                        <a href="<?= Url::to(['general/colors']) ?>"><i class="fa fa-circle-o"></i><?php echo Yii::t('app','Theme Colors') ?></a>
                         <a href="<?= Url::to(['/settings']) ?>"><i class="fa fa-circle-o"></i><?php echo Yii::t('app','Main Settings') ?></a>
                         <a href="<?= Url::to(['/settings/dash-setting']) ?>"><i class="fa fa-circle-o"></i><?php echo Yii::t('app','Dashboard Settings') ?></a>
                    </li>
                </ul>
               </li>
               <?php } if(Yii::$app->user->identity->fk_role_id == 5 || Yii::$app->user->identity->fk_role_id == 1){ ?>
               <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Finance Management">
                    <i class="fa fa-money"></i>
                </a>
                <ul class="dropdown-menu">
                   <li class="header alert" style="text-align: center;background-color:#<?php echo $colors->headerbackgroud; ?>;color: white"">Financial Management</li>
                    <li>
                        <a href="<?= Url::to(['/fee-head']) ?>"><i class="fa fa-circle-o"></i><?php echo Yii::t('app','Fee Head') ?></a>
                        <a href="<?= Url::to(['/fee-group']) ?>"><i class="fa fa-circle-o"></i><?php echo Yii::t('app','Assign Fee') ?></a>
                        <a href="<?= Url::to(['/discount-type']) ?>"><i class="fa fa-circle-o"></i><?php echo Yii::t('app','Fee Discounts') ?></a>
                        <a href="<?= Url::to(['/fee-plan']) ?>"><i class="fa fa-circle-o"></i><?php echo Yii::t('app','Fee Discount/Reallocate') ?></a>
                    </li>
                </ul>
               </li>
                <li><a href="<?php echo Url::to(['/visitors'])?>" title="<?php echo Yii::t('app','Add Visitor') ?>"><i class="fa fa-user"></i></small></a></li>
                 <li><a href="<?php echo Url::to(['general/support'])?>" title="Contact Support"><i class="fa fa-envelope-o"></i><small class="label pull-right bg-green">Support</small></a></li>
               <?php } ?>
               <?php if(Yii::$app->user->identity->fk_role_id == 1){ ?>
                    <!-- paper making 
                    <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" onclick="return confirm('Sorry you are not allowed for this action')">
                    <i class="fa fa-file-text-o"></i>
                </a>
                </li>
                        <li class="dropdown messages-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-envelope-o"></i>
                                <span class="label label-success">4</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have 4 messages</li>
                                <li>
                                    <ul class="menu">
                                        <li>
                                            <a href="#">
                                                <div class="pull-left">
                                                    <img src="<?//= Url::to('@web/img/user2-160x160.jpg') ?>" class="img-circle" alt="User Image">
                                                </div>
                                                <h4>
                                                    Support Team
                                                    <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                                </h4>
                                                <p>Why not buy a new awesome theme?</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="footer"><a href="#">See All Messages</a></li>
                            </ul>
                        </li>
                        <li class="dropdown tasks-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-flag-o"></i>
                                <span class="label label-danger">9</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have 9 tasks</li>
                                <li>
                                    <ul class="menu">
                                        <li>
                                            <a href="#">
                                                <h3>
                                                    Design some buttons
                                                    <small class="pull-right">20%</small>
                                                </h3>
                                                <div class="progress xs">
                                                    <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                        <span class="sr-only">20% Complete</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="footer">
                                    <a href="#">View all tasks</a>
                                </li>
                            </ul>
                        </li>-->
                         <?php } ?>
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- profile image -->
                            <?php
                            $file_name = Yii::$app->user->identity->Image;
                            $file_path = Yii::getAlias('@webroot').'/uploads/';
                            if(!empty($file_name) && file_exists($file_path.$file_name)) {
                                $web_path = Yii::getAlias('@web').'/uploads/';
                                $imageName = Yii::$app->user->identity->Image;

                            }else{
                                $web_path = Yii::getAlias('@web').'/img/';
                                    $imageName = 'male.jpg';

                            }
                            ?>
                            
                            <img class="user-image" src="<?= $web_path.$imageName?>" alt="User Image">
                            <!-- end of profile image -->
                                <span class="hidden-xs" style="color:#<?php echo $colors->sidebartextcolor; ?>;"><?php echo $return_name;?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                   
                     <img class="img-circle" style="width: 90px; height: 90px;" src="<?= Url::to('@web') . $logo ?>" alt="<?= Yii::$app->common->getBranchDetail()->name . '-logo' ?>">

                    <p>
                    <?php echo Yii::$app->common->getBranchDetail()->name;
                    echo '<small> Member since '.date("M-Y",strtotime(yii::$app->user->identity->created_at)).'</small>';
                    ?>
                    </p>
                                </li>
                                <!-- Menu Body -->
                                <li class="user-body">
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <?php 
                                            if(Yii::$app->user->identity->fk_role_id == 1){?>
                                                <a href="<?= Url::to(['/mesages-other']) ?>">Friends</a>
                                                <?php }else{ ?>
                                            <a data-toggle="modal" href='#change-password'>Change Password</a>
                                            <?php } ?>
                                        </div>
                                        <!-- <div class="col-xs-4 text-center">
                                            <a href="#">Sales</a>
                                        </div> -->
                                        <!-- <div class="col-xs-4 text-center">
                                            
                                        </div> -->
                                    </div>
                                    <!-- /.row -->
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="<?= Url::to(['user/profile']) ?>" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <!-- <a href="#" class="btn btn-default btn-flat">Sign out</a> -->
    <form action="<?php echo yii::$app->request->baseUrl;?>/logout" method="post">
    <input type="hidden" name="_csrf" value="Y1ZWZy5XbmkhD2ciejwEPgJvJC5dZyZZOzo9JlwHBw4KMxpTWCEhCg=="><button type="submit" class="mega-items signout-opt"> Signout</button></form>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- Control Sidebar Toggle Button by changing theme -->
                        <!-- <li class="hidden-xs">
                            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                        </li> -->
                    </ul>
                </div>
            </nav>
        </header>
        <aside class="main-sidebar" style="background-color:#<?php echo $colors->siderbarbackgroud;?>!important;">
            <section class="sidebar">
                <div class="user-panel">
                    <div class="pull-left image">
                        <?php
                            $file_name = Yii::$app->user->identity->Image;
                            $file_path = Yii::getAlias('@webroot').'/uploads/';
                            if(!empty($file_name) && file_exists($file_path.$file_name)) {
                                $web_path = Yii::getAlias('@web').'/uploads/';
                                $imageName = Yii::$app->user->identity->Image;

                            }else{
                                $web_path = Yii::getAlias('@web').'/img/';
                                    $imageName = 'male.jpg';
                            }
                            ?>
                            <img class="img-circle" src="<?= $web_path.$imageName?>" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p style="color:#<?php echo $colors->sidebartextcolor; ?>"><?php 
        $smsSet=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
        echo strtoupper($smsSet->school_name);
                         ?></p>
                        <!-- <a href="#"><i class="fa fa-circle text-success"></i> Online</a> -->
                        <a href="#"> </a>

                    </div>
                </div>
                <?php
        if($action != 'login') {
            if(Yii::$app->user->identity->fk_role_id == 1){ //main administrator
               echo $this->render('menu.php');

              }elseif(Yii::$app->user->identity->fk_role_id == 2){ //brach-manager
               echo $this->render('menuroles/branchmanager_menu.php');

              }elseif(Yii::$app->user->identity->fk_role_id == 3){ // parent
               echo $this->render('menuroles/student_menu.php');

              }elseif(Yii::$app->user->identity->fk_role_id == 4){ // teacher
               echo $this->render('menuroles/teacher_menu.php');
              
              }elseif(Yii::$app->user->identity->fk_role_id == 5){ // accountant
               echo $this->render('menuroles/accountant_menu.php');
              }elseif(Yii::$app->user->identity->fk_role_id == 6){ // accountant
               echo $this->render('menuroles/librarian_menu.php');
              }elseif(Yii::$app->user->identity->fk_role_id == 7){ // accountant
               echo $this->render('menuroles/administrator.php');
              }
        } 
        ?> 
            </section>
        </aside>
        <!-- =============================================== -->
        <div class="content-wrapper">
            <section class="content-header clearfix" >
                <?= Title::widget([
                    'header' => isset($this->params['header']) ? $this->params['header'] : '',
                    'description' => isset($this->params['description']) ? $this->params['description'] : ''
                ]) ?>
            </section>
            <section class="content">
                <?= Flash::widget() ?>
                <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
                <?= $content ?>
            </section>
        </div>
        <footer class="main-footer" style="background:#<?php echo $colors->siderbarbackgroud?>;color:#<?php echo $colors->sidebartextcolor; ?>;">
            <div class="pull-right hidden-xs"><?//= Yii::powered() ?></div>
            <strong>Copyright &copy; 2015, A Product Of <a href="http://Kryptonstechnology.com">Kryptons Tech</a>.</strong> All rights
            reserved.
        </footer>
        <!-- =============================================== -->
        <?= $this->render('/layouts/sidebar') ?>
    </div>
    <?php
    if($action != 'login') {
        //echo  $this->render('footer.php');
    }
    ?>
    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>


    <div class="modal fade" id="change-password">
    <div class="modal-dialog" style="margin-top: 129px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="close-change-pass">&times;</button>
                <h4 class="modal-title">Change Password</h4>
            </div>
            <div class="modal-body">
                <form action="" method="POST" role="form">

                    <div class="form-group field-current_password  clearfix">
                        <label for="current-password" class="field-label control-label">Current Passowrd</label>
                        <div class="field-input">
                            <input type="password" class="form-control" id="current-password" placeholder="Current password" required="required">
                        </div>
                        <div class="help-block"></div>
                    </div>

                    <div class="form-group field-new_password clearfix">
                        <label for="new-password" class="field-label control-label">New Passowrd</label>
                        <div class="field-input">
                            <input type="password" class="form-control" id="new-password" placeholder="New Password" required="required">
                        </div>
                        <div class="help-block"></div>
                    </div>


                    <div class="form-group field-confirm_password clearfix">
                        <label for="confirm-password" class="field-label control-label">Confirm Passowrd</label>
                        <div class="field-input">
                            <input type="password" class="form-control" id="confirm-password" placeholder="Confirm password" required="required">
                        </div>
                        <div class="help-block"></div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="save-password" data-url ="<?=\yii\helpers\Url::to(['site/change-password']);?>">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="close-change-pass">Close</button>

            </div>
        </div>
    </div>
</div>
<script>
    $('.select2').select2();
</script>

<!-- ClickDesk Live Chat Service for websites -->
<!-- <script type='text/javascript'>
var _glc =_glc || []; _glc.push('ag9zfmNsaWNrZGVza2NoYXRyFAsSB3dpZGdldHMYgIDg1LWymAsM');
var glcpath = (('https:' == document.location.protocol) ? 'https://my.clickdesk.com/clickdesk-ui/browser/' : 
'http://my.clickdesk.com/clickdesk-ui/browser/');
var glcp = (('https:' == document.location.protocol) ? 'https://' : 'http://');
var glcspt = document.createElement('script'); glcspt.type = 'text/javascript'; 
glcspt.async = true; glcspt.src = glcpath + 'livechat-new.js';
var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(glcspt, s);
</script> -->
<!-- End of ClickDesk -->
