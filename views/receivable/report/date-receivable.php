<?php 
use yii\helpers\Url; 
if(isset($_GET['startcnvrt'])){?>
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
<h3 style='text-align:center'>(<?= date('d-m-Y',strtotime($startcnvrt)) .' To '. date('d-m-Y',strtotime($endcnvrt)) ?>) Receivable</h3>
<?php }else{?>
<a style="margin-top: -10px" href="<?= Url::to(['receivable/receivable-date-report','startcnvrt'=>$startcnvrt,'endcnvrt'=>$endcnvrt]) ?>" class="btn btn-primary btn-sm pull-right"> <i class="fa fa-download"> Download Report</i></a>
<?php } ?>
<table class="table table-striped" align="center">
            <thead>
              <tr class="info">
                <th>SR#</th>
                <th>Receivable Category</th>
                <th>Class</th>
                <th>Name</th>    
                <th>Contact</th>   
                <th>Amount</th> 
                <th>Date</th> 
              </tr>
            </thead>
            <tbody>
              <?php 
              $count=0;
              $sum=0;
              foreach ($dateRecievable as $dateRcv) { 
                $sum=$sum+$dateRcv->amount;
                $count++;
                ?>
                <tr>
                 <td><?= $count; ?></td>
                 <td><?= $dateRcv->receivablecategory->title;?></td>
                 <td><?= $dateRcv->class->title;?></td>
                 <td><?= $dateRcv->name;?></td>
                 <td><?= $dateRcv->contact;?></td>
                 <td><?= $dateRcv->amount;?></td>
                 <td><?php echo date('d M Y',strtotime($dateRcv->created_date));?></td>
               </tr>
               <?php } ?> 
               <tr>
                <td></td>
                <th>Grand Total</th>
                <td></td>
                <td></td>
                <td></td>
                <th> Rs:<?php echo $sum; ?></th>
                <td></td>
              </tr>  
            </tbody>
          </table>