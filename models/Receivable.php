<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "receivable".
 *
 * @property integer $id
 * @property integer $receivable_category
 * @property integer $class_id
 * @property integer $name
 * @property integer $contact
 * @property integer $amount
 * @property integer $branch_id
 * @property string $created_date
 */
class Receivable extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'receivable';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['receivable_category', 'name', 'amount', 'branch_id', 'created_date'], 'required'],
            [['receivable_category', 'class_id', 'contact', 'amount', 'branch_id'], 'integer'],
            [['created_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'receivable_category' => 'Receivable Category',
            'class_id' => 'Class',
            'name' => 'Name',
            'contact' => 'Contact',
            'amount' => 'Amount',
            'branch_id' => 'Branch ID',
            'created_date' => 'Created Date',
        ];
    }
    public function getClass()
    {
        return $this->hasOne(RefClass::className(), ['class_id' => 'class_id']);
    }
    public function getReceivablecategory()
    {
        return $this->hasOne(ReceivableCategory::className(), ['id' => 'receivable_category']);
    }
}
