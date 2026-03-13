<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "exam_quiz".
 *
 * @property integer $id
 * @property integer $fk_branch_id
 * @property integer $fk_class_id
 * @property integer $fk_group_id
 * @property integer $fk_section_id
 * @property integer $stu_id
 * @property integer $fk_subject_id
 * @property integer $test_id
 * @property integer $obtained_marks
 * @property string $remarks
 * @property string $created_date
 * @property integer $user_id
 */
class ExamQuiz extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'exam_quiz';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fk_branch_id', 'fk_class_id', 'stu_id', 'fk_subject_id', 'test_id', 'obtained_marks', 'created_date', 'user_id'], 'required'],
            [['fk_branch_id', 'fk_class_id', 'fk_group_id', 'stu_id', 'fk_subject_id', 'test_id', 'obtained_marks', 'user_id'], 'integer'],
            [['created_date'], 'safe'],
            [['remarks'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'fk_branch_id' => Yii::t('app', 'Fk Branch ID'),
            'fk_class_id' => Yii::t('app', ' Class'),
            'fk_group_id' => Yii::t('app', ' Group'),
            'stu_id' => Yii::t('app', 'Stu ID'),
            'fk_subject_id' => Yii::t('app', 'Subject'),
            'test_id' => Yii::t('app', 'Quiz'),
            'obtained_marks' => Yii::t('app', 'Obtained Marks'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_date' => Yii::t('app', 'Created Date'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }
}
