<?php


namespace framework\db;

use \PDOStatement;
class Sql
{
    // 数据库表名
    protected $table;
    // 数据库主键
    protected $primary = 'id';
    // WHERE和ORDER拼装后的条件
    private $filter = '';
    // Pdo bindParam()绑定的参数集合

    private  $param = [];
    
    private $column = [];
	
	public function setColumn($column){
		$this->column = $column;
		return $this;
	}
	
    public function fetchAll(){
          $db = Db::factory();
          $sql =' select '.($this->column==null?'*':implode(' , ',$this->column)).' from '.$this->table.$this->filter;
          $stmt =  $db->prepare($sql);

          if ($this->param!=[]){
              $this->formatParam($stmt,$this->param);
          }
          $stmt->execute();
          return $stmt->fetchAll();
    }

    public function where($where){
        if ($this->filter===''){
            $this->filter.=' WHERE '.$where.' ';
        }else{
            $this->filter.=(' and '.$where);
        }
        return $this;
    }
    public function setParam($param = []){
        $this->param = $param;
        return $this;
    }
    public function formatParam(PDOStatement $sth, $params = array())
    {

        foreach ($params as $param => &$value) {
            $param = is_int($param) ? $param + 1 : ':' . ltrim($param, ':');
            $sth->bindParam($param, $value);
        }

        return $sth;
    }

    public function update($data)
    {
        $sql = sprintf("update `%s` set %s %s", $this->table, $this->formatUpdate($data), $this->filter);
        $sth = Db::factory()->prepare($sql);
        $sth = $this->formatParam($sth, $data);
        $sth = $this->formatParam($sth, $this->param);
        $sth->execute();
        return $sth->rowCount();
    }

    private function formatInsert($data)
    {
        $fields = array();
        $names = array();
        foreach ($data as $key => $value) {
            $fields[] = sprintf("`%s`", $key);
            $names[] = sprintf(":%s", $key);
        }
        $field = implode(',', $fields);
        $name = implode(',', $names);
        return sprintf("(%s) values (%s)", $field, $name);
    }

    public function add($data)
    {
        $sql = sprintf("insert into `%s` %s", $this->table, $this->formatInsert($data));
        $sth = Db::factory()->prepare($sql);
        $sth = $this->formatParam($sth, $data);
        $sth = $this->formatParam($sth, $this->param);
        $sth->execute();
        return $sth->rowCount();
    }

}