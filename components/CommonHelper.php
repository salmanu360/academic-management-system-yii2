<?php
namespace app\components;
use app\models\SmsSettings;
use app\models\FeeDiscounts;
use app\models\FeeDiscountTypes;
use app\models\FeeParticulars;
use app\models\RefClass;
use app\models\search\FineType;
use app\models\Settings;
use app\models\DashboardSetting;
use app\models\SmsLog;
use Yii;
use yii\base\Component;
use app\models\search\FeeHeads;
use app\models\Branch;
use app\models\search\FeePlanType;
use app\models\RefSection;
use app\models\Session;
use app\models\RefGroup;
use app\models\User;
use app\models\StudentParentsInfo;
use yii\helpers\ArrayHelper;

class CommonHelper extends Component
{

    //=========================================================//
    //                   Get branch Id                        //
    //=========================================================//
    public static function getBranch()
    {
        $branch = Session::find()->where(['user_id'=>Yii::$app->user->id])->One();
        if($branch && Yii::$app->user->id !='')
        {
            return $branch->fk_branch_id;
        }
        else
        {
            return false;
        }

    }
    
     //=========================================================//
    //                   Get branch detail                        //
    //=========================================================//
     public static function getBranchDetail()
    {
        $branch = Branch::find()->where(['id'=>Yii::$app->common->getBranch()])->One();
        if($branch && Yii::$app->user->id !='')
        {
            return $branch;
        }
        else
        {
            return false;
        }

    }

    //=========================================================//
    //                    get group                            //
    //=========================================================//
    public static function getGroup($class_id) {
        $data=RefGroup::find()->select(['group_id as id','title as name'])->where(['fk_class_id'=>$class_id,'fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active']) ->asArray()->all();

        return $data;
    }
    public static function getClass($class_id) {
        $data=RefClass::find()->select(['class_id as id','title as name'])->where(['class_id'=>$class_id,'fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active']) ->asArray()->all();


        return $data;
    }

     //=========================================================//
    //                    get section                          //
    //=========================================================//
    public static function getSection($class_id,$group_id = null) {
        $data=RefSection::find()->select(['section_id as id','title as name'])->where(['class_id'=>$class_id,'fk_group_id'=>$group_id,'fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->asArray()->all();
        return $data;
    }

    //=========================================================//
    //                    get full name                        //
    //=========================================================//
        public static function getName($id)
    {
        $name = User::find()->where(['id'=>$id])->One();
        if($name)
        {
            $return_name="";
            if($name->first_name){
                $return_name .= strtoupper($name->first_name);
            }if($name->middle_name){
                $return_name .=  ' '. strtoupper($name->middle_name);
            }if($name->last_name){
                $return_name .= ' '.strtoupper($name->last_name);
            }
            return  $return_name;
        }
        else
        {
            return false;
        }

    }
     public static function getUserName($id)
    {
        $name = User::find()->where(['id'=>$id])->One();
        if($name)
        {
            $return_name="";
            if($name->username){
                $return_name .= strtoupper($name->username);
            }
            return  $return_name;
        }
        else
        {
            return false;
        }

    }

    //=========================================================//
    //                       get months                        //
    //=========================================================//

    public static function getMonthName($start_date,$end_date){
        $start    = (new \DateTime($start_date))->modify('first day of this month');
        $end      = (new \DateTime($end_date))->modify('first day of next month');
        $interval = \DateInterval::createFromDateString('1 month');
        $period   = new \DatePeriod($start, $interval, $end);
        $months = [];

        foreach ($period as $dt) {
            //echo $dt->format("Y-m") . "<br/><br/><br/>";
            if($dt->format("Y-m") != date('Y-m')){
                $months[] = $dt->format("F");
            }
        }
        return $months;
    }

    //=========================================================//
    //                   get parent full name                  //
    //=========================================================//
    public static function getParentName($id)
    {
        $name = StudentParentsInfo::find()->where(['stu_id'=>$id])->one();
        if($name)
        {
            $return_name="";
            if($name->first_name){
                $return_name .= strtoupper($name->first_name);
            }if($name->middle_name){
                $return_name .=  ' '. strtoupper($name->middle_name);
            }if($name->last_name){
                $return_name .= ' '.strtoupper($name->last_name);
            }
            return  $return_name;
        }
        else
        {
            return false;
        }

    }
    //=========================================================//
    //                  Get Student Detail                      //
    //=========================================================//

    public static  function getStudent($id){
        $student = \app\models\StudentInfo::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'stu_id'=>$id])->one();
        return $student;
    }

    public static  function getOneStudentDetails($id){
        $studentAlldetails = \app\models\User::find()
            ->select(['student_info.stu_id',"concat(user.first_name, ' ' ,  user.last_name) as name",'student_parents_info.contact_no as parentcontact'])
            ->innerJoin('student_info','student_info.user_id = user.id')
            ->innerJoin('student_parents_info','student_info.stu_id = student_parents_info.stu_id')
            ->where(['user.id'=>$id])->asArray()->one();
            //echo '<pre>';print_r($studentAlldetails);die;
        return $studentAlldetails;
    }
    //================get parent details//

    public static  function getParent($id){
        $parent = \app\models\StudentParentsInfo::find()->where(['stu_id'=>$id])->one();
        return $parent;
    }

     //=========================================================//
    //                  Get Student Detail by user id          //   //=========================================================//

    public static  function getStudentByUserId($id){
        $studentUser = \app\models\StudentInfo::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'user_id'=>$id])->one();
        return $studentUser;
    }
    
    public static  function getUserTable($id){
        $user_table = \app\models\User::find()->where(['id'=>$id])->one();
        return $user_table;
    }
    public static function getStudentName($student_id){
            /*get std details*/
        $student = Yii::$app->common->getStudent($student_id);
        $userInfo = Yii::$app->common->getUserTable($student->user_id);

        if($userInfo){
            $user_detail = $userInfo->first_name.' '.$userInfo->last_name;
            return $user_detail;
        } else{
            return 'N/A';
        }
    }


    //=========================================================//
    //                  Get Employee Detail                    //
    //=========================================================//
    public static  function getEmployee($id){
        $employee = \app\models\EmployeeInfo::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'emp_id'=>$id])->one();
        return $employee;
    }

    //=========================================================//
    //                  Trillium legends                      //
    //=========================================================//
     public static function getLegends($marks)
    {
        $grade = \app\models\ExamGrading::find()->where(['branch_id'=>Yii::$app->common->getBranch()])->All();
         $percent = [];
         foreach ($grade as $key => $percentage) {
            if ($marks >= $percentage->marks_obtain_from && $marks <= $percentage->marks_obtain_to){
            $percent[]= $percentage->grade;
            }
         }
         return $percent;
    }
    public static function getGrade($marks)
    {
        $grade = \app\models\ExamGrading::find()->where(['branch_id'=>Yii::$app->common->getBranch()])->All();
         $percent = [];
         foreach ($grade as $key => $percentage) {
            if ($marks >= $percentage->marks_obtain_from && $marks <= $percentage->marks_obtain_to){
            $percent[]= $percentage->grade_name;
            }
         }
         return $percent;
    } 

    //=========================================================//
    //           Get students Array helper branch wise.        //
    //=========================================================//
    public static function  getBranchStudents(){
        $stuQuery = \app\models\User::find()
            ->select(['student_info.stu_id',"concat(user.first_name, ' ' ,  user.last_name) as name"])
            ->innerJoin('student_info','student_info.user_id = user.id')
            ->where(['user.fk_branch_id'=>Yii::$app->common->getBranch(),'user.fk_role_id'=>3,'user.status'=>'active'])->asArray()->all();
        $stuArray = ArrayHelper::map($stuQuery,'stu_id','name');
        return $stuArray;
    }
    
    //=========================================================//
    //           Get employee Array helper branch wise.        //
    //=========================================================//
    public static function  getBranchEmployee(){
        $stuQuery = User::find()
            ->select(['employee_info.emp_id',"concat(user.first_name, ' ' ,  user.last_name) as name"])
            ->innerJoin('employee_info','employee_info.user_id = user.id')
            ->where(['user.fk_branch_id'=>Yii::$app->common->getBranch()])->asArray()->all();
        $stuArray = ArrayHelper::map($stuQuery,'emp_id','name');
        return $stuArray;
    }

    public static function  getLoginEmployee(){
        $stuQuery = User::find()
            ->select(['employee_info.emp_id',"concat(user.first_name, ' ' ,  user.last_name) as name"])
            ->innerJoin('employee_info','employee_info.user_id = user.id')
            ->where(['user.fk_branch_id'=>Yii::$app->common->getBranch(),'user.id'=>Yii::$app->user->id])->asArray()->all();
        $stuArray = ArrayHelper::map($stuQuery,'emp_id','name');
        return $stuArray;
    }


    //=========================================================//
    //           Get Fee Head Array helper branch wise.        //
    //=========================================================//
    public static function  getBranchFeeHead(){

        $feeHeadArray = ArrayHelper::map(FeeHeads::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch()])->all(),'id','title');
        return $feeHeadArray;
    }


    //=========================================================//
    //          Get Fee Plan Array helper branch wise.         //
    //=========================================================//

    public static function  getBranchFeePlan(){

        $feePlanArray = ArrayHelper::map(FeePlanType::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch()])->all(),'id','title');
        return $feePlanArray;
    }

    //=========================================================//
    //          Get Fine Type Array helper branch wise.        //
    //=========================================================//

    public static function  getBranchFineType(){

        $feeTypeArray = ArrayHelper::map(FineType::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch()])->all(),'id','title');
        return $feeTypeArray;
    }

    //=========================================================//
    //          Get Fee Discount Type Array helper branch wise.        //
    //=========================================================//

    public static function  getBranchDiscountType(){

        $feeTypeArray = ArrayHelper::map(FeeDiscountTypes::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch()])->all(),'id','title');
        return $feeTypeArray;
    }

    //=========================================================//
    //          Get Fee Discount Array helper branch wise.        //
    //=========================================================//

    public static function  getBranchFeeDiscounts(){
        $query= FeeDiscounts::find()
            ->select(['fee_discounts.id as id','fee_discount_types.title as name'])
            ->innerJoin('fee_discount_types','fee_discount_types.id = fee_discounts.fk_fee_discounts_type_id')
            ->where(['fee_discounts.fk_branch_id'=>Yii::$app->common->getBranch()])->asArray()->all();

        $feeDiscountArray = ArrayHelper::map($query,'id','name');
        return $feeDiscountArray;
    }


    //=========================================================//
    //    Get Fee Particulars Array helper branch wise.        //
    //=========================================================//

    public static function  getBranchFeeParticulars(){
        $feearticularQuery = \app\models\FeeParticulars::find()
            ->select(['fee_particulars.id',"concat(user.first_name, ' ' , user.last_name,'-',fee_plan_type.title,'-',fee_heads.title) as name "])
            ->innerJoin('student_info','student_info.stu_id = fee_particulars.fk_stu_id')
            ->innerJoin('user','user.id = student_info.user_id ')
            ->innerJoin('fee_heads','fee_heads.id = fee_particulars.fk_fee_head_id')
            ->innerJoin('fee_plan_type','fee_plan_type.id = fee_particulars.fk_fee_plan_type')
            ->where(['fee_particulars.fk_branch_id'=>Yii::$app->common->getBranch()])->asArray()->all();

        $feeparticualrArray = ArrayHelper::map($feearticularQuery,'id','name');

        return $feeparticualrArray;
    }

    //=========================================================//
    //    Get student find detail  branch wise.                //
    //=========================================================//

    public static function  getBranchStdFineDetail(){
        $stdFindDetailQuery = \app\models\StudentFineDetail::find()
            ->select(['student_fine_detail.id as id',"concat(fine_type.title, '-' , student_fine_detail.amount) as name"])
            ->innerJoin('fine_type','fine_type.id = student_fine_detail.fk_fine_typ_id')
            ->where(['student_fine_detail.fk_branch_id'=>Yii::$app->common->getBranch()])->asArray()->all();

        $stdfindDetailArray = ArrayHelper::map($stdFindDetailQuery,'id','name');

        return $stdfindDetailArray;
    }

    //=========================================================//
    //                  Get Branch Settings                    //
    //=========================================================//

    public static function getBranchSettings(){
        $settings = Settings::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch()])->One();
        return $settings;
    }
    public static function getDashboardSettings(){
        $dashobardSetting = DashboardSetting::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch()])->One();
        return $dashobardSetting;
    }
    
    //=========================================================//
    //                  send sms funtion                   //
    //=========================================================//
    
    public static function SendSms($mbl,$msg,$studentId){
        $message = urlencode('Alhuda Ouch: '.$msg);
        $smsSet=SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
        $smsModel=new SmsLog();
        $mask=$smsSet->mask;
 $url="https://sms.lrt.com.pk/api/sms-single-or-bulk-api.php?username=Kryptons&password=c42af05519d1230f1e5f75c87&apikey=899a23495cbee1821894813d2ad8b341&sender=8023&phone=".$mbl."&type=English&message=".$message;
      //   $url = "https://www.hajanaone.com/api/sendsms.php?apikey=DNK43XGFQ6aN&phone=".$mbl."&sender=".$mask."&message=".$message;
  
        $ch  =  curl_init();
            $timeout  =  30;
            curl_setopt ($ch,CURLOPT_URL, $url) ;
            curl_setopt ($ch,CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ch,CURLOPT_CONNECTTIMEOUT, $timeout) ;
            $response = curl_exec($ch) ;
            curl_close($ch);
       $smsModel->SMS_body=$msg;
       $useri=$smsModel->fk_user_id=$studentId;
       $smsModel->fk_branch_id=yii::$app->common->getBranch();
       $smsModel->sent_date_time=date("Y:m:d H:i:s"); 
       $smsModel->receiver_no=$mbl; 
       // echo $response; die;
       $smsModel->status=$response; 
            if ($smsModel->save()) {
               
        } else {
        print_r($smsModel->getErrors());  
        } 
    }

   public static function SendSmsSimple($mbl,$msg){
    ini_set('max_execution_time', 300);
    $smsSet=SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
        $message = urlencode('Alhuda Ouch: '.$msg);
        $smsModel=new SmsLog();
        $mask=$smsSet->mask;
       $url="https://sms.lrt.com.pk/api/sms-single-or-bulk-api.php?username=Kryptons&password=c42af05519d1230f1e5f75c87&apikey=899a23495cbee1821894813d2ad8b341&sender=8023&phone=".$mbl."&type=English&message=".$message;
        $ch  =  curl_init();
            $timeout  =  30;
            curl_setopt ($ch,CURLOPT_URL, $url) ;
            curl_setopt ($ch,CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ch,CURLOPT_CONNECTTIMEOUT, $timeout) ;
            $response = curl_exec($ch) ;
            curl_close($ch);
       $smsModel->SMS_body=$msg;
       // echo $response;die;
      // $useri=$smsModel->fk_user_id=$studentId;
       $smsModel->fk_branch_id=yii::$app->common->getBranch();
       $smsModel->sent_date_time=date("Y:m:d H:i:s"); 
       $smsModel->receiver_no=$mbl; 
       $smsModel->status=$response; 
            if ($smsModel->save()) {
               
        } else {
        print_r($smsModel->getErrors());  
        }
    }
    //=========================================================//
    //                  get month interval                    //
    //=========================================================//

    public static function getMonthInterval($start_date,$end_date){
        $start    = (new \DateTime($start_date))->modify('first day of this month');
        $end      = (new \DateTime($end_date))->modify('first day of next month');
        $interval = \DateInterval::createFromDateString('1 month');
        $period   = new \DatePeriod($start, $interval, $end);
        $counter= 0;
        foreach ($period as $dt) {
            //echo $dt->format("Y-m") . "<br/><br/><br/>";
            /*uncomment if you dont want to count current month*/
            if($dt->format("Y-m") != date('Y-m')){
                $counter++;
                //echo $dt->format("Y-m")."<br/>";
            }else{
                //echo $dt->format("Y-m")."<br/>";
            }

        }

        return $counter;
    }

    //=========================================================//
    //                  get month interval Bulk New fee        //
    //=========================================================//

    public static function getMonthIntervalBulk($start_date,$end_date){
        $start    = (new \DateTime($start_date))->modify('first day of this month');
        $end      = (new \DateTime($end_date))->modify('first day of next month');
        $interval = \DateInterval::createFromDateString('1 month');
        $period   = new \DatePeriod($start, $interval, $end);
        $counter= 0;
        foreach ($period as $dt) {
            $counter++;

        }

        return $counter;
    }
  /* get month and year interval*/
    public static function getMonthYearInterval($start_date,$end_date){
        $start    = (new \DateTime($start_date))->modify('first day of this month');
        $end      = (new \DateTime($end_date))->modify('first day of next month');
        $interval = \DateInterval::createFromDateString('1 month');
        $period   = new \DatePeriod($start, $interval, $end);
        $counter= ''; 
        foreach ($period as $dt) {
            //echo $dt->format("Y-m") . "<br/><br/><br/>";
           /* if($dt->format("Y-m") != date('Y-m')){ 
                $counter .= $dt->format("Y-m").',';
            }
            else{
                $counter .= $dt->format("Y-m");
            }*/
        $counter .= $dt->format("Y-m").',';
           
        }

        return rtrim($counter,','); 
    }
  /* end of get month and year interval*/


    /*get student class group section*/
    public static function getStudentCGSection($student_id){
            /*get std details*/
        $student = Yii::$app->common->getStudent($student_id);
        if($student){
            $CGS = $student->class->title;

            if($student->group_id){
                $CGS .= '-'.$student->group->title;
            }
            if($student->section_id){
                $CGS .= '-'.$student->section->title;
            }
            return $CGS;
        } else{
            return 'N/A';
        }
    }

    /*get Class group and section concatinated when passing classid-groupid-sectionid*/
    public static function getCGSName($class_id,$group_id=null,$section){
        $classtitle = RefClass::find()->select(['title'])->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'class_id'=>$class_id])->one();
        $grouptitle = RefGroup::find()->select(['title'])->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'group_id'=>($group_id)?$group_id:null])->one();
        $sectiontitle = RefSection::find()->select(['title'])->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'section_id'=>$section])->one();
        //echo '<pre>';print_r($grouptitle);die;
        $cgs='';
        if(count($classtitle)>0){
            $cgs .= ucfirst($classtitle->title);
        }
        if(count($grouptitle) >0 ){
            $cgs .= ' - '.ucfirst($grouptitle->title);
        }
        if(count($sectiontitle)>0){
            $cgs .= ' - '.ucfirst($sectiontitle->title);
        }
        return $cgs;
    }
    public static function getCGName($class_id,$group_id=null){
        $classtitle = RefClass::find()->select(['title'])->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'class_id'=>$class_id])->one();
        $grouptitle = RefGroup::find()->select(['title'])->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'group_id'=>($group_id)?$group_id:null])->one();
        //echo '<pre>';print_r($grouptitle);die;
        $cgs='';
        if(count($classtitle)>0){
            $cgs .= ucfirst($classtitle->title);
        }
        if(count($grouptitle) >0 ){
            $cgs .= ' - '.ucfirst($grouptitle->title);
        }
        return $cgs;
    }

    /*==== get student against class_id and group_id*/
    public static  function getStudentByClass($class_id,$group_id=null){
        $studentAlldetails = \app\models\StudentInfo::find()
            ->select(['student_info.stu_id','student_parents_info.contact_no as parentcontact'])
            ->innerJoin('student_parents_info','student_info.stu_id = student_parents_info.stu_id')
            ->where(['student_info.class_id'=>$class_id,'student_info.group_id'=>($group_id)?$group_id:null,'student_info.is_active'=>1])->asArray()->all();
            //echo '<pre>';print_r($studentAlldetails);die;
        return $studentAlldetails;
    }

    /*multidimational search for student position based on std positon.*/

     public static function position_all($parents, $searched) {

        if (empty($searched) || empty($parents)) {
            return false;
        }
        echo 'here';die;
        foreach ($parents as $key => $value) {
            $exists = true;
            foreach ($searched as $skey => $svalue) {
                $exists = ($exists && Isset($parents[$key][$skey]) && $parents[$key][$skey] == $svalue);
            }
            if($exists){
                return  $parents[$key]['position'];
            }

        }
        return false;
    }

    public static function multidimensional_search($parents, $searched) {
        if (empty($searched) || empty($parents)) {
            return false;
        }
        foreach ($parents as $key => $value) {
            $exists = true;
            foreach ($searched as $skey => $svalue) {
                $exists = ($exists && Isset($parents[$key][$skey]) && $parents[$key][$skey] == $svalue);
            }
            if($exists){
                return  $parents[$key]['position'];
            }

        }
        return false;
    }
    public function getSignature(){
         return [
            1 => ['id'=>1,'name'=>'Principal'],
            2 => ['id'=>2,'name'=>'Controller Examination'],
        ];
    }
    public static function getSignatureCategory($id){
         if($id == 1){
            return 'Principal';
         }else if($id == 2){
            return 'Controller Examination';
         }
    }
    
    // Function to get the client IP address
    function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    /*detect browser*/
    function getBrowser()
    {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif(preg_match('/Firefox/i',$u_agent))
        {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif(preg_match('/Chrome/i',$u_agent))
        {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif(preg_match('/Safari/i',$u_agent))
        {
            $bname = 'Apple Safari';
            $ub = "Safari";
        }
        elseif(preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Opera';
            $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$u_agent))
        {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            }
            else {
                $version= $matches['version'][1];
            }
        }
        else {
            $version= $matches['version'][0];
        }

        // check if we have a number
        if ($version==null || $version=="") {$version="?";}

        return array(
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'    => $pattern
        );
    }
    /*get country list for client log used in members controller*/
    function get_ip_country(){
        if($this->get_client_ip() == '::1'){
            $ip = '39.43.144.58';//$_SERVER['REMOTE_ADDR'];
            //$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}"));
            $details = '39.43.144.58';
            return $details->country;
                 
            }else{
                      $ip = $_SERVER['REMOTE_ADDR'];
                   
           // $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}"));
            $details = '39.43.144.58';
            return $details->country;

            }
    }

}