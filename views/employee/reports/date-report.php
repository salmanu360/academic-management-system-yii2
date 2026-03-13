<section class="invoice">
<?php use yii\helpers\Url; 
if(isset($_GET['date'])){?>
<style type="text/css">
  *{ margin-left:0; padding:0;}
  th, tr, td  {
    border:1px solid black;
    padding:10px;
    font-size:1em;
  }

  tr:nth-child(even){background-color: #f2f2f2}
</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
</div>
<h3 style='text-align:center'>Employee Attendance report of (<?= date('M-Y',strtotime($start)) ?>)</h3>
<?php }else{?>

<div class="row">
	<div class="col-md-12">
		<a href="<?=Url::to(['employee/date-attendance','date'=>$start]) ?>" name="Generate Report" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Generate Report</a>
	</div>
</div>
<?php } ?>
<div class="row" style="    overflow-x: auto;">
  <div class="col-md-12">
    <?php
    $date = $start;
    $end = $start.'-' . date('t', strtotime($date)); //get end date of month
    ?>
    <table class="table table-bordered">
            <thead>
            <?php  
            $list=[];
            $month = date('m',strtotime($start));
            $year =  date('Y',strtotime($start));

            for($d=1; $d<=31; $d++)
            {
                $time=mktime(12, 0, 0, $month, $d, $year);          
                if (date('m', $time)==$month)       
                    $list[]=date('Y-m-d', $time);
            }
            ?>
            
            <tr>
              <td>Name</td>
            <?php
            $fullDate=[];
            foreach ($list as $key => $getDate) {
              ?>

             <td colspan="" rowspan="" headers=""><?php 
             echo date('d',strtotime($getDate));
             $fullDate[$key]=$getDate;
             ?></td>

            <?php  }?>
            </tr>
                     
                
                      </thead>
                      <tbody>
                         <?php
                         //echo '<pre>';print_r($fullDate);
                         foreach ($empQuery as $key => $employeeName) {
                       ?> <tr>
                          <td><?php echo $employeeName['name'] ?></td>
                         <?php foreach ($list as $key => $getDate) {?>
                        
                         <td><?php 
                         $attendance=\app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$employeeName['emp_id'],'date'=>$getDate])->one();?>
                         <?php if($attendance['leave_type'] == 'leave'){
                          echo '<span class="label label-warning">L</span>';
                         }else if($attendance['leave_type'] == 'late'){
                          echo '<span class="label label-info">Lt</span>';
                         }
                         else if($attendance['leave_type'] == 'absent'){
                          echo '<span class="label label-danger">A</span>';
                         }else if($attendance['leave_type'] == 'present'){
                          echo '<span class="label label-success">P</span>';
                         }else if($attendance['leave_type'] == 'shortleave'){
                          echo '<span class="label label-danger">SL</span>';
                         }
                        // echo $fullDate[$key]=$getDate;
                         ?></td>

                        <?php  }?>
                        </tr>
                      <?php }   ?>
                      </tbody>
                  </table>
                  </div>
                </div>
            </section>