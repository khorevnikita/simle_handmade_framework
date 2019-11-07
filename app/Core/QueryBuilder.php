<?php
/**
 * Created by PhpStorm.
 * User: Nikita
 * Date: 07.11.2019
 * Time: 16:26
 */

namespace App;


class QueryBuilder
{
    protected $pdo;
    protected $sql = "";

    protected $whereCalls = 0;
    protected $class;
    protected $table;

    /**
     * Set database credentials from Env
     * Create new connection
     * QueryBuilder constructor.
     */
    function __construct()
    {
        try {
            $dbhost = env('DB_HOST');
            $dbname = env('DB_NAME');
            $dbusername = env('DB_USER');
            $dbpassword = env('DB_PASSWORD');

            $this->pdo = new \PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);

        } catch (\PDOException $e) {
            print "Error: " . $e->getMessage();
            die();
        }
    }

    /**
     * Init class. We have the rule:
     * User model is always in users table (etc)
     * @param $class
     * @return $this
     */
    public function find($class)
    {
        $this->class = $class;

        $table = $this->tableFromClass($class);

        $this->sql = "SELECT * FROM $table";
        return $this;
    }

    /**
     *  SQL WHERE statement
     * @param $field
     * @param $mark
     * @param $value
     * @return $this
     */
    public function where($field, $mark, $value)
    {
        $v_str = is_string($value) ? "'$value'" : $value;
        $this->sql = $this->sql . ($this->whereCalls ? " AND " : " WHERE ") . " $field $mark $v_str ";
        $this->whereCalls++;
        return $this;
    }

    /**
     *
     * SQL is not null statement
     * @param $field
     * @return $this
     */

    public function whereNotNull($field)
    {
        $this->sql = $this->sql . ($this->whereCalls ? " AND " : " WHERE ") . " $field is not null ";
        return $this;
    }

    /**
     * SQL is null statement
     *
     * @param $field
     * @return $this
     */
    public function whereNull($field)
    {
        $this->sql = $this->sql . ($this->whereCalls ? " AND " : " WHERE ") . " $field is null ";
        return $this;
    }

    public function orderBy($field, $direction)
    {
        $this->sql = $this->sql . " order by $field $direction ";
        return $this;
    }

    /**
     * SQL limit 1 statement
     *
     * return called model
     *
     * @return mixed
     */
    public function first()
    {
        $this->sql = $this->sql . " LIMIT 1";
        $statement = $this->pdo->query($this->sql);
        $row = $statement->fetch(\PDO::FETCH_ASSOC);
        $response = $this->setClass($row);
        return $response;
    }

    /**
     * SQL limit * statement
     * Return array of called models
     * @param null $limit
     * @return array
     */
    public function get($limit = null)
    {
        if ($limit) {
            $this->sql = $this->sql . " LIMIT $limit";
        }
        $statement = $this->pdo->query($this->sql);

        $response = [];
        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $response[] = $this->setClass($row);
        };

        return $response;
    }

    /**
     *
     * Set sql response array on called model
     * @param $row
     * @return mixed
     */

    protected function setClass($row)
    {
        $c = new $this->class;
        if (!$row) {
            return null;
        }
        foreach ($row as $key => $value) {
            $c->{$key} = $value;
        }
        return $c;
    }

    /**
     * Route method of creating/updating model
     * @param $class
     * @return array|bool
     */
    public function save($class)
    {
        if (isset($class->id)) {
            $r = $this->update($class);
            return $r;
        }
        $r = $this->insert($class);
        return $r;
    }

    /**
     * Creating new model
     *
     * @param $class
     * @return array|bool
     */
    protected function insert($class)
    {
        $values = get_object_vars($class);
        $keys = array_keys($values);

        $table = $this->tableFromClass(get_class($class));

        $sql = 'INSERT INTO ' . $table . ' SET ' . $this->pdoSet($keys);
        $statement = $this->pdo->prepare($sql);
        $r = $statement->execute($values);

        if (!$r) {
            return $statement->errorInfo();
        }
        return $r;
    }

    /**
     * Update all model properties
     * (!) ALWAYS BY ID
     * Dont update ID of model!!!
     *
     * @param $class
     * @return array|bool
     */
    protected function update($class)
    {
        $values = get_object_vars($class);
        $keys = array_keys($values);

        $table = $this->tableFromClass(get_class($class));

        $sql = 'UPDATE ' . $table . ' SET ' . $this->pdoSet($keys) . " WHERE id=:id";
        $statement = $this->pdo->prepare($sql);
        $r = $statement->execute($values);

        if (!$r) {
            return $statement->errorInfo();
        }
        return $r;
    }

    protected function tableFromClass($class)
    {
        return strtolower(explode('\\', $class)[count(explode('\\', $class)) - 1]) . 's';
    }

    protected function pdoSet($keys)
    {
        $preparedKeys = [];
        foreach ($keys as $key) {
            $preparedKeys[$key] = "$key = :$key";
        }
        return implode(",", $preparedKeys);
    }

    public function paginate($skip = 0, $take = 1)
    {
        $this->sql = $this->sql . " LIMIT $skip, $take";
        return $this;
    }
}