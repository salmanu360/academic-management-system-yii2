<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "book_issue".
 *
 * @property integer $id
 * @property integer $book_id
 * @property integer $class_id
 * @property integer $group_id
 * @property integer $section_id
 * @property integer $user_id
 * @property string $issue_date
 * @property string $due_date
 * @property string $return_date
 * @property string $fine
 * @property string $remarks
 * @property string $status
 * @property integer $fk_branch_id
 */
class BookIssue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $user_ids;
    public static function tableName()
    {
        return 'book_issue';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['book_id', 'issue_date', 'due_date', 'status', 'fk_branch_id'], 'required'],
            [['book_id', 'class_id', 'group_id', 'section_id', 'user_id', 'fk_branch_id','user_type'], 'integer'],
            [['issue_date', 'due_date', 'return_date','user_type'], 'safe'],
            [['remarks', 'status'], 'string'],
            [['fine'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'book_id' => 'Book',
            'class_id' => 'Class',
            'group_id' => 'Group',
            'section_id' => 'Section',
            'user_id' => 'User',
            'issue_date' => 'Issue Date',
            'due_date' => 'Due Date',
            'return_date' => 'Return Date',
            'fine' => 'Fine',
            'remarks' => 'Remarks',
            'status' => 'Status',
            'fk_branch_id' => 'Fk Branch ID',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getBook()
    {
        return $this->hasOne(AddBooks::className(), ['id' => 'book_id']);
    }
}
