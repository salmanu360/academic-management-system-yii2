<?php use yii\helpers\Url; ?>
 <style type="text/css">#pasroutetostop:hover{color:blue;}</style>
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
<h3 style='text-align:center'>Route Wise Transport Report</h3>                
                  <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>SR</th>
                            <th>Route</th>
                            <th>Total Students</th>
                            

                            
                          </tr>
                        </thead>
                            <tbody>
                            <?php 
                            $count=0;
                            foreach ($route as $queryy) {
                              $count++;
                              ?>
                            <tr>
                            <td><?= $count; ?></td>
                                <td><?= $queryy['route_name']?>
                                </td>
                                <td><?= $queryy['no_of_students_availed_transport'];?></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                     </table>