<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property int $fk_branch_id
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $username
 * @property string $email
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $pass
 * @property string $avatar
 * @property string $status
 * @property int $fk_role_id
 * @property string $last_ip_address
 * @property int $last_login
 * @property string $created_at
 * @property string $updated_at
 * @property string $name_in_urdu
 * @property string $Image
 */
class UserStepone extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 'active';
    public $password;
    public $confirm_password;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_branch_id', 'first_name', 'last_name', 'username', 'password_hash', 'status', 'fk_role_id', 'created_at'], 'required'],
            [['fk_branch_id', 'fk_role_id', 'last_login'], 'integer'],
            ['username', 'unique'],
            [['created_at', 'updated_at'], 'safe'],
            [['first_name'], 'string', 'max' => 555],
            [['middle_name'], 'string', 'max' => 20],
            [['last_name'], 'string', 'max' => 80],
            [['username', 'email', 'password_hash', 'password_reset_token', 'avatar'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['last_ip_address'], 'string', 'max' => 50],
            [['name_in_urdu'], 'string', 'max' => 300],
             [['Image'], 'safe'],
             ['Image', 'file', 'extensions' => ['png','jpg','jpeg','gif'], 'maxSize' => 1024 * 300 * 0.5] //150kb
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_branch_id' => 'Fk Branch ID',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'last_name' => 'Last Name',
            'username' => 'Registeration No.',
            'email' => 'Email',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'pass' => 'Pass',
            'avatar' => 'Avatar',
            'status' => 'Status',
            'fk_role_id' => 'Fk Role ID',
            'last_ip_address' => 'Last Ip Address',
            'last_login' => 'Last Login',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'name_in_urdu' => 'Name In Urdu',
            'Image' => 'Student Image',
        ];
    }

    
    /**/
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        //'fk_branch_id'=>Yii::$app->common->getBranch();
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /*validate email already exist*/
    public function validateEmailAlreadyExist()
    {
        $alreadyexistbyemail = $this->find()
            ->where("id !=".$this->id." and email ='".$this->email."'")
            ->count();
        if ($alreadyexistbyemail > 0)
        {
            $this->addError('email', 'This email address has already been taken.');
        }
    }

    /*validate Username already exist*/
    public function validateUsernameAlreadyExist()
    {
        $alreadyexistbyusername = $this->find()
            ->where("id !=".$this->id." and username ='".$this->email."'")
            ->count();
        if ($alreadyexistbyusername > 0)
        {
            $this->addError('username', 'This username has already been taken.');
        }
    }
    /*
    * user full name
    */
    public function getfullName()
    {
        return $this->first_name.' '.$this->middle_name .''.$this->last_name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkRole()
    {
        return $this->hasOne(UserRoles::className(), ['id' => 'fk_role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'fk_branch_id']);
    }

    public function getStudent()
    {
        return $this->hasMany(StudentInfo::className(), ['user_id' => 'id']);
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert))
        {
            // Place your custom code here
            if($this->isNewRecord)
            {
                $this->created_at = new \yii\db\Expression('NOW()');
                if(Yii::$app->controller->id != 'branch'){
                    $this->fk_branch_id = Yii::$app->common->getBranch();
                }
                $this->updated_at = null;
            }
            elseif(!$this->isNewRecord)
            {
                $this->updated_at = new \yii\db\Expression('NOW()');
            }
            return true;
        }
        else
        {
            return false;
        }
    }
}
