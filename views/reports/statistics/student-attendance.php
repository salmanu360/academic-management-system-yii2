  <style type="text/css">
    table.tr{
      background: red;
    }
  </style>
  <table class="table table-striped">
    <thead>
      <tr class="primary">
        <th>Date</th>
        <th>leave type</th>
        <th>Remarks</th>    
      </tr>
    </thead>
    <tbody>
      <?php 
      //echo '<pre>';print_r($attndnce);die;
      foreach ($attndnce as $att) {
      	/*if($att->leave_type == 'present'){
      	}else{*/
         ?>
         <tr>
          <td><?php echo date("d-m-y",strtotime($att->date))?></td>
          <td><?php echo $att->leave_type?></td>
          <td><?php echo $att->remarks?></td>   
        </tr>
        <?php } 
       // }
        ?>   
      </tbody>
    </table>