<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "class_timetable".
 *
 * @property integer $id
 * @property integer $fk_branch_id
 * @property integer $class_id
 * @property integer $group_id
 * @property integer $subject_id
 * @property string $day
 * @property string $start_date
 * @property string $end_date
 */
class ClassTimetable extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $checktimetableshow;
    public static function tableName()
    {
        return 'class_timetable';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fk_branch_id', 'class_id', 'subject_id', 'day', 'start_date', 'end_date'], 'required'],
            [['fk_branch_id', 'class_id', 'group_id', 'subject_id'], 'integer'],
            [['start_date', 'end_date'], 'string', 'max' => 225],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_branch_id' => 'Fk Branch ID',
            'class_id' => 'Class',
            'group_id' => 'Group',
            'subject_id' => 'Subject',
            'day' => 'Day',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
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
    public function getsubject()
    {
        return $this->hasOne(Subjects::className(), ['id' => 'subject_id']);
    }
}
