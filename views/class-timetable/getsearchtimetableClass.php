<?php
use yii\helpers\Url;
 $this->registerCssFile(Yii::getAlias('@web').'/css/timepicker/bootstrap-datetimepicker.min.css',['depends' => [yii\web\JqueryAsset::className()]]); ?>
            <?php $this->registerJsFile(Yii::getAlias('@web').'/js/timepicker/bootstrap-datetimepicker.min.js',['depends' => [yii\web\JqueryAsset::className()]]); ?>
   <?php //echo '<pre>';print_r($subjectsdetails);die; ?>
    <div class="box box-warning">
    <div class="box-header with-border" style="background: white;color: black;">
        <h3 class="box-title"><i class="fa fa-users"></i> Class Timetable</h3>
        <input type="submit" data-classid="<?= $classid; ?>" data-groupid="<?= $groupid; ?>" name="Generate Report" id="ClassTimetableClasswisePdf" class="btn btn-primary pull-right" value="Generate Report" data-url=<?php echo Url::to(['class-timetable/generate-timetable-class-pdf']) ?> />
    </div>
    <div class="box-body">
    <div class="row">
     <?php if (Yii::$app->session->hasFlash('')): ?>
          <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              
              <?= Yii::$app->session->getFlash('success') ?>
          </div>
            <?php endif; ?>
        <div class="col-md-12">
    <table class="table table-striped table-hover classwise">
    <thead>
        <tr class="info">
            <th>Day </th>
            <th>Subject </th>
            <th>Start Time</th>
            <th>End Time </th>
        </tr>
    </thead>
    <tbody>
    <?php 
    //echo '<pre>';print_r($subjectsdetails);die;
     foreach($subjectsdetails as $timetable): ?>       
    <tr>
    <td style="width: 400px;">
    	<input type="text" id="classtimetable-day" class="form-control classtimetable-day" name="ClassTimetable[day][]" value="<?= $timetable->day ?>" aria-required="true" aria-invalid="false" readonly>
    </td>

    <td>
        <input type="text" id="classtimetable-day" class="form-control classtimetable-day" name="ClassTimetable[subject_id][]" value="<?= $timetable->subject->title ?>" aria-required="true" aria-invalid="false" readonly>
    </td>
    <td>
    	
    <div class="input-group bootstrap-timepicker timepicker">
    <input type="text" id="starttime" class="form-control input-small timepicker1 starttime" name="ClassTimetable[start_date][]" value="<?= $timetable->start_date ?>" readonly>
    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
    </div>
    </td>
    <td>
    <div class="input-group bootstrap-timepicker timepicker">
    <input type="text" id="endtime" class="form-control input-small timepicker1 endtime" name="ClassTimetable[end_date][]" value="<?= $timetable->end_date ?>" readonly>
    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
    </div>
    </td>	
    </tr>    
        <?php endforeach; ?>
        </tbody>
        </table>
       
        
        </div>
        
        </div>
        </div>
