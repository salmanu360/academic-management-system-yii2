<?php 
use app\models\StudentInfo;
use app\models\StuRegLogAssociation;
use app\models\User;
use app\models\StudentLeaveInfo;
use yii\helpers\Url;
//$employeeParent = \app\models\EmployeeParentsInfo::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'emp_id'=>$emplInfo->emp_id])->one();
$getData=Yii::$app->request->get();
$classId=$getData['class_id'];
$class_details=\app\models\RefClass::find()->where(['class_id'=>$classId])->one();
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
 background: url('<?php echo $certificate_path ?>position.png');
background-repeat: no-repeat;

/*background-attachment: fixed;*/
}
 li{
  list-style-type: none;
  font-size: 22px;
 }
 li.bigText{
  font-size: 24px;
 }
 li.littleBig{
  font-size: 23px;
 }

</style>
<body>
 <div style="width: 100%; text-align: center; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#037dbd;margin: 0;padding: 15px 0 8px 0;padding-left: 100px">
    <?= strtoupper(Yii::$app->common->getBranchDetail()->address) ?>
  </h2>
  </div>
  <?php if(!empty($file_name) && file_exists($file_path.$file_name)) {
                                $web_paths = Yii::getAlias('@web').'/uploads/';
                                $imageNameStudent = $student->user->Image; ?>
      <img style="margin-left:30px;margin-top: 10px" width="85px" height="71px" src="<?= $web_paths.$imageNameStudent?>" alt="<?=Yii::$app->common->getName($student->user->id);?>">
      <?php }else{?>
  <img style="margin-left:30px;margin-top: 10px" width="85px" height="71px" src="<?= Url::to('@web/img')?>/<?=($student->gender_type == 1)? 'male.jpg' :'female.png' ?>" alt="<?=Yii::$app->common->getName($student->user->id);?>">
             <?php }?>
  <div class="expairydate_section" style="margin-left: 860px;margin-top: -60px">
      <img width="85px" height="71px" class="user-image" src="<?= Url::to('@web/uploads/school-logo/'.Yii::$app->common->getBranchDetail()->logo)?>" alt="school logo"> 
  </div>
   <div class="startdate_section" style="float: left;
  margin-left: 90px;margin-top: 30px;color:#5e4619;font-weight: bold;">
    <?php echo date('M d, Y') ?>
     </div>
  <div class="main_section" style="margin-left: 50px;
  float: left;
  margin-top: 30px;">
   <i>
    <ol>
    <li class="bigText"><i>In recognized of the successful completion of the requisites and on nomination of</i></li>
    <div style="height: 8px"></div>
    <li class="bigText"><i> <?php echo strtoupper(Yii::$app->common->getBranchDetail()->address) ?></i></li>
    <div style="height: 8px"></div>
    <li class="littleBig"><i> by virtue of their authority, hereby confers upon the student of <b><?php echo $class_details->title?></b>, </i></li>
    <div style="height: 8px"></div>
    <li class="bigText"><i> <strong><?php if($student->gender_type == 1){echo 'Mr.';}else{echo 'Ms.';}?> <?php echo yii::$app->common->getName($student->user_id)?></strong> <?php if($student->gender_type == 1){echo 'S/O';}else{echo 'D/O';} ?> <strong><?php echo yii::$app->common->getParentName($student->stu_id); ?></strong> the <b><?php echo $position?> position Holder</b></i></li>
    <div style="height: 8px"></div>
    <li><i> in <?php echo strtoupper($exam_details->type) ?> (Exam) held in <?php echo date('d M Y',strtotime($exam_details->exam_date)) ?>.</i></li>
    <div style="height: 12px"></div>
    <center><h3 style="margin-left:180px">With all the honors, rights and privileges there to pertaining.</h3></center>
</ol>
</i> 
    </div>  
</body>