<?php 
use yii\helpers\Url;
use kartik\date\DatePicker;
$this->title = 'Expense Reports';
 ?>
<div class="row">
        <div class="col-md-11">
        <a class="btn btn-block btn-social btn-facebook">
                <i class="fa fa-bar-chart-o"></i> Expense Reports
              </a>
        </div>
        </div>
<br>
<div class="row">
        <div class="col-md-11">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Today Expenses</a></li>
              <li><a href="#tab_2" data-toggle="tab">Current Month Expenses</a></li>
              <li><a href="#tab_3" data-toggle="tab">Date Expenses</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
              <?php if(count($todayExpense) > 0){ ?>
              <a style="margin-top: -10px" href="<?= Url::to(['expenses/today-expense-pdf']) ?>" class="btn btn-primary btn-sm pull-right">
              <i class="fa fa-download"> Download Report</i></a>
              <table class="table table-striped">
              <thead>
                <tr class="info">
                  <th>SR#</th>
                  <th>Category</th>
                  <th>Title</th>
                  <th>Payment Method</th>    
                  <th>Date</th>   
                  <th>Amount</th> 
                </tr>
              </thead>
              <tbody>
                <?php 
                $count=0;
                $sum=0;
                foreach ($todayExpense as $todayExpense) { 
                  $sum=$sum+$todayExpense->amount;
                  $count++;
                   ?>
                   <tr>
                   <td><?= $count; ?></td>
                    <td><?= $todayExpense->expenseCategory->title;?></td>
                    <td><?= $todayExpense->title;?></td>
                    <td><?= $todayExpense->paymentMehtod->title;?></td>
                    <td><?= date('d M Y',strtotime($todayExpense->date));?></td>
                    <td><?= $todayExpense->amount;?></td>
                  </tr>
                  <?php } ?> 
                  <tr>
                  <td></td>
                  <th>Grand Total</th>
                  <td></td>
                  <td></td>
                  <td></td>
                    <th> Rs:<?php echo $sum; ?></th>
                  </tr>  
                </tbody>
              </table>
              <?php }else{ ?>
                <div class='row'><div class='col-md-1 col-sm-1'></div><div class='Alert alert-danger col-sm-3'><strong>No Expenses Found Today!</strong></div> </div>
              <?php } ?>
              </div>
  
              <!-- start of tab2 and end of tab 1 -->
              <div class="tab-pane" id="tab_2">
              <?php if(count($currentMonth) > 0){ ?>
              <a style="margin-top: -10px" href="<?= Url::to(['expenses/month-expense-pdf']) ?>" class="btn btn-primary btn-sm pull-right">
              <i class="fa fa-download"> Download Report</i></a>
              <table class="table table-striped">
              <thead>
                <tr class="info">
                  <th>SR#</th>
                  <th>Category</th>
                  <th>Title</th>
                  <th>Payment Method</th>    
                  <th>Date</th>   
                  <th>Amount</th> 
                </tr>
              </thead>
              <tbody>
                <?php 
                $count=0;
                $sums=0;
                foreach ($currentMonth as $currentMonth) { 
                  $sums=$sums+$currentMonth->amount;
                  $count++;
                   ?>
                   <tr>
                   <td><?= $count; ?></td>
                    <td><?= $currentMonth->expenseCategory->title;?></td>
                    <td><?= $currentMonth->title;?></td>
                    <td><?= $currentMonth->paymentMehtod->title;?></td>
                    <td><?= date('d M Y',strtotime($currentMonth->date));?></td>
                    <td><?= $currentMonth->amount;?></td>
                  </tr>
                  <?php } ?> 
                  <tr>
                  <td></td>
                  <th>Grand Total</th>
                  <td></td>
                  <td></td>
                  <td></td>
                    <th>Rs:<?php echo $sums; ?></td>
                  </tr>  
                </tbody>
              </table>
              <?php }else{ ?>
                <div class='row'><div class='col-md-1 col-sm-1'></div><div class='Alert alert-danger col-sm-3'><strong>No Expenses Found This Month!</strong></div> </div>
              <?php } ?>
              </div>
              <!-- start of tab3 and end of tab 2 -->
              <div class="tab-pane" id="tab_3">
             <div class="row">
               <div class="col-md-3">
                 <?php 
                    echo '<label>Start Date:</label>';
                    echo DatePicker::widget([
                    'name' => 'startdate', 
                    'value' => date('01-m-Y'),
                    'options' => ['placeholder' => ' ','id'=>'startdateExpense'],
                    'pluginOptions' => [
                        'format' => 'dd-m-yyyy',
                        'todayHighlight' => true,
                        'autoclose'=>true,
                    ]
                  ]);?>
               </div>
               <div class="col-md-3">
                 <?php echo '<label>End Date:</label>';
                        echo DatePicker::widget([
                        'name' => 'enddate', 
                        'value' => date('d-m-Y'),
                        'options' => ['placeholder' => ' ','id'=>'enddateExpense'],
                        'pluginOptions' => [
                            'format' => 'dd-m-yyyy',
                            'todayHighlight' => true,
                            'autoclose'=>true,
                        ]
                      ]); ?>
               </div>
               <div class="col-md-1">
               <div style="height: 23px"></div>
                 <input type="submit" id="dateExpenseReport" name="submits" class="btn btn-primary" data-url=<?php echo Url::to(['expenses/expense-date-report']) ?>  />
               </div>
             </div>
             <br />
             <div class="row">
             <div class="col-md-12" id="showDateExpense"></div>
               
             </div>
              </div>
               </div>
              </div>
              </div>
              </div>
              