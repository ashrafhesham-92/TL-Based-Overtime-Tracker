<?php

class Rules extends \Phalcon\Mvc\Model
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
    public $name;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'rules';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Rules[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Rules
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function initialize()
    {
        $this->hasMany(
            'id',
            Users::class,
            'rule_id',
            [
                'alias' => 'users'
            ]
        );

    }

    public static function member()
    {
        return self::findFirst([
            'name like {name}',
            'bind' => [
                'name' => '%member%'
            ]
        ])->id;
    }

    public static function teamLeader()
    {
        return self::findFirst([
            'name like {name}',
            'bind' => [
                'name' => '%team leader%'
            ]
        ])->id;
    }

}
