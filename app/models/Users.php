<?php

use Phalcon\Mvc\Model\Validator\Email as Email;

class Users extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var integer
     */
    public $rule_id;

    /**
     *
     * @var integer
     */
    public $team_id;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'users';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function initialize()
    {
        $this->belongsTo(
            'rule_id',
            Rules::class,
            'id',
            [
                'alias' => 'rule'
            ]
        );

        $this->belongsTo(
            'team_id',
            Teams::class,
            'id',
            [
                'alias' => 'team'
            ]
        );

        $this->hasMany(
            'id',
            UserOvertime::class,
            'user_id',
            [
                'alias' => 'overtimes'
            ]
        );
    }

    public static function exists($email)
    {
        return self::findFirst([
            'email like {email}',
            'bind' => [
                'email' => '%'.$email.'%'
            ]
        ]);
    }

    public function redirectMe()
    {
        switch($this->rule_id)
        {
            case Rules::member():
                return 'userovertime';
                break;
            case Rules::teamLeader():
                return 'users';
                break;
        }
    }

}
