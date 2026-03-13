<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "exam_grading".
 *
 * @property int $id
 * @property string $grade
 * @property string $marks_obtain_from
 * @property string $marks_obtain_to
 * @property string $grade_name
 * @property string $created_by
 * @property int $updated_id
 * @property string $created_date
 * @property string $updated_date
 * @property int $branch_id
 */
class ExamGrading extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'exam_grading';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['grade', 'marks_obtain_from', 'marks_obtain_to', 'grade_name', 'created_by', 'created_date', 'branch_id'], 'required'],
            [['created_by', 'created_date', 'updated_date'], 'safe'],
            [['updated_id', 'branch_id'], 'integer'],
            [['grade', 'marks_obtain_from', 'marks_obtain_to', 'grade_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'grade' => 'Grade',
            'marks_obtain_from' => 'Marks Obtain From',
            'marks_obtain_to' => 'Marks Obtain To',
            'grade_name' => 'Grade Name',
            'created_by' => 'Created By',
            'updated_id' => 'Updated ID',
            'created_date' => 'Created Date',
            'updated_date' => 'Updated Date',
            'branch_id' => 'Branch ID',
        ];
    }
}
