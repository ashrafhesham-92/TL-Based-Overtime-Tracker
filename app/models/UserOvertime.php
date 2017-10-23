<?php

class UserOvertime extends \Phalcon\Mvc\Model
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
    public $date;

    /**
     *
     * @var integer
     */
    public $overtime_amount;

    /**
     *
     * @var integer
     */
    public $overtime_unit_id;

    /**
     *
     * @var string
     */
    public $project_name;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var integer
     */
    public $approved;

    /**
     *
     * @var integer
     */
    public $approved_by;

    /**
     *
     * @var string
     */
    public $approve_date;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $updated_at;

    public $details;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'user_overtime';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserOvertime[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserOvertime
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function initialize()
    {
        $this->belongsTo(
            'overtime_unit_id',
            Units::class,
            'id',
            [
                'alias' => 'unit'
            ]
        );

        $this->belongsTo(
            'user_id',
            Users::class,
            'id',
            [
                'alias' => 'user'
            ]
        );

        $this->belongsTo(
            'approved_by',
            Users::class,
            'id',
            [
                'alias' => 'approvedBy'
            ]
        );
    }

}
