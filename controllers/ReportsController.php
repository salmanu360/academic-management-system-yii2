<?php
namespace app\controllers;
ini_set('max_execution_time', 300);
use Yii;
use app\models\Exam;
use app\models\ExamType;
use app\models\RefGroup;
use app\models\RefSection;
use app\models\StudentInfo;
use app\models\search\RefDegreeTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use mPDF;
use app\models\StudentLeaveInfo;
use app\models\StudentAttendance;
use app\models\EmployeeInfo;
use app\models\StudentMarks;
use app\models\FeeSubmission;
use app\models\FeePlan;
use app\models\RefClass;
use app\models\ExamQuiz;
use app\models\ExamQuizType;
use app\models\FineDetail;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
/**
 * DegreeController implements the CRUD actions for RefDegreeType model.
 */
class ReportsController extends Controller
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
    /**
     * @return Action statistics
     */
    public function actionStatistics()
    {
        $model = new StudentInfo();
        $request = Yii::$app->request;
        $total_students=StudentInfo::find()->where(['is_active'=>1,'fk_branch_id'=>yii::$app->common->getBranch()])->all();
          $getclaswise=yii::$app->db->createCommand("SELECT rc.title, count(*) as `No_of_students` FROM student_info si2 inner join ref_class rc on rc.class_id=si2.class_id where si2.fk_branch_id=".yii::$app->common->getBranch()." and si2.is_active =1 and si2.stu_id not in (select fk_stu_id from stu_reg_log_association) group by rc.title")->queryAll();
         $getclaswiseDeactive=yii::$app->db->createCommand("SELECT rc.title, count(*) as `No_of_students` FROM student_info si2 inner join ref_class rc on rc.class_id=si2.class_id where si2.fk_branch_id=".yii::$app->common->getBranch()." and si2.is_active =0 and si2.stu_id not in (select fk_stu_id from stu_reg_log_association) group by rc.title")->queryAll();
          $promotedclaswise=yii::$app->db->createCommand("select rc.title as `class_name`, count(*) as `No_of_new_promoted_class_wise` from student_info si inner join ref_class rc on rc.class_id=si.class_id where si.fk_branch_id=".yii::$app->common->getBranch()." and si.stu_id in (select fk_stu_id from stu_reg_log_association) GROUP by rc.title")->queryAll();
          $promtedclasswixeAvg=yii::$app->db->createCommand("select abc.class_name,abc.No_Of_Student, ((abc.No_Of_Student)/ (select count(*) from student_info))*100 as `Average_Promoted_Students_per_Class` from (select rc.title as `class_name`, count(*) as `No_Of_Student` from student_info si inner join ref_class rc on rc.class_id=si.class_id where si.stu_id in (select fk_stu_id from stu_reg_log_association) and si.is_active=1 GROUP by rc.title)abc")->queryAll();
            return $this->render('statistics', [
                'total_students'=>$total_students,
                'getclaswise' => $getclaswise,
                'promotedclaswise' => $promotedclaswise,
                'promtedclasswixeAvg' => $promtedclasswixeAvg,
                'getclaswiseDeactive' => $getclaswiseDeactive,
                'model' => $model,
                ]);
    }

    public function actionSessionFee()
    {
         $model = new StudentInfo();
        return $this->render('finance/session-fee', [
            'model'    =>$model,
        ]);
    }

    public function actionAcademics()
    {
        $model = new \app\models\Exam();
        return $this->render('academics', [
            'model'    =>$model,
        ]);
    }
    public function actionGetSessionFee(){
        $data=Yii::$app->request->post();

        if(!empty(Yii::$app->request->get('cid'))){
        $classid=Yii::$app->request->get('cid');
        $group_id=Yii::$app->request->get('gid');
        $section_id=Yii::$app->request->get('sid'); 
        $studentTable=\app\models\StudentInfo::find()->where([
                'class_id'=>$classid,
                'group_id'   => ($group_id)?$group_id:null,
                'section_id'=>$section_id,
                'is_active'=>1
            ])->all();
        $settings = Yii::$app->common->getBranchSettings();
        $sessionStartDate=date('Y-m-d',strtotime($settings->current_session_start));
        $sessionEndDate=date('Y-m-d',strtotime($settings->current_session_end));
        $view= $this->renderAjax('finance/get-session-fee',['studentTable'=>$studentTable,'sessionStartDate'=>$sessionStartDate,'sessionEndDate'=>$sessionEndDate,'classid'=>$classid,'group_id'=>$group_id,'section_id'=>$section_id]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($view);
        $mpdf->Output('session-fee-'.date("d-m-Y").'.pdf', 'D');
        }else{
        $classid=$data['classid'];
        $group_id=$data['groupid'];
        $section_id=$data['id'];
        $studentTable=\app\models\StudentInfo::find()->where([
                'class_id'=>$classid,
                'group_id'   => ($group_id)?$group_id:null,
                'section_id'=>$section_id,
                'is_active'=>1
            ])->all();
        $settings = Yii::$app->common->getBranchSettings();
        $sessionStartDate=date('Y-m-d',strtotime($settings->current_session_start));
        $sessionEndDate=date('Y-m-d',strtotime($settings->current_session_end));
        $view= $this->renderAjax('finance/get-session-fee',['studentTable'=>$studentTable,'sessionStartDate'=>$sessionStartDate,'sessionEndDate'=>$sessionEndDate,'classid'=>$classid,'group_id'=>$group_id,'section_id'=>$section_id]);
        return json_encode(['counStudent'=>$view]);
        }
    }

    public function actionShowOverall()
    {
         $start= Yii::$app->request->post('start');
         $end = Yii::$app->request->post('end');

         $startcnvrt=date('Y-m-d',strtotime($start));
         $endcnvrt=date('Y-m-d',strtotime($end));
         $query=yii::$app->db->createCommand("
                select count(DISTINCT(sa.fk_stu_id)) as total,sa.leave_type from student_attendance sa inner join student_info si on si.stu_id=sa.fk_stu_id inner join ref_class rc on rc.class_id=si.class_id left join ref_group rg on rg.group_id=si.group_id left join ref_section rs on rs.section_id=si.section_id where si.fk_branch_id='".yii::$app->common->getBranch()."' and si.is_active=1 and sa.date BETWEEN '".$startcnvrt."' and '".$endcnvrt."' group by sa.leave_type
            ")->queryAll();
         $overallView=$this->renderPartial('statistics/overall',['query'=>$query]);
         return json_encode(['overallview'=>$overallView]);   
    }
   public function actionShowCls()
    {
         $start= Yii::$app->request->post('start');
        //echo '<br />';
         $end = Yii::$app->request->post('end');
         $class = Yii::$app->request->post('cls');
         $grp = Yii::$app->request->post('grp');
         $section = Yii::$app->request->post('sectn');
         $startcnvrt=date('Y-m-d',strtotime($start));
         $endcnvrt=date('Y-m-d',strtotime($end));
        if(!empty($class) && empty($grp) && empty($section)){
          //  echo '1';
            $where = "where si.fk_branch_id='".yii::$app->common->getBranch()."' and si.is_active=1  and sa.date BETWEEN '".$startcnvrt."' and '".$endcnvrt."' and rc.class_id=".$class." and sa.leave_type <>'present' 
                group by rc.class_id,rc.title,rg.group_id,rg.title,rs.section_id,rs.title,sa.leave_type";
        }else if(!empty($class) && !empty($grp) && empty($section)){
           // echo '2';

            $where = "where si.fk_branch_id='".yii::$app->common->getBranch()."' and si.is_active=1  and sa.date BETWEEN '".$startcnvrt."' and '".$endcnvrt."' and rc.class_id=".$class." and rg.group_id =".$grp."  and sa.leave_type <>'present'
                group by rc.class_id,rc.title,rg.group_id,rg.title,rs.section_id,rs.title,sa.leave_type";
        }else if(!empty($class) && !empty($grp) && !empty($section)){
           // echo '3';
            $where = "where si.fk_branch_id='".yii::$app->common->getBranch()."' and si.is_active=1  and sa.date BETWEEN '".$startcnvrt."' and '".$endcnvrt."' and rc.class_id='".$class."' and rg.group_id =".$grp."  and sa.leave_type <>'present'  and rs.section_id=".$section."
                group by rc.class_id,rc.title,rg.group_id,rg.title,rs.section_id,rs.title,sa.leave_type";
        }else if(!empty($class) && empty($grp) && !empty($section)){
            $where = "where si.fk_branch_id='".yii::$app->common->getBranch()."' and si.is_active=1  and sa.date BETWEEN '".$startcnvrt."' and '".$endcnvrt."' and rc.class_id='".$class."' and sa.leave_type <>'present'  and rs.section_id=".$section."
                group by rc.class_id,rc.title,rg.group_id,rg.title,rs.section_id,rs.title,sa.leave_type";
        }
        else{
            $where="";
        }
        if(empty($where)){}else{
         $query=yii::$app->db->createCommand("
                select count(sa.leave_type) as total,rc.class_id,rc.title,rg.group_id,rg.title as group_title,rs.section_id,rs.title as section_title,sa.leave_type from student_attendance sa 
                inner join student_info si on si.stu_id=sa.fk_stu_id
                inner join ref_class rc on rc.class_id=si.class_id 
                left join ref_group rg on rg.group_id=si.group_id
                left join ref_section rs on rs.section_id=si.section_id 
            ".$where)->queryAll();
 }
         if(count($query) > 0){
         $overallViewCls=$this->renderAjax('statistics/overallClass',['query'=>$query]);
         }else{
            $overallViewCls = 'Not Found';
         }
         return json_encode(['overallclass'=>$overallViewCls]);
    }
    public function actionShowGroups()
    {
         $start= Yii::$app->request->post('start');
         $end = Yii::$app->request->post('end');
         $grp = Yii::$app->request->post('grp');
         $startcnvrt=date('Y-m-d',strtotime($start));
         $endcnvrt=date('Y-m-d',strtotime($end));
         $query=yii::$app->db->createCommand("
                select count(sa.leave_type),rc.class_id,rc.title,rg.group_id,rg.title,sa.leave_type from student_attendance sa inner join student_info si on si.stu_id=sa.fk_stu_id inner join ref_class rc on rc.class_id=si.class_id left join ref_group rg on rg.group_id=si.group_id where si.fk_branch_id='".yii::$app->common->getBranch()."' and si.is_active=1 and sa.date BETWEEN '".$startcnvrt."' and '".$endcnvrt."' and rc.class_id=11 and sa.leave_type <>'present' and rg.group_id=7 group by rc.class_id,rc.title,rg.group_id,rg.title,sa.leave_type
            ")->queryAll();
         if(count($query) > 0){
         $overallViewCls=$this->renderPartial('statistics/overallgrp',['query'=>$query]);
         }else{
            $overallViewCls = 'Not Found';
         }
         return json_encode(['overallgrps'=>$overallViewCls]); 
    }
    public function actionGetSection()
    {
        $class_id=Yii::$app->request->post('id');
        $group_id = Yii::$app->request->post('depdrop_all_params')['group-id'];
        if (!empty($class_id)) {
            $count_group = RefGroup::find()->where(['fk_class_id'=>$class_id,'fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->count();
            if($count_group ==0){
                $out = Yii::$app->common->getSection($class_id,Null);
                return   Json::encode(['output'=>$out, 'selected'=>'']);
            }else{
                return false;
            }
        }
        elseif(!empty($class_id) && !empty($group_id)){
            $out = Yii::$app->common->getSection($class_id,$group_id);
            return   Json::encode(['output'=>$out, 'selected'=>'']);
        } else{
            $count_group = RefGroup::find()->where(['fk_class_id'=>$class_id,'fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->count();
            if($count_group ==0){
                $out = Yii::$app->common->getSection($class_id,Null);
                return   Json::encode(['output'=>$out, 'selected'=>'']);
            }else{
                return false;
            }
        }
    }
    public function actionGetClasses(){
        $id=Yii::$app->request->post('id');
        $group=RefGroup::find()->where(['fk_class_id'=>$id,'fk_branch_id'=>yii::$app->common->getBranch()])->all();
        $section=RefSection::find()->where(['class_id'=>$id,'fk_branch_id'=>yii::$app->common->getBranch()])->all();
        if(!empty($group)){
           $options = "<option value=''>Select Group</option>";
        foreach($group as $group)
        {
            $options .= "<option value='".$group->group_id."'>".$group->title."</option>";
        }
        return json_encode(['notempty'=>$options]);
        }else{
           $optionsSectn = "<option value=''>Select Group</option>";
        foreach($section as $sectionxx)
        {
            $optionsSectn .= "<option value='".$sectionxx->section_id."'>".$sectionxx->title."</option>";
        }
        return json_encode(['notempty'=>$optionsSectn]);
        }
       }
   /*end of get subjects of group and section*/
       // student data on the basis of class id
       public function actionClassData(){
        $id=Yii::$app->request->post('id');
        $group=RefGroup::find()->where(['fk_class_id'=>$id,'fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all();
        $section=RefSection::find()->where(['class_id'=>$id,'fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all();
        $getStudntcount=StudentInfo::find()->where(['class_id'=>$id,'fk_branch_id'=>yii::$app->common->getBranch(),'is_active'=>1])->all();
        if(!empty($group)){
           $counStudent='Total No of Students ' .count($getStudntcount);
           $options = "<option value=''>Select Group</option>";
        foreach($group as $group)
        {
            $options .= "<option value='".$group->group_id."'>".$group->title."</option>";
        }
        return json_encode(['groupdata'=>$options,'counStudent'=>$counStudent]);
        }else{
           $counStudent='Total No of Students ' .count($getStudntcount);
           $optionsSectn = "<option value=''>Select Section</option>";
        foreach($section as $sectionxx)
        {
            $optionsSectn .= "<option value='".$sectionxx->section_id."'>".$sectionxx->title."</option>";
        }
        return json_encode(['sectiondata'=>$optionsSectn,'counStudent'=>$counStudent]);

        }
    }
     public function actionGroupData(){
        $id=Yii::$app->request->post('id');
        $classid=Yii::$app->request->post('classid');
        $section=RefSection::find()->where(['class_id'=>$classid,'fk_group_id'=>$id,'fk_branch_id'=>yii::$app->common->getBranch(),'status'=>'active'])->all();
        $getStudntcount=StudentInfo::find()->where(['class_id'=>$classid,'group_id'=>$id,'fk_branch_id'=>yii::$app->common->getBranch(),'is_active'=>1])->all();
        
           $counStudent='Total No of Students ' .count($getStudntcount);
           $optionsSectn = "<option value=''>Select Section</option>";
        foreach($section as $sectionxx)
        {
            $optionsSectn .= "<option value='".$sectionxx->section_id."'>".$sectionxx->title."</option>";
        }
        return json_encode(['sectiondata'=>$optionsSectn,'counStudent'=>$counStudent]);
    }
     public function actionSectionData(){
        $id=Yii::$app->request->post('id');
        $classid=Yii::$app->request->post('classid');
        $groupid=Yii::$app->request->post('groupid');
        if(!empty($groupid)){
        $getStudntcount=StudentInfo::find()->where(['class_id'=>$classid,'group_id'=>$groupid,'section_id'=>$id,'fk_branch_id'=>yii::$app->common->getBranch(),'is_active'=>1])->all();
        }else{
            $getStudntcount=StudentInfo::find()->where(['class_id'=>$classid,'section_id'=>$id,'fk_branch_id'=>yii::$app->common->getBranch(),'is_active'=>1])->all();
        }
        $counStudent='Total No of Students ' .count($getStudntcount);
        $optionsSectn = "<option value=''>Select Section</option>";
        return json_encode(['counStudent'=>$counStudent]);
     }
     public function actionStudentDataClasswise(){
        $classid=Yii::$app->request->post('classid');
        $sectionid=Yii::$app->request->post('sectionid');
        $groupid=Yii::$app->request->post('groupid');
        if($sectionid == '' && $groupid == ''){
        $getStudntname=StudentInfo::find()->where(['class_id'=>$classid,'fk_branch_id'=>yii::$app->common->getBranch(),'is_active'=>1])->all();
        }elseif ($sectionid == '') {
           $getStudntname=StudentInfo::find()->where(['class_id'=>$classid,'group_id'=>$groupid,'fk_branch_id'=>yii::$app->common->getBranch(),'is_active'=>1])->all();
        }elseif($groupid == ''){
            $getStudntname=StudentInfo::find()->where(['class_id'=>$classid,'section_id'=>$sectionid,'fk_branch_id'=>yii::$app->common->getBranch(),'is_active'=>1])->all();
            }
        else{
            $getStudntname=StudentInfo::find()->where(['class_id'=>$classid,'section_id'=>$sectionid,'group_id'=>$groupid,'fk_branch_id'=>yii::$app->common->getBranch(),'is_active'=>1])->all();
            }
        $studentviewclass=$this->renderAjax('statistics/studentnameclasswise',['getStudntname'=>$getStudntname]);
        return json_encode(['counStudent'=>$studentviewclass]);
     }
   public function actionGetSections(){

        $id=Yii::$app->request->post('id');
        $section=RefSection::find()->where(['fk_group_id'=>$id])->all();
        $options= "<option value=''>Select Section</option>";
       foreach($section as $section)
       {
        $options .= "<option value='".$section->section_id."'>".$section->title."</option>";
        }
        return $options;
        }//end of Section
        public function actionId(){
            $model=new StudentInfo();
            return $this->render('academics/idcard',['model'=>$model]);
        }
        public function actionIdCard(){
            $data=Yii::$app->request->post('StudentInfo');
            return $this->render('academics/idcardiframe',['stu_id'=>$data['stu_id']]);
            
        }
        public function actionIdcardPdf($id){
            $stu_id=Yii::$app->request->get('id');
            $student = StudentInfo::find()
                ->where([
                  'fk_branch_id'  =>Yii::$app->common->getBranch(),
                  'stu_id' => $stu_id,
                  ])->one();
            $class_name=\app\models\RefClass::find()->select(['title'])->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active','class_id'=>$student->class_id])->one();
            $section=RefSection::find()->where(['section_id'=>$student->section_id,'fk_branch_id'=>yii::$app->common->getBranch()])->one();
            $view= $this->renderAjax('academics/idcardpdf', ['student'=>$student,'class_name'=>$class_name,'section'=>$section]);
            $view1= $this->renderAjax('academics/idcardpdf2', ['student'=>$student,'class_name'=>$class_name,'section'=>$section]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');

        $stylesheet = file_get_contents('css/card.css');
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->WriteHTML($view);
        $mpdf->WriteHTML($view1);
        $mpdf->Output('student-card-'.date("d-m-Y").'.pdf', 'I'); 
        }
         public function actionIdcardPdf2($id){
            $stu_id=Yii::$app->request->get('id');
            $student = StudentInfo::find()
                ->where([
                  'fk_branch_id'  =>Yii::$app->common->getBranch(),
                  'stu_id' => $stu_id,
                  ])->one();
            $view= $this->renderAjax('academics/idcardpdf2', ['id' => 'card','student'=>$student]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $stylesheet = file_get_contents('css/card.css');
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->WriteHTML($view);
        $mpdf->Output('student-card-'.date("d-m-Y").'.pdf', 'I'); 
        }
       public function actionTest(){
        $student=StudentInfo::find()->one();
        return $view= $this->render('test');
        /*$this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($view);
        $mpdf->Output('overall-transport-route-wise-'.date("d-m-Y").'.pdf', 'I');*/ 
        } 
        public function actionTest1(){
        $view= $this->renderAjax('test1');
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $stylesheet = file_get_contents('css/card.css');
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->WriteHTML($view);
        $mpdf->Output('overall-transport-route-wise-'.date("d-m-Y").'.pdf', 'I'); 
        }
    public function actionGetZoneGeneric(){
         $zoneQuery=yii::$app->db->createCommand("select count(si.stu_id) as `no_of_students_availed_transport`,z.id as `zone_id`,z.title as `zone_name` from transport_allocation si 
            inner join student_info st on st.user_id=si.stu_id
            inner join stop s on s.id=si.fk_stop_id inner join route r on r.id=s.fk_route_id 
            inner join zone z on z.id=r.fk_zone_id 
            WHERE st.is_active=1 and si.branch_id=".yii::$app->common->getBranch()." AND si.status=1 group by z.id,z.title")->queryAll();
        $zoneView=$this->renderAjax('statistics/zone-generic',['zone'=>$zoneQuery]);
        return json_encode(['zonegenric'=>$zoneView]);
    }
     public function actionGetZoneGenericPdf(){
       $zoneQuery=yii::$app->db->createCommand("select count(si.stu_id) as `no_of_students_availed_transport`,z.id as `zone_id`,z.title as `zone_name` from transport_allocation si 
            inner join student_info st on st.user_id=si.stu_id
            inner join stop s on s.id=si.fk_stop_id inner join route r on r.id=s.fk_route_id 
            inner join zone z on z.id=r.fk_zone_id 
            WHERE st.is_active=1 and si.branch_id=".yii::$app->common->getBranch()." AND si.status=1 group by z.id,z.title")->queryAll();
        $zoneView=$this->renderAjax('statistics/zone-generic-pdf',['zone'=>$zoneQuery]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $stylesheet = file_get_contents('css/std-ledger-pdf.css');
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->WriteHTML($zoneView);
        $mpdf->Output('overall-transport-zone-wise-'.date("d-m-Y").'.pdf', 'D'); 
     }
    public function actionGetrouteZonewise(){
        $zoneid=yii::$app->request->post('zoneid');
        $routeQuery=yii::$app->db->createCommand("select count(si.stu_id) as `no_of_students_availed_transport`,z.id as `zone_id`,z.title as `zone_name`,r.id as `route_id`,r.title as `route_name` from transport_allocation si 
            inner join student_info st on st.user_id=si.stu_id
            inner join stop s on s.id=si.fk_stop_id 
            inner join route r on r.id=s.fk_route_id 
            inner join zone z on z.id=r.fk_zone_id 
            WHERE st.is_active=1 and si.branch_id=".yii::$app->common->getBranch()." AND si.status=1 and z.id=".$zoneid." group by z.id,z.title,r.id,r.title")->queryAll();
        $routeView=$this->renderAjax('statistics/route-zone',['route'=>$routeQuery,'zoneid'=>$zoneid]);

        return json_encode(['zoneRoutes'=>$routeView]);
    }
    public function actionGetroutewisePdf(){
        $zoneid=yii::$app->request->get('zoneid');
        $routeQuery=yii::$app->db->createCommand("select count(si.stu_id) as `no_of_students_availed_transport`,z.id as `zone_id`,z.title as `zone_name`,r.id as `route_id`,r.title as `route_name` from transport_allocation si 
            inner join student_info st on st.user_id=si.stu_id
            inner join stop s on s.id=si.fk_stop_id 
            inner join route r on r.id=s.fk_route_id 
            inner join zone z on z.id=r.fk_zone_id 
            WHERE st.is_active=1 and si.branch_id=".yii::$app->common->getBranch()." AND si.status=1 and z.id=".$zoneid." group by z.id,z.title,r.id,r.title")->queryAll();
        $routeView=$this->renderAjax('statistics/route-zone-pdf',['route'=>$routeQuery]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $stylesheet = file_get_contents('css/std-ledger-pdf.css');
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->WriteHTML($routeView);
        $mpdf->Output('overall-transport-route-wise-'.date("d-m-Y").'.pdf', 'D'); 
        return json_encode(['zoneRoutes'=>$routeView]);
    }
    public function actionGetstopRoutewise(){
        $routeid=yii::$app->request->post('routeid');
        $zoneid=yii::$app->request->post('zoneid');
        $stopQuery=yii::$app->db->createCommand("select count(si.stu_id) as `no_of_students_availed_transport`,z.id as `zone_id`,z.title as `zone_name`,r.id as `route_id`,r.title as `route_name`,s.id as ` stop_id`,s.title as `stop_name` from transport_allocation si 
            inner join student_info st on st.user_id=si.stu_id
            inner join stop s on s.id=si.fk_stop_id 
            inner join route r on r.id=s.fk_route_id 
            inner join zone z on z.id=r.fk_zone_id 
            WHERE st.is_active=1 and si.branch_id=".yii::$app->common->getBranch()." AND si.status=1 and z.id=".$zoneid." and r.id=".$routeid." group by z.id,z.title,r.id,r.title,s.id,s.title")->queryAll();
        $stopView=$this->renderAjax('statistics/stop-route',['stop'=>$stopQuery,'routeid'=>$routeid,'zoneid'=>$zoneid]);
        return json_encode(['stopRoutes'=>$stopView]);
    }
    public function actionGetstopRoutewisePdf(){
         $routeid=yii::$app->request->get('routeid');
         $zoneid=yii::$app->request->get('zoneid');
        $stopQuery=yii::$app->db->createCommand("select count(si.stu_id) as `no_of_students_availed_transport`,z.id as `zone_id`,z.title as `zone_name`,r.id as `route_id`,r.title as `route_name`,s.id as ` stop_id`,s.title as `stop_name` from transport_allocation si 
            inner join student_info st on st.user_id=si.stu_id
            inner join stop s on s.id=si.fk_stop_id 
            inner join route r on r.id=s.fk_route_id 
            inner join zone z on z.id=r.fk_zone_id 
            WHERE st.is_active=1 and si.branch_id=".yii::$app->common->getBranch()." AND si.status=1 and z.id=".$zoneid." and r.id=".$routeid." group by z.id,z.title,r.id,r.title,s.id,s.title")->queryAll();
        $stopView=$this->renderAjax('statistics/stop-route-pdf',['stop'=>$stopQuery]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $stylesheet = file_get_contents('css/std-ledger-pdf.css');
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->WriteHTML($stopView);
        $mpdf->Output('overall-transport-stop-wise-'.date("d-m-Y").'.pdf', 'D'); 
    }
    public function actionGetstudentStopwise(){
        $stop_id=yii::$app->request->post('stopid');
        // $getStopStudent=\app\models\TransportAllocation::find()->where(['fk_stop_id'=>$stop_id,'branch_id'=>yii::$app->common->getBranch()])->all();
        $getStopStudent= \app\models\TransportAllocation::find()
                    ->select(['transport_allocation.*','si.user_id','si.class_id','si.group_id','si.section_id','CONCAT(user.first_name," ",user.last_name) as student_name','spi.first_name as parent_name','spi.contact_no as father_contact','s.title as stop_name','s.fare','r.title as route_name','z.title as zone_name','allot.discount_amount'])
                    ->innerJoin('student_info si','transport_allocation.stu_id=si.user_id')
                    ->innerJoin('user','user.id=si.user_id')
                    ->innerJoin('student_parents_info spi','spi.stu_id=si.stu_id')
                    ->innerJoin('stop s','s.id=transport_allocation.fk_stop_id')
                    ->innerJoin('route r','r.id=s.fk_route_id')
                    ->innerJoin('zone z','z.id=r.fk_zone_id')
                    ->innerJoin('transport_allocation allot','allot.stu_id=si.user_id')
                    ->where(['si.fk_branch_id'=>Yii::$app->common->getBranch(),'si.is_active'=>1,'transport_allocation.fk_stop_id'=>$stop_id])
                   // ->orderBy(['student_info.roll_no'=>SORT_ASC])
                    ->asArray()
                    ->all();
        /*$getStopStudent=\app\models\TransportAllocation::find()->where(['fk_stop_id'=>$stop_id,'branch_id'=>yii::$app->common->getBranch()])->all();*/
        $stuView=$this->renderAjax('statistics/student-stop',['stopStudent'=>$getStopStudent,'stop_id'=>$stop_id]);
        return json_encode(['stuView'=>$stuView]);
    }
    public function actionGetstudentStopwisePdf(){
        $stop_id=yii::$app->request->get('id');
        $getStopStudent= \app\models\TransportAllocation::find()
                    ->select(['transport_allocation.*','si.user_id','si.class_id','si.group_id','si.section_id','CONCAT(user.first_name," ",user.last_name) as student_name','spi.first_name as parent_name','spi.contact_no as father_contact','s.title as stop_name','s.fare','r.title as route_name','z.title as zone_name','allot.discount_amount'])
                    ->innerJoin('student_info si','transport_allocation.stu_id=si.user_id')
                    ->innerJoin('user','user.id=si.user_id')
                    ->innerJoin('student_parents_info spi','spi.stu_id=si.stu_id')
                    ->innerJoin('stop s','s.id=transport_allocation.fk_stop_id')
                    ->innerJoin('route r','r.id=s.fk_route_id')
                    ->innerJoin('zone z','z.id=r.fk_zone_id')
                    ->innerJoin('transport_allocation allot','allot.stu_id=si.user_id')
                    ->where(['si.fk_branch_id'=>Yii::$app->common->getBranch(),'si.is_active'=>1,'transport_allocation.fk_stop_id'=>$stop_id])
                   // ->orderBy(['student_info.roll_no'=>SORT_ASC])
                    ->asArray()
                    ->all();
        $stuView=$this->renderAjax('statistics/student-stop-pdf',['stopStudent'=>$getStopStudent,'stop_id'=>$stop_id]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($stuView);
        $mpdf->Output('stop-students-'.date("d-m-Y").'.pdf', 'D');
    }
    // end of transport
    /**
     * @return Action Finances
     */
    public function actionFinances()
    {
        
        return $this->render('finances', [
        ]);
    }
    public function actionAccounts()
    {
        $model=new Exam();
        /*$currentMonth =yii::$app->db->createCommand("SELECT * FROM fee_submission WHERE MONTH(recv_date) = MONTH(CURRENT_DATE())")->queryAll();*/
        /*$currentYear =yii::$app->db->createCommand("SELECT * FROM fee_submission WHERE YEAR(recv_date) = YEAR(CURRENT_DATE())")->queryAll();*/
        /*$todayFeeRcv=FeeSubmission::find()
               ->where(['branch_id'=>Yii::$app->common->getBranch(),'date(recv_date)'=>date('Y-m-d')])->all();*/
        $todayFeeRcv = \app\models\User::find()
            ->select(['student_info.stu_id','student_info.user_id','student_info.class_id','student_info.group_id','student_info.section_id',"concat(user.first_name, ' ' ,  user.last_name) as name",'fee_submission.head_recv_amount','fee_submission.fee_head_id','fee_submission.transport_amount','fee_submission.hostel_amount'])
            ->innerJoin('student_info','student_info.user_id = user.id')
            ->innerJoin('fee_submission','fee_submission.stu_id = student_info.stu_id')
            ->where(['user.status'=>'active','student_info.is_active'=>1,'fee_submission.recv_date'=>date('Y-m-d')])
            ->asArray()->all();

        $refClass=RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all();
        
        return $this->render('accounts', [
            //'currentMonth'=>$currentMonth,
            'model'=>$model,
            'refClass'=>$refClass,
            'todayFeeRcv'=>$todayFeeRcv,
           //'currentYear'=>$currentYear,
        ]);
      }

      public function actionTodayLedger(){
         $model=new Exam();
         $todayFeeRcv = \app\models\User::find()
            ->select(['student_info.stu_id','student_info.user_id','student_info.class_id','student_info.group_id','student_info.section_id',"concat(user.first_name, ' ' ,  user.last_name) as name",'fee_submission.head_recv_amount','fee_submission.fee_head_id','fee_submission.transport_amount','fee_submission.hostel_amount'])
            ->innerJoin('student_info','student_info.user_id = user.id')
            ->innerJoin('fee_submission','fee_submission.stu_id = student_info.stu_id')
            ->where(['user.status'=>'active','student_info.is_active'=>1,'fee_submission.recv_date'=>date('Y-m-d')])
            ->asArray()->all();
        return $this->render('finance/today-ledger-report', [
            'model'=>$model,
            'todayFeeRcv'=>$todayFeeRcv,
        ]);
      }
      public function actionDateLedger(){
        $start= Yii::$app->request->get('startdate');
        $end = Yii::$app->request->get('enddate');
        $startcnvrt=date('Y-m',strtotime($start));
        $endcnvrt=date('Y-m',strtotime($end));
        $sum = \app\models\FeeArears::find()->sum('arears');
        $where = "branch_id='".yii::$app->common->getBranch()."' and from_date BETWEEN '".$startcnvrt."' and '".$endcnvrt."'";
        $dateFee=FeeSubmission::find()->where($where);
        $dataProvider = new ActiveDataProvider([
                 'query' => $dateFee,
            ]);
        $dataProvider->pagination->pageSize=200;
        return $this->render('finance/date-ledger',['dataProvider'=>$dataProvider,'sum'=>$sum,'start'=>$start,'end'=>$end]);
          
      }
      public function actionTodayRcv(){
      	if(!isset($_GET['class_id'])){
      	$todayFeeRcv=FeeSubmission::find()->where(['recv_date'=>date('Y-m-d'),'fee_status'=>1])->all();
      	return $todayFeeView = $this->render('finance/today-recv',['todayFeeRcv'=>$todayFeeRcv]);
      	}else{
	   $class_id=yii::$app->request->get('class_id');
	   $todayFeeRcv=FeeSubmission::find()->where(['recv_date'=>date('Y-m-d'),'fee_status'=>1])->all();
	   $todayLedgerView = $this->renderAjax('finance/today-recv',['class_id'=>$class_id,'todayFeeRcv'=>$todayFeeRcv]);
	   $this->layout = 'pdf';
       $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
       $mpdf->WriteHTML($todayLedgerView);
       $mpdf->Output('today-ledger-'.date("d-m-Y").'.pdf', 'D');
      	}
        /*$todayFeeRcv = \app\models\User::find()
            ->select(['student_info.stu_id','student_info.user_id','student_info.class_id','student_info.group_id','student_info.section_id',"concat(user.first_name, ' ' ,  user.last_name) as name",'fee_submission.head_recv_amount','fee_submission.fee_head_id','fee_submission.transport_amount','fee_submission.hostel_amount'])
            ->innerJoin('student_info','student_info.user_id = user.id')
            ->innerJoin('fee_submission','fee_submission.stu_id = student_info.stu_id')
            ->where(['user.status'=>'active','student_info.is_active'=>1,'fee_submission.recv_date'=>date('Y-m-d')])
            ->asArray()->all();*/
        
       
      }

      public function actionTodayAllLedger(){
        $todayFeeRcv = \app\models\User::find()
            ->select(['student_info.stu_id','student_info.user_id','student_info.class_id','student_info.group_id','student_info.section_id',"concat(user.first_name, ' ' ,  user.last_name) as name",'fee_submission.head_recv_amount','fee_submission.fee_head_id','fee_submission.transport_amount','fee_submission.hostel_amount'])
            ->innerJoin('student_info','student_info.user_id = user.id')
            ->innerJoin('fee_submission','fee_submission.stu_id = student_info.stu_id')
            ->where(['user.status'=>'active','student_info.is_active'=>1,'fee_submission.recv_date'=>date('Y-m-d')])
            ->asArray()->all();
        $todayFeeView = $this->renderAjax('finance/pdf/today-all',['todayFeeRcv'=>$todayFeeRcv]);
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($todayFeeView);
        $mpdf->Output('today-fee-'.date("d-m-Y").'.pdf', 'D');
      }

    /*yearly report*/
    public function actionYearlyReport(){
        $year=yii::$app->request->post('year');
        $query="recv_date like '".$year."%'";
        $yearSql=\app\models\FeeSubmission::find()->where($query)->all();
        if(count($yearSql)>0){
         $yearView=$this->renderAjax('finance/yearly-report',['yearSql'=>$yearSql,'year'=>$year]);
     }else{
        $yearView= "<div class='row'><div class='col-md-1 col-sm-1'></div><div class='Alert alert-danger col-sm-6'><strong>No Fee Record Found!</strong></div> </div>";
     }
     return json_encode(['getYearlyReport'=>$yearView]);
    } //end of function 
    public function actionYearlyReportPdf(){
        $year=yii::$app->request->get('year');
        $query="recv_date like '".$year."%'";
        $yearSql=\app\models\FeeSubmission::find()->where($query)->all();
        $yearView = $this->renderAjax('finance/yearly-report',['yearSql'=>$yearSql,'year'=>$year]);
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($yearView);
        $mpdf->Output('yearly-fee-'.date("d-m-Y").'.pdf', 'D');
        
    } //end of function
     public function actionYearlyReportClasswise(){
        $class_id=yii::$app->request->post('class_id');
        $student_id=yii::$app->request->post('student_id');
        $year=yii::$app->request->post('year');
        $studentTable=\app\models\StudentInfo::find()->where(['user_id'=>$student_id])->one();
        $query="from_date like '".$year."%' and stu_id=".$studentTable->stu_id;
        $yearSql=\app\models\FeeSubmission::find()->where($query)->all();
        if(count($yearSql)>0){
         $yearView=$this->renderAjax('finance/yearly-report-classwise',['yearSql'=>$yearSql,'year'=>$year,'student_id'=>$student_id,'studentTable'=>$studentTable]);
     }else{
        $yearView= "<div class='row'><div class='col-md-1 col-sm-1'></div><div class='Alert alert-danger col-sm-6'><strong>No Fee Record Found!</strong></div> </div>";
     }
     return json_encode(['yearlyFeeReportClassWiseStudents'=>$yearView]);
    } //end of function
    public function actionMonthlyReportClasswise(){
        $class_id=yii::$app->request->post('class_id');
        $month=yii::$app->request->post('month');
        $student_id=yii::$app->request->post('student_id');
        $year=yii::$app->request->post('year');
        $studentTable=\app\models\StudentInfo::find()->where(['user_id'=>$student_id])->one();
        $query="from_date like '".$month."%' and stu_id=".$studentTable->stu_id;
        $yearSql=\app\models\FeeSubmission::find()->where($query)->all();
        
        if(count($yearSql)>0){
         $yearView=$this->renderAjax('finance/monthly-report-classwise',['yearSql'=>$yearSql,'year'=>$year,'student_id'=>$student_id,'studentTable'=>$studentTable,'month'=>$month]);
     }else{
        $yearView= "<div class='row'><div class='col-md-1 col-sm-1'></div><div class='Alert alert-danger col-sm-6'><strong>No Fee Record Found!</strong></div> </div>";
     }
     return json_encode(['yearlyFeeReportClassWiseStudents'=>$yearView]);
    } //end of function

    public function actionMonthlyReportClasswisePdf(){
        $student_id=yii::$app->request->get('student_id');
        $month=yii::$app->request->get('month');
        $studentTable=\app\models\StudentInfo::find()->where(['user_id'=>$student_id])->one();
        $query="from_date like '".$month."%' and stu_id=".$studentTable->stu_id;
        $yearSql=\app\models\FeeSubmission::find()->where($query)->all();
        $yearStudentView=$this->renderAjax('finance/monthly-report-classwise',['yearSql'=>$yearSql,'student_id'=>$student_id,'studentTable'=>$studentTable,'month'=>$month]);
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($yearStudentView);
        $mpdf->Output('monthly-student-fee-'.date("d-m-Y").'.pdf', 'D');
    } //end of function

     public function actionYearlyReportClasswisePdf(){
        $student_id=yii::$app->request->get('student_id');
        $year=yii::$app->request->get('year');
        $studentTable=\app\models\StudentInfo::find()->where(['user_id'=>$student_id])->one();
        $query="from_date like '".$year."%' and stu_id=".$studentTable->stu_id;
        $yearSql=\app\models\FeeSubmission::find()->where($query)->all();
        $yearStudentView=$this->renderAjax('finance/yearly-report-classwise',['yearSql'=>$yearSql,'year'=>$year,'student_id'=>$student_id,'studentTable'=>$studentTable]);
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($yearStudentView);
        $mpdf->Output('yearly-student-fee-'.date("d-m-Y").'.pdf', 'D');
    } //end of function

    public function actionOverllCashFlow(){
         $start= Yii::$app->request->post('start');
         $end = Yii::$app->request->post('end');
         $startcnvrt=date('Y-m-d',strtotime($start));
         $endcnvrt=date('Y-m-d',strtotime($end));
         $query=yii::$app->db->createCommand("
                select fhw.fk_branch_id,CAST(fcr.transaction_date AS DATE) AS DATE_PURCHASED,dayname(fcr.transaction_date), sum(fhw.payment_received) as `fee_received`,MIN(fhw.fk_chalan_id) from fee_head_wise fhw inner join fee_transaction_details fcr on fcr.id=fhw.fk_chalan_id where fhw.fk_branch_id=".yii::$app->common->getBranch()." and CAST(fcr.transaction_date AS DATE) >= '".$startcnvrt."' and CAST(fcr.transaction_date AS DATE) <= '".$endcnvrt."' GROUP by fhw.fk_branch_id, CAST(fcr.transaction_date AS DATE),dayname(fcr.transaction_date)
            ")->queryAll();
         if(count($query)>0){
         $cashflowView=$this->renderAjax('finance/cashflow',['query'=>$query,'startcnvrt'=>$startcnvrt,'endcnvrt'=>$endcnvrt]);
     }else{
         $cashflowView= "<div class='row'><div class='col-md-1 col-sm-1'></div><div class='Alert alert-danger col-sm-3'><strong>No Record Found!</strong></div> </div>";
     }
         return json_encode(['cashflowhere'=>$cashflowView]);
     
     }
    public function actionOverllCashFlowPdf(){
         $start= Yii::$app->request->get('start');
         $end = Yii::$app->request->get('end');
         $startcnvrt=date('Y-m-d',strtotime($start));
         $endcnvrt=date('Y-m-d',strtotime($end));
         $query=yii::$app->db->createCommand("
                select fhw.fk_branch_id,CAST(fcr.transaction_date AS DATE) AS DATE_PURCHASED,dayname(fcr.transaction_date), sum(fhw.payment_received) as `fee_received`,MAX(fhw.transport_fare) as transport_fare from fee_head_wise fhw inner join fee_transaction_details fcr on fcr.id=fhw.fk_chalan_id where fhw.fk_branch_id=".yii::$app->common->getBranch()." and CAST(fcr.transaction_date AS DATE) >= '".$startcnvrt."' and CAST(fcr.transaction_date AS DATE) <= '".$endcnvrt."' GROUP by fhw.fk_branch_id, CAST(fcr.transaction_date AS DATE),dayname(fcr.transaction_date)
            ")->queryAll();
         if(count($query)>0){
         $cashflowView = $this->renderPartial('finance/cashflowpdf',['query'=>$query,'startcnvrt'=>$startcnvrt,'endcnvrt'=>$endcnvrt]);
 
          $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML("<h3 style='text-align:center'>Daily Cash Flow</h3>");
        $mpdf->WriteHTML($cashflowView);
        $mpdf->Output('daily-cash-flow-'.date("d-m-Y").'.pdf', 'D');

         }else{
             $cashflowView= "<div class='row'><div class='Alert alert-warning'><strong>Not Found!</strong></div> </div>";
             return json_encode(['cashflowhere'=>$cashflowView]);
         }
     }
     /*student ledger pdf*/
    public function actionStudentLedgerPdf()
    {
        if(Yii::$app->request->get()){
            $stu_id = Yii::$app->request->get('stu_id');
            $class_id = Yii::$app->request->get('class_id');
            $getStudentInfo = Yii::$app->common->getStudent($stu_id);
            $getStu=$getStudentInfo->user_id;
            $studentName= Yii::$app->common->getName($getStudentInfo->user_id);
             $getStudentInfo=StudentInfo::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'stu_id'=>$stu_id])->one();
             $father=$getStudentInfo->stu_id;
            $getChalans=Yii::$app->db->createCommand("select fhw.fk_branch_id,fhw.fk_stu_id, ftd.challan_no,ftd.id,ftd.manual_recept_no,ftd.transaction_date as `fee_submission_date` from fee_head_wise fhw inner join fee_transaction_details ftd on ftd.id=fhw.fk_chalan_id where fhw.fk_stu_id=".$stu_id." and fhw.fk_branch_id=".Yii::$app->common->getBranch()." GROUP BY fhw.fk_branch_id,ftd.id, ftd.challan_no, ftd.transaction_date")->queryAll();
            if(count($getStudentInfo)>0){
                $stuView=$this->renderPartial('finance/student-ledger',['stu_id'=>$stu_id,'getStudentInfo'=>$getStudentInfo,'userid'=>$getStu,'getChalans'=>$getChalans,'father'=>$father]);
                $this->layout = 'pdf';
                $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
                $stylesheet = file_get_contents('css/std-ledger-pdf.css');
                $mpdf->WriteHTML($stylesheet,1);
                $mpdf->WriteHTML("<h3 style='text-align:center'>Student Ledger</h3>");
                $mpdf->WriteHTML($stuView,2);
                $mpdf->Output('Student-Ledger-'.$getStudentInfo->class->title.'-'.$studentName.'-'.date("d-m-Y").'.pdf', 'D');
            }else{
                $stuView= "<div class='row'><div class='Alert alert-warning'><strong>Not Found!</strong></div> </div>";
            }
                     }
    }
    public function actionGetStuClasswise(){
        $class_id=intval(yii::$app->request->post('id'));
        $getStu=StudentInfo::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'class_id'=>$class_id])->all();

        $option="<option>Select Students</option>";
        foreach ($getStu as $getStudents) {
            $option.="<option value=".$getStudents->stu_id.">".Yii::$app->common->getName($getStudents->user_id)."</option>";
        }
        return json_encode(['studata'=>$option]);
     }
     public function actionGetStuClasswiseStu(){
         $class_id=intval(yii::$app->request->post('id'));
        $getStu=StudentInfo::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'class_id'=>$class_id])->all();

        $option="<option>Select Students</option>";
        foreach ($getStu as $getStudents) {
            $option.="<option value=".$getStudents->stu_id.">".Yii::$app->common->getName($getStudents->user_id)."</option>";
        }
        return json_encode(['studata'=>$option]);
     }
     public function actionShowStuDataStu(){
            $stu_id= Yii::$app->request->post('stu_id');
            $getStudentInfo=StudentInfo::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'stu_id'=>$stu_id])->one();
            $getStu=$getStudentInfo->user_id;
            $attndnce=StudentAttendance::find()->where(['fk_stu_id'=>$stu_id])->all();
         if(count($attndnce)>0){
         $stuView=$this->renderAjax('statistics/student-attendance',['attndnce'=>$attndnce,'getStudentInfo'=>$getStudentInfo]);
     }else{
         $stuView= "<div class='row'><div class='col-md-2'></div><div class='Alert alert-warning col-md-4'><strong>No Record Found!</strong></div> </div>";
     }
         return json_encode(['attendance'=>$stuView]);
     
     }    

    public function actionShowStuData(){
         $stu_id= Yii::$app->request->post('stu_id');
         $getStudentInfo=StudentInfo::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'stu_id'=>$stu_id])->one();
         $getStu=$getStudentInfo->user_id;
         $father=$getStudentInfo->stu_id;

         $getChalans=Yii::$app->db->createCommand("select fhw.fk_branch_id,fhw.fk_stu_id, ftd.challan_no,ftd.id,ftd.manual_recept_no,ftd.transaction_date as `fee_submission_date` from fee_head_wise fhw inner join fee_transaction_details ftd on ftd.id=fhw.fk_chalan_id where fhw.fk_stu_id=".$stu_id." and fhw.fk_branch_id=".Yii::$app->common->getBranch()." GROUP BY fhw.fk_branch_id,ftd.id, ftd.challan_no, ftd.transaction_date")->queryAll();
         if(count($getStudentInfo)>0){
         $stuView=$this->renderAjax('finance/student-ledger',['stu_id'=>$stu_id,'father'=>$father,'getStudentInfo'=>$getStudentInfo,'userid'=>$getStu,'getChalans'=>$getChalans]);
     }else{
         $stuView= "<div class='row'><div class='col-md-1 col-sm-1'></div><div class='Alert alert-danger col-sm-3'><strong>Not Found!</strong></div> </div>";
     }
         return json_encode(['studatas'=>$stuView,'countChallan'=>count($getChalans)]);
     
     }  

     /*show student slc and other records*/
       public function actionShowStuDetails(){
          $stu_id= Yii::$app->request->post('stuId');
          $studentTable=\app\models\StudentInfo::find()->where(['user_id'=>$stu_id])->one();
          $promotedData=\app\models\StuRegLogAssociation::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'fk_stu_id'=>$studentTable->stu_id])->all();
         if(count($promotedData)>0){
         $stuView=$this->renderAjax('statistics/student-details',['stu_id'=>$stu_id,'promotedData'=>$promotedData]);
     }else{
         $stuView= "<div class='row'><div class='col-md-1 col-sm-1'></div><div class='Alert alert-danger col-sm-3'><strong>Not Found!</strong></div> </div>";
     }
         return json_encode(['showStudentDetails'=>$stuView]);
     }  
      public function actionShowStuDetailsPdf(){
          $stu_id= Yii::$app->request->get('id');
          $studentTable=\app\models\StudentInfo::find()->where(['user_id'=>$stu_id])->one();
          $promotedData=\app\models\StuRegLogAssociation::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'fk_stu_id'=>$studentTable->stu_id])->all();
         $stuView=$this->renderAjax('statistics/student-details-pdf',['stu_id'=>$stu_id,'promotedData'=>$promotedData]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('A4');
        $mpdf->WriteHTML($stuView);
        $mpdf->Output('student-promotion-details-'.date("d-m-Y").'.pdf', 'D');
     }
     /*end of student slc and other records*/   
   /*student wise report*/
    public function actionShowStuDataHeadReport(){
        $stu_id= Yii::$app->request->post('stu_id');
        $getStudentInfo=StudentInfo::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'stu_id'=>$stu_id])->one();
        $getStu=$getStudentInfo->user_id;

        $getChalans=Yii::$app->db->createCommand("select fhw.fk_branch_id,fhw.fk_stu_id,ftd.manual_recept_no,ftd.id,ftd.manual_recept_no,ftd.transaction_date as `fee_submission_date` from fee_head_wise fhw inner join fee_transaction_details ftd on ftd.id=fhw.fk_chalan_id where fhw.fk_stu_id=".$stu_id." and fhw.fk_branch_id=".Yii::$app->common->getBranch()." GROUP BY fhw.fk_branch_id,ftd.id,ftd.manual_recept_no, ftd.transaction_date")->queryAll();
        if(count($getStudentInfo)>0){
            $stuView=$this->renderAjax('finance/show-student-data-head-report',['stu_id'=>$stu_id,'getStudentInfo'=>$getStudentInfo,'userid'=>$getStu,'getChalans'=>$getChalans]);
        }else{
            $stuView= "<div class='row'><div class='Alert alert-warning'><strong>Receipt Not Found!</strong></div> </div>";
        }
        return json_encode(['studatas'=>$stuView,'countChallan'=>count($getChalans)]);
    }
 /*get student headwise*/
    public function actionGetStuReceiptWise(){
        $class_id=intval(yii::$app->request->post('id'));
        $getStu=StudentInfo::find()->Select(['stu_id','class_id','section_id','group_id'])->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'class_id'=>$class_id])->asArray()->all();
        if(count($getStu)>0){
            $stuView=$this->renderAjax('finance/get-student-receipt-wise',['students'=>$getStu]);
        }else{
            $stuView= "<div class='row'><div class='Alert alert-warning'><strong>Students not found!</strong></div> </div>";
        }
        return json_encode(['studata'=>$stuView]);
    }
     public function actionHeadwisePaymentRecv(){
         $start= Yii::$app->request->post('start');
         $end = Yii::$app->request->post('end');
          $getStu=StudentInfo::find()->Select(['stu_id','class_id','section_id','group_id'])->where(['fk_branch_id'=>Yii::$app->common->getBranch()])->asArray()->all();
         $startcnvrt=date('Y-m-d',strtotime($start));
         $endcnvrt=date('Y-m-d',strtotime($end));
         $query=yii::$app->db->createCommand("
                select fh.fk_branch_id,fh.title, sum(fhw.payment_received) as `payment_received` from fee_head_wise fhw 
                    right join fee_particulars fp on fp.id=fhw.fk_fee_particular_id
                    right join fee_heads fh on fh.id=fp.fk_fee_head_id
                    where fh.fk_branch_id=".yii::$app->common->getBranch()." and fhw.created_date BETWEEN '".$startcnvrt."' and '".$endcnvrt." 23:59:59.999'
                    group by fh.fk_branch_id,fh.title
            ")->queryAll();
         //echo $query;die;
        $transportFare= yii::$app->db->createCommand("select sum(transport_fare) as transport_fare from fee_head_wise where fk_branch_id=".yii::$app->common->getBranch()." and created_date BETWEEN '".$startcnvrt."' and '".$endcnvrt." 23:59:59.999' ")->queryOne();
      $transportFares=yii::$app->db->createCommand("select fhw.fk_branch_id,rc.title as `class name`,rg.title as `group_name`,rs.title as `section_name`,si.stu_id,u.username, concat(u.first_name,' ',u.middle_name,' ',u.last_name) as `student_name` ,sum(fhw.transport_fare) from fee_head_wise fhw  inner join fee_heads fh on fh.id=fhw.fk_fee_head_id inner join fee_transaction_details fcr on fcr.id=fhw.fk_chalan_id inner join student_info si on si.stu_id=fhw.fk_stu_id inner join ref_class rc on rc.class_id=si.class_id left join ref_group rg on rg.group_id=si.group_id left join ref_section rs on rs.section_id=si.section_id inner join user u on u.id=si.user_id where fhw.fk_branch_id=".yii::$app->common->getBranch()." and fhw.created_date BETWEEN '".$startcnvrt."' and '".$endcnvrt." 23:59:59.999'  GROUP by fhw.transport_fare,  fhw.fk_branch_id,rc.title,rg.title,rs.title,si.stu_id,u.username,concat (u.first_name,' ',u.middle_name,' ',u.last_name)")->queryAll();
         $transportFarex=yii::$app->db->createCommand("
                select fh.fk_branch_id,fh.title,sum(fhw.transport_fare) as `transport_fare` from fee_head_wise fhw 
                    right join fee_particulars fp on fp.id=fhw.fk_fee_particular_id
                    right join fee_heads fh on fh.id=fp.fk_fee_head_id
                    where fh.fk_branch_id=".yii::$app->common->getBranch()." and fhw.created_date BETWEEN '".$startcnvrt."' and '".$endcnvrt." 23:59:59.999'
                    group by fhw.transport_fare,fh.fk_branch_id,fh.title
            ")->queryOne();
           $query_extrahead= yii::$app->db->createCommand("select fh.fk_branch_id,fh.title, sum(fhw.payment_received) as `payment_received` 
                            from fee_head_wise fhw 
                            right join fee_heads fh on fh.id=fhw.fk_fee_head_id 
                            where fh.fk_branch_id=".yii::$app->common->getBranch()." and fhw.created_date BETWEEN '".$startcnvrt."' and '".$endcnvrt." 23:59:59.999'
                            and fhw.fk_fee_particular_id IS NULL and fh.extra_head = 1
                            group by fh.fk_branch_id,fh.title")
             ->queryAll();  
         if(count($query)>0){
         $cashflowView=$this->renderAjax('finance/headwise-payment-recv',['getStu'=>$getStu,'query'=>$query,'extrahead_query'=>$query_extrahead,'transportFare'=>$transportFare,'startcnvrt'=>$startcnvrt,'endcnvrt'=>$endcnvrt]);
     }else{
         $cashflowView= "<div class='row'><div class='col-md-1 col-sm-1'></div><div class='Alert alert-danger col-sm-3'><strong>Not Found!</strong></div> </div>";
     }
         return json_encode(['cashflowhere'=>$cashflowView]);
     
     }
     public function actionHeadwisePaymentRecvPdf(){
         $start= Yii::$app->request->get('start');
         $end = Yii::$app->request->get('end');
         $startcnvrt=date('Y-m-d',strtotime($start));
         $endcnvrt=date('Y-m-d',strtotime($end));
         $query=yii::$app->db->createCommand("
                select fh.fk_branch_id,fh.title, sum(fhw.payment_received) as `payment_received` from fee_head_wise fhw 
                    right join fee_particulars fp on fp.id=fhw.fk_fee_particular_id
                    right join fee_heads fh on fh.id=fp.fk_fee_head_id
                    where fh.fk_branch_id=".yii::$app->common->getBranch()." and fhw.created_date BETWEEN '".$startcnvrt."' and '".$endcnvrt." 23:59:59.999'
                    group by fh.fk_branch_id,fh.title
            ")->queryAll();
            $query_extrahead= yii::$app->db->createCommand("select fh.fk_branch_id,fh.title, sum(fhw.payment_received) as `payment_received` 
                            from fee_head_wise fhw 
                            right join fee_heads fh on fh.id=fhw.fk_fee_head_id 
                            where fh.fk_branch_id=".yii::$app->common->getBranch()." and fhw.created_date BETWEEN '".$startcnvrt."' and '".$endcnvrt." 23:59:59.999'
                            and fhw.fk_fee_particular_id IS NULL and fh.extra_head = 1
                            group by fh.fk_branch_id,fh.title")
             ->queryAll();
             $transportFare= yii::$app->db->createCommand("select sum(transport_fare) as transport_fare from fee_head_wise where fk_branch_id=".yii::$app->common->getBranch()." and created_date BETWEEN '".$startcnvrt."' and '".$endcnvrt." 23:59:59.999' ")->queryOne();
         if(count($query)>0){ 
         $cashflowView=$this->renderAjax('finance/headwise-payment-recv-pdf',['query'=>$query,'extrahead_query'=>$query_extrahead,'transportFare'=>$transportFare]);
         $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML("<h3 style='text-align:center'>Head Wise Payment Received</h3>");
        $mpdf->WriteHTML($cashflowView);
        $mpdf->Output('headwise-payment-recv-'.date("d-m-Y").'.pdf', 'D'); 
         $cashflowView=$this->renderAjax('finance/headwise-payment-recv',['query'=>$query,'extrahead_query'=>$query_extrahead,'transportFare'=>$transportFare]);
     }else{
         $cashflowView= "<div class='row'><div class='Alert alert-warning'><strong>Not Found!</strong></div> </div>";
     }
         return json_encode(['cashflowhere'=>$cashflowView]);
     }
     public function actionDailyCashflowClaswise(){
         $date= Yii::$app->request->post('date');
         $query=yii::$app->db->createCommand("
                select MAX(fhw.transport_fare),fhw.fk_branch_id,rc.title,rc.class_id,sum(fhw.payment_received) from fee_head_wise fhw inner join fee_heads fh on fh.id=fhw.fk_fee_head_id inner join fee_transaction_details fcr on fcr.id=fhw.fk_chalan_id inner join student_info si on si.stu_id=fhw.fk_stu_id inner join ref_class rc on rc.class_id=si.class_id where fhw.fk_branch_id=".yii::$app->common->getBranch()." and cast(fcr.transaction_date as date)='".$date."' GROUP by fhw.fk_branch_id,rc.title,rc.class_id
            ")->queryAll();
         $query_extrahead =yii::$app->db->createCommand("select fhw.fk_branch_id,rc.title,rc.class_id,sum(fhw.payment_received) from fee_head_wise fhw inner join fee_heads fh on fh.id=fhw.fk_fee_head_id inner join fee_transaction_details fcr on fcr.id=fhw.fk_chalan_id inner join student_info si on si.stu_id=fhw.fk_stu_id inner join ref_class rc on rc.class_id=si.class_id where fhw.fk_branch_id=".yii::$app->common->getBranch()." and fhw.fk_fee_particular_id IS NULL and fh.extra_head = 1 and cast(fcr.transaction_date as date)='".$date."' GROUP by fhw.fk_branch_id,rc.title,rc.class_id")->queryAll();
         if(count($query)>0){
         $cashflowView=$this->renderAjax('finance/cashinflow-classwise',['query'=>$query,'date'=>$date,'query_extrahead'=>$query_extrahead]);
     }else{
         $cashflowView= "<div class='row'><div class='Alert alert-warning'><strong>Not Found!</strong></div> </div>";
     }
         return json_encode(['cashflowclass'=>$cashflowView]);
     }
     public function actionClassWiseFee(){
         $class_id= Yii::$app->request->post('classid');
         $dates= Yii::$app->request->post('dates');
         $query=yii::$app->db->createCommand("select fhw.transport_fare,fhw.fk_branch_id,rc.title as `class name`,rg.title as `group_name`,rs.title as `section_name`,si.stu_id,u.username, concat(u.first_name,' ',u.middle_name,' ',u.last_name) as `student_name` ,sum(fhw.payment_received),u.id from fee_head_wise fhw  inner join fee_heads fh on fh.id=fhw.fk_fee_head_id inner join fee_transaction_details fcr on fcr.id=fhw.fk_chalan_id inner join student_info si on si.stu_id=fhw.fk_stu_id inner join ref_class rc on rc.class_id=si.class_id left join ref_group rg on rg.group_id=si.group_id left join ref_section rs on rs.section_id=si.section_id inner join user u on u.id=si.user_id where fhw.fk_branch_id=".yii::$app->common->getBranch()." and rc.class_id=".$class_id." and fcr.transaction_date ='".$dates."' GROUP by fhw.fk_branch_id,rc.title,rg.title,rs.title,si.stu_id,u.username,concat (u.first_name,' ',u.middle_name,' ',u.last_name),u.id,fhw.transport_fare")->queryAll();
         $sumHead=yii::$app->db->createCommand("select fhw.fk_branch_id,rc.title as `class name`,rg.title as `group_name`,rs.title as `section_name`,si.stu_id,u.username, concat(u.first_name,' ',u.middle_name,' ',u.last_name) as `student_name` ,fhw.transport_fare from fee_head_wise fhw  inner join fee_heads fh on fh.id=fhw.fk_fee_head_id inner join fee_transaction_details fcr on fcr.id=fhw.fk_chalan_id inner join student_info si on si.stu_id=fhw.fk_stu_id inner join ref_class rc on rc.class_id=si.class_id left join ref_group rg on rg.group_id=si.group_id left join ref_section rs on rs.section_id=si.section_id inner join user u on u.id=si.user_id where fhw.fk_branch_id=".yii::$app->common->getBranch()." and rc.class_id=".$class_id." and fcr.transaction_date ='".$dates."' GROUP by fhw.transport_fare,  fhw.fk_branch_id,rc.title,rg.title,rs.title,si.stu_id,u.username,concat (u.first_name,' ',u.middle_name,' ',u.last_name)")->queryOne();
         if(count($query)>0){
         $cashflowView=$this->renderAjax('finance/cashinflow-classwiseget',['query'=>$query,'date'=>$dates,'class_id'=>$class_id,'sumHead'=>$sumHead]);
     }else{
         $cashflowView= "<div class='row'><div class='Alert alert-warning'><strong>Not Found!</strong></div> </div>";
     }
         return json_encode(['cashflowclasswise'=>$cashflowView]);
     }
     // yearly admissin report
     public function actionYearlyAdmission(){
        $year=yii::$app->request->post('year');
        $yearAdmission=yii::$app->db->createCommand("SELECT count(*) as `Total_No_of_students_Newly_confirmed_admitted` FROM student_info si2 inner join ref_class rc on rc.class_id=si2.class_id where si2.fk_branch_id=".yii::$app->common->getBranch()." and si2.is_active =1 and si2.stu_id not in (select fk_stu_id from stu_reg_log_association) AND cast(si2.registration_date as date) like '".$year."%'")->queryAll(); 
        $admisnViewYear=$this->renderAjax('statistics/yearly-admission',['year'=>$yearAdmission,'getyear'=>$year]);
        return json_encode(['getYearadmission'=>$admisnViewYear]);
     }
     public function actionYearlyadmissionStudentsnoClasswise(){
        $years=yii::$app->request->post('years');
        $yearAdmissions=yii::$app->db->createCommand("SELECT rc.title as `class_wise`,rc.class_id, count(*) as `No_of_students` FROM student_info si2 inner join ref_class rc on rc.class_id=si2.class_id where si2.fk_branch_id=".yii::$app->common->getBranch()." and si2.is_active =1 and si2.stu_id not in (select fk_stu_id from stu_reg_log_association) and cast(si2.registration_date as date) like '".$years."%' group by rc.title,rc.class_id")->queryAll(); 
        $admisnViewsYear=$this->renderAjax('statistics/yearly-admission-classwise',['years'=>$yearAdmissions,'getyear'=>$years]);
        return json_encode(['getYearadmissionClasswise'=>$admisnViewsYear]);
     }
        public function actionClasswisePdf(){
         $years=yii::$app->request->get('years');
         $attrname= yii::$app->request->get('attrname');
         $yearAdmissions=yii::$app->db->createCommand("SELECT rc.title as `class_wise`,rc.class_id, count(*) as `No_of_students` FROM student_info si2 inner join ref_class rc on rc.class_id=si2.class_id where si2.fk_branch_id=".yii::$app->common->getBranch()." and si2.is_active =1 and si2.stu_id not in (select fk_stu_id from stu_reg_log_association) and cast(si2.registration_date as date) like '".$years."%' group by rc.title,rc.class_id")->queryAll(); 
         if($attrname == "Generate Report"){
            $admisnViewsYear=$this->renderPartial('statistics/yearly-admission-classwiseReport',['years'=>$yearAdmissions,'getyear'=>$years]);
         }else{
            $admisnViewsYear=$this->renderAjax('statistics/yearly-admission-classwise',['years'=>$yearAdmissions,'getyear'=>$years]);
         }
        $this->layout = 'pdf';
        $mpdf = new mPDF('A4');
        $mpdf->WriteHTML("<h3 style='text-align:center'>Details Of Student Enrolled in Year '".$years."'.</h3>");
        $mpdf->WriteHTML($admisnViewsYear);
        $mpdf->Output('student-enrolled-in-year-'.date("d-m-Y").'.pdf', 'D');
        return json_encode(['getYearadmissionClasswise'=>$admisnViewsYear]);
     }// end of class wise pdf
      public function actionYearlyadmissionStudentsnoClasswiseStudent(){
        $classid=yii::$app->request->post('classid');
        $years=yii::$app->request->post('years');
        $yearAdmissionstudents=StudentInfo::find()
        ->where(['class_id'=>$classid,'is_active'=>1,'fk_branch_id'=>Yii::$app->common->getBranch()])
        ->andWhere(['like','registration_date',$years])
        ->all();
        //echo '<pre>';print_r($yearAdmissionstudents);die;
        /*$yearAdmissionstudents=yii::$app->db->createCommand("select si.stu_id as `student_id`,u.username as `registration_no`,concat(u.first_name,' ',u.middle_name,' ',u.last_name) as `student_name`, spi.first_name as `father_name`,rc.title as `class_name`,rg.title as `group_name`,rs.title as `section_name`,si.registration_date from student_info si inner join user u on u.id=si.user_id inner join ref_class rc on rc.class_id=si.class_id left join ref_group rg on rg.group_id=si.group_id inner join ref_section rs on rs.section_id=si.section_id inner join student_parents_info spi on spi.stu_id=si.stu_id where si.fk_branch_id=".yii::$app->common->getBranch()." and si.is_active =1 and si.stu_id not in (select fk_stu_id from stu_reg_log_association) and cast(si.registration_date as date) like '".$years."%' and si.class_id=".$classid."")->queryAll(); */
        $admisnViewsYearClass=$this->renderAjax('statistics/yearly-admission-classwise-students',['yearAdmissionstudents'=>$yearAdmissionstudents,'years'=>$years,'classid'=>$classid]);
        return json_encode(['getYearadmissionClasswiseStudents'=>$admisnViewsYearClass]);
     }
     public function actionYearlyadmissionStudentsnoClasswiseStudentpdf(){
         $classid=yii::$app->request->get('classid');
         $years=yii::$app->request->get('years');
        $yearAdmissionstudents=StudentInfo::find()
        ->where(['class_id'=>$classid,'is_active'=>1,'fk_branch_id'=>Yii::$app->common->getBranch()])
        ->andWhere(['like','registration_date',$years])
        ->all();
        $admisnViewsYearClass=$this->renderAjax('statistics/yearly-admission-classwise-studentspdf',['yearAdmissionstudents'=>$yearAdmissionstudents,'years'=>$years,'classid'=>$classid]);
         $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($admisnViewsYearClass);
        $mpdf->Output('student-enrolled-in-year-'.date("d-m-Y").'.pdf', 'D');
        return json_encode(['getYearadmissionClasswiseStudents'=>$admisnViewsYearClass]);
     }
     // end of yearly admission report
    public function actionClassWiseResultsheet(){
        if(Yii::$app->request->isAjax){
            $data= Yii::$app->request->post();
            if($data['class_id']){
                $students= StudentInfo::find()
                    ->select(['student_info.stu_id','student_info.roll_no sroll_no','u.username roll_no','u.id user_id'])
                    ->innerJoin('user u','u.id=student_info.user_id')
                    ->where(['student_info.fk_branch_id'=>Yii::$app->common->getBranch(),'student_info.class_id'=>$data['class_id'],'student_info.group_id'=>($data['group_id'])?$data['group_id']:null,'student_info.section_id'=>$data['section']])
                    ->orderBy(['student_info.roll_no'=>SORT_ASC])
                    ->asArray()
                    ->all();

                /*total subjects*/
                $subjects = Exam::find()
                    ->select([
                        'sb.id subject_id',
                        'sb.title subject',
                        'sum(exam.total_marks) total_marks'
                    ])
                    ->innerJoin('exam_type et','et.id=exam.fk_exam_type')
                    ->innerJoin('ref_class c','c.class_id=exam.fk_class_id')
                    ->leftJoin('ref_group g','g.group_id=exam.fk_group_id ')
                    ->leftJoin('ref_section s','s.section_id=exam.fk_section_id')
                    ->innerJoin('subjects sb','sb.id=exam.fk_subject_id and c.class_id = sb.fk_class_id and g.group_id=sb.fk_group_id')
                    ->where(['et.id'=>$data['exam_type'], 'c.class_id'=>$data['class_id'],'g.group_id'=>($data['group_id'])?$data['group_id']:null,'s.section_id'=>$data['section']])
                    ->groupBy(['sb.title','sb.id'])->asArray()->all();

                if(count($students)){
                    $studentexam_arr=[];
                    $examsubjects_arr=[];
                    foreach ($students as  $skey=>$stu_id){
                        $subjects_data = Exam::find()
                            ->select([
                                'st.stu_id',
                                'concat(u.first_name," ",u.last_name) student_name',
                                'c.class_id',
                                'c.title',
                                'g.group_id',
                                'g.title',
                                's.section_id',
                                's.title',
                                'sb.title subject',
                                'sum(exam.total_marks) total_marks',
                                'sum(exam.passing_marks) passing_marks',
                                'round(sum(sm.marks_obtained),2) marks_obtained'
                            ])
                            ->innerJoin('exam_type et','et.id=exam.fk_exam_type')
                            ->innerJoin('ref_class c','c.class_id=exam.fk_class_id')
                            ->innerJoin('subjects sb','sb.id=exam.fk_subject_id')
                            ->leftJoin('student_marks sm','sm.fk_exam_id=exam.id')
                            ->leftJoin('student_info st',' st.stu_id=sm.fk_student_id')
                            ->leftJoin('ref_group g','g.group_id=exam.fk_group_id')
                            ->leftJoin('ref_section s','s.class_id=exam.fk_class_id')
                            ->innerJoin('user u','u.id=st.user_id')
                            ->where(['et.id'=>$data['exam_type'],'exam.fk_branch_id'=>Yii::$app->common->getBranch(),'c.class_id'=>$data['class_id'],'g.group_id'=>($data['group_id'])?$data['group_id']:null,'s.section_id'=>$data['section'],'st.stu_id'=>$stu_id['stu_id'] ])
                            ->groupBy(['st.stu_id','c.class_id','c.title','g.group_id','g.title','s.section_id','s.title','sb.title'])->asArray()->all();


                        if(count($subjects_data)>0){
                            $studentexam_arr[$stu_id['stu_id']]['student_id']=$stu_id['roll_no'];
                            $studentexam_arr[$stu_id['stu_id']]['student_roll_no']=$stu_id['sroll_no'];
                            $studentexam_arr[$stu_id['stu_id']]['name']=$stu_id['user_id'];
                            foreach ($subjects_data as $indata){
                                $studentexam_arr[$stu_id['stu_id']][] = $indata['marks_obtained'];

                                if($skey==0){
                                    //echo 'asf';die;
                                    $examsubjects_arr['heads'][] = $indata['subject'];
                                    $examsubjects_arr['total_marks'][] = $indata['total_marks'];
                                }
                            }
                        }
                    }
                    $examtype = ExamType::findOne($data['exam_type']);
                    $details = $this->renderPartial('academics/class_wise_resultsheet',[
                        'query'=>$studentexam_arr,
                        'subjects'=>$subjects,
                        'class_id'=>$data['class_id'],
                        'group_id'=>($data['group_id'])?$data['group_id']:null,
                        'section_id'=>$data['section'],
                        'examtype'=>$examtype,
                        'heads_marks'=>$examsubjects_arr
                    ]);
                    return json_encode(['status'=>1,'details'=>$details]);
                }
            }
        }
        else{
            $data= Yii::$app->request->get();
            if($data['fk_class_id']){
                $students= StudentInfo::find()
                    ->select(['student_info.stu_id','student_info.roll_no sroll_no','u.username roll_no','u.id user_id'])
                    ->innerJoin('user u','u.id=student_info.user_id')
                    ->where(['student_info.fk_branch_id'=>Yii::$app->common->getBranch(),'student_info.class_id'=>$data['fk_class_id'],'student_info.group_id'=>($data['fk_group_id'])?$data['fk_group_id']:null,'student_info.section_id'=>$data['fk_section_id']])
                    ->orderBy(['student_info.roll_no'=>SORT_ASC])
                    ->asArray()
                    ->all();

                /*total subjects*/
                $subjects = Exam::find()
                    ->select([
                        'sb.id subject_id',
                        'sb.title subject',
                        'sum(exam.total_marks) total_marks'
                    ])
                    ->innerJoin('exam_type et','et.id=exam.fk_exam_type')
                    ->innerJoin('ref_class c','c.class_id=exam.fk_class_id')
                    ->leftJoin('ref_group g','g.group_id=exam.fk_group_id ')
                    ->leftJoin('ref_section s','s.section_id=exam.fk_section_id')
                    ->innerJoin('subjects sb','sb.id=exam.fk_subject_id and c.class_id = sb.fk_class_id and g.group_id=sb.fk_group_id')
                    ->where(['et.id'=>$data['fk_exam_type'], 'c.class_id'=>$data['fk_class_id'],'g.group_id'=>($data['fk_group_id'])?$data['fk_group_id']:null,'s.section_id'=>$data['fk_section_id']])
                    ->groupBy(['sb.title','sb.id'])->asArray()->all();
                if(count($students)){
                    $studentexam_arr=[];
                    $examsubjects_arr=[];
                     $scounter= 0;
                     $subjectcounter = 0;

                    ///echo '<pre>';print_r($students);die;
                    foreach ($students as  $skey=>$stu_id){
                        //echo $skey;continue;
                        $subjects_data = Exam::find()
                            ->select([
                                'st.stu_id',
                                'concat(u.first_name," ",u.last_name) student_name',
                                'c.class_id',
                                'c.title',
                                'g.group_id',
                                'g.title',
                                's.section_id',
                                's.title',
                                'sb.title subject',
                                'sum(exam.total_marks) total_marks',
                                'sum(exam.passing_marks) passing_marks',
                                'round(sum(sm.marks_obtained),2) marks_obtained'
                            ])
                            ->innerJoin('exam_type et','et.id=exam.fk_exam_type')
                            ->innerJoin('ref_class c','c.class_id=exam.fk_class_id')
                            ->innerJoin('subjects sb','sb.id=exam.fk_subject_id')
                            ->leftJoin('student_marks sm','sm.fk_exam_id=exam.id')
                            ->leftJoin('student_info st',' st.stu_id=sm.fk_student_id')
                            ->leftJoin('ref_group g','g.group_id=exam.fk_group_id')
                            ->leftJoin('ref_section s','s.class_id=exam.fk_class_id')
                            ->innerJoin('user u','u.id=st.user_id')
                            ->where(['et.id'=>$data['fk_exam_type'],'exam.fk_branch_id'=>Yii::$app->common->getBranch(),'c.class_id'=>$data['fk_class_id'],'g.group_id'=>($data['fk_group_id'])?$data['fk_group_id']:null,'s.section_id'=>$data['fk_section_id'],'st.stu_id'=>$stu_id['stu_id'] ])
                            ->groupBy(['st.stu_id','c.class_id','c.title','g.group_id','g.title','s.section_id','s.title','sb.title'])->asArray()->all();

                        if(count($subjects_data)>0){
                            //echo "<pre>";print_r($subjects_data);continue;
                            $sumTotalMarks = 0;
                            $studentexam_arr[$stu_id['stu_id']]['student_id']=$stu_id['roll_no'];
                            $studentexam_arr[$stu_id['stu_id']]['student_roll_no']=$stu_id['sroll_no'];
                            $studentexam_arr[$stu_id['stu_id']]['name']=$stu_id['user_id'];
                            $std = $stu_id['stu_id'];
                            $passing_marks_array=[];
                            foreach ($subjects_data as $inkey =>$indata){
                                
                                //echo $indata['subject'];continue;
                                if($std == $stu_id['stu_id']){
                                    $sumTotalMarks  =  $sumTotalMarks + $indata['marks_obtained'];
                                    $studentToralMarks [$stu_id['stu_id']] = $sumTotalMarks;
                                }
                                /*$studentexam_arr[$stu_id['stu_id']][] = floatval($indata['marks_obtained']); old code*/
                                 $studentexam_arr[$stu_id['stu_id']][] = floatval($indata['marks_obtained']);
                                 $passing_marks_array[]=$indata['passing_marks'];
                               //echo $skey;die;

                                if( $subjectcounter==0){
                                    $examsubjects_arr['heads'][] = $indata['subject'];
                                    $examsubjects_arr['total_marks'][] = $indata['total_marks'];
                                }

                                /*sum condition*/
                                if($std != $stu_id['stu_id']){
                                    $sumTotalMarks  = 0;
                                } 
                                
                            }
                            //die;
                            $subjectcounter++;
                        }
                    } 
                    /*echo "<pre>";print_r($examsubjects_arr);
                   die;*/
                       /*maintain student id's and sort desc.*/
                    natcasesort($studentToralMarks);
                    $sortArr = array_reverse($studentToralMarks, true);
                    $position  = [];
                    $counter= 1;
                    $stdMarks = 0;
                    /*custom sort*/
                   
                    foreach($sortArr as $key=>$totalStdObtainMarks){
                        if($stdMarks ==0){
                            $stdMarks = $totalStdObtainMarks;
                        }
                        if($stdMarks == $totalStdObtainMarks){
                            //echo $stdMarks.'----'.$totalStdObtainMarks.'----' .$counter."<br/>";
                            $position[] = ['position'=>$counter,'student_id'=>$key,'marks'=>$totalStdObtainMarks];
                             $position[] = ['position'=>$counter,'student_id'=>$key,'marks'=>$totalStdObtainMarks];
                            //$this->array_orderby(array_orderby($position, 'position', SORT_ASC););
                            $v= $this->array_orderby($position, 'position', SORT_ASC);
                        }else{
                            $counter = $counter+1;
                            $position[] = ['position'=>$counter,'student_id'=>$key,'marks'=>$totalStdObtainMarks];
                            //$this->array_orderby(array_orderby($position, 'position', SORT_ASC););
                            $v= $this->array_orderby($position, 'position', SORT_ASC);
                            //echo '<pre>';print_r($v);die;
                            

                            //echo '<pre>';print_r($position);die;


                            //echo $stdMarks.'----'.$totalStdObtainMarks.'----' .$counter."-No pos - <br/>";
                        }
                        $stdMarks = $totalStdObtainMarks;
                    }
                   // echo '<pre>';print_r($v);die;
                    $examtype = ExamType::findOne($data['fk_exam_type']);
                    $resultsheet= Yii::$app->common->getCGSName($data['fk_class_id'],$data['fk_group_id'],$data['fk_section_id']).' - '.ucfirst($examtype->type);
                   // echo '<pre>';print_r($examsubjects_arr);die;
                    $details = $this->renderPartial('academics/class_wise_resultsheet',[
                        'query'=>$studentexam_arr,
                        //'v'=>$v,
                        'passing_marks_array'=>$passing_marks_array,
                        'subjects'=>$subjects,
                        'class_id'=>$data['fk_class_id'],
                        'group_id'=>($data['fk_group_id'])?$data['fk_group_id']:null,
                        'section_id'=>$data['fk_section_id'],
                        'examtype'=>$examtype,
                        'heads_marks'=>$examsubjects_arr,
                        'positions'=>$v
                        //'positions'=>$position
                    ]);
                    //echo $details;die;
                    $this->layout = 'pdf';
                    //$mpdf = new mPDF('', 'A4');
                    $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
                    $stylesheet = file_get_contents('css/pdf.css'); // external css
                    $mpdf->WriteHTML($stylesheet,1);
                    $mpdf->WriteHTML($details);
                    $mpdf->Output('Result-sheet-'.$resultsheet.'.pdf', 'D');
                }
            }
        }
    }

    function array_orderby()
{
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
            }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}
    public function actionNewAdmissionClasswisePdf(){
        $newadmissionview=$this->renderAjax('statistics/new-admission-classwise-pdf');
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $stylesheet = file_get_contents('css/std-ledger-pdf.css');
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->WriteHTML("<h3 style='text-align:center'>New Admission Class Wise</h3>");
        $mpdf->WriteHTML($newadmissionview);
        $mpdf->Output('new-admission-class-wise-'.date("d-m-Y").'.pdf', 'D'); 

    }
     public function actionNewPromotionClasswisePdf(){
        $newadmissionview=$this->renderAjax('statistics/new-promotion-classwise-pdf');
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $stylesheet = file_get_contents('css/std-ledger-pdf.css');
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->WriteHTML("<h3 style='text-align:center'>Promotion Class Wise</h3>");
        $mpdf->WriteHTML($newadmissionview);
        $mpdf->Output('promotion-class-wise-'.date("d-m-Y").'.pdf', 'D'); 
    }
    public function actionOverAllTransportPdf(){
        $newadmissionview=$this->renderAjax('statistics/overall-transport-pdf');
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $stylesheet = file_get_contents('css/std-ledger-pdf.css');
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->WriteHTML($newadmissionview);
        $mpdf->Output('overall-transport-student-wise-'.date("d-m-Y").'.pdf', 'D'); 
    }
public function actionStudentsOverallReportPdf(){
        $startdate=yii::$app->request->get('startdate');
        $enddate=yii::$app->request->get('enddate');
        $startcnvrt=date('Y-m-d',strtotime($startdate));
        $endcnvrt=date('Y-m-d',strtotime($enddate));
        $getChalans=Yii::$app->db->createCommand("select fhw.fk_branch_id,fhw.fk_stu_id,ftd.manual_recept_no,ftd.id,ftd.manual_recept_no,ftd.transaction_date as `fee_submission_date` from fee_head_wise fhw inner join fee_transaction_details ftd on ftd.id=fhw.fk_chalan_id where fhw.fk_branch_id=".Yii::$app->common->getBranch()." and CAST(ftd.transaction_date AS DATE) >= '".$startcnvrt."' and CAST(ftd.transaction_date AS DATE) <= '".$endcnvrt."' GROUP BY fhw.fk_branch_id,fhw.fk_stu_id,ftd.id,ftd.manual_recept_no, ftd.transaction_date ORDER BY ftd.manual_recept_no ASC")->queryAll();

        if(count($getChalans)>0){
            $newadmissionview=$this->renderAjax('finance/overall-student-reports-pdf',['getChalans'=>$getChalans]);
        }else{
            $newadmissionview= "<div class='row'><div class='Alert alert-warning'><center><strong>Not Found!</strong></center></div> </div>";
        } 
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $stylesheet = file_get_contents('css/std-ledger-pdf.css');
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->WriteHTML("<h3 style='text-align:center'>Students over all report</h3>");
        $mpdf->WriteHTML($newadmissionview);
        $mpdf->Output('overall-student-reports-'.date("d-m-Y").'.pdf', 'D'); 

    }
       public function actionStudentOverllReport(){
         $startdate=yii::$app->request->post('startdate');
         $enddate=yii::$app->request->post('enddate');
         $startcnvrt=date('Y-m-d',strtotime($startdate));
         $endcnvrt=date('Y-m-d',strtotime($enddate));
         $getChalans=Yii::$app->db->createCommand("select fhw.fk_branch_id,fhw.fk_stu_id,ftd.manual_recept_no,ftd.id,ftd.manual_recept_no,ftd.transaction_date as `fee_submission_date` from fee_head_wise fhw inner join fee_transaction_details ftd on ftd.id=fhw.fk_chalan_id where fhw.fk_branch_id=".Yii::$app->common->getBranch()." and CAST(ftd.transaction_date AS DATE) >= '".$startcnvrt."' and CAST(ftd.transaction_date AS DATE) <= '".$endcnvrt."' GROUP BY fhw.fk_branch_id,fhw.fk_stu_id,ftd.id,ftd.manual_recept_no, ftd.transaction_date ORDER BY ftd.manual_recept_no ASC")->queryAll();
         if(count($getChalans)>0){
          $overallstudents= $this->renderAjax('finance/overall-students',['startcnvrt'=>$startcnvrt,'endcnvrt'=>$endcnvrt]);
         }else{
         $overallstudents= "<div class='row'><div class='col-md-1 col-sm-1'></div><div class='Alert alert-danger col-sm-3'><center><strong>No Record Found!</strong></center></div> </div>";
         }
        return json_encode(['overallstudents'=>$overallstudents]);
    }
    /*================ */
      public function actionLeaveSchool(){
        $year=yii::$app->request->post('years');
        $leaveInfo=StudentLeaveInfo::find()->where(['like','created_date',$year])->count();
        $showleavestu=$this->renderAjax('statistics/leave-school',['year'=>$year,'leaveInfo'=>$leaveInfo]);
        return json_encode(['showleavestu'=>$showleavestu]);
     }
     public function actionLeaveSchoolPdf(){
        $year=yii::$app->request->get('years');
        $leaveInfo=StudentLeaveInfo::find()->where(['like','created_date',$year])->count();
        $showleavestu=$this->renderAjax('statistics/leave-school-pdf',['year'=>$year,'leaveInfo'=>$leaveInfo]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML("<h3 style='text-align:center'>Previous students report of year($year)</h3>");
        $mpdf->WriteHTML($showleavestu);
        $mpdf->Output('Previous-student-report-'.date("d-m-Y").'.pdf', 'D'); 
        return json_encode(['showleavestu'=>$showleavestu]);
     }
     public function actionLeaveSchollClass(){
         $year=yii::$app->request->post('years');
        $leaveInfo=yii::$app->db->createCommand("SELECT count(*) as total_student,class_id from student_leave_info where created_date like '".$year."%' GROUP BY class_id")->queryAll();
        $showleavestu=$this->renderAjax('statistics/leave-school-year',['year'=>$year,'leaveInfo'=>$leaveInfo]);
        return json_encode(['showleavestu'=>$showleavestu]);
     }
      public function actionLeaveSchollClassPdf(){
         $year=yii::$app->request->get('years');
         $leaveInfo=yii::$app->db->createCommand("SELECT count(*) as total_student,class_id from student_leave_info where created_date like '".$year."%' GROUP BY class_id")->queryAll();
         $showleavestu=$this->renderAjax('statistics/leave-school-year-pdf',['year'=>$year,'leaveInfo'=>$leaveInfo]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML("<h3 style='text-align:center'>Previous students class report of year($year)</h3>");
        $mpdf->WriteHTML($showleavestu);
        $mpdf->Output('Previous-student-class-report-'.date("d-m-Y").'.pdf', 'D'); 
        return json_encode(['showleavestu'=>$showleavestu]);
     }
     public function actionLeaveScholClassStudent(){
         $year=yii::$app->request->post('years');
         $clas=yii::$app->request->post('clas');
       $leaveInfo=StudentLeaveInfo::find()->where(['class_id'=>$clas])->andWhere(['like','created_date',$year])->all();
        $showleavestu=$this->renderAjax('statistics/leave-school-year-student',['year'=>$year,'leaveInfo'=>$leaveInfo]);
        return json_encode(['showleavestu'=>$showleavestu]);
     }
     public function actionLeaveScholClassStudentPdf(){
          $year=yii::$app->request->get('years');
          $clas=yii::$app->request->get('clas');
       $leaveInfo=StudentLeaveInfo::find()->where(['class_id'=>$clas])->andWhere(['like','created_date',$year])->all();
        $showleavestu=$this->renderAjax('statistics/leave-school-year-student-pdf',['year'=>$year,'leaveInfo'=>$leaveInfo]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
       $mpdf->WriteHTML($showleavestu);
        $mpdf->Output('Previous-student-class students-report-'.date("d-m-Y").'.pdf', 'D'); 
        return json_encode(['showleavestu'=>$showleavestu]);
     }
     /*=============== end of leave report*/
     /*start of student attendance reports*/
     public function actionStudentAttendanceReport(){
         $model = new StudentInfo();
         $nameAttendance=\app\models\StudentAttendance::find()
               ->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'date(date)'=>date('Y-m-d')])->andWhere(['!=','leave_type','present'])->all();
         $beforeAttendance=\app\models\StudentAttendance::find()
               ->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'date(date)'=>date('Y-m-d')])->andWhere(['!=','leave_type','present'])->all();
         $beforeAttendance=yii::$app->db->createCommand('SELECT * FROM student_attendance WHERE fk_branch_id='.Yii::$app->common->getBranch().' and date > DATE_SUB(NOW(), INTERVAL 4 DAY) and leave_type != "present" ORDER by id desc')->queryAll();
         return $this->render('student-attendance-reports',['model'=>$model,'nameAttendance'=>$nameAttendance,'beforeAttendance'=>$beforeAttendance]);
     }
     public function actionStudentAttendanceReportPdf(){
         $model = new StudentInfo();
         $nameAttendance=\app\models\StudentAttendance::find()
               ->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'date(date)'=>date('Y-m-d')])->andWhere(['!=','leave_type','present'])->all();
         $nameAttenceStaus= $this->renderAjax('statistics/student-attendance-reports-pdf',['model'=>$model,'nameAttendance'=>$nameAttendance]);
         $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
         $mpdf->WriteHTML($nameAttenceStaus);
         $mpdf->Output('student-today-attendance-'.date("d-m-Y").'.pdf', 'D');
     }
     public function actionLastFourDaysPdf(){
         $beforeAttendance=yii::$app->db->createCommand('SELECT * FROM student_attendance WHERE fk_branch_id='.Yii::$app->common->getBranch().' and date > DATE_SUB(NOW(), INTERVAL 4 DAY) and leave_type != "present" ORDER by id desc')->queryAll();
         $fourdaysAttend= $this->renderAjax('statistics/four-days-atten-reports-pdf',['beforeAttendance'=>$beforeAttendance]);
         $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
         $mpdf->WriteHTML($fourdaysAttend);
         $mpdf->Output('student-last-four-days-attendance-'.date("d-m-Y").'.pdf', 'D');
     }
     public function actionNewlyPromotionName(){
        if(!isset($_GET['id'])){
        $class_id=Yii::$app->request->post('class_id');
        $studentDetails = \app\models\StudentInfo::find()
            ->select(['student_info.*','stu_reg_log_association.*'])
            ->innerJoin('stu_reg_log_association','stu_reg_log_association.fk_stu_id = student_info.stu_id')
            ->where([
                'student_info.fk_branch_id'=>yii::$app->common->getBranch(),
                'student_info.class_id'   => $class_id,
            ])->orderBy(['student_info.section_id'=>SORT_DESC])->asArray()->all();
        /*$studentDetails=Yii::$app->db->createCommand("select * from student_info where is_active=1 and class_id =$class_id and fk_branch_id='".yii::$app->common->getBranch()."' and exists (select * from stu_reg_log_association where fk_stu_id = student_info.stu_id)")->queryAll();*/
        $view=$this->renderAjax('academics/newly-promotion-name',['class_id'=>$class_id,'studentDetails'=>$studentDetails]);
        return json_encode(['view'=>$view]);
        }else{
        $class_id=base64_decode(Yii::$app->request->get('id'));
       $studentDetails = \app\models\StudentInfo::find()
            ->select(['student_info.*','stu_reg_log_association.*'])
            ->innerJoin('stu_reg_log_association','stu_reg_log_association.fk_stu_id = student_info.stu_id')
            ->where([
                'student_info.fk_branch_id'=>yii::$app->common->getBranch(),
                'student_info.class_id'   => $class_id,
            ])->orderBy(['student_info.section_id'=>SORT_DESC])->asArray()->all();
        $view=$this->renderAjax('academics/newly-promotion-name',['class_id'=>$class_id,'studentDetails'=>$studentDetails]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($view);
        $mpdf->Output('promotion-student-'.date("d-m-Y").'.pdf', 'D');
        }
     }
     public function actionSibling(){
        $siblingQuery=StudentInfo::find()->where(['is_active'=>1,'fk_branch_id'=>yii::$app->common->getBranch(),'avail_sibling_discount'=>1])->orderBy(['roll_no'=>SORT_ASC]);
        if(\Yii::$app->request->get('page')){
            $Page = new Pagination([
                'totalCount' => $siblingQuery->count(),
                'defaultPageSize' => 100,
                'params' => [
                    'page' => \Yii::$app->request->get('page')
                ]
            ]);
            $offset = $Page->limit * (\Yii::$app->request->get('page') - 1);
            $modelData = $siblingQuery->offset($offset)
                ->limit($Page->limit)->all();
        }else{

        $Page = new Pagination([
                'totalCount' => $siblingQuery->count(),
                'defaultPageSize' => 100,
                'params' => [
                    //'other_id' => $post_data['other_id'],
                    //'page' => $post_data['page']
                ]
            ]);
        $offset = $siblingQuery->limit;
        $modelData = $siblingQuery->offset($offset)->limit($Page->limit)->all();
       }
        return $this->render('academics/sibling',['sibling'=>$modelData,'pages' => $Page]);
     }
      public function actionGeneralReport(){
        $model = new StudentInfo();
        $request = Yii::$app->request;
        $total_students=StudentInfo::find()->where(['is_active'=>1,'fk_branch_id'=>yii::$app->common->getBranch()])->all();
        $siblingQuery=StudentInfo::find()->where(['is_active'=>1,'fk_branch_id'=>yii::$app->common->getBranch(),'avail_sibling_discount'=>1])->orderBy(['roll_no'=>SORT_ASC]);
        $getclaswise=yii::$app->db->createCommand("SELECT rc.title, count(*) as `No_of_students` FROM student_info si2 inner join ref_class rc on rc.class_id=si2.class_id where si2.fk_branch_id=".yii::$app->common->getBranch()." and si2.is_active =1 and si2.stu_id not in (select fk_stu_id from stu_reg_log_association) group by rc.title")->queryAll();
         $getclaswiseDeactive=yii::$app->db->createCommand("SELECT rc.title, count(*) as `No_of_students` FROM student_info si2 inner join ref_class rc on rc.class_id=si2.class_id where si2.fk_branch_id=".yii::$app->common->getBranch()." and si2.is_active =0 and si2.stu_id not in (select fk_stu_id from stu_reg_log_association) group by rc.title")->queryAll();

         $promotedclaswise=yii::$app->db->createCommand("select rc.title as `class_name`, count(*) as `No_of_new_promoted_class_wise` from student_info si inner join ref_class rc on rc.class_id=si.class_id where si.fk_branch_id=".yii::$app->common->getBranch()." and si.stu_id in (select fk_stu_id from stu_reg_log_association) GROUP by rc.title")->queryAll();

          $promtedclasswixeAvg=yii::$app->db->createCommand("select abc.class_name,abc.No_Of_Student, ((abc.No_Of_Student)/ (select count(*) from student_info))*100 as `Average_Promoted_Students_per_Class` from (select rc.title as `class_name`, count(*) as `No_Of_Student` from student_info si inner join ref_class rc on rc.class_id=si.class_id where si.stu_id in (select fk_stu_id from stu_reg_log_association) and si.is_active=1 GROUP by rc.title)abc")->queryAll();

              return $this->render('general-reports', [
                'total_students'=>$total_students,
                'getclaswise' => $getclaswise,
                'promotedclaswise' => $promotedclaswise,
                'promtedclasswixeAvg' => $promtedclasswixeAvg,
                'getclaswiseDeactive' => $getclaswiseDeactive,
                'model' => $model,
                //'siblingsCheck' => $siblingsCheck,
                ]);
     }
     public function actionSiblingPdf(){
        ini_set('max_execution_time', 300);
        $siblingQuery=StudentInfo::find()->where(['is_active'=>1,'fk_branch_id'=>yii::$app->common->getBranch(),'avail_sibling_discount'=>1])->orderBy(['roll_no'=>SORT_ASC])->all();
        $view=$this->renderAjax('finance/pdf/sibling-discount-pdf',['sibling' => $siblingQuery]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($view);
        $mpdf->Output('sibling-details-'.date("d-m-Y").'.pdf', 'D');
     }
      public function actionTransport(){
         $model = new StudentInfo();
        return $this->render('transport-reports',['model'=>$model]);
     }
     /*End of student attendance reports*/
     /*start of staff report*/
     public function actionStaffReport(){
        return $this->render('staff-report');
     }
     /*End of staff report*/
     /*=================start of fee reports*/
     public function actionStudentFeeDetails(){
        $data=yii::$app->request->post();
        $class_id=yii::$app->request->post('classId');
        $stu_id=$data['stuId'];
        $studentTable=\app\models\StudentInfo::find()->where(['user_id'=>$stu_id])->one();
        $student_id=$studentTable->stu_id;
        $studentParentInfo =  \app\models\StudentParentsInfo::find()->where(['stu_id'=>$student_id])->one(); 
        if(count($studentParentInfo)>0 && !empty($studentParentInfo->cnic)){
            $parent_cnic = $studentParentInfo->cnic;
            $cnic_count =  \app\models\StudentParentsInfo::find()->where(['cnic'=>$studentParentInfo->cnic])->count();
        }
        $getFeeDetails = \app\models\FeeGroup::find()
        ->where([
         'fk_branch_id'  =>Yii::$app->common->getBranch(),
         'fk_class_id'   => $class_id,
         //'fk_group_id'   => ($group_id)?$group_id:null,
         ])->all();

        if(count($getFeeDetails) > 0){ 
    $viewFeedetails=$this->renderAjax('finance/students-fee',['getFeeDetails'=>$getFeeDetails,'student_id'=>$student_id,'class_id'=>$class_id,'studentTable'=>$studentTable,'stu_id'=>$stu_id,'cnic_count'=>$cnic_count,'parent_cnic'=>$parent_cnic
        ]);
    }else{
      $viewFeedetails='<div class="row col-md-6 alert-warning">No Fee details found</div>';
    }
    return json_encode(['studentFeeDetails'=>$viewFeedetails]);
     }

      public function actionStudentFeeDetailsPdf(){
        $data=yii::$app->request->post();
        $class_id=yii::$app->request->get('classid');
        $student_id=yii::$app->request->get('stuid');
        $studentTable=\app\models\StudentInfo::find()->where(['stu_id'=>$student_id])->one();
        $userId=$studentTable->user_id;
        $student_id=$studentTable->stu_id;
        $studentParentInfo =  \app\models\StudentParentsInfo::find()->where(['stu_id'=>$student_id])->one(); 
        if(count($studentParentInfo)>0 && !empty($studentParentInfo->cnic)){
            $parent_cnic = $studentParentInfo->cnic;
            $cnic_count =  \app\models\StudentParentsInfo::find()->where(['cnic'=>$studentParentInfo->cnic])->count();
        }
        $getFeeDetails = \app\models\FeeGroup::find()
        ->where([
         'fk_branch_id'  =>Yii::$app->common->getBranch(),
         'fk_class_id'   => $class_id,
         ])->all();
        $viewFeedetails=$this->renderAjax('finance/pdf/students-fee-pdf',['getFeeDetails'=>$getFeeDetails,'student_id'=>$student_id,'class_id'=>$class_id,'userId'=>$userId,'studentTable'=>$studentTable,'cnic_count'=>$cnic_count,'parent_cnic'=>$parent_cnic
        ]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($viewFeedetails);
        $mpdf->Output('student-fee-details-'.date("d-m-Y").'.pdf', 'D');
     }
     /*======fee rcv report*/
       /*student arrear*/
       public function actionStudentArrear(){
        $data=yii::$app->request->post();
        $class_id=yii::$app->request->post('classId');
        $alumniStudents=yii::$app->request->post('alumniStudents');
        $stu_id=$data['stuId'];
        $studentTable=\app\models\StudentInfo::find()->where(['user_id'=>$stu_id])->one();
        $student_id=$studentTable->stu_id;
        
        $arrearsDetails=\app\models\FeeArears::find()->where(['branch_id'=>yii::$app->common->getBranch(),'status'=>1,'stu_id'=>$student_id])->all();
        if(count($arrearsDetails) > 0){
        $viewFeedetails=$this->renderAjax('finance/fee-arrear',['student_id'=>$student_id,'class_id'=>$class_id,'studentTable'=>$studentTable,'stu_id'=>$stu_id,'arrearsDetails'=>$arrearsDetails
        ]);
    }else{
        $fee_submission = \app\models\FeeSubmission::find()->where(['stu_id'=> $student_id,'fee_status'=>1])->one();
        if(count($fee_submission->transport_arrears)>0){
         $viewFeedetails='<h4>Transport Arrears is Rs: '.$fee_submission->transport_arrears.'</h4>';
        }else{
            $viewFeedetails='<div class="row col-md-6 alert-warning">No Fee details found</div>';
        }
    }
       //}else{
       // $viewFeedetails='<div class="row col-md-6 alert-warning">No Fee details found</div>';
       //}
       return json_encode(['studentArrear'=>$viewFeedetails]);
       }//end of function

        public function actionStudentArrearPdf(){
            $stu_id=yii::$app->request->get('id');
            $class_id=yii::$app->request->post('classId');
            $studentTable=\app\models\StudentInfo::find()->where(['user_id'=>$stu_id])->one();
            $student_id=$studentTable->stu_id;
            $arrearsDetails=\app\models\FeeArears::find()->where(['branch_id'=>yii::$app->common->getBranch(),'status'=>1,'stu_id'=>$student_id])->all();
            $viewFeedetails=$this->renderAjax('finance/pdf/fee-arrear-pdf',['arrearsDetails'=>$arrearsDetails,'student_id'=>$student_id,'class_id'=>$class_id,'studentTable'=>$studentTable,'stu_id'=>$stu_id
            ]);
            $this->layout = 'pdf';
            $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
            $mpdf->WriteHTML($viewFeedetails);
            $mpdf->Output('student-arrears-'.date("d-m-Y").'.pdf', 'D');

        }//end of function
       /*=========class arrears*/
       public function actionClassArrears(){
        $data=yii::$app->request->post();
        $classid=$data['classid'];
        $groupid=$data['groupid'];
        $sectionid=$data['id'];
        $studentArray=StudentInfo::find()
        ->where([
            'class_id'=>$classid,
            'group_id'=>($groupid)?$groupid:null,
            'section_id'=>$sectionid,
            'is_active'=>1,
        ])
        ->orderBy(['student_info.roll_no'=>SORT_DESC])
        ->asArray()->all();
        //->createCommand()->getRawSql();
         //echo '<pre>';print_r($studentArray);die;
       /* if(empty($groupid)){
            $studentArray=Yii::$app->db->createCommand("SELECT `s`.* from student_info `s` WHERE `class_id`=$classid and section_id=$sectionid and is_active=1 and exists( select `fs`.stu_id from `fee_submission` `fs` where fs.stu_id=s.stu_id and fee_status = 1) order by roll_no asc")->queryAll();
        }else{
          $studentArray=Yii::$app->db->createCommand("SELECT `s`.* from student_info `s` WHERE `class_id`=$classid and group_id=$groupid and section_id=$sectionid and is_active=1 and exists( select `fs`.stu_id from `fee_submission` `fs` where fs.stu_id=s.stu_id and fee_status = 1) order by roll_no asc")->queryAll();  
        }*/
        
        if(count($studentArray)>0){
            $classArrears=$this->renderAjax('finance/class-arrear',['studentArray'=>$studentArray,'classid'=>$classid,'groupid'=>$groupid,'sectionid'=>$sectionid]);
        }else{
            $classArrears='<div class="row col-md-offset-2 col-md-6 alert-danger">No Fee Arrears Found..!</div>';
        }
        return json_encode(['counStudent'=>$classArrears]);
       }
       public function actionClassArrearsPdf(){
        $classid=yii::$app->request->get('cid');
        $sectionid=yii::$app->request->get('id');
        $groupid=yii::$app->request->get('gid');
        $studentArray=StudentInfo::find()
        ->where([
            'class_id'=>$classid,
            'group_id'=>($groupid)?$groupid:null,
            'section_id'=>$sectionid,
            'is_active'=>1,
        ])
        ->orderBy(['student_info.roll_no'=>SORT_DESC])
        ->asArray()->all();
        $viewFeedetails=$this->renderAjax('finance/pdf/class-arrear-pdf',['studentArray'=>$studentArray,'classid'=>$classid,'groupid'=>$groupid,'sectionid'=>$sectionid
        ]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($viewFeedetails);
        $mpdf->Output('class-arrears-'.date("d-m-Y").'.pdf', 'D');
       }
       /*=========end of class arrears & start of Class Fee*/
       /*=========class arrears*/
       public function actionClassFee(){
        $classid=yii::$app->request->post('class_id');
        $groupid=yii::$app->request->post('group_id');
        $sectionid=yii::$app->request->post('section_id');
        $studentArray = \app\models\FeeSubmission::find()
            ->select(['fee_submission.*','student_info.stu_id','student_info.fk_branch_id','student_info.user_id'])
            ->innerJoin('student_info','student_info.stu_id = fee_submission.stu_id')
            ->where([
                'student_info.fk_branch_id'=>yii::$app->common->getBranch(),
                'student_info.class_id'   => $classid,
                'student_info.group_id'   => ($groupid)?$groupid:null,
                'student_info.section_id' => $sectionid,
            ])->asArray()->all();
           // echo '<pre>';print_r($studentArray);die;
        if(count($studentArray)>0){
            $classArrears=$this->renderAjax('finance/class-fee',['studentArray'=>$studentArray,'classid'=>$classid,'groupid'=>$groupid,'sectionid'=>$sectionid]);
        }else{
            $classArrears='<div class="row col-md-offset-2 col-md-6 alert-danger">No Any Fee Found..!</div>';
        }
        return json_encode(['status'=>1,'details'=>$classArrears]);
       }
       public function actionClassFeePdf(){
        $classid=yii::$app->request->get('cid');
        $groupid=yii::$app->request->get('gid');
        $sectionid=yii::$app->request->get('id');
        $studentArray = \app\models\FeeSubmission::find()
            ->select(['fee_submission.*','student_info.stu_id','student_info.fk_branch_id','student_info.user_id'])
            ->innerJoin('student_info','student_info.stu_id = fee_submission.stu_id')
            ->where([
                'student_info.fk_branch_id'=>yii::$app->common->getBranch(),
                'student_info.class_id'   => $classid,
                'student_info.group_id'   => ($groupid)?$groupid:null,
                'student_info.section_id' => $sectionid,
            ])->asArray()->all();
            $viewFeedetails=$this->renderAjax('finance/pdf/class-fee-pdf',['studentArray'=>$studentArray,'classid'=>$classid,'groupid'=>$groupid,'sectionid'=>$sectionid
            ]);
           $this->layout = 'pdf';
           $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
           $mpdf->WriteHTML($viewFeedetails);
           $mpdf->Output('class-Fee-'.date("d-m-Y").'.pdf', 'D');
       }
     /*=================end of fee reports*/
     /*==========start of exam reports*/
     public function actionExam(){
        $model=new Exam();
        $exam=\app\models\Exam::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()]);
        $examArray = \app\models\Exam::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->all();
        return $this->render('exam',[
            'model'=>$model,
            'examArray'=>$examArray,
        ]);
     }
     /*==========end of exam reports*/
     /*======== student issue slc certificate*/
     public function actionSlc(){
       $model=new StudentInfo();
        $stu_id= yii::$app->request->post('id');
        return $this->render('slc',['model'=>$model]);
     }
     public function actionLeavingPdf(){
         $stu_id= yii::$app->request->post('id');
         $radioValue= yii::$app->request->post('radioValue');
         if($radioValue == 1){
          Yii::$app->response->redirect(['reports/slc-slip','id' => $stu_id]);
         }else if($radioValue == 2){
          Yii::$app->response->redirect(['reports/character-slip','id' => $stu_id]);
         }
     }
    
     public function actionSlcSlip(){
        $id = Yii::$app->request->get('id');
        $levInfo=StudentLeaveInfo::find()->where(['stu_id'=>$id])->one();
        if(count($levInfo)>0){
        $slcview = $this->renderAjax('academics/leaving-pdf',['id'=>$id]);
        $this->layout = 'pdf';
       $mpdf = new mPDF('c', 'A4-L');
       $mpdf->WriteHTML($slcview);
        $mpdf->Output('Student-SLC.pdf', 'D');
        }else{
        Yii::$app->session->setFlash('Warning', "This student not leaving institute yet..!"); 
        Yii::$app->response->redirect(['reports/slc']);   
        }
     }
     public function actionCharacterSlip(){
        $id = Yii::$app->request->get('id');
        $levInfo=StudentLeaveInfo::find()->where(['stu_id'=>$id])->one();
        if(count($levInfo)>0){
        $slcview = $this->renderAjax('academics/character-pdf',['id'=>$id]);
        $this->layout = 'pdf';
       $mpdf = new mPDF('c', 'A4-L');
       $mpdf->WriteHTML($slcview);
        $mpdf->Output('Character-SLC.pdf', 'D');
        }else{
        Yii::$app->session->setFlash('Warning', "This student not leaving institute yet..!"); 
        Yii::$app->response->redirect(['reports/slc']);   
        }
     }
      public function actionSportsPdf(){ //sports certificate
         $user_id= yii::$app->request->post('user_id');
         $sportsName= yii::$app->request->post('sportsName');
         Yii::$app->response->redirect(['reports/sports-certificate-pdf','user_id' => $user_id,'sportsName'=>$sportsName]);
     } //end of function
     public function actionSportsCertificatePdf(){ //sports certificate
         $user_id= yii::$app->request->get('user_id');
         $sportsName= yii::$app->request->get('sportsName');
         $studentDetails = Yii::$app->common->getStudentByUserId($user_id);
       //  echo '<pre>';print_r($studentDetails);die;
         $sportsView = $this->renderAjax('academics/sports-pdf',['studentDetails'=>$studentDetails,'sportsName'=>$sportsName]);
         //echo $sportsView;die;
         $this->layout = 'pdf';
         $mpdf = new mPDF('c', 'A4-L');
         $mpdf->WriteHTML($sportsView);
         $mpdf->Output('sports-Certificate.pdf', 'D');
         
     }
     /*======== end of student issue slc,sports, certificate and start of employee experiance*/
      public function actionEmployeeCertificate(){
         $user_id= yii::$app->request->post('id');
         $date = Yii::$app->request->post('date');
         Yii::$app->response->redirect(['reports/experiance-slip','id' => $user_id,'date'=>$date]);
     }
     public function actionExperianceSlip(){
        $id = Yii::$app->request->get('id');
        $date = Yii::$app->request->get('date');
        $emplInfo=EmployeeInfo::find()->where(['user_id'=>$id])->one();
        if(count($emplInfo)>0){
        $expview = $this->renderAjax('academics/employee-experiance-pdf',['id'=>$id,'emplInfo'=>$emplInfo,'leaveDate'=>$date]);
        $this->layout = 'pdf';
         $mpdf = new mPDF('c', 'A4-L');
        $mpdf->WriteHTML($expview);
        $mpdf->Output('employee-experiance.pdf', 'D');
        }else{
        Yii::$app->session->setFlash('Warning', "Some Issue occur..!"); 
        Yii::$app->response->redirect(['reports/slc']);   
        }
     }

 /*get year exam*/
 public function actionYearlyExam(){
    if(!isset($_GET['year'])){
    $year=yii::$app->request->post('year');
    $radio=yii::$app->request->post('radio');
    $class_id=yii::$app->request->post('class_id');
    $group_id=yii::$app->request->post('group_id');
    $section_id=yii::$app->request->post('section_id');
    if($radio == 1){
    $yearGetExam=yii::$app->db->createCommand("SELECT et.id exam_type_id,et.type exam_name,et.type,et.exam_date,e.id exam_id FROM  exam e  
    INNER JOIN exam_type et on et.id = e.fk_exam_type
    where e.id NOT IN (select sm.fk_exam_id from student_marks sm where 1) AND year(et.exam_date)='$year' AND e.fk_class_id='$class_id' AND e.fk_group_id='$group_id' AND e.fk_section_id='$section_id' and e.fk_branch_id = '".yii::$app->common->getBranch()."'
    GROUP BY et.id,et.type,et.type,et.exam_date")->queryAll();
    if(count($yearGetExam)>0){
    
    $yearexamview=$this->renderAjax('exam/year-exam',['yearGetExam'=>$yearGetExam,'class_id'=>$class_id,'group_id'=>$group_id,'section_id'=>$section_id,'year'=>$year]);
    }else{
        $yearexamview='<div class="row col-md-offset-2 col-md-6 alert-danger">No Record Found..!</div>';
    }
}else{
    $yearGetExam=yii::$app->db->createCommand("SELECT et.id,et.type,et.exam_date  FROM `exam_type` et
INNER JOIN exam e on e.fk_exam_type = et.id
INNER JOIN student_marks sm on sm.fk_exam_id = e.id
WHERE year(et.exam_date)='$year' and e.fk_class_id ='$class_id'  and e.fk_group_id ='$group_id'  and e.fk_section_id = '$section_id' and e.fk_branch_id = '".yii::$app->common->getBranch()."'
GROUP BY et.type, et.id")->queryAll();
    //echo '<pre>';print_r($yearGetExam);die;
    if(count($yearGetExam)>0){
    
    $yearexamview=$this->renderAjax('exam/year-examtaken',['yearGetExam'=>$yearGetExam,'class_id'=>$class_id,'group_id'=>$group_id,'section_id'=>$section_id,'year'=>$year]);
    }else{
        $yearexamview='<div class="row col-md-offset-2 col-md-6 alert-danger">No Record Found..!</div>';
    }
}
     return json_encode(['getYearexam'=>$yearexamview]);
     }else{
    $year=yii::$app->request->get('year');
    $class_id=yii::$app->request->get('class_id');
    $group_id=yii::$app->request->get('g_id');
    $section_id=yii::$app->request->get('section_id');
    $yearGetExam=yii::$app->db->createCommand("SELECT et.id exam_type_id,et.type exam_name,et.type,et.exam_date,e.id exam_id FROM  exam e  
    INNER JOIN exam_type et on et.id = e.fk_exam_type
    where e.id NOT IN (select sm.fk_exam_id from student_marks sm where 1) AND year(et.exam_date)='$year' AND e.fk_class_id='$class_id' AND e.fk_group_id='$group_id' AND e.fk_section_id='$section_id'
    GROUP BY et.id,et.type,et.type,et.exam_date")->queryAll();
    $yearexamview=$this->renderAjax('exam/year-exam',['yearGetExam'=>$yearGetExam,'class_id'=>$class_id,'group_id'=>$group_id,'section_id'=>$section_id,'year'=>$year]);
    $this->layout = 'pdf';
    $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
    $mpdf->WriteHTML($yearexamview);
    $mpdf->Output('upcomming-exams-'.date("d-m-Y").'.pdf', 'D');
    }
    }//end of fucntion
    public function actionExamTakenPdf(){
    $year=yii::$app->request->get('years');
    $class_id=yii::$app->request->get('class_id');
    $group_id=yii::$app->request->get('g_id');
    $section_id=yii::$app->request->get('section_id');
     $yearGetExam=yii::$app->db->createCommand("SELECT et.id,et.type,et.exam_date  FROM `exam_type` et
INNER JOIN exam e on e.fk_exam_type = et.id
INNER JOIN student_marks sm on sm.fk_exam_id = e.id
WHERE year(et.exam_date)='$year' and e.fk_class_id ='$class_id'  and e.fk_group_id ='$group_id'  and e.fk_section_id = '$section_id' and e.fk_branch_id = '".yii::$app->common->getBranch()."'
GROUP BY et.type, et.id")->queryAll();
     $yearexamview=$this->renderAjax('exam/year-examtaken',['yearGetExam'=>$yearGetExam,'class_id'=>$class_id,'group_id'=>$group_id,'section_id'=>$section_id,'year'=>$year]);
    $this->layout = 'pdf';
    $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
    $mpdf->WriteHTML($yearexamview);
    $mpdf->Output('taken-exams-'.date("d-m-Y").'.pdf', 'D');
 
    }//end of function
     public function actionGetExamByYear(){
      $year=Yii::$app->request->post('year');
      $class_id=Yii::$app->request->post('class_id');
      $group_id=Yii::$app->request->post('group_id');
      $section_id=Yii::$app->request->post('section_id');
     // $examYear=ExamType::find()->where(['like','exam_date',$year])->all();
      $examYear=Exam::find()->select(['fk_exam_type'])->where(['fk_class_id'=>$class_id,'fk_group_id'=>($group_id)?$group_id:null,'fk_section_id'=>$section_id])->andWhere(['like','start_date',$year])->distinct()->all();
     // echo '<pre>';print_r($examYear);die;
      $options = "<option>Select Exam</option>";
      foreach($examYear as $getType)
      {
        $options .= "<option value='".$getType->fk_exam_type."'>".$getType->fkExamType->type."</option>";
      }
      return $options;
    }

/*student marks against exam*/
    public function actionStudentmarksAgainstExam(){
        if(!isset($_GET['e_id'])){
        $id= yii::$app->request->post('id');
        $class_id= yii::$app->request->post('class_id');
        $group_id= yii::$app->request->post('group_id');
        $section_id= yii::$app->request->post('section_id');
        $stu_id= yii::$app->request->post('stu_id');
        $examData=\app\models\Exam::find()->where(['fk_exam_type'=>$id])->all();
        $examName=\app\models\Exam::find()->where(['fk_exam_type'=>$id])->one();
        $studentMarks=\app\models\StudentMarks::find()->where(['fk_student_id'=>$stu_id,'fk_exam_id'=>$examName->id])->one();
        if(count($studentMarks)>0){
        $marksView=$this->renderAjax('exam/studetmarks',['id'=>$id,'examData'=>$examData,'stu_id'=>$stu_id,'examName'=>$examName,'class_id'=>$class_id,'group_id'=>$group_id,'section_id'=>$section_id]);
        }else{
            $marksView='<div class="row col-md-offset-2 col-md-6 alert-danger">This Exam has not taken yet..!</div>';
        }
        return json_encode(['marksView'=>$marksView]);
    }else{
       $id= yii::$app->request->get('e_id');
       $stu_id= yii::$app->request->get('s_id');
       $class_id= yii::$app->request->post('class_id');
       $group_id= yii::$app->request->post('g_id');
       $section_id= yii::$app->request->post('section_id');
       $examData=\app\models\Exam::find()->where(['fk_exam_type'=>$id])->all();
       $examName=\app\models\Exam::find()->where(['fk_exam_type'=>$id])->one();
       $marksView=$this->renderAjax('exam/studetmarks',['id'=>$id,'examData'=>$examData,'stu_id'=>$stu_id,'examName'=>$examName,'class_id'=>$class_id,'group_id'=>$group_id,'section_id'=>$section_id]);
       $this->layout = 'pdf';
       $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
       $mpdf->WriteHTML($marksView);
       $mpdf->Output('student-marks-'.date("d-m-Y").'.pdf', 'D');
    }

        }//end of function
        public function actionGetExamYearwise(){
        if(!isset($_GET['year'])){
        $year= yii::$app->request->post('year');
        $class_id= yii::$app->request->post('class_id');
        $group_id= yii::$app->request->post('group_id');
        $section_id= yii::$app->request->post('section_id');
        $stu_id= yii::$app->request->post('stu_id');
         $examQuery = \app\models\ExamType::find()
            ->select(['exam.*','exam_type.*','student_marks.*'])
            ->innerJoin('exam','exam.fk_exam_type = exam_type.id')
            ->innerJoin('student_marks','student_marks.fk_exam_id = exam.id')
            ->where(['like', 'exam_type.exam_date', $year])->andWhere(['exam.fk_class_id'=>$class_id,'fk_group_id'=>($group_id)?$group_id:null,'fk_section_id'=>$section_id,'fk_student_id'=>$stu_id])->asArray()->all();
            //echo '<pre>';print_r($examQuery);die;
        if(count($examQuery)>0){
        $marksView=$this->renderAjax('exam/year-wise',['examQuery'=>$examQuery,'class_id'=>$class_id,'group_id'=>$group_id,'section_id'=>$section_id,'year'=>$year,'stu_id'=>$stu_id]);
        }else{
            $marksView='<div class="row col-md-offset-2 col-md-6 alert-danger">This Exam has not taken yet..!</div>';
        }
        return json_encode(['marksView'=>$marksView]);
        }else{
       $year= yii::$app->request->get('year');
       $stu_id= yii::$app->request->get('s_id');
       $class_id= yii::$app->request->get('class_id');
       $group_id= yii::$app->request->get('g_id');
       $section_id= yii::$app->request->get('section_id');
       $examQuery = \app\models\ExamType::find()
            ->select(['exam.*','exam_type.*','student_marks.*'])
            ->innerJoin('exam','exam.fk_exam_type = exam_type.id')
            ->innerJoin('student_marks','student_marks.fk_exam_id = exam.id')
            ->where(['like', 'exam_type.exam_date', $year])->andWhere(['exam.fk_class_id'=>$class_id,'fk_group_id'=>($group_id)?$group_id:null,'fk_section_id'=>$section_id,'fk_student_id'=>$stu_id])->asArray()->all();
        $marksView=$this->renderAjax('exam/year-wise',['examQuery'=>$examQuery,'class_id'=>$class_id,'g_id'=>$group_id,'section_id'=>$section_id,'year'=>$year,'stu_id'=>$stu_id]);
       $this->layout = 'pdf';
       $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
       $mpdf->WriteHTML($marksView);
       $mpdf->Output('student-marks-yearly-'.date("d-m-Y").'.pdf', 'D');
   }
        }

    public function actionYearlyClasswiseFeereport(){
        if(Yii::$app->user->isGuest){
            return $this->goHome();
        }
        if(\Yii::$app->request->isAjax){
            $class_id = Yii::$app->request->post('class_id');
            $group_id = Yii::$app->request->post('group_id');
            $section_id = Yii::$app->request->post('section_id');
            $year = Yii::$app->request->post('year');
            $studentArray = \app\models\StudentInfo::find()
             ->select(['student_info.stu_id','student_info.fk_branch_id','student_info.user_id','student_parents_info.cnic'])
             ->leftJoin('student_parents_info', 'student_parents_info.stu_id=student_info.stu_id')
             ->where([
                 'student_info.fk_branch_id'=>yii::$app->common->getBranch(),
                 'student_info.class_id'   => $class_id,
                 'student_info.group_id'   => ($group_id)?$group_id:null,
                 'student_info.section_id'   => $section_id,
                 'student_info.is_active'   => 1,
             ]);
            /*if(isset($group_id) && $group_id != NULL){
             $studentArray->andWhere(['student_info.group_id'   => ($group_id)?$group_id:null]);
            }if(isset($section_id) && ($section_id !='Loading ...')){
             $studentArray->andWhere(['student_info.section_id' => $section_id]);
            }*/ 
            $students = $studentArray->asArray()->All();
            $html = $this->renderAjax('finance/yearly-classwise-fee-report',[
                'students'=>$students,
                'year'=>$year,
                'class_id'=>$class_id,
                'group_id'=>$group_id,
                'section_id'=>$section_id
            ]);
            return json_encode(['data'=>$html]);
        }

    }// end of function
    public function actionDateFees(){
        if(!isset($_GET['startcnvrt'])){
        $class_id = Yii::$app->request->post('class_id');
        $group_id = Yii::$app->request->post('group_id');
        $section_id = Yii::$app->request->post('section_id');
        $start= Yii::$app->request->post('startdate');
        $end = Yii::$app->request->post('enddate');
        $startcnvrt=date('Y-m',strtotime($start));
        $endcnvrt=date('Y-m',strtotime($end));
        $dateFee = \app\models\StudentInfo::find()
             ->select(['student_info.stu_id','student_info.fk_branch_id','student_info.user_id','fee_submission.*'])
             ->innerJoin('fee_submission', 'fee_submission.stu_id=student_info.stu_id')
             ->where([
                 'student_info.fk_branch_id'=>yii::$app->common->getBranch(),
                 'student_info.class_id'   => $class_id,
                 'student_info.group_id'   => ($group_id)?$group_id:null,
                 'student_info.section_id'   => $section_id,
                 'student_info.is_active'   => 1,
             ])->andWhere(['between', 'fee_submission.from_date', $startcnvrt, $endcnvrt])->asArray()->All();
        if(count($dateFee)>0){
            $html=$this->renderAjax('finance/fee-date-ledger',['dateFee'=>$dateFee,'startcnvrt'=>$startcnvrt,'endcnvrt'=>$endcnvrt,'class_id'=>$class_id,'group_id'=>$group_id,'section_id'=>$section_id]);
        }else{
            $html='<div class="alert alert-danger">No detail found..!</div>';
        }
         return json_encode(['data'=>$html]);   
     }else{
        $class_id = Yii::$app->request->get('class_id');
        $group_id = Yii::$app->request->get('group_id');
        $section_id = Yii::$app->request->get('section_id');
        $startcnvrt= Yii::$app->request->get('startcnvrt');
        $endcnvrt = Yii::$app->request->get('endcnvrt');
        $dateFee = \app\models\StudentInfo::find()
             ->select(['student_info.stu_id','student_info.fk_branch_id','student_info.user_id','fee_submission.*'])
             ->innerJoin('fee_submission', 'fee_submission.stu_id=student_info.stu_id')
             ->where([
                 'student_info.fk_branch_id'=>yii::$app->common->getBranch(),
                 'student_info.class_id'   => $class_id,
                 'student_info.group_id'   => ($group_id)?$group_id:null,
                 'student_info.section_id'   => $section_id,
                 'student_info.is_active'   => 1,
             ])->andWhere(['between', 'fee_submission.from_date', $startcnvrt, $endcnvrt,'class_id'=>$class_id,'group_id'=>$group_id,'section_id'=>$section_id])->asArray()->All();
        $showDateFee=$this->renderAjax('finance/fee-date-ledger',['dateFee'=>$dateFee,'startcnvrt'=>$startcnvrt,'endcnvrt'=>$endcnvrt,'class_id'=>$class_id,'group_id'=>$group_id,'section_id'=>$section_id]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($showDateFee);
        $mpdf->Output('date-slip-'.date("d-m-Y").'.pdf', 'D');

     }
    }// end of function
     public function actionYearlyClasswiseFeereportPdf(){
            $class_id = Yii::$app->request->get('class_id');
            $group_id = Yii::$app->request->get('group_id');
            $section_id = Yii::$app->request->get('section_id');
            $year = Yii::$app->request->get('year');
             $studentArray = \app\models\StudentInfo::find()
             ->select(['student_info.stu_id','student_info.fk_branch_id','student_info.user_id','student_parents_info.cnic'])
             ->leftJoin('student_parents_info', 'student_parents_info.stu_id=student_info.stu_id')
             ->where([
                 'student_info.fk_branch_id'=>yii::$app->common->getBranch(),
                 'student_info.class_id'   => $class_id,
                 'student_info.is_active'   => 1,
                 'student_info.group_id'   => ($group_id)?$group_id:null,
                 'student_info.section_id'   => $section_id,
                 'student_info.is_active'   => 1,
             ]);
            $students = $studentArray->asArray()->All();
            $html = $this->renderAjax('finance/yearly-classwise-fee-report',[
                'students'=>$students,
                'year'=>$year,
                'class_id'=>$class_id,
                'group_id'=>$group_id,
                'section_id'=>$section_id
            ]);
            $this->layout = 'pdf';
            $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
            $mpdf->WriteHTML($html);
            $mpdf->Output('yearly-fee-'.date("d-m-Y").'.pdf', 'D');
    }
    public function actionTodayClassLedger(){
        if(!isset($_GET['class_id'])){
        $class_id=yii::$app->request->post('class_id');
        $getClassStudents=StudentInfo::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'class_id'=>$class_id,'is_active'=>1])->orderBy(['roll_no'=>SORT_ASC])->all();
        $todayLedgerView = $this->renderAjax('finance/today-ledger',[
                'class_id'=>$class_id,
                'getClassStudents'=>$getClassStudents,
            ]);
        return json_encode(['data'=>$todayLedgerView]);
    }else{
       $class_id=yii::$app->request->get('class_id');
       $getClassStudents=StudentInfo::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'class_id'=>$class_id,'is_active'=>1])->orderBy(['roll_no'=>SORT_ASC])->all();
       $todayLedgerView = $this->renderAjax('finance/today-ledger',[
                'class_id'=>$class_id,
                'getClassStudents'=>$getClassStudents,
            ]); 
       $this->layout = 'pdf';
       $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
       $mpdf->WriteHTML($todayLedgerView);
       $mpdf->Output('today-ledger-'.date("d-m-Y").'.pdf', 'D');
    }
    }// end of function

    public function actionPreviousSlip(){
        // print_r($_POST);die;
        if(!isset($_GET['user_id'])){
        $user_id=yii::$app->request->post('stu_id');
        $studentTable=\app\models\StudentInfo::find()->where(['user_id'=>$user_id])->one();
        $feeSubmission=FeeSubmission::find()->where(['stu_id'=>$studentTable->stu_id,'branch_id'=>yii::$app->common->getBranch(),'fee_status'=>1])->all();
        if (count($feeSubmission)>0) {
            $view= $this->renderAjax('finance/previous-slip',['feeSubmission'=>$feeSubmission,'studentTable'=>$studentTable,'user_id'=>$user_id]);
        }else{
            $view='<div class="alert alert-danger"> No Detail Found</div>';
        }
        return json_encode(['showPreviousSlip'=>$view]);
        }else{
        $user_id=yii::$app->request->get('user_id');
        $studentTable=\app\models\StudentInfo::find()->where(['user_id'=>$user_id])->one();
        $feeSubmission=FeeSubmission::find()->where(['stu_id'=>$studentTable->stu_id,'branch_id'=>yii::$app->common->getBranch(),'fee_status'=>1])->all();
            $view= $this->renderAjax('finance/previous-slip',['feeSubmission'=>$feeSubmission,'studentTable'=>$studentTable,'user_id'=>$user_id]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($view);
        $mpdf->Output('previous-slip-'.date("d-m-Y").'.pdf', 'D');
        }
    }// end of function
    public function actionDateFee(){
        $start= Yii::$app->request->post('startdate'); 
        $end = Yii::$app->request->post('enddate');
        //$start= '2019-05';
        //$end= '2019-05';
        $startcnvrt=date('Y-m',strtotime($start));
        $endcnvrt=date('Y-m',strtotime($end));
        $where = "branch_id='".yii::$app->common->getBranch()."' and from_date BETWEEN '".$startcnvrt."' and '".$endcnvrt."'";
        $dateFee=FeeSubmission::find()->where($where);
        
        if(\Yii::$app->request->get('page')){
            $dataProvider = new ActiveDataProvider([
                 'query' => $dateFee,
                 'pagination' => [
                        'pageSize' => 2,
                        'params' => [
                    //'other_id' => $post_data['other_id'],
                    'page' => \Yii::$app->request->get('page')
                         ],
                    ],
                 
            ]);
            
        }else{
          $dataProvider = new ActiveDataProvider([
                 'query' => $dateFee,
                 'pagination' => [
                        'pageSize' => 2,
                        'params' => [
                    //'other_id' => $post_data['other_id'],
                   // 'page' => 2
                         ],
                    ],
                 
            ]);
        }

        //$dataProvider->pagination->pageSize=3;
        //if(count($dateFee)>0){
            $showDateFee=$this->renderAjax('finance/fee-date',['dataProvider'=>$dataProvider,'startcnvrt'=>$startcnvrt,'endcnvrt'=>$endcnvrt]);
        /*}else{
            $showDateFee='<div class="alert alert-danger">No detail found..!</div>';
        }*/
         return json_encode(['showDateWiseFee'=>$showDateFee]); 
    }
    public function actionDateFee1(){
        if(!isset($_GET['startcnvrt'])){
        $start= Yii::$app->request->post('startdate');
        $end = Yii::$app->request->post('enddate');
        $startcnvrt=date('Y-m',strtotime($start));
        $endcnvrt=date('Y-m',strtotime($end));
        $where = "branch_id='".yii::$app->common->getBranch()."' and from_date BETWEEN '".$startcnvrt."' and '".$endcnvrt."'";

        $dateFee=FeeSubmission::find()->where($where)->all();
        
        /*$dateFee=FeeSubmission::find()->select('sum(head_recv_amount) head_recv_amount,transport_amount, hostel_amount,  transport_arrears,hostel_arrears,stu_id,fee_head_id,recv_date')->where($where)->groupBy('fee_head_id')->all(); */ 
        if(count($dateFee)>0){
            $showDateFee=$this->renderAjax('finance/fee-date',['dateFee'=>$dateFee,'startcnvrt'=>$startcnvrt,'endcnvrt'=>$endcnvrt]);
        }else{
            $showDateFee='<div class="alert alert-danger">No detail found..!</div>';
        }
         return json_encode(['showDateWiseFee'=>$showDateFee]);   
     }else{
        $startcnvrt= Yii::$app->request->get('startcnvrt');
        $endcnvrt = Yii::$app->request->get('endcnvrt');
         $where = "branch_id='".yii::$app->common->getBranch()."' and from_date BETWEEN '".$startcnvrt."' and '".$endcnvrt."'";
        /*$dateFee=FeeSubmission::find()->select('sum(head_recv_amount) head_recv_amount,transport_amount, hostel_amount,  transport_arrears,hostel_arrears,stu_id,fee_head_id,recv_date')->where($where)->groupBy('fee_head_id')->all();*/
        $dateFee=FeeSubmission::find()->where($where)->all();
        $showDateFee=$this->renderAjax('finance/fee-date',['dateFee'=>$dateFee,'startcnvrt'=>$startcnvrt,'endcnvrt'=>$endcnvrt]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($showDateFee);
        $mpdf->Output('date-slip-'.date("d-m-Y").'.pdf', 'D');

     }
    }// end of function
    /*public function actionDiscountAvail(){
        if(!isset($_GET['id'])){
    $stu_id=yii::$app->request->post('stuId');
    $studentTable=\app\models\StudentInfo::find()->where(['user_id'=>$stu_id])->one();
    $discountQuery=FeePlan::find()->where(['stu_id'=>$studentTable->stu_id,'branch_id'=>yii::$app->common->getBranch(),'status'=>1])->all();
    $discountView=$this->renderAjax('finance/discount-avail',['discountQuery'=>$discountQuery,'studentTable'=>$studentTable,'stu_id'=>$stu_id]);
    return json_encode(['studentFeeRcv'=>$discountView]);
    }else{
    $stu_id=yii::$app->request->get('id');
    $studentTable=\app\models\StudentInfo::find()->where(['user_id'=>$stu_id])->one();
    $discountQuery=FeePlan::find()->where(['stu_id'=>$studentTable->stu_id,'branch_id'=>yii::$app->common->getBranch(),'status'=>1])->all();
    $discountView=$this->renderAjax('finance/discount-avail',['discountQuery'=>$discountQuery,'studentTable'=>$studentTable,'stu_id'=>$stu_id]);
    $this->layout = 'pdf';
    $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
    $mpdf->WriteHTML($discountView);
    $mpdf->Output('discount avail-slip-'.date("d-m-Y").'.pdf', 'D');
    }
    }*/

public function actionDiscountAvail(){
    if(!isset($_GET['id'])){
    $class_id=yii::$app->request->post('id');
    $studentTable=\app\models\StudentInfo::find()->where(['class_id'=>$class_id,'is_active'=>1,'fk_branch_id'=>yii::$app->common->getBranch()])->orderBy(['roll_no'=>SORT_ASC])->all();
    if(count($studentTable)>0){
    $discountView=$this->renderAjax('finance/discount-avail',['studentTable'=>$studentTable,'class_id'=>$class_id]);
    }else{
    $discountView='<div class="alert alert-danger">No Students Found..!</div>';
    }
    return json_encode(['studata'=>$discountView]);
    }else{
    $class_id=yii::$app->request->get('id');
    $studentTable=\app\models\StudentInfo::find()->where(['class_id'=>$class_id,'is_active'=>1,'fk_branch_id'=>yii::$app->common->getBranch()])->orderBy(['roll_no'=>SORT_ASC])->all();
    $discountView=$this->renderAjax('finance/discount-avail',['studentTable'=>$studentTable,'class_id'=>$class_id]);
    $this->layout = 'pdf';
    $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
    $mpdf->WriteHTML($discountView);
    $mpdf->Output('discount avail-slip-'.date("d-m-Y").'.pdf', 'D');
    }
    }// end of function
    public function actionTotalFeePdf()
    {
       $refClass=RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all();
       $totalFeeView=$this->renderAjax('finance/pdf/total-fee',['refClass'=>$refClass]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($totalFeeView);
        $mpdf->Output('total-fee-'.date("d-m-Y").'.pdf', 'D');
    }

    
     public function actionQuiz(){
        //$model=new ExamQuiz();
        $model=new Exam();
       /* $todayFeeRcv=FeeSubmission::find()
               ->where(['branch_id'=>Yii::$app->common->getBranch(),'date(recv_date)'=>date('Y-m-d')])->all();
        $refClass=RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all();*/
        
        return $this->render('quiz', [
            //'currentMonth'=>$currentMonth,
            'model'=>$model,
        ]);
     } 
     /*quiz reports*/
     public function actionClassSubjectDate(){
        if(!isset($_GET['class_id'])){
        $data=Yii::$app->request->post();
        $date=$data['date'];
        $class_id=$data['class_id'];
        $group_id=$data['group_id'];
        $subject_id=$data['subject_id'];
        $exam_quiz_type_details=ExamQuizType::find()->where(['class_id'=>$class_id,'group_id'=>($group_id)?$group_id:null,'quiz_date'=>$date,'subject_id'=>$subject_id])->one();
        if(count($exam_quiz_type_details)>0){
        $employee = \app\models\EmployeeInfo::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'emp_id'=>$exam_quiz_type_details->teacher_id])->one();
        $query=ExamQuiz::find()->where(['test_id'=>$exam_quiz_type_details->id])->all();
        $subject_details=\app\models\Subjects::find()->where(['id'=>$subject_id])->one();
        // echo '<pre>';print_r($quiz_details);die;
        $view_details=$this->renderAjax('quiz/class-subject-date-one',['query'=>$query,'class_id'=>$class_id,'group_id'=>$group_id,'employee'=>$employee,'exam_quiz_type_details'=>$exam_quiz_type_details,'date'=>$date,'subject_id'=>$subject_id,'subject_details'=>$subject_details]);
        }else{
            $view_details='<div class="alert alert-danger">No quiz details found</div>';
        }
        return json_encode(['getFirstReportData'=>$view_details]);

     }else{
        $data=Yii::$app->request->get();
        $date=$data['date'];
        $class_id=$data['class_id'];
        $group_id=$data['group_id'];
        $subject_id=$data['subject_id'];
        $exam_quiz_type_details=ExamQuizType::find()->where(['class_id'=>$class_id,'group_id'=>($group_id)?$group_id:null,'quiz_date'=>$date,'subject_id'=>$subject_id])->one();
        $employee = \app\models\EmployeeInfo::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'emp_id'=>$exam_quiz_type_details->teacher_id])->one();
        $query=ExamQuiz::find()->where(['test_id'=>$exam_quiz_type_details->id])->all();
        $subject_details=\app\models\Subjects::find()->where(['id'=>$subject_id])->one();
        $view_details=$this->renderAjax('quiz/class-subject-date-one',['query'=>$query,'class_id'=>$class_id,'group_id'=>$group_id,'employee'=>$employee,'exam_quiz_type_details'=>$exam_quiz_type_details,'date'=>$date,'subject_id'=>$subject_id,'subject_details'=>$subject_details]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($view_details);
        $mpdf->Output('subject-quiz-report-'.date("d-m-Y").'.pdf', 'D');
     }
     } //end of function

     public function actionClassWiseQuiz(){
        if(!isset($_GET['class_id'])){
        $data=Yii::$app->request->post();
        $date=$data['date'];
        $class_id=$data['class_id'];
        $group_id=$data['group_id'];
        $exam_quiz_type_details=ExamQuizType::find()->where(['class_id'=>$class_id,'group_id'=>($group_id)?$group_id:null,'quiz_date'=>$date])->all();
        //echo '<pre>';print_r($exam_quiz_type_details);die;
        if(count($exam_quiz_type_details)>0){
        $view_details=$this->renderAjax('quiz/class-quiz-two',['class_id'=>$class_id,'group_id'=>$group_id,'exam_quiz_type_details'=>$exam_quiz_type_details,'date'=>$date]);
        }else{
            $view_details='<div class="alert alert-danger">No quiz details found</div>';
        }
        return json_encode(['getFirstReportData'=>$view_details]);
        }else{
        $data=Yii::$app->request->get();
        $date=$data['date'];
        $class_id=$data['class_id'];
        $group_id=$data['group_id'];
        $exam_quiz_type_details=ExamQuizType::find()->where(['class_id'=>$class_id,'group_id'=>($group_id)?$group_id:null,'quiz_date'=>$date])->all();
        $view_details=$this->renderAjax('quiz/class-quiz-two',['class_id'=>$class_id,'group_id'=>$group_id,'exam_quiz_type_details'=>$exam_quiz_type_details,'date'=>$date]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($view_details);
        $mpdf->Output('class-quiz-report-'.date("d-m-Y").'.pdf', 'D');
        }
     }
     public function actionClassWiseSessionQuiz(){
        if(!isset($_GET['class_id'])){
        $data=Yii::$app->request->post();
        $class_id=$data['class_id'];
        $group_id=$data['group_id'];
        $exam_quiz_type_details=ExamQuizType::find()->where(['class_id'=>$class_id,'group_id'=>($group_id)?$group_id:null])->all();
        //echo '<pre>';print_r($exam_quiz_type_details);die;
        if(count($exam_quiz_type_details)>0){
        $view_details=$this->renderAjax('quiz/class-quiz-two',['class_id'=>$class_id,'group_id'=>$group_id,'exam_quiz_type_details'=>$exam_quiz_type_details]);
        }else{
            $view_details='<div class="alert alert-danger">No quiz details found</div>';
        }
        return json_encode(['getFirstReportData'=>$view_details]);
        }else{
        $data=Yii::$app->request->get();
        $class_id=$data['class_id'];
        $group_id=$data['group_id'];
        $exam_quiz_type_details=ExamQuizType::find()->where(['class_id'=>$class_id,'group_id'=>($group_id)?$group_id:null,'quiz_date'=>$date])->all();
        $view_details=$this->renderAjax('quiz/class-session-quiz-three',['class_id'=>$class_id,'group_id'=>$group_id,'exam_quiz_type_details'=>$exam_quiz_type_details]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($view_details);
        $mpdf->Output('class-quiz-report-'.date("d-m-Y").'.pdf', 'D');
        }
     }
     public function actionStuQuiz(){
        $get_id=Yii::$app->request->get('stu_id');
        $id=base64_decode($get_id);
        $currentDate=date('Y-m-d');
        $lastThiryDate= date('Y-m-d',strtotime('-29 day'));
        $settings = Yii::$app->common->getBranchSettings();
        $sessionStartDate=$settings->current_session_start;
        $sessionEndDate=$settings->current_session_end;
        $student_details=StudentInfo::find()->where(['stu_id'=>$id])->one();
        $quizResults = \app\models\ExamQuizType::find()
        ->select(['exam_quiz_type.*','eq.*'])
        ->innerJoin('exam_quiz eq','eq.test_id=exam_quiz_type.id')
        ->where([
            'eq.fk_branch_id'=>Yii::$app->common->getBranch(),
            'eq.stu_id'=>$id,
        ])->andWhere(['between', 'exam_quiz_type.quiz_date', $sessionStartDate, $sessionEndDate])
        ->asArray()->all();
        $view=$this->renderAjax('quiz/student-quiz',['quizResults'=>$quizResults,'stu_id'=>$id,'student_details'=>$student_details]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($view);
        $mpdf->Output('student-quiz-'.date("d-m-Y").'.pdf', 'D');
     } // end of function
     /*quiz reports ends*/
     public function actionAgeCertificate(){
        $reg=Yii::$app->request->get('reg');
        $userDetails=\app\models\User::find()->where(['username'=>$reg])->one();
        if(count($userDetails)>0){
        $studentDetails=StudentInfo::find()->where(['user_id'=>$userDetails->id])->one();
        $view=$this->renderAjax('academics/age-pdf.php',['userDetails'=>$userDetails,'studentDetails'=>$studentDetails]);
        }else{
            echo $view='<div class="alert alert-danger">No student found against this registeratio No.</div>';
        }
        //echo $view;die;
        $this->layout = 'pdf';
         $mpdf = new mPDF('c', 'A4-L');
        $mpdf->WriteHTML($view);
        $mpdf->Output('age-certificate-'.date("d-m-Y").'.pdf', 'D');
     } 
     /*get subject wise class quiz*/
     public function actionGetSubjectWiseQuiz(){
        $settings = Yii::$app->common->getBranchSettings();
        if(!isset($_GET['class_id'])){
        $data=Yii::$app->request->post();
        $class_id=$data['classid'];
        $subject_id=$data['id'];
        $group_id=$data['groupid'];
        $sessionStart=$settings->current_session_start;
        $sessionEnd=$settings->current_session_end;
        $exam_quiz_type_details=\app\models\ExamQuizType::find()->where(['class_id'=>$class_id,'group_id'=>($group_id)?$group_id:null,'subject_id'=>$subject_id,'fk_branch_id'=>Yii::$app->common->getBranch()])->andWhere(['between', 'quiz_date', $sessionStart, $sessionEnd])->orderBy(['quiz_date'=>SORT_ASC])->all();
        if(count($exam_quiz_type_details)>0){
            $subject_details=\app\models\Subjects::find()->where(['id'=>$subject_id])->one();
            $subjectView=$this->renderAjax('quiz/subject-wise-quiz',['exam_quiz_type_details'=>$exam_quiz_type_details,'subject_details'=>$subject_details,'class_id'=>$class_id,'group_id'=>$group_id,'subject_id'=>$subject_id,'sessionStart'=>$sessionStart,'sessionEnd'=>$sessionEnd]);

        }else{
            $subjectView='<div class="alert alert-danger">No quiz details found</div>';
        }
        return json_encode(['renderView'=>$subjectView]);
    }else{
        $data=Yii::$app->request->get();
        $class_id=$data['class_id'];
        $subject_id=$data['subject_id'];
        $group_id=$data['group_id'];
        $sessionStart=$settings->current_session_start;
        $sessionEnd=$settings->current_session_end;
        $exam_quiz_type_details=\app\models\ExamQuizType::find()->where(['class_id'=>$class_id,'group_id'=>($group_id)?$group_id:null,'subject_id'=>$subject_id,'fk_branch_id'=>Yii::$app->common->getBranch()])->andWhere(['between', 'quiz_date', $sessionStart, $sessionEnd])->orderBy(['quiz_date'=>SORT_ASC])->all();
            $subject_details=\app\models\Subjects::find()->where(['id'=>$subject_id])->one();
            $subjectView=$this->renderAjax('quiz/subject-wise-quiz',['exam_quiz_type_details'=>$exam_quiz_type_details,'subject_details'=>$subject_details,'class_id'=>$class_id,'group_id'=>$group_id,'subject_id'=>$subject_id,'sessionStart'=>$sessionStart,'sessionEnd'=>$sessionEnd]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($subjectView);
        $mpdf->Output('subject-wise-quiz-'.date("d-m-Y").'.pdf', 'D');
     }
    }

    public function actionSubjectWiseDateQuiz(){
        if(!isset($_GET['testId'])){
        $testId=Yii::$app->request->post('testId');
        $quiz_details=\app\models\ExamQuiz::find()->where(['test_id'=>$testId])->all();
        if(count($quiz_details)>0){
       $exam_quiz_type_details=\app\models\ExamQuizType::find()->where(['id'=>$testId])->one();
       $subject_details=\app\models\Subjects::find()->where(['id'=>$exam_quiz_type_details->subject_id])->one();
        $view=$this->renderAjax('quiz/subject-wise-date-quiz',['quiz_details'=>$quiz_details,'exam_quiz_type_details'=>$exam_quiz_type_details,'testId'=>$testId,'subject_details'=>$subject_details]);
        }else{
        $view='<div class="alert alert-danger">No details found..!</div>';
        }
        return json_encode(['view'=>$view]);
    }else{
        $testId=Yii::$app->request->get('testId');
        $quiz_details=\app\models\ExamQuiz::find()->where(['test_id'=>$testId])->all();
       $exam_quiz_type_details=\app\models\ExamQuizType::find()->where(['id'=>$testId])->one();
       $subject_details=\app\models\Subjects::find()->where(['id'=>$exam_quiz_type_details->subject_id])->one();
        $view=$this->renderAjax('quiz/subject-wise-date-quiz',['quiz_details'=>$quiz_details,'exam_quiz_type_details'=>$exam_quiz_type_details,'testId'=>$testId,'subject_details'=>$subject_details]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($view);
        $mpdf->Output('subject-wise-date-quiz-'.date("d-m-Y").'.pdf', 'D');
    }
    }

    public function actionClassWiseQuizSession(){
     $settings = Yii::$app->common->getBranchSettings();
     if(!isset($_GET['class_id'])){
     $class_id=Yii::$app->request->post('id');
     $sessionStart=$settings->current_session_start;
     $sessionEnd=$settings->current_session_end;
     $exam_quiz_type_details=\app\models\ExamQuizType::find()->where(['class_id'=>$class_id,'fk_branch_id'=>Yii::$app->common->getBranch()])->andWhere(['between', 'quiz_date', $sessionStart, $sessionEnd])->all();
     if(count($exam_quiz_type_details)>0){
    $refClass=RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active','class_id'=>$class_id])->one();
     $classView=$this->renderAjax('quiz/class-wise-session-quiz',['exam_quiz_type_details'=>$exam_quiz_type_details,'class_id'=>$class_id,'sessionStart'=>$sessionStart,'sessionEnd'=>$sessionEnd,'refClass'=>$refClass]);
    }else{
        $classView='<div class="alert alert-warning">No details found</div>';
    }
     
        return json_encode(['studata'=>$classView]);
    }else{
        $class_id=Yii::$app->request->get('class_id');
        $sessionStart=$settings->current_session_start;
        $sessionEnd=$settings->current_session_end;
        $exam_quiz_type_details=\app\models\ExamQuizType::find()->where(['class_id'=>$class_id,'fk_branch_id'=>Yii::$app->common->getBranch()])->andWhere(['between', 'quiz_date', $sessionStart, $sessionEnd])->all();
        $refClass=RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active','class_id'=>$class_id])->one();
        $classView=$this->renderAjax('quiz/class-wise-session-quiz',['exam_quiz_type_details'=>$exam_quiz_type_details,'class_id'=>$class_id,'sessionStart'=>$sessionStart,'sessionEnd'=>$sessionEnd,'refClass'=>$refClass]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($classView);
        $mpdf->Output('class-wise-quiz-'.date("d-m-Y").'.pdf', 'D');
    }

    }
    public function actionStudentWiseQuiz(){
        $settings = Yii::$app->common->getBranchSettings();
        $sessionStart=$settings->current_session_start;
        $sessionEnd=$settings->current_session_end;
        if(!isset($_GET['reg'])){
        $reg=Yii::$app->request->post('reg');
        $userDetails=\app\models\User::find()->where(['username'=>$reg])->one();
        if(count($userDetails)>0){
        $studentDetails=StudentInfo::find()->where(['user_id'=>$userDetails->id])->one();
        $exam_quiz_type_details = \app\models\ExamQuizType::find()
        ->select(['exam_quiz_type.*','eq.*'])
        ->innerJoin('exam_quiz eq','eq.test_id=exam_quiz_type.id')
        ->where([
            'eq.fk_branch_id'=>Yii::$app->common->getBranch(),
            'exam_quiz_type.class_id'=>$studentDetails->class_id,
            'eq.stu_id'=>$studentDetails->stu_id,
        ])->andWhere(['between', 'exam_quiz_type.quiz_date', $sessionStart, $sessionEnd])
        ->asArray()->all();
        $view=$this->renderAjax('quiz/student-wise-quiz-session.php',['userDetails'=>$userDetails,'studentDetails'=>$studentDetails,'exam_quiz_type_details'=>$exam_quiz_type_details,'sessionStart'=>$sessionStart,'sessionEnd'=>$sessionEnd,'reg'=>$reg]);
        }else{
            echo $view='<div class="alert alert-danger">No student found against this registeratio No.</div>';
        }
        return json_encode(['view'=>$view]);
    }else{
        $reg=Yii::$app->request->get('reg');
        $userDetails=\app\models\User::find()->where(['username'=>$reg])->one();
        $studentDetails=StudentInfo::find()->where(['user_id'=>$userDetails->id])->one();
        $exam_quiz_type_details = \app\models\ExamQuizType::find()
        ->select(['exam_quiz_type.*','eq.*'])
        ->innerJoin('exam_quiz eq','eq.test_id=exam_quiz_type.id')
        ->where([
            'eq.fk_branch_id'=>Yii::$app->common->getBranch(),
            'exam_quiz_type.class_id'=>$studentDetails->class_id,
            'eq.stu_id'=>$studentDetails->stu_id,
        ])->andWhere(['between', 'exam_quiz_type.quiz_date', $sessionStart, $sessionEnd])
        ->asArray()->all();
        $view=$this->renderAjax('quiz/student-wise-quiz-session.php',['userDetails'=>$userDetails,'studentDetails'=>$studentDetails,'exam_quiz_type_details'=>$exam_quiz_type_details,'sessionStart'=>$sessionStart,'sessionEnd'=>$sessionEnd,'reg'=>$reg]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($view);
        $mpdf->Output('student-wise-quiz-'.date("d-m-Y").'.pdf', 'D');
    }
    } // end of action
    public function actionStudentWiseQuizReport()
    {
        $settings = Yii::$app->common->getBranchSettings();
        $sessionStart=$settings->current_session_start;
        $sessionEnd=$settings->current_session_end;
        if(!isset($_GET['class_id'])){
        $data=Yii::$app->request->post();
        $class_id=$data['class_id'];
        $group_id=$data['group_id'];
        $section_id=$data['section_id'];
        $stu_id=$data['stu_id'];
        $exam_quiz_type_details = \app\models\ExamQuizType::find()
        ->select(['exam_quiz_type.*','eq.*'])
        ->innerJoin('exam_quiz eq','eq.test_id=exam_quiz_type.id')
        ->where([
            'eq.fk_branch_id'=>Yii::$app->common->getBranch(),
            'exam_quiz_type.class_id'=>$class_id,
            'exam_quiz_type.group_id'=>($group_id)?$group_id:null,
            'eq.stu_id'=>$stu_id,
        ])->andWhere(['between', 'exam_quiz_type.quiz_date', $sessionStart, $sessionEnd])->asArray()->all();
        if(count($exam_quiz_type_details)>0){
         $studentDetails=StudentInfo::find()->where(['stu_id'=>$stu_id])->one();
         $view=$this->renderAjax('quiz/student-wise-quiz-report-session.php',['class_id'=>$class_id,'group_id'=>$group_id,'section_id'=>$section_id,'sessionStart'=>$sessionStart,'sessionEnd'=>$sessionEnd,'exam_quiz_type_details'=>$exam_quiz_type_details,'studentDetails'=>$studentDetails,'stu_id'=>$stu_id]);   
        }else{
            $view='<div class="alert alert-danger">No details found..!</div>';
        }
         return json_encode(['view'=>$view]);
     }else{
        $data=Yii::$app->request->get();
        $class_id=$data['class_id'];
        $group_id=$data['group_id'];
        $section_id=$data['section_id'];
        $stu_id=$data['stu_id'];
        $exam_quiz_type_details = \app\models\ExamQuizType::find()
        ->select(['exam_quiz_type.*','eq.*'])
        ->innerJoin('exam_quiz eq','eq.test_id=exam_quiz_type.id')
        ->where([
            'eq.fk_branch_id'=>Yii::$app->common->getBranch(),
            'exam_quiz_type.class_id'=>$class_id,
            'exam_quiz_type.group_id'=>($group_id)?$group_id:null,
            'eq.stu_id'=>$stu_id,
        ])->andWhere(['between', 'exam_quiz_type.quiz_date', $sessionStart, $sessionEnd])->asArray()->all();
         $studentDetails=StudentInfo::find()->where(['stu_id'=>$stu_id])->one();
         $view=$this->renderAjax('quiz/student-wise-quiz-report-session.php',['class_id'=>$class_id,'group_id'=>$group_id,'section_id'=>$section_id,'sessionStart'=>$sessionStart,'sessionEnd'=>$sessionEnd,'exam_quiz_type_details'=>$exam_quiz_type_details,'studentDetails'=>$studentDetails,'stu_id'=>$stu_id]); 
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($view);
        $mpdf->Output('student-wise-quiz-'.date("d-m-Y").'.pdf', 'D');  
       
     }
 }
    /*sms log */
    public function actionSmsLog(){
        $futureDate=date('Y-m-d',strtotime("+1 day"));
        
        $thiryDaysSms = 'fk_branch_id='.Yii::$app->common->getBranch().' and sent_date_time >= DATE_ADD(CURDATE(), INTERVAL -30 DAY)';
        // $lastThiryDaysSms = \app\models\SmsLog::findBySql($thiryDaysSms);
        $lastThiryDaysSms = \app\models\SmsLog::find()->where($thiryDaysSms)->orderBy(['id'=>SORT_DESC]);
        if(\Yii::$app->request->get('page')){
            $Page = new Pagination([
                'totalCount' => $lastThiryDaysSms->count(),
                'defaultPageSize' => 30,
                'params' => [
                    //'other_id' => $post_data['other_id'],
                    'page' => \Yii::$app->request->get('page')
                ]
            ]);
            $offset = $Page->limit * (\Yii::$app->request->get('page') - 1);
            $modelData = $lastThiryDaysSms->offset($offset)
                ->limit($Page->limit)->all();
        }else{
            /*Default pagination will execute.*/
            $Page = new Pagination([
                'totalCount' => $lastThiryDaysSms->count(),
                'defaultPageSize' => 30,
                'params' => [
                    //'other_id' => $post_data['other_id'],
                    //'page' => $post_data['page']
                ]
            ]);
            /*generating Offset.*/
            $offset = $lastThiryDaysSms->limit;
            $modelData = $lastThiryDaysSms->offset($offset)->limit($Page->limit)->all();
        }
        return $this->render('sms-log.php',[
            'lastThiryDaysSms'=>$modelData,
             'pages' => $Page
        ]);
    }
    public function actionFailedSms(){
        $smsLog=\app\models\SmsLog::find()->where(['date(sent_date_time)'=>date('Y-m-d')])->andWhere(['NOT like','status','SMS successfully Sent.'])->all();
        return $this->render('failed-sms',[
           'smsLog'=>$smsLog,
        ]);
    }

    public function actionTodayFailSmsSend(){
   $table_id=Yii::$app->request->post('id');
   //echo '<pre>';print_r($table_id);die;
   foreach ($table_id as $key => $id) {
   // if($smsActive->status == 1){
    $smsModel=\app\models\SmsLog::find()->where(['id'=>$id])->one();
    $message = urlencode($smsModel->SMS_body);
    $mbl=$smsModel->receiver_no;
        $smsSet=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
        $mask=$smsSet->mask;
         $url = "http://www.hajanaone.com/api/sendsms.php?apikey=DNK43XGFQ6aN&phone=".$mbl."&sender=".$mask."&message=".$message;
        //echo $url;die;
        $ch  =  curl_init();
            $timeout  =  30;
            curl_setopt ($ch,CURLOPT_URL, $url) ;
            curl_setopt ($ch,CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ch,CURLOPT_CONNECTTIMEOUT, $timeout) ;
            $response = curl_exec($ch) ;
            curl_close($ch);
       $smsModel->status=$response; 
        if ($smsModel->save()) {
         $this->redirect('sms-log');  
        } else {
        print_r($smsModel->getErrors());  
        } 
}
 }
    /*sms log ends*/
    public function actionPromotedStudents(){
    $settings = Yii::$app->common->getBranchSettings();
    if(!isset($_GET['c_id'])){
     $data=Yii::$app->request->post();
     $class_id=$data['class_id'];
     $group_id=$data['group_id'];
     $section_id=$data['section_id'];
     $sessionStart=$settings->current_session_start;
     $sessionEnd=$settings->current_session_end;
     $radioValue=$data['radioValue'];
     if($radioValue == 1){
        $promotedStu=\app\models\StuRegLogAssociation::find()->where([
        'fk_class_id'=>$class_id,
        'fk_group_id'=>($group_id)?$group_id:null,
        'fk_section_id'=>$section_id,
    ])->andWhere(['between', 'promoted_date', $sessionStart, $sessionEnd])->all();
    }else{
        $promotedStu=\app\models\StuRegLogAssociation::find()->where([
        'fk_class_id'=>$class_id,
        'fk_group_id'=>($group_id)?$group_id:null,
        'fk_section_id'=>$section_id,
    ])->andWhere(['NOT between', 'promoted_date', $sessionStart, $sessionEnd])->all();
    }
     
     if(count($promotedStu) > 0){
        $view=$this->renderAjax('academics/promoted-students',['promotedStu'=>$promotedStu,'class_id'=>$class_id,'group_id'=>$group_id,'section_id'=>$section_id,'radioValue'=>$radioValue]);
     }else{

        $view='<div class="alert-danger">No Details Found..</div>';
     }
    return $view;
    }else{
    $data=Yii::$app->request->get();
     $class_id=$data['c_id'];
     $group_id=$data['g_id'];
     $section_id=$data['s_id'];
     $sessionStart=$settings->current_session_start;
     $sessionEnd=$settings->current_session_end;
     $radioValue=$data['radioValue'];
     if($radioValue == 1){
        $promotedStu=\app\models\StuRegLogAssociation::find()->where([
        'fk_class_id'=>$class_id,
        'fk_group_id'=>($group_id)?$group_id:null,
        'fk_section_id'=>$section_id,
    ])->andWhere(['between', 'promoted_date', $sessionStart, $sessionEnd])->all();
    }else{
        $promotedStu=\app\models\StuRegLogAssociation::find()->where([
        'fk_class_id'=>$class_id,
        'fk_group_id'=>($group_id)?$group_id:null,
        'fk_section_id'=>$section_id,
    ])->andWhere(['NOT between', 'promoted_date', $sessionStart, $sessionEnd])->all();
    }
     $view=$this->renderAjax('academics/promoted-students',['promotedStu'=>$promotedStu,'class_id'=>$class_id,'group_id'=>$group_id,'section_id'=>$section_id,'radioValue'=>$radioValue]);
     $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($view);
        $mpdf->Output('promoted-students-'.date("d-m-Y").'.pdf', 'D');  
    }
    } //end of action
 /*public function actionTodayFailSmsSend(){
 echo '<pre>';print_r($_POST);die;
   $user_id=Yii::$app->request->post('user_id');
   $phone=Yii::$app->request->post('phone');
   foreach ($user_id as $key => $sms) {
       $user_id=$key;
       $smsBody=$sms;
       $number=$phone[$key];
         //if($smsActive->status == 1){
              $send=Yii::$app->common->SendSms($number,$smsBody,$user_id);
         // }
   }
 }*/
 

 public function actionFine(){
     $todayFine=\app\models\FineDetail::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'created_date'=>date('Y-m-d')])->all();
     $sql = 'SELECT * FROM fine_detail WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) and fk_branch_id="'.yii::$app->common->getBranch().'"';
     $currentMonth = FineDetail::findBySql($sql)->all();
     return $this->render('fine/today-fine',['fine'=>$todayFine,'currentMonth'=>$currentMonth]);
    }
      public function actionTodayFinePdf(){
        $todayFine=\app\models\FineDetail::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'created_date'=>date('Y-m-d')])->all();
        $view = $this->renderAjax('fine/pdf/today-fine-pdf',['fine'=>$todayFine]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($view);
        $mpdf->Output('today-fine-'.date("d-m-Y").'.pdf', 'D');
      }
      public function actionCurrentmonthFinePdf(){
        $sql = 'SELECT * FROM fine_detail WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) and fk_branch_id="'.yii::$app->common->getBranch().'"';
        $currentMonth = FineDetail::findBySql($sql)->all();
        $view = $this->renderAjax('fine/pdf/currentmonth-fine-pdf',['currentMonth'=>$currentMonth]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($view);
        $mpdf->Output('current-month-fine-'.date("m-Y").'.pdf', 'D');
      }
       public function actionFineDateReport(){
        $start= Yii::$app->request->post('start');
        $end = Yii::$app->request->post('end');
        $startcnvrt=date('Y-m-d',strtotime($start));
        $endcnvrt=date('Y-m-d',strtotime($end));
        $where = "fk_branch_id='".yii::$app->common->getBranch()."' and created_date BETWEEN '".$startcnvrt."' and '".$endcnvrt."'";
        $dateFine=FineDetail::find()->where($where)->all();
        $showDateExpense=$this->renderAjax('fine/date-fine',['dateFine'=>$dateFine,'startcnvrt'=>$startcnvrt,'endcnvrt'=>$endcnvrt]);
         return json_encode(['showDateExpense'=>$showDateExpense]);   
    }

    public function actionFineDatePdf(){
        $start= Yii::$app->request->get('start');
        $end = Yii::$app->request->get('end');
        
        $where = "fk_branch_id='".yii::$app->common->getBranch()."' and created_date BETWEEN '".$start."' and '".$end."'";
        $dateFine=FineDetail::find()->where($where)->all();
        $view=$this->renderAjax('fine/pdf/date-fine-pdf',['dateFine'=>$dateFine,'start'=>$start,'end'=>$end]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($view);
        $mpdf->Output('date-fine-'.date("m-Y").'.pdf', 'D');  
    }

public function actionCertificates(){
  $model=new StudentInfo();
  $class_array = ArrayHelper::map(\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all(), 'class_id', 'title'); 
  $session_array = ArrayHelper::map(\app\models\RefSession::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch()])->all(), 'session_id', 'title'); 
  if (Yii::$app->request->post('StudentInfo')) {
     // echo '<pre>';print_r($_POST);die;
    $data=Yii::$app->request->post('StudentInfo');
    $certificateCheck=$data['certificateCheck'];
    $session_id=$data['session_id'];
    $class_id=$data['class_id'];
    $group_id=$data['group_id'];
    $section_id=$data['section_id'];
    $studentDetails=StudentInfo::find()->where(['class_id'=>$class_id,'group_id'=>($group_id)?$group_id:null,'section_id'=>$section_id,'session_id'=>$session_id,'is_active'=>0])->orderBy(['roll_no'=>SORT_ASC])->all();
return $this->render('academics/certificates',['model'=>$model,
    'class_array'=>$class_array,
    'session_array'=>$session_array,
    'studentDetails'=>$studentDetails,
    'certificateCheck'=>$certificateCheck,
]);
} 
if (Yii::$app->request->post('checbox')) {
$post_value=Yii::$app->request->post('checbox');
$certificate_value=Yii::$app->request->post('certificate_value');
if($certificate_value ==1){
$view=$this->renderAjax('academics/character-pdf',[
    'post_value'=>$post_value,
]);
}else{
  $view=$this->renderAjax('academics/leaving-pdf',[
    'post_value'=>$post_value,
]);  
}
// echo $view;
$this->layout = 'pdf';
$mpdf = new mPDF('c', 'A4-L');
$mpdf->WriteHTML($view);
$mpdf->Output('Character-SLC.pdf', 'D');
} 
return $this->render('academics/certificates',['model'=>$model,
    'class_array'=>$class_array,
    'session_array'=>$session_array,
]);
  }
}//end of class