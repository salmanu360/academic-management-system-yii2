<?php 
use yii\helpers\Url;
 if(isset($_GET['year'])){?>
<style type="text/css">
table{width: 100%}
  *{ margin-left:0; padding:0;}
  th, tr, td  {
    border:1px solid black;
    padding:10px;
    font-size:1.5em;
  }

  tr:nth-child(even){background-color: #f2f2f2}
</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
</div>
<h3 style="text-align: center;">UpComing Exam List of <?php echo yii::$app->common->getCGSName($class_id,$group_id,$section_id) ?> In <?php echo $year ?></h3>
  <?php }else{
     ?>
<a href="<?php echo Url::to(['yearly-exam','year'=>$year,'class_id'=>$class_id,'g_id'=>$group_id,'section_id'=>$section_id]) ?>" name="Generate Report" class="btn btn-primary pull-right"><i class="fa fa-download" aria-hidden="true"></i> Generate Report</a>
<?php } ?>
<table class="table table-bordered">
                        <thead>
                           <tr class="success">
                            <th>SR</th>
                            <th>Exam Name</th>
                            <th>Exam Date</th>
                            </tr>
                         </thead>
                           <tbody>
                            <?php 
                            $i=0;
                            foreach ($yearGetExam as $key => $yearGetExam) {
                              $i++;
                              ?>
                              <tr>
                              <th><?=$i; ?></th>
                              <th><a href="" data-examid="<?php echo $yearGetExam['exam_id'] ?>"><?= $yearGetExam['exam_name'] ?></a></th>
                              <th><?php echo $yearGetExam['exam_date'] ?></th>
                            </tr>
                           <?php } ?>
                           </tbody>
</table>