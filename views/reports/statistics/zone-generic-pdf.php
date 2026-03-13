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

<h3 style='text-align:center'>Zone Wise Transport Report</h3>
<table width="100%">
                        <thead>
                          <tr>
                            <th>SR</th>
                            <th>Zone</th>
                            <th>Total Students</th>
                            

                            
                          </tr>
                        </thead>
                            <tbody>
                            <?php 
                            $count=0;
                            foreach ($zone as $queryy) {
                              $count++;
                              ?>
                            <tr>
                            <td><?= $count; ?></td>
                                <td>
                                <?php echo $queryy['zone_name']?>
                                </td>
                                <td><?php echo $queryy['no_of_students_availed_transport'];?></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                     </table>