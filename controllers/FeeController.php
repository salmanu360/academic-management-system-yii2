<?php
namespace app\controllers;
ini_set('max_execution_time', 300);
use app\models\FeeDiscounts;
use app\models\FeeArears;
use app\models\HostelDetail;
use app\models\StuRegLogAssociation;
use app\models\SundryAccount;
use app\models\FeeSubmission;
use yii\data\ActiveDataProvider;
use app\models\FeeGroup;
use app\models\Stop;
use app\models\StudentInfo;
use app\models\User;
use app\models\FeeHead;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use mPDF;
use yii\helpers\ArrayHelper;
/**
 * ExamsController implements the CRUD actions for Exam model.
 */
class FeeController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        return $this->render('index', [
            'message'=>'Fee Generator',
        ]);
    }

    /*fee structure */
    public function actionStructure(){
        if (Yii::$app->user->isGuest) {
            $this->redirect(['site/login']);
        }
        else {
            $feestructure = new FeeGroup();
            if(Yii::$app->request->post()){
                $data =  Yii::$app->request->post('FeeGroup');
                $class  = $data['fk_class_id'];
                $group  = $data['fk_group_id'];

                $searchModel = new \app\models\search\FeeGroup();
                if ($class) {
                    $searchModel->fk_class_id=$class;
                }
                if($group){
                    $searchModel->fk_group_id = $group;
                }

                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                return $this->render('structure', [
                    'type'          => 'post',
                    'model'     => $feestructure,
                    'searchModel'   => $searchModel,
                    'dataProvider'  => $dataProvider,
                ]);
            }else{
                return $this->render('structure', [
                    'type'      => '',
                    'model'     => $feestructure
                ]);
            }
        }
    }

    /*generate fee challan PAGE*/
    public function actionGenerateFeeChallan(){

        if (Yii::$app->user->isGuest) {
            $this->redirect(['site/login']);
        }
        else {
            $model = new StudentInfo();

            /*
            *   Process for non-ajax request
            */
            return $this->render('generate-fee-challan', [
                'model' => $model,
            ]);
        }
    }


    /*generate challan student list*/

    public function actionGenerateChallanStdListClass(){
        if (Yii::$app->user->isGuest) {
            $this->redirect(['site/login']);
        }
        else {
            if(Yii::$app->request->isAjax){
                $data  =  Yii::$app->request->post();
                if(Yii::$app->request->post()){
                    if($data['section_id']){
                        $class_id = $data['class_id'];
                        $group_id = $data['group_id'];
                        $section_id = $data['section_id'];
                        /*query*/
                        $query = StudentInfo::find()->where([
                            'fk_branch_id'  => Yii::$app->common->getBranch(),
                            'class_id'      => $class_id,
                            //'group_id'      => ($group_id)?$group_id:null,
                            //'section_id'    => $section_id,
                             'is_active'     => 1,
                             //'roll_no'       =>SORT_ASC
                        ]);
                        $searchModel = new \app\models\search\StudentInfoSearch();

                        //$searchModel->patient_id = $post_data['pat_id'];
                        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                        $dataProvider= new ActiveDataProvider([
                            'query'=>$query,
                            'sort' => [
                                'defaultOrder' => [
                                    //'stu_id' => SORT_DESC
                                    'roll_no'       =>SORT_ASC

                                ]
                            ],
                            'pagination' => [
                                'pageSize' => 5000,
                                'params' => [
                                    'class_id'      => $class_id,
                                    'group_id'      => ($group_id)?$group_id:null,
                                    'section_id'    => $section_id,
                                ],
                            ]
                        ]);
                        // print_r(Yii::$app->request->queryParams);die;

                        $details =  $this->renderAjax('getStudents', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                        ]);

                        return json_encode(['status'=>1 ,'details'=>$details]);
                    }
                    else{
                        return json_encode(['status'=>1,'details'=>'<div class="alert alert-warning">
  <strong>Note!</strong>Record Not Found.</div>']);
                    }
                }
                else{
                    /*geting data on pagination.*/
                    $post_data  = Yii::$app->request->get();
                    $class_id   = $post_data['class_id'];
                    $group_id   = (isset($post_data['group_id']))?$post_data['group_id']:null;
                    $section_id = $post_data['section_id'];
                    /*query*/
                    $query = StudentInfo::find()->where([
                        'fk_branch_id'  => Yii::$app->common->getBranch(),
                        'class_id'      => $class_id,
                        'group_id'      => $group_id,
                        'section_id'    => $section_id,
                    ]);
                    $countQuery = clone $query;

                    $pages = new \yii\data\Pagination(['totalCount' => $countQuery->count()]);
                    $dataProvider = new ActiveDataProvider([
                        'query' => $query,
                        'sort' => [
                            'defaultOrder' => [
                                // 'stu_id' => SORT_DESC
                                    'roll_no'       =>SORT_ASC
                            ]
                        ]
                    ]);

                    return $this->renderAjax('getStudents',
                        [
                            'dataProvider' => $dataProvider,
                            'pages' => $pages,
                        ]);
                }
            }
        }
    }
    public function actionGenerateChallanStdList(){
        if (Yii::$app->user->isGuest) {
            $this->redirect(['site/login']);
        }
        else {
            if(Yii::$app->request->isAjax){
                $data  =  Yii::$app->request->post();
                if(Yii::$app->request->post()){
                    if($data['section_id']){
                        $class_id = $data['class_id'];
                        $group_id = $data['group_id'];
                        $section_id = $data['section_id'];
                        /*query*/
                        $query = StudentInfo::find()->where([
                            'fk_branch_id'  => Yii::$app->common->getBranch(),
                            'class_id'      => $class_id,
                            'group_id'      => ($group_id)?$group_id:null,
                            'section_id'    => $section_id,
                            'is_active'     => 1
                        ]);
                        $searchModel = new \app\models\search\StudentInfoSearch();

                        //$searchModel->patient_id = $post_data['pat_id'];
                        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                        $dataProvider= new ActiveDataProvider([
                            'query'=>$query,
                            'sort' => [
                                'defaultOrder' => [
                                    // 'stu_id' => SORT_DESC
                                    'roll_no'       =>SORT_ASC

                                ]
                            ],
                            'pagination' => [
                                'pageSize' => 5000,
                                'params' => [
                                    'class_id'      => $class_id,
                                    'group_id'      => ($group_id)?$group_id:null,
                                    'section_id'    => $section_id,
                                ],
                            ]
                        ]);
                        // print_r(Yii::$app->request->queryParams);die;

                        $details =  $this->renderAjax('getStudents', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                        ]);

                        return json_encode(['status'=>1 ,'details'=>$details]);
                    }
                    else{
                        return json_encode(['status'=>1,'details'=>'<div class="alert alert-warning">
  <strong>Note!</strong>Record Not Found.</div>']);
                    }
                }
                else{
                    /*geting data on pagination.*/
                    $post_data  = Yii::$app->request->get();
                    $class_id   = $post_data['class_id'];
                    $group_id   = (isset($post_data['group_id']))?$post_data['group_id']:null;
                    $section_id = $post_data['section_id'];
                    /*query*/
                    $query = StudentInfo::find()->where([
                        'fk_branch_id'  => Yii::$app->common->getBranch(),
                        'class_id'      => $class_id,
                        'group_id'      => $group_id,
                        'section_id'    => $section_id,
                    ]);
                    $countQuery = clone $query;

                    $pages = new \yii\data\Pagination(['totalCount' => $countQuery->count()]);
                    $dataProvider = new ActiveDataProvider([
                        'query' => $query,
                        'sort' => [
                            'defaultOrder' => [
                                // 'stu_id' => SORT_DESC
                                    'roll_no'       =>SORT_ASC
                                
                            ]
                        ]
                    ]);

                    return $this->renderAjax('getStudents',
                        [
                            'dataProvider' => $dataProvider,
                            'pages' => $pages,
                        ]);
                }
            }
        }
    }
    /*student fee deails*/
    public function actionGenerateStudentFee(){
        //$diff= 1;
         $diff=yii::$app->request->post('diff');
        $data=Yii::$app->request->post();

        $student_id= $data['stu_id'];
        $class_id = $data['classid'];
        $group_id = $data['groupid'];
        $section_id = $data['sectionid'];
        $parent_cnic = '';
        $cnic_count = 0;
        if(empty($data['Fromdate'])){
            $Fromdate = date('Y-m-01');
        }else{
            $Fromdate = $data['Fromdate'];
        } 
        $studentParentInfo =  \app\models\StudentParentsInfo::find()->where(['stu_id'=>$student_id])->one(); 
        if(count($studentParentInfo)>0 && !empty($studentParentInfo->cnic)){
            $parent_cnic = $studentParentInfo->cnic;
            $cnic_count =  \app\models\StudentParentsInfo::find()->where(['cnic'=>$studentParentInfo->cnic])->count();
        } 

        if(empty($data['toDate'])){
         $toDate = date('Y-m-t');
        }else{
         $toDate = $data['toDate'];
        }
        $getFeeDetails = \app\models\FeeGroup::find()
        ->where([
         'fk_branch_id'  =>Yii::$app->common->getBranch(),
         'fk_class_id'   => $class_id,
         'fk_group_id'   => ($group_id)?$group_id:null,
         ])->all();
        if(count($getFeeDetails) > 0){ 
    $viewFeedetails=$this->renderAjax('students-fee',['getFeeDetails'=>$getFeeDetails,'student_id'=>$student_id,'class_id'=>$class_id,'group_id'=>$group_id,'section_id'=>$section_id,'diff'=>$diff,'Fromdate'=>$Fromdate,'toDate'=>$toDate,
        'cnic_count'=>$cnic_count,
        'parent_cnic'=>$parent_cnic
        ]);
    }else{
      $viewFeedetails='<div class="row col-md-6 alert-warning">No Fee details found</div>';
    }
    return json_encode(['html'=>$viewFeedetails]);
    } // end of function

    /*=========================
        single fee slip generation
    ===========================*/
    public function actionGenerateSingleFeePdf(){
        $SerializeData = array();
        $hostel_amount = 0;
        $transport_amount = 0;
        $absent_fine = 0;
        $transport_arrears = 0;
        $hostel_arrears = 0;
        $one_time_head_arear_details = [];


        parse_str($_POST['formsearilze'], $SerializeData);
        //echo "<pre>";print_r($SerializeData);exit;
        $diff = 0;
        if(isset($SerializeData['StudentDisount'])){
            if(isset($SerializeData['StudentDisount']['input_total_transport_fare'])){
                $transport_amount = $SerializeData['StudentDisount']['input_total_transport_fare'];
                if($SerializeData['transaction_transport_arrears_amount'] != null){
                    $transport_arrears = $SerializeData['transaction_transport_arrears_amount'];
                }
            }
            if(isset($SerializeData['StudentDisount']['input_total_hostel_fare'])){
                $hostel_amount = $SerializeData['StudentDisount']['input_total_hostel_fare'];
                $hostel_arrears = $SerializeData['transaction_hostel_arrears_amount'];
                if($SerializeData['transaction_hostel_arrears_amount'] != null){
                    $hostel_arrears = $SerializeData['transaction_hostel_arrears_amount'];
                }
            }
        }
        if(isset($SerializeData['StudentFine'])){
            if(isset($SerializeData['StudentFine']['absendFine'])){
                $absent_fine =$SerializeData['StudentFine']['absendFine'];
            }
        }
        $diff=$SerializeData['diff'];
        $data=Yii::$app->request->post();
        $student_id= $SerializeData['stu_id'];
        $class_id = $data['classid'];
        $group_id = $data['groupid'];
        $fee_head_id = (isset($SerializeData['fee_head_id']))?$SerializeData['fee_head_id']:null;
        $Fromdate = $SerializeData['Fromdate'];
        $toDate = $SerializeData['toDate'];
        $transaction_head_amount = $SerializeData['transaction_head_amount'];
        $transaction_head_arrears_amount = (isset($SerializeData['transaction_head_arrears_amount']))?$SerializeData['transaction_head_arrears_amount']:null;
        $total_arrears_amount = (isset($SerializeData['FeeTransactionDetails']['total_arrears_amount']))?$SerializeData['FeeTransactionDetails']['total_arrears_amount']:null;
        $siblings_details=0;

        if(isset($SerializeData['sibling_discount'])){
                $siblings_details = $SerializeData['sibling_discount'];
            }
            /*get one time arrears details if exisist*/
            //$one_time_head_arear_details = [];
            $query_get_ontime_arears  = FeeArears::find()->select(['sum(fee_arears.arears) as arears','fh.title as title'])
            ->leftJoin('fee_head as fh', 'fh.id=fee_arears.fee_head_id')
             ->where([
                 'fee_arears.status'=>1,
                 'fee_arears.stu_id'=>$student_id/*,
                 'fh.one_time_payment'   => 1*/
             ])->asArray()->all();
             /*if exisist*/
             if(count($query_get_ontime_arears)>0){
                foreach ($query_get_ontime_arears as $othad_key => $one_time_head_arear_detail) {
                     $query_get_ontime_arears[$othad_key]['title'] = $one_time_head_arear_detail['title'];
                     $query_get_ontime_arears[$othad_key]['arears'] = $one_time_head_arear_detail['arears'];
                 }
             }
             /* end of get one time arrears details if exisist*/
            
             
              

   // echo $viewFeedetails;die; 
            return $this->redirect(['fee-slip-single',
                'student_id'             => $student_id,
                'class_id'               => $class_id,
                'group_id'               => $group_id,
                'diff'                   => $diff,
                'Fromdate'               => $Fromdate,
                'toDate'                 => $toDate,
                'fee_head_id'            => $fee_head_id,
                'transaction_head_amount' => $transaction_head_amount,
                'transaction_head_arrears_amount'  => $transaction_head_arrears_amount,
                'total_arrears_amount'  => $total_arrears_amount,
                'siblings_details'      => $siblings_details,
                'transport_amount'      => $transport_amount,
                'hostel_amount'         => $hostel_amount,
                'transport_arrears'     => $transport_arrears,
                'hostel_arrears'        => $hostel_arrears,
                'absent_fine'           => $absent_fine,
                'ontime_arears'         => $query_get_ontime_arears,
            ]);
         //$this->redirect(['fee-slip-single']);

       
      
   
    }// end of function

    /*redirect function of fee slip */
    public function actionFeeSlipSingle(){
        $data = Yii::$app->request->get();
        $transaction_head_amount=$data['transaction_head_amount'];
        if($transaction_head_amount){
            $transaction_head_amount=$transaction_head_amount;
        }else{
            $transaction_head_amount[]='';
        } 
        $viewFeedetails=$this->renderAjax('report/single-fee-slip',['student_id'=>$data['student_id'],'class_id'=>$data['class_id'],'group_id'=>$data['group_id'],'Fromdate'=>$data['Fromdate'],'toDate'=>$data['toDate'],'fee_head_id'=>(isset($data['fee_head_id']))?$data['fee_head_id']:null,'transaction_head_amount'=>$transaction_head_amount,'transaction_head_arrears_amount'=>(isset($data['transaction_head_arrears_amount']))?$data['transaction_head_arrears_amount']:null,'total_arrears_amount'=>(isset($data['total_arrears_amount']))?$data['total_arrears_amount']:null,'siblings_details'=>$data['siblings_details'],'transport_amount'=>$data['transport_amount'],
            'hostel_amount'=>$data['hostel_amount'],
            'absent_fine'=>$data['absent_fine'],
            'transport_arrears'=>$data['transport_arrears'],
            'hostel_arrears'=>$data['hostel_arrears'],
            'diff'=>$data['diff'],'ontime_arears'=>(isset($data['ontime_arears'])?$data['ontime_arears']:null)
        ]);
        /*$viewFeedetails=$this->renderAjax('report/single-fee-slip',['student_id'=>$data['student_id'],'class_id'=>$data['class_id'],'group_id'=>$data['group_id'],'Fromdate'=>$data['Fromdate'],'toDate'=>$data['toDate'],'fee_head_id'=>(isset($data['fee_head_id']))?$data['fee_head_id']:null,'transaction_head_amount'=>(isset($data['transaction_head_amount']))?$data['transaction_head_amount']:null,'transaction_head_arrears_amount'=>(isset($data['transaction_head_arrears_amount']))?$data['transaction_head_arrears_amount']:null,'total_arrears_amount'=>(isset($data['total_arrears_amount']))?$data['total_arrears_amount']:null,'siblings_details'=>$data['siblings_details'],'transport_amount'=>$data['transport_amount'],
            'hostel_amount'=>$data['hostel_amount'],
            'absent_fine'=>$data['absent_fine'],
            'transport_arrears'=>$data['transport_arrears'],
            'hostel_arrears'=>$data['hostel_arrears'],
            'diff'=>$data['diff'],
        ]);*/ 
       $this->layout = 'pdf';
       $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
       $mpdf->AddPage();
       $mpdf->WriteHTML($viewFeedetails);
       $mpdf->Output('Student-fee-slip.pdf', 'D');
    }
    /*end redirect function of fee slip */

    public function actionFeeSubmit(){
        
         $data=yii::$app->request->post();
         $class_id = $data['class-id'];
         $group_id = $data['group-id'];
         $section_id = $data['section-id'];

         $feeHeadId= (isset($data['fee_head_id']))?$data['fee_head_id']:null;
         $headamount= (isset($data['transaction_head_amount']))?$data['transaction_head_amount']:null;
        // $headamount= ($data['transaction_head_amount'])?$data['transaction_head_amount']:0;
         if($headamount){
            $headamount=$headamount;
        }else{
            $headamount[]=0;
        }
         $arrears= (isset($data['transaction_head_arrears_amount']))?$data['transaction_head_arrears_amount']:null;
         $studentFine = $data['StudentFine'];
         //following array contains total hostel/transport amount
         $studentDiscount = $data['StudentDisount'];
 
         $fromdate=$data['Fromdate'];
         $todate=$data['toDate'];
         $fromdateCnvrt=date('Y-m',strtotime($fromdate));
         $todateCnvrt=date('Y-m',strtotime($todate));
         $callMonth=yii::$app->common->getMonthYearInterval($fromdate,$todate);
         $custom_ext_head_arr=[];
         $absendFine_amount =0;
         $transport_amount =0;
         $hostel_amount =0;
         //echo $callMonth;die;
         
         //$actualHeadAmount= $data['actualHeadAmount'];
         if(count($feeHeadId)>0){
            foreach ($feeHeadId as $key => $feeHead_id) {
               /*$feeHeadQuery=FeeHead::find()->where(['id'=>$feeHead_id,'one_time_payment'=>1])->count();
               if($feeHeadQuery == 0){*/
                $update_fee_arears_rec     = "UPDATE  fee_arears  SET status = 0 WHERE branch_id = ".Yii::$app->common->getBranch()." and fee_head_id =".$feeHead_id." and stu_id =".$data['stu_id'];
                    \Yii::$app->db->createCommand($update_fee_arears_rec)->execute();

               //}

            }
         }
         $update_fee_submission_rec     = "UPDATE fee_submission  SET fee_status = 0 WHERE branch_id = ".Yii::$app->common->getBranch()." and stu_id =".$data['stu_id'];
        /*inactive prevous arrears*/
        /*$update_fee_arears_rec     = "UPDATE  fee_arears  SET status = 0 WHERE branch_id = ".Yii::$app->common->getBranch()." and stu_id =".$data['stu_id'];

        \Yii::$app->db->createCommand($update_fee_arears_rec)->execute();*/

        \Yii::$app->db->createCommand($update_fee_submission_rec)->execute();

        /*hoste/transport/absent fine*/
        /*absent*/
            if(isset($studentFine)){
                if(isset($studentFine['absendFine'])){
                    $absendFine_amount=$studentFine['absendFine'];
                }
            }
            /*hostel/transport fare*/
            if(isset($studentDiscount)){
                /*transport*/
                if(isset($studentDiscount['input_total_transport_fare'])){
                    $transport_amount=$studentDiscount['input_total_transport_fare'];
                }
                /*hostel*/
                if(isset($studentDiscount['input_total_hostel_fare'])){
                    $hostel_amount = $studentDiscount['input_total_hostel_fare'];
                }
                 
            } 
        $i=1;
        $taskcomobine='';
        foreach ($headamount as $key => $transection_amount) {
            $model=new FeeSubmission();
            $model->stu_id=$data['stu_id'];
            $model->from_date=$fromdateCnvrt;
            $model->to_date=$todateCnvrt;
            $model->fee_status=1;
            $model->branch_id=yii::$app->common->getBranch();
            $model->fee_head_id=$key;
            $model->head_recv_amount=$transection_amount;
            if($i<=1){
                $model->absent_fine=$absendFine_amount;
                $model->transport_amount = $transport_amount;
                $model->hostel_amount    = $hostel_amount;
            
                /*sibling disount*/
                if(isset($data['sibling_discount'])){
                    if(isset($data['sibling_discount'][$key])){
                        $model->sibling_discount=$data['sibling_discount'][$key];
                    }
                }
                if(!empty($data['transaction_transport_arrears_amount'])){
    
                    $model->transport_arrears=$data['transaction_transport_arrears_amount'];
                }
                if(!empty($data['transaction_hostel_arrears_amount'])){
                    $model->hostel_arrears=$data['transaction_hostel_arrears_amount'];
                }
            }
            $model->year_month_interval=$callMonth;
            $model->recv_date=date('Y-m-d');  
            
            //$model->head_arrears=$headamount[$key]-
             /*save arrears*/
            if(count($arrears)>0){
                if(isset($arrears[$key]) && $arrears[$key] > 0){
                    $model_arrears=new FeeArears();
                    $model_arrears->stu_id = $data['stu_id'];
                    $model_arrears->branch_id = yii::$app->common->getBranch();
                    $model_arrears->fee_head_id = $key;
                    $model_arrears->arears = $arrears[$key];
                    $model_arrears->date = date('Y-m-d');
                    $model_arrears->from_date = $fromdate;
                    $model_arrears->status = 1;
                    if($model_arrears->save()){}else{print_r($model_arrears->getErrors());die;}
                }
            }
            if(!$model->save()){ 
                print_r($model->getErrors());die;
            }
            /*code for sms*/
                $feeHeadName=FeeHead::find()->where(['id'=>$key])->one();
                $feeTitle=(!empty($feeHeadName->title)?strtoupper($feeHeadName->title):null);
                $studentParentsInfo= \app\models\StudentParentsInfo::find()->where(['stu_id'=>$data['stu_id']])->one();
                $contact=$studentParentsInfo->contact_no;
                $taskcomobine.=$feeTitle .' : Rs. '.$transection_amount.', \n';
                $contactArray[$contact]=$taskcomobine;
                $student_details = Yii::$app->common->getStudent($data['stu_id']);
                $studentName=Yii::$app->common->getName($student_details->user_id);
                /*code for sms ends*/
            $i++;
        }
            /*code for sms second part*/
            $settings = Yii::$app->common->getBranchSettings();
            $feeOnOff = $settings->fee_sms_on_off;
            if($feeOnOff == 1){
            $smsActive=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
             if($smsActive->status == 1){
                $sums=0;
            foreach ($contactArray as $key => $value) {
                if($transport_amount != 0){
                   $t_amount='Transport : Rs. '.$transport_amount; 
                }else{
                    $t_amount='';
                }
                $displayClassName='Fee submission details of: '.$studentName.'\n'.$value.''.$t_amount;
                $send=Yii::$app->common->SendSms($key,$displayClassName,$data['stu_id']);
            }
        }
        }
            /*code for sms second part ends*/
         /*activate student.*/
                $student= StudentInfo::findOne($data['stu_id']);
                $student_details = Yii::$app->common->getStudent($data['stu_id']);
                if($student->is_active == 0){
                    $user = User::findOne($student_details->user_id);
                    if($user->status == 'inactive'){
                        $user->status = 'active';
                        $user->save();
                    }
                    $student->is_active = 1;
                    $student->save();
                }
             $this->redirect(['fee-submission','c_id'=>$class_id,'g_id'=>$group_id,'s_id'=>$section_id]);
             Yii::$app->session->setFlash('success', "Fee Submitted Successfully");
       
    }
    /*end of student fee deails*/
    /*fee submission*/
     public function actionFeeSubmissionA(){
         if (Yii::$app->user->isGuest) {
            $this->redirect(['site/login']);
        }
        else {
            $model = new StudentInfo();
            return $this->render('admission/fee-submissionA', [
                'model' => $model,
            ]);
        }
    }
    public function actionOldadmission(){
      $class_id=Yii::$app->request->post('class_id');
      $fee_head=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'extra_head'=>0,'one_time_payment'=>1,'promotion_head'=>0])->one();
      $query = StudentInfo::find()->where([
                        'fk_branch_id'  => Yii::$app->common->getBranch(),
                        'class_id'      => $class_id
                    ])->all();
     
     $details= $this->renderAjax('admission/get-students',['query'=>$query,'fee_head'=>$fee_head]);
     return json_encode(['status'=>1 ,'details'=>$details]);
    }
    public function actionFeeSubmission(){
         if (Yii::$app->user->isGuest) {
            $this->redirect(['site/login']);
        }
        else {
            $model = new StudentInfo();
            return $this->render('fee-submission', [
                'model' => $model,
            ]);
        }
    }
    // current month fee not recv
    public function actionMonth(){
         $model = new StudentInfo();
         $class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title');
       return $this->render('report/current_month_fee', [
                'model' => $model,
                'class_array' => $class_array,
            ]);
    }
    public function actionMonthBulkReport(){
        error_reporting(0);
        $data= \Yii::$app->request->post('StudentInfo');
       // echo '<pre>';print_r($_POST);die;
         $class_id = $data['class_id'];
         $group_id = $data['group_id'];
         $section_id = (isset($data['section_id']) && !empty($data['section_id']))?$data['section_id']:Null;
         //$Fromdate = '2024-02-01'; //Y-m-d
         //$toDate = '2024-02-28';
         $Fromdate = date('Y-m-01');
         $toDate = date('Y-m-t');
         $cnic_count=0;
         $parent_cnic = '';
         $diff = Yii::$app->common->getMonthIntervalBulk($Fromdate,$toDate) ;
         $ex_head_id =  Yii::$app->request->post('head-id');
         $ex_head_amount =  Yii::$app->request->post('head-amount');
         /*echo "<pre>";
         print_r($ex_head_amount);exit;*/
         $studentArray = \app\models\StudentInfo::find()
             ->select(['student_info.stu_id','student_info.fk_branch_id','student_info.user_id','student_parents_info.cnic'])
             ->leftJoin('student_parents_info', 'student_parents_info.stu_id=student_info.stu_id')
             ->where([
                 'student_info.fk_branch_id'=>yii::$app->common->getBranch(),
                 'student_info.class_id'   => $class_id,
                 'student_info.is_active'   => 1,
             ]);
         if(isset($group_id) && $group_id != NULL){
             $studentArray->andWhere(['student_info.group_id'   => ($group_id)?$group_id:null]);
         }if(isset($section_id) && ($section_id !='Loading ...')){
             $studentArray->andWhere(['student_info.section_id' => $section_id]);
         }
         $students = $studentArray->asArray()->All();
         /*per student slip*/
         if(count($students)>0){
             //$this->layout = 'pdf';
             //$mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
             $getFeeDetails = \app\models\FeeGroup::find()
                 ->where([
                     'fk_branch_id'  =>Yii::$app->common->getBranch(),
                     'fk_class_id'   => $class_id,
                 ]);
             if(isset($group_id) && $group_id != NULL) {
                 $getFeeDetails->andWhere(['fk_group_id' => ($group_id) ? $group_id : null]);
             }
             $totalFeeDetails = $getFeeDetails->all();
             /*echo "<pre>";
         print_r($totalFeeDetails);exit;*/
             $explode_interval=[];
            foreach ($students as $keystd =>$student_id){
                $studentParentInfo =  \app\models\StudentParentsInfo::find()->where(['stu_id'=>$student_id])->one(); 
                /*check student siblings.*/
                if(count($studentParentInfo)>0 && !empty($studentParentInfo->cnic)){
                    $parent_cnic = $studentParentInfo->cnic;
                    $cnic_count =  \app\models\StudentParentsInfo::find()->where(['cnic'=>$studentParentInfo->cnic])->count(); 
                } 
                foreach ($totalFeeDetails as $key => $getFeeDetails) {
                     $fromConvert = date('Y-m', strtotime($Fromdate));
                     $toConvert = date('Y-m', strtotime($toDate));
                     $getDateFeesCount = \app\models\FeeSubmission::find()->select(['year_month_interval'])->where(['stu_id' => intval($student_id['stu_id']), 'branch_id' => yii::$app->common->getBranch(), 'fee_head_id' => $getFeeDetails->fk_fee_head_id])->asArray()->all();
                     /*concatination of previous fee months record*/
                     $interval_concat = '';
                    foreach ($getDateFeesCount as $gdfc => $month_year_int) {
                         $gdfc = $gdfc + 1;
                        // echo $gdfc;
                         $explode_interval[$student_id['stu_id']][]=  $month_year_int['year_month_interval'];

                    }
                }
                if($explode_interval[$student_id['stu_id']] !=null){
                    $main_collectionToimplode =implode(',', $explode_interval[$student_id['stu_id']]);
                    $main_array = explode(',', $main_collectionToimplode);
                     if (!in_array($fromConvert, $main_array) && !in_array($toConvert, $main_array)) {

                         $viewFeedetails = $this->renderAjax('report/currentmonth-fee-slip-pdf',[
                             'getFeesDetails'=> $totalFeeDetails,
                             'student_id'=>$student_id['stu_id'],
                             'group_id'=>$group_id,
                             'class_id'=>$class_id,
                             'section_id'=>$section_id,
                             'toDate'=>$toDate,
                             'Fromdate'=>$Fromdate,
                             'diff'=>$diff,
                             'cnic_count'=>$cnic_count,
                             'parent_cnic'=>$parent_cnic,
                             'extra_head_ids'=>(count($ex_head_id)>0)?$ex_head_id:0,
                             'ex_head_amount'=>$ex_head_amount,
                         ]);
                        // $mpdf->AddPage();
                         //$mpdf->WriteHTML($viewFeedetails);
                     } else {
                         continue;
                     }
                }else{
                     $viewFeedetails = $this->renderAjax('report/currentmonth-fee-slip-pdf',[
                         'getFeesDetails'=> $totalFeeDetails,
                         'student_id'=>$student_id['stu_id'],
                         'group_id'=>$group_id,
                         'class_id'=>$class_id,
                         'section_id'=>$section_id,
                         'toDate'=>$toDate,
                         'Fromdate'=>$Fromdate,
                         'diff'=>$diff,
                         'cnic_count'=>$cnic_count,
                         'parent_cnic'=>$parent_cnic,
                         'extra_head_ids'=>(count($ex_head_id)>0)?$ex_head_id:0,
                         'ex_head_amount'=>$ex_head_amount,
                     ]);
                     //$mpdf->AddPage();
                     //$mpdf->WriteHTML($viewFeedetails);
                } 
                echo $viewFeedetails; 
            } // end of foreach

            echo "<h3><br><br><br><br>Grand Total: <span id='grandTotal'></span></h3>";
            echo '<script>
            var sum = 0;
            $(".nettotal").each(function() {
                sum += Number($(this).val());
            });
            $("#grandTotal").text(sum);
            </script></h3>';
            
            die;  
             $mpdf->Output('bulk-fee-slip-'.date('Y-m-d H:i:s').'.pdf', 'D');
             return $this->redirect('generate-fee-challan');

         }else{
             Yii::$app->session->setFlash('warning',"There's no active students in this class at the moment.");
             return $this->redirect('generate-fee-challan');
         }

    } //end of function
    // current month fee not recv end
    /*end of fee submission*/
     public function actionGenerateBulk(){
        $data= \Yii::$app->request->post('StudentInfo');
         $class_id = $data['class_id'];
         $group_id = $data['group_id'];
         $section_id = (isset($data['section_id']) && !empty($data['section_id']))?$data['section_id']:Null;
         $Fromdate = $data['Fromdate'];
         $toDate = $data['toDate'];
         
         $cnic_count=0;
         $parent_cnic = '';
         $diff = Yii::$app->common->getMonthIntervalBulk($Fromdate,$toDate) ;


         $ex_head_id =  Yii::$app->request->post('head-id');
         $ex_head_amount =  Yii::$app->request->post('head-amount');
         /*echo "<pre>";
         print_r($ex_head_amount);exit;*/

         $studentArray = \app\models\StudentInfo::find()
             ->select(['student_info.stu_id','student_info.fk_branch_id','student_info.user_id','student_parents_info.cnic'])
             ->leftJoin('student_parents_info', 'student_parents_info.stu_id=student_info.stu_id')
             ->where([
                 'student_info.fk_branch_id'=>yii::$app->common->getBranch(),
                 'student_info.class_id'   => $class_id,
                 'student_info.is_active'   => 1,
             ]);
         if(isset($group_id) && $group_id != NULL){
             $studentArray->andWhere(['student_info.group_id'   => ($group_id)?$group_id:null]);
         }if(isset($section_id) && ($section_id !='Loading ...')){
             $studentArray->andWhere(['student_info.section_id' => $section_id]);
         }

         $students = $studentArray->asArray()->All();

         /*per student slip*/
         if(count($students)>0){
             $this->layout = 'pdf';
             //$mpdf = new mPDF('', 'A4');
             $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
             $getFeeDetails = \app\models\FeeGroup::find()
                 ->where([
                     'fk_branch_id'  =>Yii::$app->common->getBranch(),
                     'fk_class_id'   => $class_id,
                 ]);
             if(isset($group_id) && $group_id != NULL) {
                 $getFeeDetails->andWhere(['fk_group_id' => ($group_id) ? $group_id : null]);
             }
             $totalFeeDetails = $getFeeDetails->all();
             /*echo "<pre>";
         print_r($totalFeeDetails);exit;*/
             $explode_interval=[];
            foreach ($students as $keystd =>$student_id){
                /*if all head is in full disount then skip that students code new*/
                /*foreach ($totalFeeDetails as $key => $getFeeHeadOnly) {
                $getHeadAmount = \app\models\FeeGroup::find()->where(['fk_fee_head_id' => $getFeeHeadOnly->fk_fee_head_id])->one();

                $discountDetails=\app\models\FeePlan::find()->where(['stu_id'=>$student_id,'fee_head_id'=>$getFeeHeadOnly->fk_fee_head_id,'status'=>1,'discount'=>$getHeadAmount->amount])->one();
                
                }*/
                /*if($student_id['stu_id'] == $discountDetails->stu_id){
                    continue;
                    }*/
          
                /*if all head is in full disount then skip that students code new ends*/
                    //fee challan will generate here
                $studentParentInfo =  \app\models\StudentParentsInfo::find()->where(['stu_id'=>$student_id])->one(); 
                /*check student siblings.*/
                if(count($studentParentInfo)>0 && !empty($studentParentInfo->cnic)){
                    $parent_cnic = $studentParentInfo->cnic;
                    $cnic_count =  \app\models\StudentParentsInfo::find()->where(['cnic'=>$studentParentInfo->cnic])->count(); 
                } 
                foreach ($totalFeeDetails as $key => $getFeeDetails) {
                     $fromConvert = date('Y-m', strtotime($Fromdate));
                     $toConvert = date('Y-m', strtotime($toDate));
                     $getDateFeesCount = \app\models\FeeSubmission::find()->select(['year_month_interval'])->where(['stu_id' => intval($student_id['stu_id']), 'branch_id' => yii::$app->common->getBranch(), 'fee_head_id' => $getFeeDetails->fk_fee_head_id])->asArray()->all();


                     /*concatination of previous fee months record*/
                     $interval_concat = '';
                    foreach ($getDateFeesCount as $gdfc => $month_year_int) {
                         $gdfc = $gdfc + 1;
                        // echo $gdfc;
                         $explode_interval[$student_id['stu_id']][]=  $month_year_int['year_month_interval'];

                    }

                }
                
                
                if($explode_interval[$student_id['stu_id']] !=null){
                    $main_collectionToimplode =implode(',', $explode_interval[$student_id['stu_id']]);
                    $main_array = explode(',', $main_collectionToimplode);
                     if (!in_array($fromConvert, $main_array) && !in_array($toConvert, $main_array)) {

                         $viewFeedetails = $this->renderAjax('report/bulk-fee-slip-pdf',[
                             'getFeesDetails'=> $totalFeeDetails,
                             'student_id'=>$student_id['stu_id'],
                             'group_id'=>$group_id,
                             'class_id'=>$class_id,
                             'section_id'=>$section_id,
                             'toDate'=>$toDate,
                             'Fromdate'=>$Fromdate,
                             'diff'=>$diff,
                             'cnic_count'=>$cnic_count,
                             'parent_cnic'=>$parent_cnic,
                             'extra_head_ids'=>(count($ex_head_id)>0)?$ex_head_id:0,
                             'ex_head_amount'=>$ex_head_amount,
                         ]);
                         $mpdf->AddPage();
                         $mpdf->WriteHTML($viewFeedetails);
                     } else {
                         continue;
                     }
                }else{
                     $viewFeedetails = $this->renderAjax('report/bulk-fee-slip-pdf',[
                         'getFeesDetails'=> $totalFeeDetails,
                         'student_id'=>$student_id['stu_id'],
                         'group_id'=>$group_id,
                         'class_id'=>$class_id,
                         'section_id'=>$section_id,
                         'toDate'=>$toDate,
                         'Fromdate'=>$Fromdate,
                         'diff'=>$diff,
                         'cnic_count'=>$cnic_count,
                         'parent_cnic'=>$parent_cnic,
                         'extra_head_ids'=>(count($ex_head_id)>0)?$ex_head_id:0,
                         'ex_head_amount'=>$ex_head_amount,
                     ]);
                     $mpdf->AddPage();
                     $mpdf->WriteHTML($viewFeedetails);
                } 
                //echo $viewFeedetails; 
            } // end of foreach 
            //die;  
             $mpdf->Output('bulk-fee-slip-'.date('Y-m-d H:i:s').'.pdf', 'D');
             return $this->redirect('generate-fee-challan');

         }else{
             Yii::$app->session->setFlash('warning',"There's no active students in this class at the moment.");
             return $this->redirect('generate-fee-challan');
         }

    } //end of function
    /*============= generate bulk fee slip*/
    /*=========== extra head submission*/
    public function actionSubmitExtraHead(){
        $model=new FeeSubmission();
        $data=yii::$app->request->post();
        $stu_id=$data['stu_id'];
        $fee_head_id=$data['fee_head_id'];
        $amount=$data['amount'];
        $model->stu_id=$stu_id;
        $model->fee_head_id=$fee_head_id;
        $model->head_recv_amount=$amount;
        $model->from_date=date('Y-m');
        $model->to_date=date('Y-m');
        $model->year_month_interval=date('Y-m');
        $model->recv_date=date('Y-m-d');
        $model->fee_status=1;
        $model->branch_id=yii::$app->common->getBranch();
        $existDay=FeeSubmission::find()->where(['fee_head_id'=>$fee_head_id,'recv_date'=>date('Y-m-d'),'stu_id'=>$stu_id])->count();
        if($existDay >0){
            return json_encode(['extraheadFee'=>0,'msg'=>'Fee already taken for today.']);
        }
        if($model->save()){
            return json_encode(['extraheadFee'=>1]);
        }else{
            return json_encode(['extraheadFee'=>0,'msg'=>'Oops..! Something went wrong.']);
        }
    }
    /*=========== end of extra head submission*/

    /*==current month arrears move to next student who not submitted current month fee*/
    public function actionArrearsMove(){
         if (Yii::$app->user->isGuest) {
            $this->redirect(['site/login']);
        }
        else {
            $model = new StudentInfo();
            return $this->render('arrears-move', [
                'model' => $model,
            ]);
        }
    } // end of action
    public function actionGetCurrentMonthNotsubmitedFee(){
        $data=Yii::$app->request->post();
        $class_id  = $data['class_id'];
        $group_id  = $data['group_id'];
        $section_id= $data['section_id'];
        $date=date('Y-m');
            $studentFeeDetails=StudentInfo::find()
            ->select(['student_info.stu_id','student_info.roll_no','student_info.user_id','student_info.avail_sibling_discount',
            'fee_submission.fee_head_id','fee_submission.fee_status','fee_submission.transport_arrears','fee_submission.transport_amount','fee_submission.transport_arrears','fee_submission.head_recv_amount','fee_submission.year_month_interval'])
            ->leftJoin('fee_submission','fee_submission.stu_id=student_info.stu_id')
            ->where([
                'student_info.class_id'=>$class_id,
                'student_info.group_id'=>($group_id)?$group_id:null,
                'student_info.section_id'=>$section_id,
                'student_info.is_active'=>1,
            ])
            ->orderBy(['student_info.roll_no'=>SORT_ASC])
            ->asArray()->all();
            /*$studentFeeDetails=Yii::$app->db->createCommand("SELECT `s`.* from student_info `s` WHERE `class_id`=$class_id and section_id=$section_id and is_active=1 and not exists( select `fs`.stu_id from `fee_submission` `fs` where fs.stu_id=s.stu_id and FIND_IN_SET('$date',fs.year_month_interval)) order by roll_no asc")->queryAll();*/
       
        if(count($studentFeeDetails)>0){
        $view=$this->renderAjax('current-fee-not-submited.php',['class_id'=>$class_id,'group_id'=>$group_id,'section_id'=>$section_id,'studentFeeDetails'=>$studentFeeDetails]);
        }else{
        $view='<div class="alert alert-warning">No Details Found..!</div>';     
        }
        return json_encode(['status'=>1,'details'=>$view]);
    }
    public function actionGetCurrentMonthNotsubmitedFeeReport(){
        $data=Yii::$app->request->post();
        $class_id  = $data['class_id'];
        $group_id  = $data['group_id'];
        $section_id= $data['section_id'];
        $date=date('Y-m');
        if(empty($group_id)){
            $studentFeeDetails=Yii::$app->db->createCommand("SELECT `s`.* from student_info `s` WHERE `class_id`=$class_id and section_id=$section_id and is_active=1 and not exists( select `fs`.stu_id from `fee_submission` `fs` where fs.stu_id=s.stu_id and FIND_IN_SET('$date',fs.year_month_interval)) order by roll_no asc")->queryAll();
        }else{
            $studentFeeDetails=Yii::$app->db->createCommand("SELECT `s`.* from student_info `s` WHERE `class_id`=$class_id and group_id='".$group_id."' and section_id=$section_id and is_active=1 and not exists( select `fs`.stu_id from `fee_submission` `fs` where fs.stu_id=s.stu_id and FIND_IN_SET('$date',fs.year_month_interval)) order by roll_no asc")->queryAll();
        }
        if(count($studentFeeDetails)>0){
        $view=$this->renderAjax('current-fee-not-submited-report.php',['class_id'=>$class_id,'group_id'=>$group_id,'section_id'=>$section_id,'studentFeeDetails'=>$studentFeeDetails]);
        }else{
        $view='<div class="alert alert-warning">No Details Found..!</div>';     
        }
        return json_encode(['status'=>1,'details'=>$view]);
    }

    public function actionGetCurrentMonthNotsubmitedFeeReportPdf(){
        $data=Yii::$app->request->get();
        $class_id  = $data['class_id'];
        $group_id  = $data['group_id'];
        $section_id= $data['section_id'];
        $date=date('Y-m');
        if(empty($group_id)){
            $studentFeeDetails=Yii::$app->db->createCommand("SELECT `s`.* from student_info `s` WHERE `class_id`=$class_id and section_id=$section_id and is_active=1 and not exists( select `fs`.stu_id from `fee_submission` `fs` where fs.stu_id=s.stu_id and FIND_IN_SET('$date',fs.year_month_interval)) order by roll_no asc")->queryAll();
        }else{
            $studentFeeDetails=Yii::$app->db->createCommand("SELECT `s`.* from student_info `s` WHERE `class_id`=$class_id and group_id='".$group_id."' and section_id=$section_id and is_active=1 and not exists( select `fs`.stu_id from `fee_submission` `fs` where fs.stu_id=s.stu_id and FIND_IN_SET('$date',fs.year_month_interval)) order by roll_no asc")->queryAll();
        }
        
        $view=$this->renderAjax('report/current-fee-not-submited-report-pdf.php',['class_id'=>$class_id,'group_id'=>$group_id,'section_id'=>$section_id,'studentFeeDetails'=>$studentFeeDetails]);
        $this->layout = 'pdf';
       $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
       $mpdf->AddPage();
       $mpdf->WriteHTML($view);
       $mpdf->Output('fee.pdf', 'D');
    }
    public function actionMoveArrearsSubmit(){
       $data=Yii::$app->request->post('fee');
       $transportData=Yii::$app->request->post('transport');
 
        foreach ($data as $key => $value) {
            $stu_id=$key;
            foreach ($value as $key1 => $head) {
                $update_fee_arears_rec     = "UPDATE  fee_arears  SET status = 0 WHERE branch_id = ".Yii::$app->common->getBranch()." and fee_head_id =".$key1." and stu_id =".$stu_id;
                            \Yii::$app->db->createCommand($update_fee_arears_rec)->execute();
                if (empty($head)) {
                        continue;
                      }
                 $fee_head_id= $key1;
                 $head_amount=$head;
                 $model=new FeeArears();
                 $model->stu_id=$stu_id;
                 $model->fee_head_id=$fee_head_id;
                 $model->arears=$head_amount;
                 $model->date=date('Y-m-d');
                 $model->from_date=date('Y-m-d');
                 $model->status=1;
                 $model->branch_id=1;
                 if($model->save()){}else{
                    print_r($model->getErrors());die;
                 }
            }
        }
        // foreach for transport
        foreach($transportData as $studentId=> $transport){
        if (empty($transport)) {
            continue;
          }
        // $feeSubmissonOldData=FeeSubmission::find()->select('stu_id')->where(['stu_id'=>$studentId,'fee_status'=>1])->distinct()->one();
        $feeSubmissonOldData=FeeSubmission::find()->where(['stu_id'=>$studentId,'fee_status'=>1,'fee_head_id'=>$key1])->one();
        if(count($feeSubmissonOldData) > 0){

             $update_fee_submission_rec     = "UPDATE fee_submission  SET fee_status = 0 WHERE branch_id = ".Yii::$app->common->getBranch()." and stu_id =".$feeSubmissonOldData['stu_id'];

              \Yii::$app->db->createCommand($update_fee_submission_rec)->execute();
              //if($update_fee_submission_rec){
            $update_transport_arears= "UPDATE fee_submission SET transport_arrears =".$transport." , fee_status=1 where stu_id= ".$feeSubmissonOldData['stu_id']. " order by id Desc limit 1";
            \Yii::$app->db->createCommand($update_transport_arears)->execute();
              ///}

           /*$update_transport_arears= "UPDATE fee_submission AS t JOIN ( SELECT MIN(ID) MinID FROM fee_submission GROUP BY stu_id HAVING COUNT(*) > 1 ) AS m ON t.id = m.MinID SET t.transport_arrears =".$transport." t.fee_status=1 where and stu_id=".$feeSubmissonOldData['stu_id'];*/
          // echo $update_transport_arears;die;
          
        }else{
        $models=new FeeSubmission();
         $models->stu_id=$studentId;
         $models->fee_head_id=0;
         $models->head_recv_amount=0;
         $models->transport_amount=0;
         $models->transport_arrears=$transport;
         $models->hostel_arrears=0;
         $models->absent_fine=0;
         $models->from_date=date('Y-m');
         $models->to_date=date('Y-m');
         $models->year_month_interval=date('Y-m');
         $models->recv_date=date('Y-m-d');
         $models->fee_status=1;
         $models->branch_id=Yii::$app->common->getBranch();
         $models->save();
        }
         
            // $feeSubmissonOldData['transport_arrears'];
         
         //$update_transport_arears     = "UPDATE fee_submission SET transport_arrears = ".$transport." WHERE branch_id = ".Yii::$app->common->getBranch()." and fee_status=1 and stu_id =".$studentId;
        //  \Yii::$app->db->createCommand($update_transport_arears)->execute();
         // if($models->save()){}else{print_r($models->getErrors());die;}
        }
        Yii::$app->session->setFlash('success', "Move successfully to arrears.. do not try to collect fee of current month now");
        $this->redirect('arrears-move');
    }
    /*==current month arrears move to next student who not submitted current month fee ends*/
    public function actionPreviousDetailsStudent($id){
        if(Yii::$app->user->isGuest){
            return $this->goHome();
        }
        if(\Yii::$app->request->isAjax){
        $settings = Yii::$app->common->getBranchSettings();
        $stu_id=$id;
       return  $html = $this->renderAjax('report/previous-fee-report',[
                'student_id'=>$stu_id,'settings'=>$settings
            ]);
           // return json_encode(['data'=>$html]);
        }
        }
}
