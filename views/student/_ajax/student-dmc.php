<?php
use yii\helpers\Url;
$logo = $branch_details->logo;
if($logo){
   /* $temp =explode('_',$branch_details->logo);
    $logo= $temp[0];
    $path_info = pathinfo(Url::to('@webroot/uploads/school-logo/').$branch_details->logo);
   $logo.'.'.$path_info['extension'] */

}
/*s/o of d/o*/
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
if($student->gender_type = 1){
    $s_d= 'S/O';
    $gender_name = 'Male';
}else{
    $s_d= 'D/O';
    $imageName = 'female.png';
}
?>
<div class="fs_student shade">
    <div class="col-sm-6 dmc-logo">
        <div class="row">
            <div class="col-sm-9 thumb">
            </div>
        </div>
    </div>
    <div class="col-sm-6 sd_col">
      <!-- <a style="position: absolute;right: 10px;top: 8px;" href="<?/*=Url::to(['exams/student-dmc',
            'exam_id'    => $exam_details->id,
            'stu_id'     => $student->stu_id,
            'class_id'   => $student->class_id,
            'group_id'   => ($student->group_id)?$student->group_id:null,
            'section_id' => $student->section_id,
            'position'=> (isset($position))?$position:'N/A'
        ]);*/?>"><span class="glyphicon glyphicon-print btn btn-default btn-sm" title="Print DMC"></span>
      </a> -->
        <div class="col-sm-4 sd-thumb">    
        </div>
    </div>
    <div class="col-sm-12 dmc_tcontent"></div>
    </div>
<div class="row">
    <div class="col-md-12">
    <div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
          <div class="col-sm-3 dm-thumb">
              <img style="width: 100px;height: 95px;" src="<?= Url::to('@web/uploads/school-logo/').$branch_details->logo?>" alt="DMC">
            </div>
            <div class="col-md-6">
            <div class="row">
              <div class="col-md-12">
                  
              <p style="font-weight: bold;font-size: 18px">Report form of <b><?=Yii::$app->common->getCGSName($student->class_id,($student->group_id)?$student->group_id:null,$student->section_id)?></b> </p>
              </div>  
            </div>
              <table>
                  <tr>
                  <span></span>
                      <th>Student:  </th>
                      <th><?=ucfirst(Yii::$app->common->getName($student->user_id))?> </th>
                       </tr>
                       <tr>
                           
                      <th>Parent:   </th>
                       <th><?=Yii::$app->common->getParentName($student->stu_id)?></th> 
                       </tr>
                       <tr><th>Reg. No:  </th>
                      <th><?=$student->user->username?></th>  
                      </tr>
                      <tr><th>Roll No.:  </th>
                      <th><?=(!empty($student->roll_no)?$student->roll_no:'N/A')?></th>  
                      </tr>

                      <!-- <tr><th>Position &nbsp;&nbsp;&nbsp;</th>
                      <th>    
          <span><stong style="color:green;">Position <b>
         <?//=Yii::$app->common->multidimensional_search($positions, ['student_id'=>$student->stu_id])?> 
         <?//= (isset($position))?$position:'N/A' ?>
           
         </b></stong></span> 
    
                      </th>
                      </tr> -->
              </table>

            </div>
              
            <div class="col-md-2 pull-right">
            
            <div class="sdt_in">
              <img src="<?= $web_path.$imageName?>" alt="<?=ucfirst(Yii::$app->common->getName($student->user_id))?>">
            </div>
            </div>
            <div class="col-md-3 pull-right">
            
            </div>

        </div>
       <div class="row">
         <div class="col-md-3 pull-right"><span style="font-weight: bold;    margin-left: 15px;">Passing Marks: <?php echo $exam_details->passing_percentage ?> %</strong></div>
       </div>
    </div>
    <div class="panel-body">
       <table class="table table-bordered">
    <thead>
      <tr class="info">
        <th>#</th>
        <th colspan="2">Subject Name</th>
        <th>Total Marks</th>
        <th>Marks Obtained</th>
        <th>Grade</th>
        <th>Percentage</th>
        <th colspan="3">Remarks</th>
      </tr>
    </thead>
    <tbody>
    <?php
                $i=1;
                $total_marks      = 0;
                $total_obt_marks  = 0;
//echo '<pre>';print_r($query);die;
                foreach($query as $sub_data){
                    $subject_percentage = round($sub_data['marks_obtained']*100/$sub_data['total_marks'],2);
                ?>
                     <tr>
                        <td><?=$i?></td>
                        <td colspan="2"><?= ucfirst($sub_data['subject'])?></td>
                        <td> <?= $sub_data['total_marks']?></td>
                        <td><?= floatval($sub_data['marks_obtained'])?></td>
                        <td><?= Yii::$app->common->getLegends($subject_percentage)?> </td>
                        <td><?= $subject_percentage?> % </td>
                        <td  colspan="3"><?= $sub_data['remarks']?></td>
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
                  <tr class="success">
                        <th></th>
                        <th colspan="2">Total</th>
                
                          <th class="tt_l"><?=$total_marks?></th>
                        <th class="tt_pls"><?=$total_obt_marks?></th>
                        <th class="tt_prcnt"><?=Yii::$app->common->getLegends($overall_percentage)?></th>
                        <th class="tt_prcnt"><?=$overall_percentage?>%</th>
                        <th class="tt_prcnt"></th>

                       
                    </tr>
                </tfoot>
  </table>
    </div>
    <div class="panel-footer">
        
    </div>
    </div>
        
    </div>
</div>