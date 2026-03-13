<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "support_team".
 *
 * @property integer $id
 * @property string $message
 * @property string $send_date
 * @property string $reply_date
 * @property string $reply
 * @property integer $sender_id
 * @property string $read_status
 * @property integer $recv_id
 * @property integer $fk_branch_id
 */
class SupportTeam extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'support_team';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message', 'send_date', 'reply_date', 'reply', 'sender_id', 'read_status', 'recv_id', 'fk_branch_id'], 'required'],
            [['message', 'reply', 'read_status'], 'string'],
            [['send_date', 'reply_date'], 'safe'],
            [['sender_id', 'recv_id', 'fk_branch_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message' => 'Message',
            'send_date' => 'Send Date',
            'reply_date' => 'Reply Date',
            'reply' => 'Reply',
            'sender_id' => 'Sender ID',
            'read_status' => 'Read Status',
            'recv_id' => 'Recv ID',
            'fk_branch_id' => 'Fk Branch ID',
        ];
    }
}
