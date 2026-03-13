 <style type="text/css">
  *{ margin:0; padding:0;}
  th, tr, td  {
    border:1px solid black;
    padding:7px;
    font-size:0.8em;
  }
</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
</div>

<h3 style='text-align:center'>Expenses of the month(<?= date('M-Y') ?>)</h3>
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