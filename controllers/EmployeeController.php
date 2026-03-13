<?php
namespace app\controllers;
ini_set('max_execution_time', 300);
use Yii;
use app\models\User;
use app\models\EmployeeInfo;
use app\models\search\EmployeeInfoSearch;
use app\models\search\EmployeeAttendanceSearch;
use app\models\EmplEducationalHistoryInfo;
use app\models\EmployeeAttendance;
use app\models\Checkinout;
use app\models\SalaryMain;
use app\models\EmployeePayroll;
use app\models\RefProvince;
use app\models\RefDistrict;
use app\models\SalaryAllownces;
use app\models\RefCities;
use app\models\EmployeeSalarySelection;
use app\models\EmployeeAllowances;
use app\models\EmployeeDeductions;
use app\models\SalaryPayStages;
use app\models\EmployeeSalaryDeductionDetail;
use app\models\RefGroup;
use app\models\SalaryDeductionType;
use app\models\SalaryPayGroups;
use app\models\RefDesignation;
use app\models\RefSection;
use app\models\EmployeeParentsInfo;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;
use mPDF;
use arogachev\yii2Excel;
use app\models\search\EmplEducationalHistoryInfoSearch;

class EmployeeController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    //===== employee attendance Report===// 
    public function actionEmplAttndReport(){
        $searchModel = new EmployeeInfoSearch();
        $searchModel->fk_branch_id = Yii::$app->common->getBranch();
        $searchModel->is_active=1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $empQuery = User::find()
    ->select(['employee_info.emp_id',"concat(user.first_name, ' ' ,  user.last_name) as name"])
    ->innerJoin('employee_info','employee_info.user_id = user.id')
    ->where(['employee_info.fk_branch_id'=>Yii::$app->common->getBranch()])->asArray()->all();
        return $this->render('employe-attendance-report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'empQuery' => $empQuery
        ]);
    }
    public function actionEmplAttndReportPdf(){
        $searchModel = new EmployeeInfoSearch();
        $searchModel->fk_branch_id = Yii::$app->common->getBranch();
        $searchModel->is_active=1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $todayEmplAtt= $this->renderAjax('reports/employe-attendance-report-today-pdf', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($todayEmplAtt);
        $mpdf->Output('today-employee-attendance-'.date("d-m-Y").'.pdf', 'D');
    }
    public function actionMonthlyAttendanceReportPdf(){
        $monthlyEmplAtt= $this->renderAjax('reports/attendance-report-monthly-pdf');
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($monthlyEmplAtt);
        $mpdf->Output('monthly-employee-attendance-'.date("d-m-Y").'.pdf', 'D');
    }
    public function actionEmployeeAttendanceReportPdf(){
        $searchModel = new EmployeeInfoSearch();
        $searchModel->fk_branch_id = Yii::$app->common->getBranch();
        $searchModel->is_active=1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $emplattendpdf= $this->renderAjax('reports/empl-attend-report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        //$stylesheet = file_get_contents('css/std-ledger-pdf.css');
       // $mpdf->WriteHTML($stylesheet,1);
        $mpdf->WriteHTML("<h3 style='text-align:center'>Staff Over All Attendance Report</h3>");
        $mpdf->WriteHTML($emplattendpdf);
        $mpdf->Output('Staff-Attendance-Report-'.date("d-m-Y").'.pdf', 'D'); 
    }


    
    //===== end of employee attendance Report===//

   ///pdf
    public function actionTest(){
        return $this->render('test');
    }
    
    public function actionChart(){
        return $this->render('chart');
    }

    public function actionGetDesignation(){
     $id=yii::$app->request->post('id');
      $destination=RefDesignation::find()->where(['fk_department_id'=>$id,'fk_branch_id'=>yii::$app->common->getBranch()])->All();
      //echo '<pre>';print_r($destination);
      $option="<option>Select Designation</option>";
      foreach ($destination as $des) {
       $option.= "<option value='".$des->designation_id."'>".$des->Title."</option>";
      }
      return $option;
    }

    public function actionCreateMpdf($id){
        $this->layout = 'pdf';
         $mpdf=new mPDF();
         $model2= EmplEducationalHistoryInfo::findOne(['emp_id'=>$id]); 
         $mpdf->WriteHTML($this->render('generatePdf', [
            'model' => $this->findModel($id),
            'model2'=>$model2
          ]));

         $mpdf->Output();



        // $mpdf=new mPDF();
        // $mpdf->WriteHTML($this->renderPartial('mpdf'));
        // $mpdf->Output();
        // //$mpdf->Output('MyPDF.pdf', 'D'); //for force downloading
        // exit;
        //return $this->renderPartial('mpdf');
    }

    public function actionMpdfDemo1() {
    $pdf = new pdf([
        'mode' => pdf::MODE_CORE, // leaner size using standard fonts
        'content' => $this->renderPartial('mpdf'),
        'options' => [
            'title' => 'Privacy Policy - Krajee.com',
            'subject' => 'Generating PDF files via yii2-mpdf extension has never been easy'
        ],
        'methods' => [
            'SetHeader' => ['Generated By: Krajee Pdf Component||Generated On: ' . date("r")],
            'SetFooter' => ['|Page {PAGENO}|'],
        ]
    ]);
            return $pdf->render();
        }

    /////////////excel

    public function actionExport() {
        $searchModel = new EmployeeInfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        ExcelView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'fullExportType'=> 'xlsx', //can change to html,xls,csv and so on
            'grid_mode' => 'export',
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'code',
                'name',
                'population',
              ],
        ]);
    }


    ///////////end of excel

public function actionReport() {
    // get your HTML raw content without any layouts or scripts
    $content = $this->renderPartial('mpdf');
 
    // setup kartik\mpdf\Pdf component
    $pdf = new Pdf([
        // set to use core fonts only
        'mode' => Pdf::MODE_CORE, 
        // A4 paper format
        'format' => Pdf::FORMAT_A4, 
        // portrait orientation
        'orientation' => Pdf::ORIENT_PORTRAIT, 
        // stream to browser inline
        'destination' => Pdf::DEST_BROWSER, 
        // your html content input
        'content' => $content,  
        // format content from your own css file if needed or use the
        // enhanced bootstrap css built by Krajee for mPDF formatting 
        'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
        // any css to be embedded if required
        'cssInline' => '.kv-heading-1{font-size:18px}', 
         // set mPDF properties on the fly
        'options' => ['title' => 'Krajee Report Title'],
         // call mPDF methods on the fly
        'methods' => [ 
            'SetHeader'=>['Krajee Report Header'], 
            'SetFooter'=>['{PAGENO}'],
        ]
    ]);
 
    // return the pdf output as per the destination setting
    return $pdf->render(); 
}


    ///end of pdf

    public function actionEmpCalendar(){
        $attendanceModel = new EmployeeAttendance();
        return $this->render('/employee/calendar', ['attendanceModel' => $attendanceModel]);
    }

    
    public function actionSaveEmpId(){

         $ids=Yii::$app->request->post('emp_is');
         $dat=Yii::$app->request->post('d');
         $dats= date('Y-m-d',strtotime($dat));
          $query=EmployeeAttendance::find()->where(['fk_empl_id'=>$ids]) 
            ->andWhere(['between', 'date', $dats.' 00:00:00', $dats.' 23:59:59'])
            ->one();

            $query1=EmployeeAttendance::find()->where(['fk_empl_id'=>$ids]) 
            ->andWhere(['between', 'date', $dats.' 00:00:00', $dats.' 23:59:59']);
            $provider = new ActiveDataProvider([
                'query' => $query1,
            ]);

            if($query){
                 $getVal= $this->renderAjax('getdetails', ['passvalue' => $provider]);
                 return json_encode(['type'=>$query->leave_type,'remarks'=>$query->remarks,'newprovide'=>$getVal]);

            }else{
                return 'false';
            }

    }//end of empId



    public function actionSaveLeave(){
       $post_date= date('Y-m-d',strtotime($_POST['getDate']));
       $post_emp=$_POST['employee'];
       $exists=EmployeeAttendance::find()->where(['fk_empl_id'=>$post_emp]) 
       ->andWhere(['between', 'date', $post_date.' 00:00:00', $post_date.' 23:59:59'])
       ->one();
       $smsActive=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
       $employeeName=EmployeeInfo::find()->where(['emp_id'=>$post_emp])->one();
       if(count($exists) > 0){
        $model = EmployeeAttendance::findOne($exists->id);
       }else{
            $model = new EmployeeAttendance();
        }
       $model->fk_empl_id=$_POST['employee'];
       $model->leave_type=$_POST['select'];
       $model->remarks=$_POST['remark'];
       $model->date=$_POST['getDate'];
       $ownerContact=Yii::$app->common->getBranchDetail()->mobile;
       $nameCnvrt=Yii::$app->common->getName($employeeName->user_id);
       $msg='Respectfull Sir..!Employee '.$nameCnvrt.' '.$_POST['select'].' today in Alhuda College Gulabad';
       $model->fk_branch_id=yii::$app->common->getBranch();
       $model->time=date("H:i:s");
        if($model->save()){
            $get_leave_type=$model->leave_type;
            if($get_leave_type == 'present'){}else{
                if($smsActive->status == 1){
              Yii::$app->common->SendSms($ownerContact,$msg,$post_emp);
          }
           }
            if($get_leave_type == 'absent'){
                echo '<span class="label label-danger">A</span>';
            }else if($get_leave_type == 'leave'){
                echo '<span class="label label-warning">L</span>';
            }else if($get_leave_type == 'late'){
               echo '<span class="label label-info">LT</span>';
            }else if($get_leave_type == 'Latewithexcuse'){
              echo '<span class="label label-info">LE</span>';   
            }else if($get_leave_type == 'shortleave'){
             echo '<span  class="label btn-vk">SL</span>';
            }else if($get_leave_type == 'present'){
                echo '<span style="color:green">P</span>';
            }
       }else{
            print_r($model->getErrors());
       } 
       } // end of 


      

    public function actionFunctions(){
      
        return $this->renderAjax('EmpCalfunctions');
        
    }


    // country
    public function actionCountry(){
    
        $id=Yii::$app->request->post('id');
        $provinces= RefProvince::find()->where(['country_id'=>$id])->all();
     
        echo "<option selected='selected'>Select Provinces</option>";
        foreach($provinces as $province)
        {
            echo "<option value='".$province->province_id."'>".$province->province_name."</option>";
            
        }
    
   }//end of country 

    public function actionProvince(){
    
        $id=Yii::$app->request->post('id');
        $District=RefDistrict::find()->where(['province_id'=>$id])->all();
     
    echo "<option selected='selected'>Select District</option>";
    foreach($District as $district)
    {
        
            echo "<option value='".$district->district_id."'>".$district->District_Name."</option>";
        
    }
    

    }//end of Province 

    public function actionDistrict(){
    
        $id=Yii::$app->request->post('id');
        $city=RefCities::find()->where(['district_id'=>$id])->all();
     
    echo "<option selected='selected'>Select City</option>";
    foreach($city as $city)
    {
        
            echo "<option value='".$city->city_id."'>".$city->city_name."</option>";
        
    }
    

    }//end of District


    /**
     * Lists all EmployeeInfo models.
     * @return mixed
     */
    public function actionExcel()
    {
        $searchModel = new EmployeeInfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderPartial('excel', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndex()
    {
        $searchModel = new EmployeeInfoSearch();
        $model = new EmployeeInfo();
        $model->scenario = 'change';
        $searchModel->fk_branch_id = Yii::$app->common->getBranch();
        $searchModel->is_active=1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionInactive()
    {
        $searchModel = new EmployeeInfoSearch();
        $searchModel->fk_branch_id = Yii::$app->common->getBranch();
        $searchModel->is_active=0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('inactive', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /*generate pdf biometric*/
    public function actionInactiveEmp(){
        if(Yii::$app->user->isGuest){
            return $this->redirect(['site/login']);
        }else{
            $query= User::find()
                ->select(['*'])
                ->leftJoin('employee_info','employee_info.user_id = user.id')
                ->where(['user.fk_branch_id'=>Yii::$app->common->getBranch(),'employee_info.is_active'=>0])
                ->asArray()
                ->all();
            /*create employee biometric pdf*/
            $html =  $this->renderAjax('generate-pdf-empb',['query'=>$query,'id'=>1]);
            $this->layout = 'pdf';
            $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
            $mpdf->WriteHTML($html);
            $mpdf->Output('Employees-biometric.pdf', 'D');
        }

    }
    public function actionGeneratePdfEmpb(){
        if(Yii::$app->user->isGuest){
            return $this->redirect(['site/login']);
        }else{
            $query= User::find()
                ->select(['*'])
                ->leftJoin('employee_info','employee_info.user_id = user.id')
                ->where(['user.fk_branch_id'=>Yii::$app->common->getBranch(),'employee_info.is_active'=>1])
                ->asArray()
                ->all();
            /*create employee biometric pdf*/
            $html =  $this->renderAjax('generate-pdf-empb',['query'=>$query,'id'=>0]);
            $this->layout = 'pdf';
            $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
            $mpdf->WriteHTML($html);
            $mpdf->Output('Employees-biometric.pdf', 'D');
        }
    }

    public function actionSalaryPay(){
      $id=Yii::$app->request->post('id');
     $exist=SalaryMain::find()->where(['fk_emp_id'=>$id])->one();

     $employeeLeaveCount=EmployeeAttendance::find()->where(['fk_empl_id'=>$id,'leave_type'=>'leave'])->all();
     $employeelatecomingCount=EmployeeAttendance::find()->where(['fk_empl_id'=>$id,'leave_type'=>'latecomer'])->all();
     $employeeslCount=EmployeeAttendance::find()->where(['fk_empl_id'=>$id,'leave_type'=>'shortleave'])->all();
     $employeeabsentCount=EmployeeAttendance::find()->where(['fk_empl_id'=>$id,'leave_type'=>'absent'])->all();
      if(count($exist)>0){
           $c_date=date('m');
           $mon=date('m',strtotime($exist->salary_month));
           if($c_date == $mon){
            $exist= 'This Employee Has Already Been Taken Salary This Month';
           }
          }
     $employee_payroll = EmployeePayroll::find()->where(['fk_emp_id'=>$id])->one();
     $emply_alwnc = EmployeeAllowances::find()->where(['fk_emp_id'=>$id,'status'=>1])->All();
     $payrollDeduction = EmployeeDeductions::find()->select(['fk_deduction_id'])->where(['fk_emp_id'=>$id,'status'=>1])->All();
     $basic=$employee_payroll->basic_salary;
     $gross=$employee_payroll->total_amount;
     $stageId=$employee_payroll->fk_pay_stages;
     $group=$employee_payroll->fkGroup->title;
     $stage=$employee_payroll->fkPayStages->title;
     $total_alwnc=$employee_payroll->total_allownce;
     $total_deducn=$employee_payroll->total_deductions;
     
     $salaryView=$this->renderAjax('salary-pay',['basic'=>$basic,'gross'=>$gross,'total_alwnc'=>$total_alwnc,'total_deducn'=>$total_deducn,'group'=>$group,'stage'=>$stage,'emply_alwnc'=>$emply_alwnc,'payrollDeduction'=>$payrollDeduction,'employeeLeaveCount'=>$employeeLeaveCount,'employeelatecomingCount'=>$employeelatecomingCount,'employeeslCount'=>$employeeslCount,'employeeabsentCount'=>$employeeabsentCount]);
     return json_encode(['salaryView'=>$salaryView,'gros'=>$gross,'stag'=>$stage,'stageId'=>$stageId,'basics'=>$basic,'exist'=>$exist]);
    // return json_encode(['basic'=>$basic]);


    }

    /**
     * Displays a single EmployeeInfo model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        //echo $id;die;
        //$searchModel = new EmplEducationalHistoryInfoSearch();
       // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $model2= EmplEducationalHistoryInfo::findOne(['emp_id'=>$id]); 
        $leave = EmployeeAttendance::find()->where(['fk_empl_id'=>$id,'leave_type'=>'leave'])->all();
        $absent= EmployeeAttendance::find()->where(['fk_empl_id'=>$id,'leave_type'=>'absent'])->all();
        //echo count($absent);die;
        $present = EmployeeAttendance::find()->where(['fk_empl_id'=>$id,'leave_type'=>'present'])->all();
        $shortleave = EmployeeAttendance::find()->where(['fk_empl_id'=>$id,'leave_type'=>'shortleave'])->all();
        
        $query1=EmplEducationalHistoryInfo::find()->where(['emp_id'=>$id]);
            $dataProvider = new ActiveDataProvider([
                'query' => $query1,
            ]);
        
        return $this->render('view', [
            'model' => $this->findModel($id),
            'model2'=>$model2,
            'leave'=>$leave,
            'absent'=>$absent,
            'shortleave'=>$shortleave,
            'present'=>$present,
            //'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionViewProfilePdf()
    {
         $id=yii::$app->request->get('id');

        //$searchModel = new EmplEducationalHistoryInfoSearch();
       // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $model2= EmplEducationalHistoryInfo::findOne(['emp_id'=>$id]); 
        $leave = EmployeeAttendance::find()->where(['fk_empl_id'=>$id,'leave_type'=>'leave'])->all();
        $absent= EmployeeAttendance::find()->where(['fk_empl_id'=>$id,'leave_type'=>'absent'])->all();
        //echo count($absent);die;
        $present = EmployeeAttendance::find()->where(['fk_empl_id'=>$id,'leave_type'=>'present'])->all();
        $shortleave = EmployeeAttendance::find()->where(['fk_empl_id'=>$id,'leave_type'=>'shortleave'])->all();
        
        $query1=EmplEducationalHistoryInfo::find()->where(['emp_id'=>$id]);
            $dataProvider = new ActiveDataProvider([
                'query' => $query1,
            ]);
        
       $profile= $this->renderAjax('profile-pdf', [
            'model' => $this->findModel($id),
            'model2'=>$model2,
            'leave'=>$leave,
            'absent'=>$absent,
            'shortleave'=>$shortleave,
            'present'=>$present,
            //'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

       $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $stylesheet = file_get_contents('css/profile.css');
        $mpdf->WriteHTML($stylesheet,1);
       // $mpdf->WriteHTML("<h3 style='text-align:center'>Over All Transport Zone Wise</h3>");
        $mpdf->WriteHTML($profile);
        $mpdf->Output('Employee-profile.pdf-'.date("d-m-Y").'.pdf', 'D'); 

     /*   $this->layout = 'pdf';
            $mpdf=new mPDF('','A4');
            $stylesheet = file_get_contents('css/std-ledger-pdf.css');
        $mpdf->WriteHTML($stylesheet,1);
            $mpdf->WriteHTML($profile);
        $mpdf->Output('Employee-profile.pdf-'.date("d-m-Y").'.pdf', 'D'); 
          */  
    }

    /**
     * Creates a new EmployeeInfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    

    /*public function actionGetStageDetail(){
        $id=Yii::$app->request->post('id');
        $getDeduction=SalaryPayStages::find()->where(['id'=>$id])->one();
        $alwnc=SalaryAllownces::find()->where(['fk_stages_id'=>$id])->one();
        //echo $getDeduction->amount;
          return json_encode(['ammount'=>$getDeduction->amount,'alwnc'=>$alwnc->title]);
        
    }*/
    public function actionGetStageDetail(){

         $id=Yii::$app->request->post('id');
        $alwnc=SalaryAllownces::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>1,'fk_stages_id'=>$id])->All();
        
       $allownce= "";
        foreach($alwnc as $alwnc)
        {
            $allownce.= "<option value='".$alwnc->id."'>".$alwnc->title."</option>";
            
        }
        //return $alonwnce;

        $salr=SalaryDeductionType::find()->where(['fk_stages_id'=>$id])->All();
        $sal= "";
        foreach($salr as $slr)
        {
            $sal.= "<option value='".$slr->id."'>".$slr->title."</option>";
            
        }


        $salr=SalaryPayStages::find()->select('amount')->where(['id'=>$id])->one();
        $stgamnt=$salr->amount;

        return Json_encode(['html'=>$allownce,'sal'=>$sal,'amnt'=>$stgamnt]);

        //return $allownce;
        
           
        
    }

    /* public function actionGetAllownce(){
           $id=Yii::$app->request->post('id');
           //print_r($id);
           $count=0;
           $stgeid=Yii::$app->request->post('stageid');
           $stagevalue= SalaryPayStages::find()->where(['id'=>$stgeid])->one();
           if(count($id)> 0){ 
            $renderView=$this->renderAjax('get-allownce',['stageid'=>$id,'stagevl'=>$stagevalue]);
          //echo $renderView;die;
           }else{
           //return $renderView;
            $renderView = 'Not Found';
           }
           return json_encode(['viewtable'=>$renderView]);
         
      }*/
       public function actionGetAllownce(){

           $alwncid=Yii::$app->request->post('alwncid');
           $deductid=Yii::$app->request->post('deductid');
           // print_r($deductid);die;
            $stageid=Yii::$app->request->post('stageid');

           $stagevalue= SalaryPayStages::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'status'=>1,'id'=>$stageid])->one();
           $renderView=$this->renderAjax('get-allownce',[
            'stageid'=>$alwncid,
            'stagevl'=>$stagevalue,
            'deductions'=>$deductid
            ]);
           
           return json_encode(['viewtable'=>$renderView]);
         
      }

      public function actionGetDeduction(){
            $ids=Yii::$app->request->post('id');
            $gettotalAlwnc=Yii::$app->request->post('gettotalAlwnc');
           
            //$ids=explode(',',$id);
          $count=0;
          if(count($ids)> 0){ 
            $renderViews=$this->renderAjax('get-deduction',['stageid'=>$ids,'gettotalAlwnc'=>$gettotalAlwnc]);
          }else{
            $renderViews = 'Not Found';
          }
           return json_encode(['viewtables'=>$renderViews]);
         
          }



      /*public function actionGetDeduction(){
        $id=Yii::$app->request->post('id');
        $ids=explode(',',$id);
        $count=0;
       foreach ($ids as $ide) {
        $getDeduction=SalaryDeductionType::find()->where(['id'=>$ide])->sum('amount');
        $count= $count+$getDeduction;
       }
       return $count;
       }*/


      /*public function actionGetAllownce(){
          $id=Yii::$app->request->post('id');
           $ids=explode(',',$id);
           //$count=0;
          
          foreach ($ids as $ids) {
             $stageamount= SalaryAllownces::find()->where(['id'=>$ids])->All(); 
             foreach ($stageamount as $key) {
                 echo $key->amount;
             }
              
          }
         // return $count;
         // echo $stageamount->amount;
        
      }*/

  

    public function actionCreate()
    {
       
        $model = new EmployeeInfo();
        $model2 = new EmployeeParentsInfo();
        $usermodel = new User();
        $employeesalaryselection= new EmployeeAllowances();
        $employeesalarydeductiondetail= new EmployeeDeductions();
        $employeePayroll= new EmployeePayroll();
        $model->marital_status=1;
        $model->gender_type=1;   
        $model2->gender=1;
        $emplarray=Yii::$app->request->post('EmployeeInfo');
        $emplContact=$emplarray['contact_no'];
        if ($usermodel->load(Yii::$app->request->post())){    
          $reg=Yii::$app->request->post('User');
          $userRegester= $reg['username'];
          $stringName = str_replace(' ', '', $userRegester);
          $schoolName=Yii::$app->common->getBranchDetail()->name;
          $stringSchoolName = str_replace(' ', '', $schoolName);
           $salrySelectinGroup     = $_POST['EmployeeAllowances']['fk_allownces_id'];
           $salarydeduction        = $_POST['EmployeeDeductions']['fk_deduction_id'];
           $dfrntAdress=$_POST['EmployeeInfo']['different_address'];
           $file =$usermodel->Image= UploadedFile::getInstance($usermodel, 'Image');
          if($file){
             $usermodel->Image=$file; 
         }
            $password=Yii::$app->common->getBranchDetail()->password;
            $random_password= Yii::$app->getSecurity()->generateRandomString($length = 7);
            $random_password=$password;
            $usermodel->setPassword($random_password);
            $usermodel->generateAuthKey();
            $usermodel->fk_branch_id=Yii::$app->common->getBranch();
            
            $settingQuery=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
            $schoolName=strtolower($settingQuery->school_name);
            //$usermodel->fk_role_id=4;
            $usermodel->status='active';
            if($usermodel->save()){
                 if(!empty($file)){
                 $file->saveAs(\Yii::$app->basePath . '/web/uploads/'.$file);
                }
                $user_id = $usermodel->id;
                //message here
                $mesgControl =\app\models\MessageControl::find()->where(['branch_id'=>Yii::$app->common->getBranch(),'message_id'=>'hiring'])->One();

                $msg=$mesgControl->message.'.<br> Your Login is '.$stringName.' and password is '.$password.',(http://33344450.com/'.$schoolName.')';
                $smsActive=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
                 if($smsActive->status == 1){
                  Yii::$app->common->SendSms($emplContact,$msg,$user_id);
                 }
                //end of message
                if ($model->load(Yii::$app->request->post())){
                    $model->user_id=$usermodel->id;
                    $model->fk_branch_id=Yii::$app->common->getBranch();
                    $model->is_active =1;
                    if($dfrntAdress == 1){
                         $model->fk_ref_country_id2=$_POST['EmployeeInfo']['fk_ref_country_id22'];
                         $model->fk_ref_province_id2=$_POST['EmployeeInfo']['fk_ref_province_id22'];
                         $model->fk_ref_district_id2=$_POST['EmployeeInfo']['fk_ref_district_id22'];
                         $model->fk_ref_city_id2=$_POST['EmployeeInfo']['fk_ref_city_id22'];
                    }
                    if($model->save()){
                        if ($employeePayroll->load(Yii::$app->request->post())){
                           $empoyee_payrol         = Yii::$app->request->post('EmployeePayroll');
                           $empoyee_deductns_pyroll= $empoyee_payrol['total_amount'];
                           $b_sal=$empoyee_payrol['basic_salary'];
                           $t_alwnc=$empoyee_payrol['total_allownce'];
                           $t_dedu=$empoyee_payrol['total_deductions'];
                           $totl_formula=$b_sal + $t_alwnc - $t_dedu;
                           $employeePayroll->fk_emp_id=$model->emp_id;
                            $employeePayroll->total_amount=$totl_formula;
                            $employeePayroll->created_date=date("Y:m:d H:i:s");
                            if($employeePayroll->save()){
                            }else{
                              print_r($employeePayroll->getErrors());die;
                            }
                            } //end of deduction if post group and stage
                      if(!empty($salarydeduction)){
                       if ($employeesalarydeductiondetail->load(Yii::$app->request->post())){
                       foreach($salarydeduction as $deduction){
                        $deductionmodel=new EmployeeDeductions();
                        $deductionmodel->fk_emp_id=$model->emp_id;
                        $deductionmodel->fk_payroll_id=$employeePayroll->id;
                        $deductionmodel->fk_deduction_id = $deduction;
                        $deductionmodel->created_date =date('Y:m:d H:i:s');
                        if($deductionmodel->save()){                           
                        }else{
                            print_r($deductionmodel->getErrors());die;
                        }
                       } // end of foreach

                            } //end of deduction if
                          }
                        //end of insert deduction
                        // insert allownces
               if ($employeesalaryselection->load(Yii::$app->request->post())){
                if(!empty($salrySelectinGroup)){
               foreach($salrySelectinGroup as $grp){
                $mdls=new EmployeeAllowances();
                $mdls->fk_emp_id=$model->emp_id;
                $mdls->fk_payroll_id=$employeePayroll->id;
                $mdls->fk_allownces_id = $grp;
                $mdls->created_date=date('Y:m:d H:i:s');
                if($mdls->save()){   
                }else{
                    print_r($mdls->getErrors());die;
                }
               
               }
                    } //end of salry if empty
                  }
                      //end of insert alownces 
                        if ($model2->load(Yii::$app->request->post())){
                            $model2->emp_id=$model->emp_id;
                            if($model2->save()){
                                /*email will be composed here*/
                                    /*returning to the detail view of employe.*/
                            if(Yii::$app->request->post('submit')==='create_continue'){
                                //employe education info form. 
                                return $this->redirect(['education/create', 'id' => $model->emp_id]);
                            }else{
                                return $this->redirect(['employee/view', 'id' => $model->emp_id]);
                            }
                            }else{
                            //  print_r($model2->getErrors());die;
                                return $this->render('create', [
                            'model' => $model,
                            'model2' => $model2,
                            'usermodel' => $usermodel,
                            'employeesalaryselection' => $employeesalaryselection,
                            'employeesalarydeductiondetail' => $employeesalarydeductiondetail,
                            'employeePayroll' => $employeePayroll,
                                ]);
                            }
                         }
                    }else{
                         //print_r($model->getErrors());die;
                         return $this->render('create', [
                'model' => $model,
                'model2' => $model2,
                'usermodel' => $usermodel,
                'employeesalaryselection' => $employeesalaryselection,
                'employeesalarydeductiondetail' => $employeesalarydeductiondetail,
                'employeePayroll' => $employeePayroll,
            ]);
                    }
                }
            }else{
               // print_r($usermodel->getErrors());die;
                 return $this->render('create', [
                    'model' => $model,
                    'model2' => $model2,
                    'usermodel' => $usermodel,
                    'employeesalaryselection' => $employeesalaryselection,
                    'employeesalarydeductiondetail' => $employeesalarydeductiondetail,
                    'employeePayroll' => $employeePayroll,
                ]);
                //return $this->redirect(['create', 'id' => $model->emp_id]);
            }

        } else {
          // print_r($model->getErrors());die;
            return $this->render('create', [
                'model' => $model,
                'model2' => $model2,
                'usermodel' => $usermodel,
                'employeesalaryselection' => $employeesalaryselection,
                'employeesalarydeductiondetail' => $employeesalarydeductiondetail,
                'employeePayroll' => $employeePayroll,
            ]);
        }
    }

    /**
     * Updates an existing EmployeeInfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
   public function actionUpdate($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

         /*if(Yii::$app->request->post()){

        
            echo "<pre>";print_r(Yii::$app->request->post());exit;
           //echo "<pre>";print_r($_POST['EmployeeDeductions']['fk_deduction_id']);
        die;
        }*/

       
        $model = $this->findModel($id);
        $usermodel=User::find()->where(['id'=>$model->user_id])->one();

        $model2=EmployeeParentsInfo::find()->where(['emp_id'=>$id])->one();

        $employeePayroll=EmployeePayroll::find()->where(['fk_emp_id'=>$id])->one();
        if(count($employeePayroll) == 0){
        $employeePayroll=new EmployeePayroll();

        }
         $employeesalaryselection = new EmployeeAllowances();
         $employeesalarydeductiondetail = new EmployeeDeductions();
        $old_image=$usermodel->Image;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        $employeesalaryselection=EmployeeAllowances::find()->where(['fk_emp_id'=>$id])->one();

          if(count($employeesalaryselection) > 0){
            $employeesalaryselection = EmployeeAllowances::findOne($employeesalaryselection->id);

        }else{
            $employeesalaryselection = new EmployeeAllowances();

        }

        if(count($employeesalarydeductiondetail)>0){
            $employeesalarydeductiondetail = EmployeeDeductions::findOne($employeesalarydeductiondetail->id);
        }else{
            $employeesalarydeductiondetail = new EmployeeDeductions();
        }
           
            $salrySelectinGroup= $_POST['EmployeeAllowances']['fk_allownces_id'];
            $salarydeduction= $_POST['EmployeeDeductions']['fk_deduction_id'];
            $update_allownces_status = "UPDATE employee_allowances  SET status = 0 WHERE fk_emp_id =".$id;

            $update_deduction_status = "UPDATE employee_deductions  SET status = 0 WHERE fk_emp_id =".$id;
        \Yii::$app->db->createCommand($update_allownces_status)->execute();
        \Yii::$app->db->createCommand($update_deduction_status)->execute();
            if($usermodel->load(Yii::$app->request->post())){
               if(!empty($_FILES['User']['name']['Image'])){
                 
                 $file =UploadedFile::getInstance($usermodel, 'Image');
                 $file->saveAs(\Yii::$app->basePath . '/web/uploads/'.$file);
                 $usermodel->Image=$file;
                }else{
                    $usermodel->Image=$old_image;
                }

               // $usermodel->status=1;
               if($usermodel->save(false)){
                if($model2->load(Yii::$app->request->post())){
                    $model2->save();
                if($employeePayroll->load(Yii::$app->request->post())){
                           $empoyee_payrol=Yii::$app->request->post('EmployeePayroll');
                           $empoyee_deductns_pyroll= $empoyee_payrol['total_amount'];
                           $b_sal=$empoyee_payrol['basic_salary'];
                           $t_alwnc=$empoyee_payrol['total_allownce'];
                           $t_dedu=$empoyee_payrol['total_deductions'];
                           $totl_formula=$b_sal + $t_alwnc - $t_dedu;
                           $employeePayroll->total_amount=$totl_formula;
                           $employeePayroll->created_date=date("Y:m:d H:i:s");
                           $employeePayroll->fk_emp_id=$model->emp_id;
                    if($employeePayroll->save()){}else{print_r($employeePayroll->getErrors());}
                  }
               }
               //salary
               if(!empty($salrySelectinGroup)){ 
               foreach($salrySelectinGroup as $grp){
                $employeesalaryselectionUpdate = EmployeeAllowances::find()->where(['fk_allownces_id'=>$grp,'fk_emp_id'=>$id])->one();
                if(count($employeesalaryselectionUpdate) == 0){
              $employeesalaryselectionUpdate = new EmployeeAllowances();
             $employeesalaryselectionUpdate->fk_payroll_id=$employeePayroll->id;

              $employeesalaryselectionUpdate->fk_emp_id=$model->emp_id;
              $employeesalaryselectionUpdate->fk_allownces_id = $grp;
              $employeesalaryselectionUpdate->created_date=date('Y:m:d H:i:s');
              $employeesalaryselectionUpdate->status=1;                
                }else{
                  $employeesalaryselectionUpdate->status=1;
                     }
                if($employeesalaryselectionUpdate->save()){
                    
                }else{
                    print_r($employeesalaryselectionUpdate->getErrors());die;
                }
               }//end of foreach
            }// end of if
                        // insert deduction
                        if(!empty($salarydeduction)){
                       foreach($salarydeduction as $deduction){
               //print_r($deduction);die;

                       $employeesalaryDeductionUpdate = EmployeeDeductions::find()->where(['fk_deduction_id'=>$deduction,'fk_emp_id'=>$id])->one();
               //print_r($employeesalaryDeductionUpdate);die;
                        if(count($employeesalaryDeductionUpdate) == 0){
                        $employeesalaryDeductionUpdate = new EmployeeDeductions();
                        $employeesalaryDeductionUpdate->fk_payroll_id=$employeePayroll->id;
                        $employeesalaryDeductionUpdate->fk_emp_id=$model->emp_id;
                        $employeesalaryDeductionUpdate->fk_deduction_id = $deduction;
                        //$deductionmodel->amount = $_POST['EmployeeDeductions']['amount'];
                        $employeesalaryDeductionUpdate->created_date =date('Y:m:d H:i:s');
                        $employeesalaryDeductionUpdate->updated_date =date('Y:m:d H:i:s');
                        $employeesalaryDeductionUpdate->status =1;
                        }else{
                        $employeesalaryDeductionUpdate->status =1;

                        }
                        
                        if($employeesalaryDeductionUpdate->save()){    
                        }else{
                            print_r($employeesalaryDeductionUpdate->getErrors());die;
                        }
                       }// end of foreach

                     }    
                        //end of insert deduction    
               //end of salary
               }else{
                //print_r($usermodel->getErrors());die;
               }
             }
            return $this->redirect(['view', 'id' => $model->emp_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'model2' => $model2,
                'usermodel' => $usermodel,
                'employeesalaryselection'=>$employeesalaryselection,
                'employeesalarydeductiondetail'=>$employeesalarydeductiondetail,
                'employeePayroll' => $employeePayroll,
            ]);
        }
    }

   public function actionDelete($id){
        $decodedId=base64_decode($id);
        $model= EmployeeInfo::findOne($decodedId);
        $model->is_active = '0';
        $model->save(); 
        if (!Yii::$app->request->isAjax) {
             Yii::$app->session->setFlash('success', "Employee successfully Deactivated");
            return $this->redirect(['index']);
        } 
    }
    public function actionReactive($id){
        $decodedId=base64_decode($id);
        $model= EmployeeInfo::findOne($decodedId);
        $model->is_active = '1';
        $model->save(); 
        if (!Yii::$app->request->isAjax) {
            Yii::$app->session->setFlash('success', "Employee successfully activated");
            return $this->redirect(['index']);
        } 
    }
    protected function findModel($id)
    {
        if (($model = EmployeeInfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    //======== country 2 for admission============//
    public function actionCountry2(){

        $id=Yii::$app->request->post('id');
        $provinces=RefProvince::find()->where(['country_id'=>$id])->all();

            echo "<option selected='selected'>Select Provinces</option>";
            foreach($provinces as $province)
            {

                    echo "<option value='".$province->province_id."'>".$province->province_name."</option>";

            }


    }//end of country

    public function actionProvince2(){
        $id=Yii::$app->request->post('id');
        $District=RefDistrict::find()->where(['province_id'=>$id])->all();
        echo "<option selected='selected'>Select District</option>";
        foreach($District as $district)
        {
        echo "<option value='".$district->district_id."'>".$district->District_Name."</option>";
        } }//end of Province

    public function actionDistrict2(){

        $id=Yii::$app->request->post('id');
        $city=RefCities::find()->where(['district_id'=>$id])->all();

    echo "<option selected='selected'>Select City</option>";
    foreach($city as $city)
    {

            echo "<option value='".$city->city_id."'>".$city->city_name."</option>";

    }


    }//end of District

    //======== end of country 2============//

    /* biomatric of employee */

    public function actionBiomatric(){
        set_time_limit(0);
        $checkinOutTable=Checkinout::find()->where(['CHECKTYPE'=>'I'])->all();

        $msg=0;
        foreach ($checkinOutTable as $bio) {
                $model=new EmployeeAttendance();

            $userIds=$bio->USERID;
         //   echo '<br />';
            /*for the checking of existance of employee*/
            $employeeInfo=EmployeeInfo::find()->select('user_id')->where(['user_id'=>$userIds])->all();

            foreach ($employeeInfo as $empinf) {
            
            /*end of checking the existance of employee*/ 
       
                 $model->fk_empl_id=$empinf->user_id;
                 $model->date=$bio->CHECKTIME;
                 $model->leave_type='present';

        if($model->save()){
        }else{print_r($model->getErrors());}
            
        
    } // end of foreach

}

    if($msg == 0){
            echo "<h3 style='color:green'>Successfully inserted Employee biomatric attendance</h3>";

    }

        


      //  return $this->render('biomatric',['model'=>$model,'checkinOutTable'=>$checkinOutTable]);
     }

    /* end of biomatric employee */


    public function actionAttendance(){
      $attendanceModel = new EmployeeAttendance();
      $getEmplpoyee=EmployeeInfo::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'is_active'=>1])->All();
       $getEmplpoyeedate=EmployeeAttendance::find()->where(['date(date)'=>date('Y-m-d')])->one();
        return $this->render('attendance', ['attendanceModel' => $attendanceModel,'getEmplpoyee'=>$getEmplpoyee,'getEmplpoyeedate'=>$getEmplpoyeedate]);
    }

     public function actionSaveAttendance(){
        $attendanceModel = new EmployeeAttendance();
        $array=yii::$app->request->post('EmployeeAttendance');
        $emp_id=$array['emp_id'];
        $leave_type=$array['leave_type'];
        $date=date('Y-m-d');
        $smsActive=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
        $settings = Yii::$app->common->getBranchSettings();
        $remarks=$array['remarks'];
        $ownerContact=Yii::$app->common->getBranchDetail()->mobile;
        foreach( $emp_id as $key => $emp_id ) {
        $model= new EmployeeAttendance;
        $employeeName=EmployeeInfo::find()->where(['emp_id'=>$emp_id])->one();
        $nameCnvrt=Yii::$app->common->getName($employeeName->user_id);
        if($leave_type[$key] == 'leave'){
            $msg='Respectful Sir..!Employee '.$nameCnvrt.' is on leave today';
        }else if($leave_type[$key] == 'shortleave'){
            $msg='Respectful Sir..!Employee '.$nameCnvrt.' is on Short Leave today';
        }else{
        $msg='Respectful Sir..!Employee '.$nameCnvrt.' '.$leave_type[$key].' today';
        }
        $employee_sms_on_off = $settings->employee_sms_on_off;
            if($employee_sms_on_off == 1){
        if($leave_type[$key] == 'present'){}else{
                if($smsActive->status == 1){
              Yii::$app->common->SendSms($ownerContact,$msg,$emp_id);
          }
           }
       }
        $model->fk_branch_id=yii::$app->common->getBranch();
        $model->fk_empl_id=$emp_id;
        $model->leave_type=$leave_type[$key];
        $model->remarks=$remarks[$key];
        $model->time=date('H:i:s');
        $model->date=$date;
           if($model->save()){
                Yii::$app->session->setFlash('success', "Employee Attendance Successfully saved");
                $this->redirect(['attendance-list']);
             }else{
             print_r($model->getErrors());die;
             }
      } 
    }

    public function actionAttendanceList()
    {
        $searchModel = new EmployeeAttendanceSearch();
         $query1=EmployeeAttendance::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'date'=>date('Y-m-d')]);
         $dataProvider = new ActiveDataProvider([
                'query' => $query1,
            ]);
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('attendance-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
     

    public function actionUpdateAttendance($id) 
    { 
        $model=EmployeeAttendance::find()->where(['id'=>$id])->one();
        if ($model->load(Yii::$app->request->post())) { 
             $smsActive=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
            $data=Yii::$app->request->post('EmployeeAttendance');
             $leaveType=$data['leave_type'];
             $employeeName=EmployeeInfo::find()->where(['emp_id'=>$id])->one();
             $nameCnvrt=Yii::$app->common->getName($employeeName->user_id);
             if($leaveType == 'leave'){
              $msg='(Updated) Respectful Sir..!Employee '.$nameCnvrt.' is on leave today';
             } else if($leaveType == 'shortleave'){
                $msg='(Updated) Respectful Sir..!Employee '.$nameCnvrt.' take Short Leave';
             }else{
                $msg='(Updated) Respectfull Sir..!Employee '.$nameCnvrt.' '.$leaveType.' today';
             }
            $ownerContact=Yii::$app->common->getBranchDetail()->mobile;
            if($leaveType == 'present'){}else{
                if($smsActive->status == 1){
              Yii::$app->common->SendSms($ownerContact,$msg,$id);
          }
           }
          if($model->save()){
            Yii::$app->session->setFlash('success', "Employee Attendance Successfully Updated");
            return $this->redirect(['attendance-list']); 
          }else{
            print_r($model->getErrors());die;
          }
        } else { 
            return $this->render('update-attendace', [ 
                'model' => $model, 
            ]); 
        } 
    }// end of main function
    public function actionAttcal(){
    $empQuery = User::find()
    ->select(['employee_info.emp_id',"concat(user.first_name, ' ' ,  user.last_name) as name"])
    ->innerJoin('employee_info','employee_info.user_id = user.id')
    ->where(['employee_info.fk_branch_id'=>Yii::$app->common->getBranch()])->asArray()->all();
        $emplList=[];
        foreach ($empQuery as $key => $arrayEmp) {
          $emplList[]=['id'=>$arrayEmp['emp_id'],'title'=>$arrayEmp['name']];
         }
        $attndnce=EmployeeAttendance::find()->select(['id','fk_empl_id','leave_type','date'])->asArray()->all();
        $emp_attendance=[];
        foreach ($attndnce as $key => $emplAtt) {
          if($emplAtt['leave_type']=='absent'){
             $color='red';
          }else if($emplAtt['leave_type']=='leave'){
             $color='#ff8000';
          }else if($emplAtt['leave_type']=='late'){
             $color='#0080ff';
          }else if($emplAtt['leave_type']=='present'){
             $color='green';
          }
          if($emplAtt['leave_type'] == 'absent'){
            $attendance='A';
          }else if($emplAtt['leave_type'] == 'leave'){
            $attendance='L';
          }else if($emplAtt['leave_type'] == 'late'){
            $attendance='LT';
          }else if($emplAtt['leave_type'] == 'present'){
            $attendance='P';
          }
          $emp_attendance[]=['id'=>$emplAtt['id'],'resourceId'=>$emplAtt['fk_empl_id'],'start'=>$emplAtt['date'],'title'=>$attendance,'color'=>$color];
        }
    //echo '<pre>';print_r($attndnce);die;
      return $showEmpAttView= $this->render('att-cal-emp',['emplList'=>json_encode($emplList),'emp_attendance'=>json_encode($emp_attendance)]);
        
    } // end of function
    public function actionEmployeeCalendarEvent(){
               $id=yii::$app->request->post('id');
               $cal= $this->renderAjax('calendrevent-employee',['id'=>$id]);
               return json_encode(['cal'=>$cal]);

             }

    /*print position certificate*/
    public function actionBestCertificate($id){
        $emp_id=$id;
        $employee = Yii::$app->common->getEmployee($emp_id);
        $branch_details = Yii::$app->common->getBranchDetail();
        $details_html = $this->renderPartial('reports/best-teacher-certificate-pdf', [
                        'employee' => $employee,
                        'branch_details' => $branch_details,

                        ]);
        //echo $details_html;die;
        $this->layout = 'pdf';
        $mpdf = new mPDF('c', 'A4-L');
        $mpdf->WriteHTML($details_html);
        $mpdf->Output('position-certificate-'.$resultsheet.'.pdf', 'D');
        
    }
    public function actionDateAttendance(){ //date wise report
    if(!isset($_GET['date'])){
    $start= Yii::$app->request->post('start');
    $empQuery = User::find()
    ->select(['employee_info.emp_id',"concat(user.first_name, ' ' ,  user.last_name) as name"])
    ->innerJoin('employee_info','employee_info.user_id = user.id')
    ->where(['employee_info.fk_branch_id'=>Yii::$app->common->getBranch(),'employee_info.is_active'=>1])->asArray()->all();
    $view=$this->renderAjax('reports/date-report',['empQuery'=>$empQuery,'start'=>$start]);
    return json_encode(['showDateReceivable'=>$view]);
    }else{
    $start= Yii::$app->request->get('date');
    $empQuery = User::find()
    ->select(['employee_info.emp_id',"concat(user.first_name, ' ' ,  user.last_name) as name"])
    ->innerJoin('employee_info','employee_info.user_id = user.id')
    ->where(['employee_info.fk_branch_id'=>Yii::$app->common->getBranch(),'employee_info.is_active'=>1])->asArray()->all();
    $view=$this->renderAjax('reports/date-report',['empQuery'=>$empQuery,'start'=>$start]);
    $this->layout = 'pdf';
    $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
    $mpdf->WriteHTML($view);
    $mpdf->Output('employee-attendance-'.date("d-m-Y").'.pdf', 'D');   
    }
    }

    public function actionCal(){
      return $this->render('cal');
   }
   public function actionChange()
   {
    $userId=Yii::$app->request->post('userId');
    if(Yii::$app->request->post()){
     $data=Yii::$app->request->post('EmployeeInfo');
     $password=$data['password'];
     $user=User::find()->where(['id'=>$userId])->one();
     $s= $user->password_hash = Yii::$app->security->generatePasswordHash($password);
     if($user->save()){
        Yii::$app->session->setFlash('success', "Password Successfully Changed");
        $this->redirect('index');
    }else{
        echo '<div class="alert alert-danger">Some error has occur.. please contact to the support or try again</div>';
        $this->redirect('index');
    }
   }
}
public function actionSearchEmployee()
    {    
        $model = new EmployeeInfo();
        $searchModel = new EmployeeInfoSearch();
        $inputVal=Yii::$app->request->post('val');
        $studentDetails = EmployeeInfo::find()
            ->select(['employee_info.*'])
            ->innerJoin('user','user.id = employee_info.user_id')
            ->where(['user.username'=>$inputVal])
            ->orWhere(['like','user.first_name',$inputVal])
            ->andWhere(['employee_info.is_active'=>1]);
        $dataProvider = new ActiveDataProvider([
                  'query' => $studentDetails,
                  'pagination' => [
                  'pageSize' => 10,
                  ], ]);
        $details= $this->renderAjax('search', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
        return json_encode(['ajaxCrudDatatable'=>$details]);
    }
    /*end of print position certificate*/
} // end of class
