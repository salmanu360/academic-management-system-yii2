<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fee_group".
 *
 * @property integer $id
 * @property integer $fk_branch_id
 * @property integer $fk_class_id
 * @property integer $fk_fee_head_id
 * @property string $created_date
 * @property string $updated_date
 * @property integer $updated_by
 * @property string $is_active
 * @property integer $fk_group_id
 * @property integer $amount
 */
class FeeGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fee_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fk_branch_id', 'fk_class_id', 'fk_fee_head_id', 'amount'], 'required'],
            [['fk_branch_id', 'fk_class_id', 'fk_fee_head_id', 'updated_by', 'fk_group_id', 'amount'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [['is_active'], 'string'],
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
            'fk_class_id' => 'Class',
            'fk_fee_head_id' => 'Fee Head',
            'created_date' => 'Created Date',
            'updated_date' => 'Updated Date',
            'updated_by' => 'Updated By',
            'is_active' => 'Is Active',
            'fk_group_id' => 'Group',
            'amount' => 'Amount',
        ];
    }

    public function getClass()
    {
        return $this->hasOne(RefClass::className(), ['class_id' => 'fk_class_id']);
    }
    public function getFkClass()
    {
        return $this->hasOne(RefClass::className(), ['class_id' => 'fk_class_id']);
    }
    public function getFkFeeHead()
    {
        return $this->hasOne(FeeHead::className(), ['id' => 'fk_fee_head_id']);
    }
    public function getFkGroup()
    {
        return $this->hasOne(RefGroup::className(), ['group_id' => 'fk_group_id']);
    }
    public function getFkBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'fk_branch_id']);
    }
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}
