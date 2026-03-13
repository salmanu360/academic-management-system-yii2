<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "expenses".
 *
 * @property integer $id
 * @property integer $fk_branch_id
 * @property integer $expense_category_id
 * @property string $title
 * @property string $description
 * @property integer $payment_mehtod
 * @property string $date
 * @property string $amount
 *
 * @property Branch $fkBranch
 * @property ExpenseCategory $expenseCategory
 * @property PaymentMethod $paymentMehtod
 */
class Expenses extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'expenses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fk_branch_id', 'expense_category_id', 'title', 'payment_mehtod', 'date', 'amount'], 'required'],
            [['fk_branch_id', 'expense_category_id', 'payment_mehtod'], 'integer'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 555],
            [['date', 'amount'], 'string', 'max' => 255],
            [['fk_branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::className(), 'targetAttribute' => ['fk_branch_id' => 'id']],
            [['expense_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExpenseCategory::className(), 'targetAttribute' => ['expense_category_id' => 'id']],
            [['payment_mehtod'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentMethod::className(), 'targetAttribute' => ['payment_mehtod' => 'id']],
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
            'expense_category_id' => 'Expense Category',
            'title' => 'Title',
            'description' => 'Description',
            'payment_mehtod' => 'Payment Mehtod',
            'date' => 'Date',
            'amount' => 'Amount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'fk_branch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpenseCategory()
    {
        return $this->hasOne(ExpenseCategory::className(), ['id' => 'expense_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMehtod()
    {
        return $this->hasOne(PaymentMethod::className(), ['id' => 'payment_mehtod']);
    }
}
