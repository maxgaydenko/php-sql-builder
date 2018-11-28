<?php
namespace MaxGaydenko\SqlBuilder;

class Delete extends Query
{
 public $where = [];

 public function __construct($table, $where = null)
 {
  $this->table = $table;
  if($where)
   $this->addWhere($where);
 }

 public function addWhere($where) {
  if(is_array($where)) {
   foreach($where as $k => $v) {
    if(!is_numeric($k)) {
     $this->where[] = "{$k} = :{$k}";
     $this->params[":{$k}"] = $v;
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

 public function query()
 {
  return ($this->table && $this->where && count($this->where) > 0)
   ? "DELETE FROM {$this->table} WHERE ".implode(" AND ", array_values($this->where))
   : "";
 }
}