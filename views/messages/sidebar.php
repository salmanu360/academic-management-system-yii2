<?php
use yii\helpers\Url;
$id=base64_decode(Yii::$app->request->get('id'));
  ?>
<style>
	.nav-sidebar { 
    border-right: 1px solid #ddd;
    background: #ffff;
}
.nav-sidebar a {
    color: #333;
    -webkit-transition: all 0.08s linear;
    -moz-transition: all 0.08s linear;
    -o-transition: all 0.08s linear;
    transition: all 0.08s linear;
    -webkit-border-radius: 4px 0 0 4px; 
    -moz-border-radius: 4px 0 0 4px; 
    border-radius: 4px 0 0 4px; 
}
.nav-sidebar .active a { 
    cursor: pointer;
    background-color: #428bca; 
    color: #fff; 
    text-shadow: 1px 1px 1px #666; 
}
.nav-sidebar .active a:hover {
    background-color: #428bca;   
}
.nav-sidebar .text-overflow a,
.nav-sidebar .text-overflow .media-body {
    white-space: nowrap;
    overflow: hidden;
    -o-text-overflow: ellipsis;
    text-overflow: ellipsis; 
}
/* Right-aligned sidebar */
.nav-sidebar.pull-right { 
    border-right: 0; 
    border-left: 1px solid #ddd; 
}
.nav-sidebar.pull-right a {
    -webkit-border-radius: 0 4px 4px 0; 
    -moz-border-radius: 0 4px 4px 0; 
    border-radius: 0 4px 4px 0; 
}
</style>
<nav class="nav-sidebar">
    <ul class="nav">
        <li <?php if($id == 'quick'){ echo 'class=active';}?>  ><a href="<?php echo Url::to(['site/sms','id'=>base64_encode('quick')]);?>">Quick SMS</a></li>
        <li <?php if($id == 'other'){ echo 'class=active';}?>><a href="<?php echo Url::to(['/mesages-other','id'=>base64_encode('other')]);?>">Contact List</a></li>
        <li <?php if($id == 'smstocontactlist'){ echo 'class=active';}?>><a href="<?php echo Url::to(['/mesages-other/contacts','id'=>base64_encode('smstocontactlist')]);?>">SMS to Contact List</a></li>
        <li <?php if($id == 'feesms'){ echo 'class=active';}?>><a href="<?php echo Url::to(['messages/feesms','id'=>base64_encode('feesms')]);?>">Send Fee of <?php echo date('M')?></a></li>
       <li <?php if($id == 'inbox'){ echo 'class=active';}?>><a href="<?php echo Url::to(['/messages','id'=>base64_encode('inbox')]);?>">Inbox</a></li>
        <li class="nav-divider"></li>
    </ul>
</nav>