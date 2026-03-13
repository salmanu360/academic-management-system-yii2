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
    <div class="col-md-6">
         <button class="pull-right export-classwise-resultsheet" data-url = "<?php echo Url::to(['reports/class-wise-resultsheet'])?>" title="Result Sheet">
        </button>
        <button style="margin-top: 1px;margin-left: 6px;" id="btn" onclick='printDiv();' class="pull-right btn btn-warning" title="Print position wise">Print Sheet Position Wise <span class="glyphicon glyphicon-print" ></span> </button>

        <button style="margin-top: 1px;" id="btn" onclick='printRollwise();' class="pull-right btn btn-info" title="Print"><span class="glyphicon glyphicon-print" ></span> </button>
    </div>
<div id="sss">
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
            //echo '<pre>';print_r($query);die();
        $student=[];
        //foreach ($passing_marks_array as $passing_marks_arrays){
        foreach ($query as $student_id=>$marks){
            $totalMarks_arr = [];
            $totalMarks = 0;
            $total_marks_obtain= 0;
            $percentage=0;
            echo "<tr>";
            echo "<td style='border: 1px solid #0000008f;'>".$i."</td>";
            foreach ($marks as $key=>$std_mark_obt){   
           // echo $key;
                if($key==='student_roll_no'){
                    echo "<td style='border: 1px solid #0000008f;'>";
                    echo $std_mark_obt;
                    echo "</td>";
                }else if($key==='name'){
                    echo "<td style='border: 1px solid #0000008f;'>";
                    echo Yii::$app->common->getName($std_mark_obt);
                    echo "</td>";
                }else if($key==='student_id'){
                    echo "<td style='border: 1px solid #0000008f;'>";
                    echo $std_mark_obt;
                    echo "</td>";
                }else{
                    $totalMarks_arr[$i][] = $totalMarks+$std_mark_obt;
                    echo "<td style='border: 1px solid #0000008f;'>";
                    echo floatval($std_mark_obt);
                   // if(floatval($std_mark_obt) < $passing_marks_arrays){
                    //echo '<span class="failed">'.floatval($std_mark_obt).'</span>';    
                   // }else{
                    //echo floatval($std_mark_obt);
                   // }
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
           // echo number_format($total_marks_obtain, 2, '.', '');
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
            //echo "<tr>";
            $i++;
            $student=$positions;
        }
        
        ?>
        </tbody>
        </table>
        <!--<div style="height:23px"></div>-->
<button id="btnGo" style="display: none;">GO</button>
    </div>
</div>
</div>
<?php if(!isset($_GET['fk_class_id'])){ ?>
<a class="btn btn-success" href="<?=Url::to(['export-all-dmc','class_id'=>$class_id,'group_id'=>$group_id,'section_id'=>$section_id,'exam_id'=>$examtype->id,'stu'=>$student])?>" title=""><h4 style='color:black'>Export & Print All DMC's</h4></a>
<?php } ?>
    <script type="text/javascript">
        $('#btnGo').on('click', function () {
    // get rows as array and detach them from the table
    var rows = $('.tbl tr:not(:first)').detach();
    // sort rows by the number in the td with class "pts"
    rows.sort(function (row1, row2) {
        return parseInt($(row1).find('td.pts').text()) - parseInt($(row2).find('td.pts').text());
    });
    // add each row back to the table in the sorted order (and update the rank)
    var rank = 1;
    rows.each(function () {
        $(this).find('td:first').text(rank + '.');
        rank++;
        $(this).appendTo('.tbl');
    });
});   
function printDiv() 
{
  $("#btnGo").trigger("click");
  var divToPrint=document.getElementById('sss');
  var newWin=window.open('','Print-Window');
  newWin.document.open();
  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
  newWin.document.close();
  setTimeout(function(){newWin.close();},10);
}

function printRollwise() 
{
  var divToPrint=document.getElementById('sss');
  var newWin=window.open('','Print-Window');
  newWin.document.open();
  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
  newWin.document.close();
  setTimeout(function(){newWin.close();},10);
}
</script>