<?php
use yii\helpers\Url;
$logo = $branch_details->logo;
if($logo){
}
$parentInfo = $student->studentParentsInfos;
$file_name = $student->user->Image;
$file_path = Yii::getAlias('@webroot').'/uploads/';
if(!empty($file_name) && file_exists($file_path.$file_name)) {
    $web_path = Yii::getAlias('@web').'/uploads/';
    $imageName = $student->user->Image;

}else{
    $web_path = Yii::getAlias('@web').'/img/';
    if($student->gender_type == 1){
        $imageName = 'male.jpg';
    }else{
        $imageName = 'female.png';

    }
}
$settings=Yii::$app->common->getBranchSettings();
$countSettingsPaperFailed=$settings->failed_paper;
?>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
</div>
<div class="panel-body" style="    padding: 15px;">
          <section class="panel panel-primary mrgn-bttm-0" style="    border-color: black;background-color: #dddddd30;
    border: 1px solid transparent;
    border-radius: 4px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);">
            <header class="panel-heading" style=" padding: 10px 15px;
    border-bottom: 1px solid transparent;
    border-top-right-radius: 3px;
    border-top-left-radius: 3px;">
             
<header>
    <!-- <img class="logo floatLeft" alt="Logo"  /> -->
    <img width="118px" height="101px" class="user-image floatLeft" src="<?= Url::to('@web/uploads/school-logo/').$branch_details->logo?>" alt="User Image" style="float: left">
    <img width="118px" class="user-image floatRight" height="101px" src="<?= $web_path.$imageName?>" alt="<?=ucfirst(Yii::$app->common->getName($student->user_id))?>" style="float: right;">
    <!-- <img class="logo floatRight" alt="Logo" /> -->
    <h4 class="logoHeader" style="height: 120px;
    vertical-align: middle;
    text-align: center;">
    <?php 
    /*if($student->gender_type = 1){
    $s_d= 'S/O';
    $gender_name = 'Male';
}else{
    $s_d= 'D/O';
    $imageName = 'female.png';
}*/
    echo ucfirst(Yii::$app->common->getName($student->user_id))?> <?=($student->gender_type == 1)? 'S/O':'D/O'?> <?=Yii::$app->common->getParentName($student->stu_id)?> <br>
    Class: <?=Yii::$app->common->getCGSName($class_id,($group_id)?$group_id:null,$section_id)?> <br>
    Reg No: <?=$student->user->username?><br>
    Roll No: <?=(!empty($student->roll_no)?$student->roll_no:'N/A')?><br>
    <?php  
    if(isset($_GET['stu'])){
    foreach($query as $sub_data1){

    } 
    echo 'Position: '.Yii::$app->common->multidimensional_search($position, ['student_id'=>$sub_data1['stu_id']]);
    }else{?>
    Position <b><?=(isset($position))?$position:'N/A'?>
    <?php }
    ?>
    </h4>
    <td colspan="2" align="center"><br><span style=" color:black;"><?=ucfirst($exam_details->type)?> Examination Held in <?php echo date('d-M-Y',strtotime($exam_details->exam_date)) ?>  </span> </td>
    <div style="font-weight: bold;     margin-left: 531px;margin-top: -21px;">Passing Marks: <?php echo $exam_details->passing_percentage ?> %</div>   
</header>
</header>     
</section>
      <section class="panel panel-primary" style=" border-color: black; background: white !important">
        <header class="panel-heading">
        <table border="1" width="100%">
        <tr style="background: #bcbeca">
            <td align="center">S.No</td>
            <td align="center"><?=Yii::t('app','Subject')?></td>
            <td align="center"><?=Yii::t('app','Total Marks')?></td>
            <td align="center"><?=Yii::t('app','Obtained Marks')?></td>
            <td align="center"><?=Yii::t('app','Percentage')?></td>
            <td align="center"><?=Yii::t('app','Grade')?></td>
            <td align="left"><?=Yii::t('app','Remarks')?></td>
        </tr>
        <tbody>
        <?php
        $i=1;
        $total_marks      = 0;
        $total_obt_marks  = 0;
        $paper_failed  = 0;
        //echo '<pre>';print_r($query);die;
        foreach($query as $sub_data){
            $subject_percentage = round($sub_data['marks_obtained']*100/$sub_data['total_marks'],2);
            if($sub_data['marks_obtained'] < $sub_data['passing_marks']){
                     $count_totalFailed=count($sub_data['marks_obtained']);
                     $paper_failed+=$count_totalFailed;
                    }
            ?>
            <tr>
                <td><?=$i?></td>
                <td><?= ucfirst($sub_data['subject'])?></td>
                <td style="text-align: center;"><?= $sub_data['total_marks']?></td>
                <td style="text-align: center;"><?php if($sub_data['marks_obtained'] < $sub_data['passing_marks']){
                          echo '<span style="color:red;border:1px solid red">'.floatval($sub_data['marks_obtained']).'</span>';
                        }else{
                          echo floatval($sub_data['marks_obtained']);
                        }?></td>
                <td style="text-align: center;"><?= $subject_percentage?> % </td>
                <td style="text-align: center;"><?php
                $totlaPercent=Yii::$app->common->getLegends($subject_percentage);
                        foreach ($totlaPercent as $key => $percen) {
                             echo $percen;
                            }?> </td>
                <td>
                <?php 
                        if(empty($sub_data['remarks'])){
                           $grade=Yii::$app->common->getGrade($subject_percentage); 
                            foreach ($grade as $key => $value) {
                             $checkMinus = $sub_data['marks_obtained'];
                                        if (strpos($checkMinus, '-') !== false) {
                                            echo '<span style="background:#d8afaf;color: black;border:1px red solid;padding: 2px;">Absent</span>';
                                        }else{
                             echo $value;
                             }
                            }
                        }else{
                          echo $sub_data['remarks'];
                        }?>
                   </td>
            </tr>
            <?php
            $total_marks = $total_marks+$sub_data['total_marks'];
            $total_obt_marks = $total_obt_marks+$sub_data['marks_obtained'];
            $i++;
        }
        $overall_percentage = round($total_obt_marks *100 / $total_marks,2);
        ?>
        </tbody>
        <tfoot>
        <tr >
            <th class="tt_l" colspan="3" style="text-align: right;background: #6dd8a0; padding: 10px;font-size: 15px;text-align: center;color: #fff;">Total <?=$total_marks?></th>
            <th class="tt_pls" style="background: #2e3c54;  padding: 10px;font-size: 15px;text-align: center;color: #fff;"><?=$total_obt_marks?></th>
            <th class="tt_prcnt" style=" background: #404040;  padding: 10px;font-size: 15px;text-align: center;color: #fff;"><?=$overall_percentage?>%</th>
            <th class="tt_prcnt" style=" background: #404040;  padding: 10px;font-size: 15px;text-align: center;color: #fff;"><?php 
            $totlaPercent=Yii::$app->common->getLegends($overall_percentage);
                        foreach ($totlaPercent as $key => $percen) {
                             echo $percen;
                            }
            ?></th>
            <th class="tt_prcnt" style=" background: #404040;  padding: 10px;font-size: 15px;text-align: center;color: #fff;">
                <?php if($paper_failed >= $countSettingsPaperFailed){echo '<span style="color:white">FAILED</span>';}else{echo '<span style="color:white">PASSED</span>';} ?>
            </th>

        </tr>
        </tfoot>
        </table>
        <p></p>  
        <table width="80%" border="0" style=" margin-bottom: 80px"> 
            <tr>
                <td>
                <h4 style="margin-top: 0px">Errors and ommissions are subjected to subsequent rectification</h4> 
                    <h4 style="margin-top: -10px">Examination was taken as a whole in parts.</h4>
                    <h4 style="margin-top: -10px">Date of Issue <?php echo date('d M-Y') ?> </h4></td>
                <td></td>
            </tr>
            
        </table>
         <?php $controlerSign=\app\models\Signature::find()->where(['branch_id'=>Yii::$app->common->getBranch(),'category'=>2])->one();
         if(!empty($controlerSign)){?>
       <img style="height:55px;margin-top: -40px" src="<?php echo Yii::$app->request->baseUrl.'/uploads/doc_signs/'.$controlerSign->image.'.png' ?>" alt="">
   <?php } ?>
            <div>  Controller of Examination
            </div>
            <?php $principalSign=\app\models\Signature::find()->where(['branch_id'=>Yii::$app->common->getBranch(),'category'=>1])->one();
            if(!empty($principalSign)){?>
                <div style="margin-left: 580px;margin-top: -36px;">
                <span>
                <img style="height:55px;margin-top: -40px;" src="<?php echo Yii::$app->request->baseUrl.'/uploads/doc_signs/'.$principalSign->image.'.png' ?>" alt="">
                Principal Signature</span>
            </div>
            <?php }else{ ?>
            <div style="margin-left: 580px;margin-top: -20px;"><span>
                Principal Signature</span>
            </div>
        <?php }?>
                    </header>
                </section>
            </div>