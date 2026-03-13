<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "leave_application".
 *
 * @property integer $id
 * @property integer $login_id
 * @property integer $leave_category
 * @property string $from_date
 * @property string $to_date
 * @property string $reason
 * @property integer $fk_branch_id
 */
class LeaveApplication extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'leave_application';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login_id', 'leave_category', 'from_date', 'to_date', 'reason', 'fk_branch_id'], 'required'],
            [['login_id', 'leave_category', 'fk_branch_id'], 'integer'],
            [['reason', 'approval_status'], 'string'],
            [['from_date', 'to_date'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login_id' => 'Login ID',
            'leave_category' => 'Leave Category',
            'from_date' => 'From Date',
            'to_date' => 'To Date',
            'reason' => 'Reason',
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
