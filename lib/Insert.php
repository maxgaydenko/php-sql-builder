<?php

namespace MaxGaydenko\SqlBuilder;

class Insert extends Query
{
 public $fields = [];

 public function __construct($table, array $fields = [])
 {
  $this->table = $table;
  $this->addFields($fields);
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

 public function query()
 {
  $fields = [];
  foreach ($this->fields as $k => $v) {
   if (!is_numeric($k))
    $fields[] = "{$k} = {$v}";
  }
  return ($this->table && count($fields) > 0)
   ? "INSERT INTO {$this->table} SET " . implode(", ", $fields)
   : "";
 }

}