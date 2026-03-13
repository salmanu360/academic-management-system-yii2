<?php 
  use yii\helpers\Url;
 ?>
 <a href="javascript:void(0)" class="YearCals" data-year="<?= $years; ?>" data-url="<?php echo Url::to(['reports/yearly-admission']) ?>">Newly Admission</a>-->

 <a href="javascript:void(0)" class="classwiseYearAdmisn" data-url=<?php echo Url::to(['reports/yearlyadmission-studentsno-classwise']) ?> data-year=<?= $years; ?>>Class Wise</a>

 <input type="submit" data-classid="<?= $classid; ?>"  data-year="<?= $years; ?>" value="Generate Report" class="classwiseYearAdmisnStudents btn btn-success" data-url="<?php echo Url::to(['reports/yearlyadmission-studentsno-classwise-studentpdf'])?>" name="Generate Report" />
                  <table class="table table-striped">
                  <table class="table table-striped">
                          <thead>
                          <tr>
                            <th>SR</th>
                            <th>Registeration No.</th>
                            <th>Name</th>
                            <th>Father Name</th>
                            <th>Class</th>
                            <th>Registeration Date</th>
                           </tr>
                           </thead>
                           <tbody>
                            <?php
                            $count=0;
                            $sum=0;
                           // echo '<pre>';print_r($year);die;
                             foreach ($yearAdmissionstudents as $years) {
                              $count++;
                              ?>
                            <tr>
                              <td><?php echo $count; ?></td>
                              <td><?php echo Yii::$app->common->getUserName($years->user_id); ?></td>
                              <td><?php echo Yii::$app->common->getName($years->user_id); ?></td>
                              <td><?php if(!empty($years['stu_id'])){
                                 echo Yii::$app->common->getParentName($years['stu_id']);
                              }else{
                                echo 'N/A';
                              }  ?></td>
                              <td><?php echo Yii::$app->common->getCGSName($years->class_id,$years->group_id,$years->section_id); ?></td>
                              <td><?php echo $years->registration_date ?></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                     </table>