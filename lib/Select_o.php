<?php
namespace MaxGaydenko\SqlBuilder;

class Select_o extends Query
{
 public $fields = [];
 public $from = "";
 public $join = [];
 public $where = [];
 public $group_by = "";
 public $having = [];
 public $order_by = "";
 public $limit = 1000;
 public $offset = 0;
 public $params = [];

 public function __construct($from = "")
 {
  $this->from = $from;
 }

 public function setFrom($from) {
  $this->from = $from;
  return $this;
 }

 public function addObj($classname, $tableAlias = '', $outputPrefix = '') {
  foreach (array_keys(get_class_vars($classname)) as $var)
   $this->fields[$outputPrefix.$var] = ($tableAlias? "{$tableAlias}.": '').$var;
  return $this;
 }

 public function addFields(array $fields) {
  foreach($fields as $k => $v) {
   $this->fields[$k] = $v;
  }
  return $this;
 }

 public function addJoin($join, $key = null) {
  if($key)
   $this->join[$key] = $join;
  else
   $this->join[] = $join;
  return $this;
 }

 public function addWhere($where, $key = null) {
  if($key)
   $this->where[$key] = $where;
  else
   $this->where[] = $where;
  return $this;
 }

 public function setGroupBy($group_by) {
  $this->group_by = $group_by;
  return $this;
 }

 public function setOrderBy($order_by) {
  $this->order_by = $order_by;
  return $this;
 }

 public function setLimit($limit) {
  $this->limit = $limit;
  return $this;
 }

 public function setOffset($offset) {
  $this->offset = $offset;
  return $this;
 }

 public function addParams(array $params) {
  foreach ($params as $k=>$v) {
   if(!is_numeric($k))
    $this->params[$k] = $v;
  }
  return $this;
 }

 public function query()
 {
  $fields = [];
  foreach ($this->fields as $k => $v) {
   if(!is_numeric($k))
    $fields[] = ($k == $v) ? $k : ($v . " AS " . $k);
  }
  $query = "SELECT " . implode(", ", $fields);
  if($this->from)
   $query .= " FROM ".$this->from;
  if($this->join && count($this->join) > 0)
   $query .= " ".implode(" ", array_values($this->join));
  if($this->where && count($this->where) > 0)
   $query .= " WHERE ".implode(" AND ", array_values($this->where));
  if($this->group_by)
   $query .= " GROUP BY ".$this->group_by;
  //@todo having;
  if($this->order_by)
   $query .= " ORDER BY ".$this->order_by;
  if($this->limit)
   $query .= " LIMIT ".$this->limit;
  if($this->offset)
   $query .= " OFFSET ".$this->offset;
  return $query;
 }

}