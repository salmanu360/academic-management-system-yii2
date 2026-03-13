<?php


if(count($subjects_data)>0) {
    ?>
    <div class="col-sm-9">
        <div class="info_st">
        <table class="table">
      
            <?php
            $i=1;
            $total_marks= 0;
            $total_marks= 0;
            $total_obtain = 0;
            foreach ($subjects_data as $key => $subject_data) {
                if($i % 3 == 1){
                    echo "<tr>";
                }
                ?>
                    <th><span><?=$subject_data['subject']?></span>
                    <?=$subject_data['marks_obtained']?></th>
                <?php
                if($i % 3 == 0 ){
                    echo "</tr>";
                }
                $i++;
                $total_marks = $total_marks+$subject_data['total_marks'];
                $total_obtain = $total_obtain+$subject_data['marks_obtained'];
            }

                ?>
        </table>
        </div>
        <div class="info_st">
        <table class="table">
            <tr>
                <th class="col-sm-4"><span>Total Marks</span>
                    <p class="obtain_m"><?=$total_marks?></p>
                </th>
                <th class="col-sm-4"><span>Marks Obtained</span>
                    <p class="obtain_m"><?=$total_obtain?></p>
                </th>
            </tr>
            </table>
        </div>
    </div>
    <?php
}else{
    echo "<span style='color:red'>No Record Found</span>";
}
?>

