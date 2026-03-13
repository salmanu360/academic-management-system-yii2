<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "message_control".
 *
 * @property int $id
 * @property string $message_id
 * @property string $message
 * @property int $branch_id
 * @property string $created_at
 * @property int $created_by
 */
class MessageControl extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message_control';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message_id', 'message', 'branch_id', 'created_at', 'created_by'], 'required'],
            [['message'], 'string'],
            [['branch_id', 'created_by'], 'integer'],
            [['created_at'], 'safe'],
            [['message_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message_id' => 'Message ID',
            'message' => 'Message',
            'branch_id' => 'Branch ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }
}
