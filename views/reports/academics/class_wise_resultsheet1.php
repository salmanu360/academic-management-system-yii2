<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<style>
    tbody:before {
    content: "-";
    display: block;
    line-height: 1em;
    color: transparent;
}
 *{ margin:0; padding:0;}
  th, tr, td  {
    /*border:0.3px solid black;*/
    padding:5px;
    font-size:1em;
  }

 /* tr:nth-child(even){background-color: #f2f2f2}*/
  #first{ min-width: 15px; width: 15px; }

.failed{
    color: red;
    border:1px red solid;
    font-weight: bold;
    text-decoration: underline;
}
table, th, td {
   border: 1px solid black;
}
thead.report-header {
   display: table-header-group;
}
</style>
<div class="col-md-12 print-padding-bottom">
    <div class="col-md-6">&nbsp;<br/></div>
   
<div class="text-center" style="text-align: center;">
    <strong class="text-center" id="header"><?=Yii::$app->common->getCGSName($class_id,$group_id,$section_id).' - '.ucfirst($examtype->type)?> (Passing Marks: <?php echo $examtype->passing_percentage ?> %)</strong>
</div>
<div class="col-md-12">
    <div id="class-wise-container" class="table-responsive kv-grid-container">
        <table class="kv-grid-table table table-bordered table-striped kv-table-wrap tbl">
            <thead class="report-header">
        <tr>
        <th style="border: 1px solid #0000008f">#</th>
        <th valign="middle" style="border: 1px solid #0000008f">Reg. No.</th>
        <th valign="middle" style="border: 1px solid #0000008f">Roll No.</th>
        <th valign="middle" style="border: 1px solid #0000008f">Name</th>
            <?php
            $max_marks= 0;
            //echo $heads_marks;die;
            foreach ($heads_marks['heads'] as $key=>$sub){
                echo "<th style='border: 1px solid #0000008f'>".ucfirst($sub)."<br/>(".$heads_marks['total_marks'][$key].")</th>";

                 $max_marks= $max_marks+$heads_marks['total_marks'][$key];
            }
            ?>
            <th valign="middle" style="border: 1px solid #0000008f">Marks</th>
            <th valign="middle" style="border: 1px solid #0000008f">Percentage</th>
            <th valign="middle" style="border: 1px solid #0000008f">Position</th>
        </tr>
        </thead>
            <tbody style="    border: 1px solid #0000008f ;">
        <?php
        $i=1;
        $student=[];
        foreach ($query as $student_id=>$marks){
            $totalMarks_arr = [];
            $totalMarks = 0;
            $total_marks_obtain= 0;
            $percentage=0;
            echo "<tr>";
            echo "<td style='border: 1px solid #0000008f;'>".$i."</td>";
            foreach ($marks as $key=>$std_mark_obt){ 
            echo '<br>';
                if($key==='student_roll_no'){
                    echo "<td style='border: 1px solid #0000008f;'>";
                    echo $std_mark_obt;
                    echo "</td>";
                }else if($key==='name'){
                    echo "<td style='border: 1px solid #0000008f;'>";
                    
                  $d= Yii::$app->common->getOneStudentDetails($std_mark_obt);
                    echo $contact=$d['parentcontact'];
                    echo $studentname=Yii::$app->common->getName($std_mark_obt);
                    $send=Yii::$app->common->SendSmsSimple($contact,$studentname);
                    
                    echo "</td>";
                }else if($key==='student_id'){

                    echo "<td style='border: 1px solid #0000008f;'>";

                    echo $std_mark_obt;
                    echo "</td>";
                }else{
                    $totalMarks_arr[$i][] = $totalMarks+$std_mark_obt;
                    echo "<td style='border: 1px solid #0000008f;'>";
                    echo floatval($std_mark_obt);
                    echo "</td>";
                }
            }
            $total_marks_obtain = array_sum($totalMarks_arr[$i]);
            if($max_marks>0){
                $percentage= $total_marks_obtain*100/$max_marks;
            }else{
                $percentage = 0;
            }
            echo "<td style='border: 1px solid #0000008f;'>";
           echo floatval($total_marks_obtain).'/'. $max_marks;
            echo "</td>";
            echo "<td style='border: 1px solid #0000008f;'>";
            echo round($percentage,1)."%";
            echo "</td>";
            if(isset($positions)){
                if(Yii::$app->common->multidimensional_search($positions, ['student_id'=>$student_id]) == 1){
                    echo '<td class="pts" width="20" style="color:#028c4d;border: 1px solid #0000008f" >'.Yii::$app->common->multidimensional_search($positions, ['student_id'=>$student_id]).'</td>';
                }else if(Yii::$app->common->multidimensional_search($positions, ['student_id'=>$student_id]) == 2){
                    echo '<td class="pts" style="color:#bb48a8;border: 1px solid #0000008f" width="20">'.Yii::$app->common->multidimensional_search($positions, ['student_id'=>$student_id]).'</td>';
                }else if(Yii::$app->common->multidimensional_search($positions, ['student_id'=>$student_id]) == 3){
                    echo '<td class="pts" style="color:#3f51b5;border: 1px solid #0000008f" width="20">'.Yii::$app->common->multidimensional_search($positions, ['student_id'=>$student_id]).'</td>';
                }else if(Yii::$app->common->multidimensional_search($positions, ['student_id'=>$student_id]) == 4){
                    echo '<td class="pts" style="color:#8D38C9;border: 1px solid #0000008f" width="20">'.Yii::$app->common->multidimensional_search($positions, ['student_id'=>$student_id]).'</td>';
                }else if(Yii::$app->common->multidimensional_search($positions, ['student_id'=>$student_id]) == 5){
                    echo '<td class="pts" style="color:#E238EC;border: 1px solid #0000008f" width="20">'.Yii::$app->common->multidimensional_search($positions, ['student_id'=>$student_id]).'</td>';
                }else if(Yii::$app->common->multidimensional_search($positions, ['student_id'=>$student_id]) == 6){
                    echo '<td class="pts" style="color:#87AFC7;border: 1px solid #0000008f" width="20">'.Yii::$app->common->multidimensional_search($positions, ['student_id'=>$student_id]).'</td>';
                }else if(Yii::$app->common->multidimensional_search($positions, ['student_id'=>$student_id]) == 7){
                    echo '<td class="pts" style="color:#5E7D7E;border: 1px solid #0000008f" width="20">'.Yii::$app->common->multidimensional_search($positions, ['student_id'=>$student_id]).'</td>';
                }else if(Yii::$app->common->multidimensional_search($positions, ['student_id'=>$student_id]) == 8){
                    echo '<td class="pts" style="color:#728C00;border: 1px solid #0000008f" width="20">'.Yii::$app->common->multidimensional_search($positions, ['student_id'=>$student_id]).'</td>';
                }else if(Yii::$app->common->multidimensional_search($positions, ['student_id'=>$student_id]) == 9){
                    echo '<td class="pts" style="color:#9CB071;border: 1px solid #0000008f" width="20">'.Yii::$app->common->multidimensional_search($positions, ['student_id'=>$student_id]).'</td>';
                }else if(Yii::$app->common->multidimensional_search($positions, ['student_id'=>$student_id]) == 10){
                    echo '<td class="pts" style="color:#C68E17;border: 1px solid #0000008f" width="20">'.Yii::$app->common->multidimensional_search($positions, ['student_id'=>$student_id]).'</td>';
                }else{

                echo "<td width='20' class='pts' style='border: 1px solid #0000008f;'>".Yii::$app->common->multidimensional_search($positions, ['student_id'=>$student_id])."</td>";
                }
            }else{
                echo "<td>N/A</td>";
            }
            $i++;
            $student=$positions;
        }
        ?>
        </tbody>
        </table>
    </div>
</div>

