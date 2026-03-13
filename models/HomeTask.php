<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "home_task".
 *
 * @property integer $id
 * @property integer $class_id
 * @property integer $group_id
 * @property integer $subject_id
 * @property integer $teacher_id
 * @property string $class_work
 * @property string $home_task
 * @property string $remarks
 * @property string $date
 * @property integer $fk_branch_id
 * @property integer $user_id
 */
class HomeTask extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'home_task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['class_id', 'subject_id', 'teacher_id', 'home_task', 'date', 'fk_branch_id', 'user_id'], 'required'],
            [['class_id', 'group_id', 'subject_id', 'teacher_id', 'fk_branch_id', 'user_id'], 'integer'],
            [['date'], 'safe'],
            [['class_work', 'home_task', 'remarks'], 'string', 'max' => 555],
            //[['subject_id'], 'unique','message'=>'This Subject Quiz is Already taken on given date','targetAttribute' => ['subject_id', 'teacher_id','class_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'class_id' => Yii::t('app', 'Class'),
            'group_id' => Yii::t('app', 'Group'),
            'subject_id' => Yii::t('app', 'Subject'),
            'teacher_id' => Yii::t('app', 'Teacher'),
            'class_work' => Yii::t('app', 'Class Work'),
            'home_task' => Yii::t('app', 'Home Task'),
            'remarks' => Yii::t('app', 'Remarks'),
            'date' => Yii::t('app', 'Date'),
            'fk_branch_id' => Yii::t('app', 'Fk Branch ID'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }

    public function getClass()
    {
        return $this->hasOne(RefClass::className(), ['class_id' => 'class_id']);
    }
    public function getGroup()
    {
        return $this->hasOne(RefGroup::className(), ['group_id' => 'group_id']);
    }
    public function getSubject()
    {
        return $this->hasOne(Subjects::className(), ['id' => 'subject_id']);
    }
    public function getTeacher()
    {
        return $this->hasOne(EmployeeInfo::className(), ['emp_id' => 'teacher_id']);
    }
}
