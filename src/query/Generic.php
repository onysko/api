<?php
/**
 * Created by PhpStorm.
 * User: VITALYIEGOROV
 * Date: 08.12.15
 * Time: 23:11
 */
namespace samsoncms\api\query;

use samsoncms\api\Material;
use samsonframework\orm\ArgumentInterface;
use samsonframework\orm\Condition;
use samsonframework\orm\QueryInterface;

/**
 * Material with additional fields query.
 * @package samsoncms\api
 */
class Generic
{
    /** @var array Collection of all supported entity fields */
    protected static $parentFields = array(
        Material::F_PRIMARY=> Material::F_PRIMARY,
        Material::F_PRIORITY => Material::F_PRIORITY,
        Material::F_IDENTIFIER => Material::F_IDENTIFIER,
        Material::F_DELETION => Material::F_DELETION,
        Material::F_PUBLISHED => Material::F_PUBLISHED,
        Material::F_PARENT => Material::F_PARENT,
        Material::F_CREATED => Material::F_CREATED,
    );

    /** @var string Entity identifier */
    protected static $identifier;

    /** @var string Entity navigation identifiers */
    protected static $navigationIDs = array();

    /**
     * @var string Collection of entity field names
     * @deprecated Created for old application who need real additional field names
     */
    public static $fieldRealNames = array();

    /** @var string Collection of entity field names */
    public static $fieldNames = array();


    /** @var QueryInterface Database query instance */
    protected $query;

    /** @var array Collection of entity fields to retrieved from database */
    protected $selectedFields;

    /** @var Condition Query conditions */
    protected $conditions;

    /** @var array Collection of ordering parameters */
    protected $orderBy = array();

    /** @var array Collection of limit parameters */
    protected $limit = array();

    /**
     * Convert date value to database format.
     * TODO: Must implement at database layer
     *
     * @param string $date Date value for conversion
     * @return string Converted date to correct format
     */
    protected function convertToDateTime($date)
    {
        return date("Y-m-d H:i:s", strtotime($date));
    }

    /**
     * Add condition to current query.
     *
     * @param string $fieldName Entity field name
     * @param string $fieldValue Value
     * @return $this Chaining
     */
    public function where($fieldName, $fieldValue = null, $fieldRelation = ArgumentInterface::EQUAL)
    {
        $this->conditions->add($fieldName, $fieldValue, $fieldRelation);

        return $this;
    }

    /**
     * Set field for sorting.
     *
     * @param string $fieldName Additional field name
     * @param string $order Sorting order
     * @return $this Chaining
     */
    public function orderBy($fieldName, $order = 'ASC')
    {
        if (array_key_exists($fieldName, static::$parentFields)) {
            $this->orderBy = array($fieldName, $order);
        }

        return $this;
    }

    /**
     * Add primary field query condition.
     *
     * @param string $value Field value
     * @return $this Chaining
     * @see Material::where()
     */
    public function primary($value)
    {
        return $this->where(Material::F_PRIMARY, $value);
    }

    /**
     * Add identifier field query condition.
     *
     * @param string $value Field value
     * @return $this Chaining
     * @see Material::where()
     */
    public function identifier($value)
    {
        return $this->where(Material::F_IDENTIFIER, $value);
    }

    /**
     * Add active flag condition.
     *
     * @param bool $value Field value
     * @return $this Chaining
     * @see Material::where()
     */
    public function active($value)
    {
        return $this->where(Material::F_DELETION, $value);
    }

    /**
     * Add entity published field query condition.
     *
     * @param string $value Field value
     * @return $this Chaining
     * @see Material::where()
     */
    public function published($value)
    {
        return $this->where(Material::F_PUBLISHED, $value);
    }

    /**
     * Add entity creation field query condition.
     *
     * @param string $value Field value
     * @param string $relation @see ArgumentInterface types
     * @return $this Chaining
     * @see Material::where()
     */
    public function created($value, $relation = ArgumentInterface::EQUAL)
    {
        return $this->where(Material::F_CREATED, $this->convertToDateTime($value), $relation);
    }

    /**
     * Add entity modification field query condition.
     *
     * @param string $value Field value
     * @param string $relation @see ArgumentInterface types
     * @return $this Chaining
     * @see Material::where()
     */
    public function modified($value, $relation = ArgumentInterface::EQUAL)
    {
        return $this->where(Material::F_MODIFIED, $this->convertToDateTime($value), $relation);
    }

    /**
     * Perform SamsonCMS query and get entities collection.
     *
     * @return \samsoncms\api\Entity[] Collection of found entities
     */
    public function find()
    {
        $this->query->entity(static::$identifier);

        // Add query sorter for showed page
        if (count($this->orderBy) === 2) {
            $this->query->orderBy($this->orderBy[0], $this->orderBy[1]);
        }

        // Proxy to regular database query
        return $this->query
            ->whereCondition($this->conditions)
            ->exec();
    }

    /**
     * Perform SamsonCMS query and get collection of entities fields.
     *
     * @param string $fieldName Entity field name
     * @return array Collection of entity fields
     */
    public function fields($fieldName)
    {
        // Proxy to regular database query
        return $this->query
            ->entity(static::$identifier)
            ->whereCondition($this->conditions)
            ->fields($fieldName);
    }

    /**
     * Perform SamsonCMS query and get first matching entity.
     *
     * @return \samsoncms\api\Entity First matching entity
     */
    public function first()
    {
        // Proxy to regular database query
        $return = $this->query
            ->entity(static::$identifier)
            ->limit(1)
            ->whereCondition($this->conditions)
            ->exec();

        return array_shift($return);
    }

    /**
     * Perform SamsonCMS query and get amount resulting entities.
     *
     * @return int Amount of resulting entities
     */
    public function count()
    {
        // Proxy to regular database query
        return $this->query
            ->entity(static::$identifier)
            ->whereCondition($this->conditions)
            ->count();
    }

    /**
     * Generic constructor.
     *
     * @param QueryInterface $query Database query instance
     */
    public function __construct(QueryInterface $query)
    {
        $this->query = $query;
        $this->conditions = new Condition();
    }
}
