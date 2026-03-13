<head>
	<style>
		.table-bordered {
  border: 1px solid #ecf0f1 !important;

}
	</style>
</head>
<?php
$list=array();
$month = 9;
$year = 2017;
for($d=1; $d<=31; $d++)
{
    $time=mktime(12, 0, 0, $month, $d, $year);          
    if (date('m', $time)==$month)       
        $list[]=date('Y-m-d-D', $time);
        $listDate[]=date('Y-m-d', $time);
        $lists[]=date('d', $time);
        $listname[]=date('D', $time);
}
?>   
<div class="box box-default">
        
        <div class="box-body">      
  <div class="row">
  	<div class="col-md-12">
  	<div class="table-responsive">
  		<table class="table table-stripped table-bordered">
    <thead>
      <tr class="success">
      <th>Name</th>
      <?php foreach ($list as $key => $list) {
        echo '<th>'.$lists[$key].'<br />'.$listname[$key].'</th>';  
      } ?>
      </tr>
    </thead>
    <tbody>
    <?php 
        $getName=\app\models\EmployeeInfo::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'is_active'=>1])->All();
        foreach ($getName as $key => $name) {
        	   $currentDate=$listDate[$key];
           $attendance =\app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$name->emp_id])->one();
        	?>
        <tr class="info">
        	<td><?= Yii::$app->common->getName($name->user_id); ?></td>
        	
        	<td>
        	<?php
                 echo $attendance['leave_type'];
                             
        	 ?>
        	
        		
        	</td>
        </tr>
        <?php }?>
 
    </tbody>
  </table>
  	</div>
  </div>
</div>
</div>
</div>