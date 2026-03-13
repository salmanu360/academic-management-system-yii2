<?php
use yii\helpers\Url;
$certificate_path = Yii::getAlias('@web').'/img/cerificate/';
$file_name = Yii::$app->user->identity->Image;
$file_path = Yii::getAlias('@webroot').'/uploads/';
if(!empty($file_name) && file_exists($file_path.$file_name)) {
  $web_path = Yii::getAlias('@web').'/uploads/school-logo/';
  $imageName = Yii::$app->common->getBranchDetail()->logo;

}else{
  $web_path = Yii::getAlias('@web').'/uploads/school-logo';
  $imageName = 'male.jpg';

}
?>
<style type="text/css">
<?php 
$this->registerCss(" 

  @media print{    
    .footer{
      display:none;
    }
    header {
      display:none;
    }
  }
  ");
?>
body{
 background: url('<?php echo $certificate_path ?>Certificate-01333_03.png');
 background-repeat: no-repeat;

 /*background-attachment: fixed;*/
}
li{
  list-style-type: none;
  font-size: 30px;
}

</style>
<body>
  <div style="width: 100%; text-align: center; color: #000; font-size:14px;">
    <h2 style="font-size:16px; font-weight:700; color:#037dbd;margin: 0;padding: 15px 0 8px 0;padding-left: 100px">
      <?= strtoupper(Yii::$app->common->getBranchDetail()->address) ?>
    </h2>
  </div>
  <div class="main_section" style="margin-left: 23px;">
    <div class="Company_name" style="padding-left: 260px;
    font-family: shardee;
    font-size: 30px;
    font-weight: 700;
    color:#037dbd;
    padding-top: 20px;"> BEST TEACHER CERTIFICATE</div> 
    <div class="startdate_section" style="float: left;
    margin-left: 20px;
    margin-top: 30px;">
    Given under our hand in this day<br>
    <?php echo date('M d, Y') ?>
  </div>
  <div class="expairydate_section" style="margin-left: 820px;margin-top: -40px">
    <img width="118px" height="101px" class="user-image" src="<?= $web_path.$imageName?>" alt="User Image">
  </div>
  <br>
  <div class="certify" style=" float:left;margin-left: 20px;
  ">This is to certify that</div> 
  <div class="condidate_name" style="margin-left: 390px;"><h2 style="color:#037dbd;">
   <?php if($employee->gender_type == 1){echo 'Mr.';}else{echo 'Ms.';}?> <?php echo yii::$app->common->getName($employee->user_id); ?></h2>
   </div> 

   <div class="text" style="margin-left: 20px;
   float: left;
   margin-top: 10px;
   font-size: 17px;">
   <i>
    <ol>
      <li><i>In recognized of the successful completion of the requisites and on</i></li>
      <li><i>nomination of </i></li>
      <li><i> by virtue of their authority, hereby confers upon </i></li>
      <li><i>  the  position Holder in  (Exam) held in.</i></li>
      <center><h3 style="margin-left:180px">With all the honors, rights and privileges there to pertaining.</h3></center>
    </ol>
  </i>
</div>
</div>
<div style="height: 40px"></div>
<div class="lastleft_section" style=" margin-left: 80px;">
  <i style="color:#037dbd; font-size: 18px;"></i><br>
  <span>ISSEUED BY </span><br>

</div>
<div class="lastright_section" style="margin-left: 820px;">
  <i style="color:#037dbd; font-size: 18px;"><span><?php echo strtoupper(Yii::$app->common->getBranchSettings()->principal_name) ?></span></i><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <span>PRINCIPAL</span><br>
</div>
</body>