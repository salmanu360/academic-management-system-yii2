  <style>
 table{


/* width:950px;
margin-left:19%;
border-collapse: separate;
border-spacing: 5px; */

}

th, tr, td  {
border:1px solid #469DC8;
padding:10px;
font-size:1.5em;
}

tr:nth-child(even){background-color: #f2f2f2}
</style>
<?= \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

             [
            'label'=>'Full Name',
            'value'=>function($data){
             return Yii::$app->common->getName($data->user_id);
            }

            ],
            [
                'label'=>'Present',
                'value' =>function($data){
                   $query = \app\models\AttendanceMain::find()->where(['fk_user_id'=>$data->user_id])->all();
                   $query = \app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$data->emp_id,'leave_type'=>'present']);

                    //print_r($query);
                    
                   
                    /*if type is year mont that follwoing query will be executed*/
                    //if($attendance_type == 'year-month'){
                    //    $query->andWhere(['=','DATE_FORMAT(date, "%Y-%m")',$year_month]);
                   // };

                    $presentCount = $query->count();
                    return $presentCount;
                }
            ],
            [
                'label'=>'Leave',
                'value' =>function($data){
                    $query = \app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$data->emp_id,'leave_type'=>'leave']);

                    /*if type is year mont that follwoing query will be executed*/
                    //if($attendance_type == 'year-month'){
                    //    $query->andWhere(['=','DATE_FORMAT(date, "%Y-%m")',$year_month]);
                   // };

                    $presentCount = $query->count();
                    return $presentCount;
                }
            ],
            [
                'label'=>'late Commer',
                'value' =>function($data){
                    $query = \app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$data->emp_id,'leave_type'=>'latecommer']);

                    /*if type is year mont that follwoing query will be executed*/
                    //if($attendance_type == 'year-month'){
                    //    $query->andWhere(['=','DATE_FORMAT(date, "%Y-%m")',$year_month]);
                   // };

                    $presentCount = $query->count();
                    return $presentCount;
                }
            ],
            [
                'label'=>'Absent',
                'value' =>function($data){
                    $query = \app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$data->emp_id,'leave_type'=>'absent']);

                    /*if type is year mont that follwoing query will be executed*/
                    //if($attendance_type == 'year-month'){
                    //    $query->andWhere(['=','DATE_FORMAT(date, "%Y-%m")',$year_month]);
                   // };

                    $presentCount = $query->count();
                    return $presentCount;
                }
            ],
            [
                'label'=>'Total Present %',
                'value' =>function($data){
                    $query = \app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$data->emp_id,'leave_type'=>'present']);
                     if($query->count()> 0){
                     $query1 = \app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$data->emp_id,'leave_type'=>'present'])->one();
                     $datemonth=date('m',strtotime($query1->date));
                     $number = cal_days_in_month(CAL_GREGORIAN, $datemonth, 2018);
                     $presnt= $query->count()/$number*100 .'%';
                     return number_format((float)$presnt, 2);
                     }else{
                        return '0';
                     }
                }
            ],
             [
                'label'=>'Total Leave %',
                'value' =>function($data){
                    $query = \app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$data->emp_id,'leave_type'=>'leave']);
                     if($query->count()> 0){
                     $query1 = \app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$data->emp_id,'leave_type'=>'leave'])->one();
                     $datemonth=date('m',strtotime($query1->date));
                     $number = cal_days_in_month(CAL_GREGORIAN, $datemonth, 2018);
                     $lev= $query->count()/$number*100;
                     return number_format((float)$lev, 2);
                     }else{
                        return '0';
                     }
                }
            ],
             [
                'label'=>'Total Short Leave %',
                'value' =>function($data){
                    $query = \app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$data->emp_id,'leave_type'=>'shortleave']);

                    if($query->count()> 0){
                     $query1 = \app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$data->emp_id,'leave_type'=>'shortleave'])->one();
                     $datemonth=date('m',strtotime($query1->date));
                     $number = cal_days_in_month(CAL_GREGORIAN, $datemonth, 2018);
                     $shrtl= $query->count()/$number*100;
                     return number_format((float)$shrtl, 2);
                     }else{
                        return '0';
                     }
                }
            ],
            [
                'label'=>'Total Absent %',
                'value' =>function($data){
                    $query = \app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$data->emp_id,'leave_type'=>'absent']);
                
                     if($query->count()> 0){
                     $query1 = \app\models\EmployeeAttendance::find()->where(['fk_empl_id'=>$data->emp_id,'leave_type'=>'absent'])->one();
                     $datemonth=date('m',strtotime($query1->date));
                     $number = cal_days_in_month(CAL_GREGORIAN, $datemonth, 2018);
                    $absnt= $query->count()/$number*100 .' %';
                     return number_format((float)$absnt, 2);
                      
                     }else{
                        return '0';
                     }
                    
                    
                }
            ],
        ],
    ]); ?>