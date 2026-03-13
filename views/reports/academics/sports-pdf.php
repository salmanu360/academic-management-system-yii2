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
 background: url('<?php echo Url::to('@web/img/cerificate/sports.jpg')?>');
background-repeat: no-repeat;
}
</style>
<body>

  <h2 style="font-size:22px;text-align: center; font-weight:700; color:red;margin: 0;padding: 70px 0 8px 0;">
    <?= strtoupper(Yii::$app->common->getBranchDetail()->address) ?>
  </h2>
  <!-- student image -->
  <?php if(!empty($file_name) && file_exists($file_path.$file_name)) {
                                $web_paths = Yii::getAlias('@web').'/uploads/';
                                $imageNameStudent = $studentDetails->user->Image; ?>
      <img style="margin-left:40px;margin-top: -70px" width="85px;" height="71px" src="<?= $web_paths.$imageNameStudent?>" alt="<?=Yii::$app->common->getName($studentDetails->user->id);?>">
      <?php }else{?>
  <img style="margin-left:40px;margin-top: -70px" width="85px" height="71px" src="<?= Url::to('@web/img')?>/<?=($studentDetails->gender_type == 1)? 'male.jpg' :'female.png' ?>" alt="<?=Yii::$app->common->getName($getstuid->user->id);?>">
             <?php }?>
  <!-- end of student image -->
  <div class="expairydate_section" style="margin-left: 820px;margin-top: -70px">
      <img width="100px" height="90px" class="user-image" src="<?= Url::to('@web/uploads/school-logo/'.Yii::$app->common->getBranchDetail()->logo)?>" alt="User Image">
    </div>
  <address style="margin-top: 70px;padding-top: 70px;margin-left: 260px;font-size: 20px">
 <?php echo strtoupper(Yii::$app->common->getName($studentDetails->user_id))?> <?php if($studentDetails->gender_type == 1){echo 'S/O';}else{echo 'D/O';}?> <?php echo strtoupper(Yii::$app->common->getParentName($studentDetails->stu_id)) ?>

 <address style="margin-top: 60px;padding-top: 60px;font-size: 20px;margin-left: -60px">
  <?php echo strtoupper($sportsName) ?>
 </address>
 <address style="margin-left:330px;padding-top: -25px"><?php echo strtoupper(Yii::$app->common->getcgsName($studentDetails->class_id,$studentDetails->group_id,$studentDetails->section_id)) ?></address>
 <address style="margin-top: 38px;padding-top: 38px;font-size: 20px">
   <?php echo date('M d, Y') ?>
 </address>
  </address>
</body>