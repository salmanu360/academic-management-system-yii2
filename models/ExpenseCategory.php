<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "expense_category".
 *
 * @property integer $id
 * @property string $title
 * @property integer $fk_branch_id
 * @property string $status
 */
class ExpenseCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'expense_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'fk_branch_id'], 'required'],
            [['fk_branch_id'], 'integer'],
            [['status'], 'string'],
            [['title'], 'string', 'max' => 555],
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
            'fk_branch_id' => 'Fk Branch ID',
            'status' => 'Status',
        ];
    }
}
