<?php

namespace App\system\Model;



class Model
{
    protected $table;
    protected $select = [];
    protected $from;
    protected $where = [];
    protected $limit = '';
    protected $sql = '';
    protected $typeSql = 'select';

    protected $data;
    protected $id;

public function __construct(){

    $this->ins_db = DbDriver::get_instance();

    if (!$this->table) {
        $this->table = strtolower((new \ReflectionClass($this))->getShortName()).'s';
    }

    if($this->id) {
        $this->id = $id;

        $obj = new static;
        $obj->select(['*'])->from()->where('id', $id);
        $result = $obj->get();
        if ($result && $result[0]) {
            $this->data = $result[0];
        }
        else{
            throw  new \Exception('Error');
        }
}
}
public function select($fields)
{
    if(is_array($fields)){
        $this->select = $fields;
    }
    else if(is_string($fields) && func_num_args() == 1) {
        $this->select[] = $fields;
    }
    else if(func_num_args() > 1) {
        $this->select[] = func_get_args();
    }
    return $this;


}

public function from($table = null)
{
    if($table){
        $this->from = $table;
    }
    else {
        $this->from = $this->table;
    }
}
public function where($field, $operator = '', $value = '')
{
    if(func_num_args() == 2){
        $this->where[] = $field." = ".$this->ins_db->getInsDb()->real_escape_string($operator);
    }
    else {
        $this->where[] = $field.' '. $operator.' '.$this->ins_db->getInsDb()->real_escape_string($operator);
    }
    return $this;
    }

    public function limit($start, $offset)
    {
        $this->limit = $start.', '.$offset;
        return $this;
    }


    public static function __callstatic($name, $arguments){

        switch ($name){
            case 'all':
                $obj = new static;
                $obj->select(['*'])->from();
                return $obj->get();

            case 'find':
                $obj = new static;
                $obj->select(['*'])->from()->where('id', $arguments[0]);
                return $obj->get();

            default:
                break;
        }

    }


    public function save()
    {

        if($this->id) {
            $this->sql = 'UPDATE '.$this->table.' SET ';

        foreach ($this->data as $key=>$val) {
            $this->sql .=$key."='".$val."',";
        }

        $this->sql = rtrim($this->sql, ',');

        $this->where('id', $this->id);
        $this->sql .= $this->WherePrepare();

        $this->typeSql = 'update';
        return $this->execute();
        }
        else {
            if (count($this->data)) {
                $keys = array_keys($this->data);
                $values = array_values($this->data);

                $this->sql = 'INSERT INTO '.$this->table;

                $this->sql .= " (". implode(",",$keys).") ";

                $this->sql .= "VALUES (";

                $this->sql = rtrim($this->sql, ',').")";

                $this->typeSql = 'insert';
                return $this->execute();
            }
        }
    }

    public function destroy()
    {
        if($this->id) {
            $this->where('id', $this->id);
            $this->sql = 'DELETE FROM '.$this->table;
            $this->sql .= $this->wherePrepare();
            $this->typeSql = 'delete';

            return $this->execute();

        }
    }

    public function fromPrepare()
    {
        $str = 'FROM '.$this->from;
        return $str;
    }

    public function selectPrepare()
    {
        $str = 'SELECT '. implode(',', $this->select);
        return $str;
    }

    public function limitPrepare()
    {
        $str = '';
        if ($this->limit) {
            $str = 'LIMIT '. $this->limit;
        }

        return $str;
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name. $this->data)) {
            return $this->data[$name];
        }

        return null;

    }

    public function wherePrepare()
    {
        $str = '';

        if (count($this->where) > 0 ) {
            $ii = 0;
            foreach ($this->where as $key=>$val) {
                if ($ii == 0) {
                    $str .= ' '.' WHERE '.$val;
                }
                if ($ii >0) {

                    $str .= ' '.' AND '.$val;
                }
                $ii++;
            }
        }

        return $str;
    }


    public function get()
    {
        $this->sql = $this->selectPrepare() . $this->fromPrepare(). $this->wherePrepare() . $this->limitPrepare();
        $this->typeSql = 'select';
        if ($this->sql) {
            return $this->execute();
        }

        return null;
    }

    protected function execute() {

        if ($this->sql) {
            switch ($this->typeSql) {
                case 'select':
                    $result = $this->ins_db->query($this->sql);

                    if (!$result) {
                        throw new \Exception('Request error'.$this->ins_db->connect_errno.' ');
                    }

                    if ($result->num_rows == 0) {
                        return FALSE;
                    }

                    for ($i = 0; $i < $result->num_rows; $i++) {
                        $row[] = $result->fetch_assoc();
                    }

                    return $row;


                case 'delete' :
                case 'update' :
                    $result = $this->ins_db->query($this->sql);

                    if (!$result) {
                        throw new \Exception('Request error '.$this->ins_db->connect_errno. ' ');
                    }

                    return true;

                case 'insert' :

                    $result = $this->ins_db->query($this->sql);

                    if (!$result) {
                        throw new \Exception('Error DB: '.$this->errno.' |');

                    }

                    return $this->ins_db->getInsDb()->insert_id;
                    break;
                default:

                    break;

            }


        }
    }
}