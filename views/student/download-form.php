<?php
use yii\helpers\Url;
$file_name = $model->user->Image;
$file_path = Yii::getAlias('@webroot').'/uploads/';
if(!empty($file_name) && file_exists($file_path.$file_name)) {
  $web_path = Yii::getAlias('@web').'/uploads/school-logo/';
  $imageName = Yii::$app->common->getBranchDetail()->logo;

}else{
  $web_path = Yii::getAlias('@web').'/uploads/school-logo';
  $imageName = 'male.jpg';

} ?>
<div class="container">

  <div style="  height: 70px; width: auto; padding-top: 10px; ">
    <div style="width: 25%; float: left;"><div class="logo" style="margin-left: 40px; margin-top: 20px; background-image: url('<?= Url::to('@web/uploads/school-logo/'.Yii::$app->common->getBranchDetail()->logo)?>'); height: 100px; width: 100px; background-position:center; background-size: cover;"></div></div>
    <div style="width: 50%; float: left;"><div class="title"><h3><?=Yii::$app->common->getBranchDetail()->address?></h3></div> 
    <div class="address"><h3>Cell: <?=Yii::$app->common->getBranchDetail()->phone?></h3></div></div>
    <div style="width: 25%; float: left;">
     <?php if(!empty($file_name) && file_exists($file_path.$file_name)) {
      $web_paths = Yii::getAlias('@web').'/uploads/';
      $imageNameStudent = $model->user->Image; ?>
      <div class="circle" style="background-image: url('<?= $web_paths.$imageNameStudent?>'); background-position:center; background-size: cover;height: 110px; width:100px;" ></div>
    <?php }else{?>
      <div class="circle" style="background-image: url('<?= Url::to('@web/img')?>/<?=($model->gender_type == 1)? 'male.jpg' :'female.png' ?>'); background-position:center; background-size: cover;height: 110px; width:100px;" ></div>
    <?php } ?>

  </div>  
  <div style="background-image: url('<?php echo Yii::$app->request->baseUrl?>/img/img.png'); width: auto; height: 80px; margin-top: -20px;
  background-size: cover;  background-repeat: no-repeat; " ></div>        
</div>




<form class="admForm">
  <label style=" font-size: 20px; font-weight: bold;">Admission no.</label>
  <input style="width: 200px; height: 30px;" type="text" name="admForm" value="<?php echo strtoupper($model->user->username) ?>">
</form>
<div class="container1">
  <form >
    <div style="height: 3px"></div>
    <label style="margin: 15px;" > 1. <b>Student's Name</b></label> : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?= strtoupper(Yii::$app->common->getName($model->user_id)) ?>
    <div style="margin-top: -6px;border-bottom: 1px solid #888686;width: 450px;margin-left: 200px">
    </div>
    <div style="height: 5px"></div>
    <label style="margin: 15px;" > 2. <b>Father's Name</b></label> :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?= strtoupper(Yii::$app->common->getParentName($model->stu_id)) ?>
    <div style="margin-top: -6px;border-bottom: 1px solid #888686;width: 450px;margin-left: 200px">
    </div>
    <div style="height: 5px"></div>
    
    <label style="margin: 15px;"> 3. <b>Guardian's Name</b></label> :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?=($parents_details->guardian_name)?$parents_details->guardian_name:''; ?>
    <div style="margin-top: -6px;border-bottom: 1px solid #888686;width: 450px;margin-left: 200px">
    </div>


    <div style="height: 5px"></div>
    <label style="margin: 15px;" > 4. <b>Student Mob #</b></label> : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?= ($model->contact_no)?$model->contact_no:'N/A'  ?>
    <div style="margin-top: -6px;border-bottom: 1px solid #888686;width: 450px;margin-left: 200px">
    </div>
    <div style="height: 5px"></div>
    <label style="margin: 15px;" > 5. <b>Guardian Mob #</b></label> : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php echo ($parents_details->contact_no)?$parents_details->contact_no:'N/A';?>
    <div style="margin-top: -6px;border-bottom: 1px solid #888686;width: 450px;margin-left: 200px">
    </div>
    <div style="height: 5px"></div>
    <label style="margin: 15px;" > 6. <b>Father CNIC #</b></label> : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?=$parents_details->cnic  ?>
    <div style="margin-top: -6px;border-bottom: 1px solid #888686;width: 450px;margin-left: 200px">
    </div>
    <div style="height: 5px"></div>
    <label style="margin: 15px;" > 7. <b>Father Profession</b></label> : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?=$profession_details->title  ?>
    <div style="margin-top: -6px;border-bottom: 1px solid #888686;width: 450px;margin-left: 200px">
    </div>
    <div style="height: 5px"></div>
    <label style="margin: 15px;" > 8. <b>Father Designation</b></label> : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?= ($parents_details->designation)?$parents_details->designation:'N/A'  ?>
    <div style="margin-top: -6px;border-bottom: 1px solid #888686;width: 450px;margin-left: 200px">
    </div>
    <div style="height: 5px"></div>
    <label style="margin: 15px;" > 9. <b>Father Organization</b></label> : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?= ($parents_details->organisation)?$parents_details->organisation:'N/A'  ?>
    <div style="margin-top: -6px;border-bottom: 1px solid #888686;width: 450px;margin-left: 200px">
    </div>
    <div style="height: 5px"></div>
    <label style="margin: 15px;" > 10. <b>Date of Birth</b></label> : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php echo date('d-m-Y',strtotime($model->dob)) ?> (<?php echo date('d M Y',strtotime($model->dob))?>)
    <div style="margin-top: -6px;border-bottom: 1px solid #888686;width: 450px;margin-left: 200px">
    </div>
    <div style="height: 5px"></div>
    <label style="margin: 15px;" > 11. <b>Date of Birth (in words)</b></label> :
    <?php  $num = date('d',strtotime($model->dob));
    $num1 = date('F',strtotime($model->dob));
    $num2 = date('Y',strtotime($model->dob));
  $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
  //echo $f->format($num);
  echo strtoupper($f->format($num)) .' '. strtoupper($num1) .' '.strtoupper($f->format($num2));
    //$exp = explode('-', $num);
    //$f = new NumberFormatter("en_US", NumberFormatter::SPELLOUT);
    //echo ucfirst($f->format($exp[0])) . ' and ' . ucfirst($f->format($exp[2]));
     ?>
    <div style="margin-top: -6px;border-bottom: 1px solid #888686;width: 450px;margin-left: 200px">
    </div>

    <div style="height: 7px"></div>
    <label style="margin: 15px;" > 12. <b>Address</b></label> :
    <?php echo $model->location1?>,<?php echo $model->city->city_name?>-<?php echo $model->district->District_Name?>-<?php echo $model->province->province_name?> 
    <div style="margin-top: -6px;border-bottom: 1px solid #888686;width: 550px;margin-left: 100px">
    </div>
    <div style="height: 7px"></div>
    <label style="margin: 15px;" > 13. <b>Religion</b></label> : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?= strtoupper($model->religion->Title) ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;14:<b>Nationality</b>:  <u>PAKISTANI</u>&nbsp;&nbsp;&nbsp; 15. <b>Tribe</b>:  <u><?php echo ($model->tribe)?strtoupper($model->tribe):'' ?></u>
    <div style="margin-top: -6px;border-bottom: 1px solid #888686;width: 80px;margin-left: 200px">
    </div>

    <div style="height: 5px"></div>
    <label style="margin: 15px;" > 16. <b>Class in which admission is sought</b></label> :
    <?php echo Yii::$app->common->getCGSName($model->class_id,$model->group_id,$model->section_id); ?>
    <div style="margin-top: -6px;border-bottom: 1px solid #888686;width: 450px;margin-left: 200px">
    </div>
    <div style="height: 5px"></div>
    <label style="margin: 15px;" > 17. <b>Class & School last attended</b></label> :
    <?php if(count($StudentEducationalHistoryInfo)>0){echo $StudentEducationalHistoryInfo->Institute_name;}else{echo 'N/A';} ?>
    <div style="margin-top: -6px;border-bottom: 1px solid #888686;width: 450px;margin-left: 200px">
    </div>
    
    <div class="containerRules">
      <p class="rules">
        a. I agree with and accept the rules, regulations and requirements of <?php echo strtoupper(Yii::$app->common->getBranchDetail()->name) ?>, <?php echo strtoupper(Yii::$app->common->getBranchDetail()->address) ?> and pledge to abide by them. I fully understand that the decision of PRINCIPAL in all matters will be final and not challengeable.
        <br>
        <br>
        b. I undertake that i will pay all the school dues in advance in respect of my child/ward regularly. as required by the school administration.
        <br>
        <br>
        c. I fully understand that fees and funds etc, once paid are NOT REFUNDABLE.
        <br>
        <br>
        d. I would be agree on the withdrawl of my son/ward at any stage if the school administration finds that he is not deserving or his stay is detrimental to the interest of the institution.
        <br>

        <br>
        <div class="columnDate">
         <div class="date">
          Date:  <?php echo date('d M Y',strtotime($model->registration_date)) ?> 
        </div>  
      </div>           
      <div class="columnSign"> 
       <div class="sign">
        Father/Guardian signature ____________________ 
      </div>
    </div>

  </p>
</div>
</form>

</div>

<div class="column" style=" margin-left: 30px;
margin-right: 30px;"  >
<div class="row" >
  <hr>
</div>

<div style="width: 40%; float: left; ">
  <div class="heading" >
    <p class="txt">FOR OFFICE USE ONLY</p>
  </div>
</div>

<div class="row" >
  <hr>
</div>
</div>

<div class="container2">

  <br>
  <label style="margin: 15px;">Admission No.</label> &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; <b><u><?php echo $model->user->username ?></u></b>
  <br>
  <br>

  <label style="margin: 15px;">Admission In-Charge</label> &nbsp; ___________________________________
  <br>
  <br>

  <label style="margin: 15px;">Remarks</label> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ____________________________________
  <div class="principal">Principal Signature</div> 
  <br>

</div>

</div>