<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "messages".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $message
 * @property string $reply
 * @property string $send_date
 * @property string $reply_date
 * @property integer $fk_branch_id
 */
class Messages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'messages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id','subject', 'message', 'send_date', 'fk_branch_id','sender_id'], 'required'],
            [['user_id', 'fk_branch_id'], 'integer'],
            [['message','subject'], 'string'],
            [['send_date','subject'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'message' => 'Message',
            'send_date' => 'Send Date',
            'fk_branch_id' => 'Fk Branch ID',
        ];
    }
}
