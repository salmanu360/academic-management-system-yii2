        <style type="text/css">
        *{ margin:0; padding:0;}
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
      <h3 style='text-align:center'>Today Receivable</h3>
      <table class="table table-striped">
        <thead>
          <tr class="info">
            <th>SR#</th>
            <th>Receivable Category</th>
            <th>Class</th>
            <th>Name</th>    
            <th>Contact</th>   
            <th>Amount</th> 
          </tr>
        </thead>
        <tbody>
          <?php 
          $count=0;
          $sum=0;
          foreach ($todayRecievalble as $todayRecievalble) { 
            $sum=$sum+$todayRecievalble->amount;
            $count++;
            ?>
            <tr>
             <td><?= $count; ?></td>
             <td><?= $todayRecievalble->receivablecategory->title;?></td>
             <td><?= $todayRecievalble->class->title;?></td>
             <td><?= $todayRecievalble->name;?></td>
             <td><?= $todayRecievalble->contact;?></td>
             <td><?= $todayRecievalble->amount;?></td>
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