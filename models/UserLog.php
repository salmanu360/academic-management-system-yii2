<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_log".
 *
 * @property int $id
 * @property int $user_id
 * @property string $country
 * @property string $ip_address
 * @property string $browser
 * @property string $version
 * @property string $platform
 * @property string $login_date_time
 * @property string $logout_date_time
 */
class UserLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'country', 'ip_address', 'browser'], 'required'],
            [['user_id'], 'integer'],
            [['login_date_time', 'logout_date_time'], 'safe'],
            [['country', 'ip_address'], 'string', 'max' => 55],
            [['browser'], 'string', 'max' => 30],
            [['version', 'platform'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'country' => 'Country',
            'ip_address' => 'Ip Address',
            'browser' => 'Browser',
            'version' => 'Version',
            'platform' => 'Platform',
            'login_date_time' => 'Login Date Time',
            'logout_date_time' => 'Logout Date Time',
        ];
    }
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
