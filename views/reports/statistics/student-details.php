 <?php 
use yii\helpers\Url;
  ?>
 <a href="<?php echo Url::to(['reports/show-stu-details-pdf/','id'=>$stu_id]) ?>" name="Generate Report" class="btn btn-primary pull-right">Generate Report</a>
  <table class="table table-striped">
            <thead>
              <tr>
                <th>SR.</th>
                <th>Name</th>
                <th>Father/Mother</th>
                <th>Previous Class</th>
                <th>Previous Section</th>
                <th>Promoted Class</th>
                <th>Promoted Section</th>
                <th>Promoted Date</th>
              </tr>
            </thead>
            <tbody>
            <?php 
              $i=0;
              //echo '<pre>';print_r($promotedData);die;
              foreach ($promotedData as $promoted) {
              	$studentTable=\app\models\StudentInfo::find()->where(['stu_id'=>$promoted->fk_stu_id])->one();
              	//echo $studentTable->user_id;
                $i++;
               ?>
              <tr>
                <td><?= $i; ?></td>
                <td><?= Yii::$app->common->getName($studentTable->user_id);?></td>
                <td><?= Yii::$app->common->getParentName($studentTable->stu_id);?></td>
                <td><?= $promoted->classCurrent->title ?></td>
                <td><?= $promoted->section->title ?></td>
                <td><?= $promoted->class->title ?></td>
                <td><?= $promoted->sectionCurrent->title ?></td>
                <td><?= $promoted->promoted_date ?></td>

              </tr>
              <?php } ?>
            </tbody>
          </table>