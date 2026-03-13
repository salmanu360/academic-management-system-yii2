<?php 
use yii\helpers\Url;
$certificate_path = Yii::getAlias('@web').'/img/cerificate/';
$file_name = $studentDetails->user->Image;
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
li{
  list-style-type: none;
  font-size: 20px;
  
 }
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
 background: url('<?php echo Url::to('@web/img/cerificate/age.jpg')?>');
background-repeat: no-repeat;
}
</style>
<body>
<?php 
$dateString='10-05-1975';
$years = round((time()-strtotime($dateString))/(3600*24*365.25));
echo $years;
 ?>
  <h2 style="font-size:22px;text-align: center; font-weight:700; color:#818936;margin: 0;padding: 50px 0 8px 0;">
    <?= strtoupper(Yii::$app->common->getBranchDetail()->address) ?>
  </h2>
  <div>
    <?php if(!empty($file_name) && file_exists($file_path.$file_name)) {
                                $web_paths = Yii::getAlias('@web').'/uploads/';
                                $imageNameStudent = $studentDetails->user->Image; ?>
      <img style="margin-left:40px;margin-top: -70px" width="85px;" height="71px" src="<?= $web_paths.$imageNameStudent?>" alt="<?=Yii::$app->common->getName($studentDetails->user->id);?>">
      <?php }else{?>
  <img style="margin-left:40px;margin-top: -70px" width="85px" height="71px" src="<?= Url::to('@web/img')?>/<?=($studentDetails->gender_type == 1)? 'male.jpg' :'female.png' ?>" alt="<?=Yii::$app->common->getName($getstuid->user->id);?>">
             <?php } ?>
  </div>
  <div class="expairydate_section" style="margin-left: 820px;margin-top: -70px">
      <img width="100px" height="90px" class="user-image" src="<?= Url::to('@web/uploads/school-logo/'.Yii::$app->common->getBranchDetail()->logo)?>" alt="User Image">
    </div>
<div class="text" style="margin-left: 11px;
  float: left;
  margin-top: 150px;
  font-size: 17px; text-align: center;"><i>
    
  <ol>
  <li><p><strong><?php if($studentDetails->gender_type == 1){echo 'Mr.';}else{echo 'Ms.';}?> <?php echo yii::$app->common->getName($studentDetails->user_id); ?> <?php if($studentDetails->gender_type == 1){echo 'S/O';}else{echo 'D/O';}?> <b><?php echo yii::$app->common->getParentName($studentDetails->stu_id); ?> </strong> Resident of <?php echo strtolower($studentDetails->location1 .' '. $studentDetails->city->city_name .' '.  $studentDetails->district->District_Name .' '. $studentDetails->province->province_name .' '. $studentDetails->country->country_name) ?></p></li>

    <li><p>has been
remained a regular student of this school under
registration No. <b><?php echo $userDetails->username ?></b>.</p></li>
<li><p><?php echo ($studentDetails->gender_type == 1)? 'His':'Her' ?> date of birth according to the school admission is: 
In Figure : <b><u><?php echo date('d-m-Y',strtotime($studentDetails->dob)) ?> </u></b></p></li>
</ol>
  </i></div>
<address style="margin-top: 70px;padding-top: 70px;margin-left: 200px;font-size: 20px"><u><?php echo date('d-m-Y') ?></u></address>
  <!-- student image -->
  
   
</body>