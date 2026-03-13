<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "assigments".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $class_id
 * @property integer $group_id
 * @property integer $subject_id
 * @property string $date_of_submission
 * @property string $image
 * @property string $status
 */
class Assigments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'assigments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'class_id', 'subject_id', 'date_of_submission', 'status','fk_branch_id','assign_by'], 'required'],
            [['class_id', 'group_id', 'subject_id','fk_branch_id'], 'integer'],
            [['date_of_submission','assign_by'], 'safe'],
            [['status'], 'string'],
            [['title', 'description'], 'string', 'max' => 555],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'class_id' => 'Class',
            'group_id' => 'Group',
            'subject_id' => 'Subject',
            'date_of_submission' => 'Date Of Submission',
            'image' => 'Image',
            'status' => 'Status',
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
