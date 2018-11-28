<?php
namespace MaxGaydenko\SqlBuilder;

class Update extends Query
{
 public $fields = [];
 public $where = [];

 public function __construct($table, array $fields = [], $where = null)
 {
  $this->table = $table;
  $this->addFields($fields);
  if($where)
   $this->addWhere($where);
 }

 public function addFields(array $fields)
 {
  foreach ($fields as $k => $v) {
   if(!is_numeric($k)) {
    if($v instanceof Func) {
     $this->fields[$k] = $v;
    }
    else {
     $this->fields[$k] = ":{$k}";
     $this->params[":{$k}"] = $v;
    }
   }
  }
  return $this;
 }

 public function addWhere($where) {
  if(is_array($where)) {
   foreach($where as $k => $v) {
    if(!is_numeric($k)) {
     $this->where[] = "{$k} = :{$k}";
     $this->params[":{$k}"] = $v;
     if(isset($this->fields[$k]))
      unset($this->fields[$k]);
    }
   }
  }
  else {
   $this->where[] = $where;
  }
  return $this;
 }

 public function addParams(array $params)
 {
  foreach ($params as $k => $v) {
   if (!is_numeric($k))
    $this->params[$k] = $v;
  }
  return $this;
 }

 public function query1111()
 {
  if($this->where && count($this->where) > 0)
   return "DELETE FROM {$this->table} WHERE ".implode(" AND ", array_values($this->where));
  return "";
 }

 public function query()
 {
  $fields = [];
  foreach($this->fields as $k => $v) {
   if(!is_numeric($k))
    $fields[] = "{$k} = {$v}";
  }

  return ($this->table && count($fields) > 0 && $this->where && count($this->where) > 0)
   ? "UPDATE {$this->table} SET " . implode(", ", $fields)." WHERE ".implode(" AND ", array_values($this->where))
   : "";
 }
}