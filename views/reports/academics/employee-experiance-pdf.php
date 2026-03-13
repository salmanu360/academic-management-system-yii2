<?php 
use app\models\StudentInfo;
use app\models\StuRegLogAssociation;
use app\models\User;
use app\models\StudentLeaveInfo;
use yii\helpers\Url;
$employeeParent = \app\models\EmployeeParentsInfo::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'emp_id'=>$emplInfo->emp_id])->one();
$certificate_path = Yii::getAlias('@web').'/img/cerificate/';
$file_name = $emplInfo->user->Image;
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
  
 }
 li.bigText{
  font-size: 24px;
 }
 li.littleBig{
  font-size: 23px;
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
 background: url('<?php echo $certificate_path ?>Certificate-01333_03.png');
background-repeat: no-repeat;

/*background-attachment: fixed;*/
}

</style>
<body>
  <div style="width: 100%; text-align: center; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#037dbd;margin: 0;padding: 25px 0 8px 0;padding-left: 88px">
    <?= strtoupper(Yii::$app->common->getBranchDetail()->address) ?>
  </h2>
</div>
  <div class="main_section" style="margin-left: 23px;">
    <div class="Company_name" style="padding-left: 260px;
  font-family: shardee;
  font-size: 28px;
  font-weight: 700;
  color:#037dbd;
  padding-top: 15px;"> EMPLOYEE EXPERIENCE CERTIFICATE</div>
  <!-- student image -->
  <?php if(!empty($file_name) && file_exists($file_path.$file_name)) {
                                $web_paths = Yii::getAlias('@web').'/uploads/';
                                $imageNameStudent = $emplInfo->user->Image; ?>
      <img style="margin-left:30px;margin-top: -60px" width="85px;" height="71px" src="<?= $web_paths.$imageNameStudent?>" alt="<?=Yii::$app->common->getName($emplInfo->user->id);?>">
      <?php }else{?>
  <img style="margin-left:30px;margin-top: -60px" width="85px" height="71px" src="<?= Url::to('@web/img')?>/<?=($emplInfo->gender_type == 1)? 'male.jpg' :'female.png' ?>" alt="<?=Yii::$app->common->getName($emplInfo->user->id);?>">
             <?php }?>
  <!-- end of student image --> 
     <div class="startdate_section" style="float: left;
  margin-left: 20px;
  margin-top: 30px;">
    Given under our hand in this day<br>
    <?php echo date('M d, Y') ?>
     </div>
     <div class="expairydate_section" style="margin-left: 820px;margin-top: -150px">
       <img width="100px" height="88px" class="user-image" src="<?= Url::to('@web/uploads/school-logo/'.Yii::$app->common->getBranchDetail()->logo)?>" alt="User Image">
     <!--  Certificate number : 00421<br>
      Date of Expiration :  October 31, 2011 -->
    </div>
      <br><br> <br><br>

    <div class="certify" style=" float:left;margin-left: 20px;
 "><ol><li>This is to certify that</li></ol></div> 
    <div class="condidate_name" style="margin-left: 390px;"><h2 style="color:#037dbd;">
   <?php if($emplInfo->gender_type == 1){echo 'Mr.';}else{echo 'Ms.';}?> <?php echo yii::$app->common->getName($id); ?></h2>
</div> 
<div class="text" style="margin-left: 20px;
  float: left;
  margin-top: 10px;
  font-size: 18 px;"><i>
<ol>
    <li><?php if($emplInfo->gender_type == 1){echo 'S/O';}else{echo 'D/O.';}?> <b><?php echo ucfirst($employeeParent->first_name .' '.$employeeParent->last_name ); ?></b>,<?php if($emplInfo->gender_type == 1){echo 'has';}else{echo 'her';}?> worked as a <?php echo $emplInfo->designation->Title ?> from <?php echo date('d M , Y',strtotime($emplInfo->hire_date)) ?> to <?php echo date('d M , Y',strtotime($leaveDate)) ?>. During <?php if($emplInfo->gender_type == 1){echo 'his';}else{echo 'her.';}?> tenure,</li>
    <li><?php if($emplInfo->gender_type == 1){echo 'he';}else{echo 'she.';}?> has been very regular and dedicated towards work.</li>
    <li> <?php if($emplInfo->gender_type == 1){echo 'He';}else{echo 'She.';}?> has a charismatic personality and easily bonds with children of all temperaments. <?php if($emplInfo->gender_type == 1){echo 'He';}else{echo 'She.';}?> understands well the needs and emotions of every child and cares for them accordingly.</li>
    <li><?php if($emplInfo->gender_type == 1){echo 'He';}else{echo 'She.';}?> had a good command over the subjects <?php if($emplInfo->gender_type == 1){echo 'he';}else{echo 'she.';}?> taught. <?php if($emplInfo->gender_type == 1){echo 'His';}else{echo 'Her.';}?> students have always shown good grades.</li>
    <li><?php if($emplInfo->gender_type == 1){echo 'He';}else{echo 'She.';}?> has great communication skills and is very cooperative. <?php if($emplInfo->gender_type == 1){echo 'He';}else{echo 'She.';}?> brings innovative teaching methods to keep <?php if($emplInfo->gender_type == 1){echo 'his';}else{echo 'her.';}?> class active.</li>
    <li> <?php if($emplInfo->gender_type == 1){echo 'He';}else{echo 'She.';}?> is a good planner and manages work nicely.</li><br>
    <li>We wish <?php if($emplInfo->gender_type == 1){echo 'him';}else{echo 'her.';}?> good luck for <?php if($emplInfo->gender_type == 1){echo 'his';}else{echo 'her.';}?> future.</li>
    </i></ol></div>
    </div>
    <div style="height: 40px"></div>
    <pre>
          ISSUED BY                                                                           PRINCIPAL
  </pre>
    <!-- <div class="lastleft_section" style=" margin-left: 80px;">
      <i style="color:#037dbd; font-size: 18px;"></i><br>
      <span>ISSUED BY </span><br>
      
     </div>
    <div class="lastright_section" style="margin-left: 820px; margin-top: -30px">
      <i style="color:#037dbd; font-size: 18px;"><span></span></i><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <span>PRINCIPAL</span><br>
    </div> -->
</body>