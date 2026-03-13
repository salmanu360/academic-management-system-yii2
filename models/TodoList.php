<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "todo_list".
 *
 * @property integer $id
 * @property string $title
 * @property string $start_date
 * @property string $end_date
 * @property integer $branch_id
 */
class TodoList extends \yii\db\ActiveRecord
{
    public $subject;
    public $message;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'todo_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'start_date', 'end_date', 'branch_id'], 'required'],
            [['start_date', 'end_date'], 'safe'],
            [['branch_id'], 'integer'],
            [['title'], 'string', 'max' => 1200],
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
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'branch_id' => 'Branch ID',
        ];
    }
}
