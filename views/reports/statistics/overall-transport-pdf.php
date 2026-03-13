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
<h3 style='text-align:center'>Students Avail Transport</h3>
 <p>
    <?php  $transportStudents=yii::$app->db->createCommand("select si.stu_id,concat (u.first_name,' ',u.middle_name,' ',u.last_name) as `student_name`,z.title as `zone_name`,r.title as `route_name`, s.title as `stop_name`,s.fare as `fare`,st.class_id as `class`,st.stu_id as `student_id`,rc.title as `class_name`,u.username as `username` from transport_allocation si
                    inner join user u on u.id=si.stu_id
                    inner join student_info st on st.user_id=si.stu_id
                    inner join ref_class rc on rc.class_id=st.class_id
                    inner join stop s on s.id=si.fk_stop_id
                    inner join route r on r.id=s.fk_route_id
                    inner join zone z on z.id=r.fk_zone_id where st.is_active=1 and si.branch_id='".yii::$app->common->getBranch()."' ORDER BY u.username ASC
                    ")->queryAll();  ?>
    </p>
    <table class="table table-striped" style="margin-top:-20px">
                      <thead>
                      <tr>
                      <th>SR.</th>
                      <th>Reg No.</th>
                      <th>Student</th>
                      <th>Parent</th>
                      <th>Class</th>
                      <th>Zone</th>
                      <th>Route </th>
                      <th>Stop</th>
                      <th>Fare</th>
                      </tr>
                      </thead>
                      <tbody>
                      <?php 
                      $countStu=0;
                      $totalTransport=0;
                      foreach ($transportStudents as $transports) {
                        $countStu++;
                        $totalTransport=$totalTransport+$transports['fare'];
                       ?>
                      <tr>
                      <td><?= $countStu; ?></td>
                      <td><?= $transports['username'] ?></td>
                      <td><?= Yii::$app->common->getName($transports['stu_id']); ?></td>
                     <td> <?= Yii::$app->common->getParentName($transports['student_id']); ?></td>
                      <td> <?= $transports['class_name'] ?></td>
                      <td><?= $transports['zone_name'] ?></td>
                      <td><?= $transports['route_name'] ?></td>
                      <td><?= $transports['stop_name'] ?></td>
                      <td>Rs. <?= $transports['fare'] ?></td>
                      </tr>
                      <?php } ?>
                       <tr>
                        <td colspan="3"></td>
                        <th colspan="3">Total</th>
                        <th colspan="3">Rs. <?php echo $totalTransport ?></th>
                      </tr>
                      </tbody>
                      </table>