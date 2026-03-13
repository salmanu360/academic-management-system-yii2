<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "exam_quiz_type".
 *
 * @property integer $id
 * @property integer $subject_id
 * @property integer $class_id
 * @property integer $group_id
 * @property integer $teacher_id
 * @property integer $total_marks
 * @property integer $passing_marks
 * @property integer $user_id
 * @property string $quiz_date
 * @property string $created_date
 * @property integer $fk_branch_id
 */
class ExamQuizType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'exam_quiz_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subject_id', 'class_id', 'teacher_id', 'total_marks', 'passing_marks', 'user_id', 'quiz_date', 'created_date', 'fk_branch_id'], 'required'],
            [['subject_id', 'class_id', 'group_id', 'teacher_id', 'total_marks', 'passing_marks', 'user_id', 'fk_branch_id'], 'integer'],
            [['quiz_date', 'created_date'], 'safe'],
            //[['quiz_date'], 'unique','message'=>'This Subject Quiz is Already taken on given date','targetAttribute' => ['quiz_date', 'subject_id','class_id']],
            ['passing_marks', 'compare','compareAttribute'=>'total_marks','operator'=>'<',
            'message'=>'Passing marks must be less than Total marks', 'type' => 'number'],
            ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'subject_id' => Yii::t('app', 'Subject'),
            'class_id' => Yii::t('app', 'Class'),
            'group_id' => Yii::t('app', 'Group'),
            'teacher_id' => Yii::t('app', 'Teacher'),
            'total_marks' => Yii::t('app', 'Total Marks'),
            'passing_marks' => Yii::t('app', 'Passing Marks'),
            'user_id' => Yii::t('app', 'User'),
            'quiz_date' => Yii::t('app', 'Quiz Date'),
            'created_date' => Yii::t('app', 'Created Date'),
            'fk_branch_id' => Yii::t('app', 'Fk Branch ID'),
        ];
    }
}
