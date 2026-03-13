<?php 
use app\models\StudentInfo;
use app\models\StuRegLogAssociation;
use app\models\User;
use app\models\StudentLeaveInfo;
use app\models\RefClass;
use yii\helpers\Url;
$levInfo=StudentLeaveInfo::find()->where(['stu_id'=>$id])->one();
if(count($levInfo)>0){
$getstuid=studentInfo::find()->where(['stu_id'=>$id])->one();
$classname_leaveform=RefClass::find()->where(['class_id'=>$levInfo->enrollment_class])->one();
// echo '<pre>';print_r($levInfo);die;
$users=User::find()->where(['id'=>$getstuid->user_id])->one();
$firstClass=StuRegLogAssociation::find()->where(['fk_stu_id'=>$id])->orderBy(['id'=>SORT_DESC])->one();
$certificate_path = Yii::getAlias('@web').'/img/cerificate/';
$file_name = $getstuid->user->Image;
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
}
li{
  list-style-type: none;
  
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
  <h2 style="font-size:16px; font-weight:700; color:#037dbd;margin: 0;padding: 28px 0 8px 0;">
    <?= strtoupper(Yii::$app->common->getBranchDetail()->address) ?>
  </h2>
</div>

  <div class="main_section" style="margin-left: 23px;">
    <div class="Company_name" style="padding-left: 270px;
  font-family: shardee;
  font-size: 28px;
  font-weight: 700;
  color:#037dbd;
  padding-top: 20px;"> SCHOOL LEAVING CERTIFICATE</div> 
  <!-- student image -->
  <?php if(!empty($file_name) && file_exists($file_path.$file_name)) {
                                $web_paths = Yii::getAlias('@web').'/uploads/';
                                $imageNameStudent = $getstuid->user->Image; ?>
      <img style="margin-left:30px;margin-top: -60px" width="85px;" height="71px" src="<?= $web_paths.$imageNameStudent?>" alt="<?=Yii::$app->common->getName($getstuid->user->id);?>">
      <?php }else{?>
  <img style="margin-left:30px;margin-top: -60px" width="85px" height="71px" src="<?= Url::to('@web/img')?>/<?=($getstuid->gender_type == 1)? 'male.jpg' :'female.png' ?>" alt="<?=Yii::$app->common->getName($getstuid->user->id);?>">
             <?php }?>
  <!-- end of student image -->
     <div class="startdate_section" style="float: left;
  margin-left: 20px;
  margin-top: 30px;">
    Given under our hand in this day<br>
    <?php echo date('M d, Y') ?>
     </div>
     <div class="expairydate_section" style="margin-left: 820px;margin-top: -150px">
      <img width="110px" height="96px" class="user-image" src="<?= Url::to('@web/uploads/school-logo/'.Yii::$app->common->getBranchDetail()->logo)?>" alt="User Image">
    </div><br> <br> <br><br>
    <div class="certify" style=" float:left;margin-left: 40px;
 "><ol>
  <li>This is to certify that</li></ol></div> 
    <div class="condidate_name" style="margin-left: 390px;"><h2 style="color:#037dbd;">
   <?php if($getstuid->gender_type == 1){echo 'Mr.';}else{echo 'Ms.';}?> <?php echo yii::$app->common->getName($getstuid->user_id); ?></h2>
</div> 
<div class="text" style="margin-left: 11px;
  float: left;
  margin-top: 10px;
  font-size: 17px;"><i>

<ol>
  <li><?php if($getstuid->gender_type == 1){echo 'S/O';}else{echo 'D/O';}?> <b><?php echo yii::$app->common->getParentName($id); ?></b>, Resident of <?php echo strtolower($getstuid->location1 .' '. $getstuid->city->city_name .' '.  $getstuid->district->District_Name .' '. $getstuid->province->province_name .' '. $getstuid->country->country_name) ?> </li>
  <li>has been enrolled in this school in standard, <?php echo (!empty($levInfo->enrollment_class))? $classname_leaveform->title:'Add enroll class first(error)'; ?> OF <b><?php echo date('d M, Y',strtotime($getstuid->registration_date)) ?></b> under
registration No. <b><?php echo $users->username ?></b>, and left</li>
<li> on <?php echo date('d M',strtotime($levInfo->created_date)) ?> at standard <?= $levInfo->class->title ?>. <?php echo ($getstuid->gender_type == 1)? 'His':'Her' ?> date of birth according to the school admission is: 
In Figure : <b><?php echo date('d M Y',strtotime($getstuid->dob)) ?></b>.</li>
<li><?php echo ($getstuid->gender_type == 1)? 'He':'She' ?> has been found abiding by the all rules and regulations of the school
and performed well in <?php echo ($getstuid->gender_type == 1)? 'his':'her' ?> academic and co-curricular</li>
<li>activities at this school.</li>
<li>This school accepts <?php echo ($getstuid->gender_type == 1)? 'his':'her' ?> reason for leaving the school and recommends <?php echo ($getstuid->gender_type == 1)? 'him':'her' ?> for
admission in any academic institution in the </li>
<li>same grade.</li>
</ol>
</i>
</div>
    </div>
    <div style="height: 50px"></div>
<pre>
          ISSUED BY                                                                           PRINCIPAL
  </pre>
    <!-- <div class="lastleft_section" style=" margin-left: 80px;">
      <i style="color:#037dbd; font-size: 18px;"></i><br>
      <span style="padding-top: -170px">ISSUED BY </span><br>
    </div>
    <div class="lastright_section" style="margin-left: 820px; margin-top: -50px">
      <i style="color:#037dbd; font-size: 18px;"><span></span></i><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <span>PRINCIPAL</span><br>
    </div> -->
</body>
<?php }else{ ?>
<div class="alert alert-danger">No SLC Found</div>
<?php } ?>