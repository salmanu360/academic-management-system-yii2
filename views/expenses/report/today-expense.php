<?php use yii\helpers\Url;
$this->title='Today Expenses';?>
<div class="panel panel-body">
<div class="modal-header alert alert-info">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h5 style='text-align:center' class="modal-title"> Expenses of <?= date('d M Y') ?></h5>
</div>
  <table class="table table-striped table-responsive">
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
<div class="modal-footer">
 <a href="<?php echo Url::to(['/site']) ?>" class="btn btn-danger"> back</a>
</div>
</div>