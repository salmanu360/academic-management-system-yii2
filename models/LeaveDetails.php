<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "leave_details".
 *
 * @property integer $id
 * @property integer $leave_category
 * @property integer $designation
 * @property integer $count
 * @property integer $fk_branch_id
 */
class LeaveDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'leave_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['leave_category', 'designation', 'count', 'fk_branch_id'], 'required'],
            [['leave_category', 'designation', 'count', 'fk_branch_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'leave_category' => 'Leave Category',
            'designation' => 'Designation',
            'count' => 'Leave Count',
            'fk_branch_id' => 'Fk Branch ID',
        ];
    }

     public function getLeaveCategory()
    {
        return $this->hasOne(LeaveCategory::className(), ['id' => 'leave_category']);
    }
     public function getLeaveDesignation()
    {
        return $this->hasOne(RefDesignation::className(), ['designation_id' => 'designation']);
    }
}
