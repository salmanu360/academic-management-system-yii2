<?php 
use yii\helpers\Url;
 ?>
<?php if(count($dateExpense) > 0){ ?>
<a style="margin-top: -10px" data-url="<?= Url::to(['expenses/date-expense-pdf']) ?>" data-start="<?=$startcnvrt; ?>" data-end="<?=$endcnvrt; ?>" class="btn btn-primary btn-sm pull-right" id="dateExpensePdf">
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
                foreach ($dateExpense as $todayExpense) { 
                  $sum=$sum+$todayExpense->amount;
                  $count++;
                   ?>
                   <tr>
                   <td><?= $count; ?></td>
                    <td><?= $todayExpense->expenseCategory->title;?></td>
                    <td><?= $todayExpense->title;?></td>
                    <td><?= $todayExpense->paymentMehtod->title;?></td>
                    <td><?= date('d M Y',strtotime($todayExpense->date))?></td>
                    <td><?= $todayExpense->amount;?></td>
                  </tr>
                  <?php } ?> 
                  <tr>
                  <td></td>
                  <th>Grand Total</th>
                  <td></td>
                  <td></td>
                  <td></td>
                    <th>Rs:<?php echo $sum; ?></th>
                  </tr>  
                </tbody>
              </table>
              <?php }else{ ?>
              <div class='row'><div class='col-md-1 col-sm-1'></div><div class='Alert alert-danger col-sm-3'><strong>No Expenses Found!</strong></div> </div>
              <?php } ?>