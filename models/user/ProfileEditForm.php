<?php

namespace app\models\user;
 
use yii\base\Model;
use yii\db\ActiveQuery;
use Yii;
use app\models\User;
 
class ProfileEditForm extends Model
{
    public $username;
    public $email;
    public $firstname;
    public $lastname;
 
    /**
     * @var User
     */
    private $_user;
 
    public function __construct(User $user, $config = [])
    {
        $this->_user = $user;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->firstname = $user->firstname;
        $this->lastname = $user->lastname;
        parent::__construct($config);
    }
 
    public function rules()
    {
        return [
            [['firstname', 'lastname'], 'string'],
        ];
    }
 
    public function update()
    {
        if ($this->validate()) {
            $user = $this->_user;
            $user->firstname = $this->firstname;
            $user->lastname = $this->lastname;
            return $user->save();
        } else {
            return false;
        }
    }
}