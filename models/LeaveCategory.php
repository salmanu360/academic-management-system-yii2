<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "leave_category".
 *
 * @property integer $id
 * @property string $leave_category
 * @property integer $fk_branch_id
 * @property string $status
 */
class LeaveCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'leave_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['leave_category', 'fk_branch_id', 'status'], 'required'],
            [['fk_branch_id'], 'integer'],
            [['status'], 'string'],
            [['leave_category'], 'string', 'max' => 255],
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
            'fk_branch_id' => 'Fk Branch ID',
            'status' => 'Status',
        ];
    }
}
