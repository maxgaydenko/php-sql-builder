<?php

namespace MaxGaydenko\SqlBuilder;

class Select extends Query
{
 public $fields = [];
 public $join = [];
 public $where = [];
 public $group_by = "";
// public $having = []; @todo having
 public $order_by = "";
 public $limit = null;
 public $offset = 0;

 public function __construct($table)
 {
  $this->table = $table;
 }

 public function add(array $fields, $tableAlias = '', $outputPrefix = '')
 {
  foreach ($fields as $k => $v) {
   if (is_numeric($k))
    $this->fields[$outputPrefix . $v] = ($tableAlias ? "{$tableAlias}." : "") . $v;
   else
    $this->fields[$outputPrefix . $k] = ($tableAlias ? "{$tableAlias}." : "") . $v;
  }
  return $this;
 }

 public function addJoin($join, $type = "")
 {
  $this->join[] = ($type ? "{$type} " : "") . "JOIN {$join}";
  return $this;
 }

 public function addWhere($where)
 {
  if (is_array($where)) {
   foreach ($where as $k => $v) {
    if (!is_numeric($k)) {
     $this->where[] = "{$k} = :{$k}";
     $this->params[":{$k}"] = $v;
    }
   }
  } else {
   $this->where[] = $where;
  }
  return $this;
 }

 public function setGroupBy($group_by)
 {
  $this->group_by = $group_by;
  return $this;
 }

 public function setOrderBy($order_by)
 {
  $this->order_by = $order_by;
  return $this;
 }

 public function setLimit($limit)
 {
  $this->limit = $limit;
  return $this;
 }

 public function setOffset($offset)
 {
  $this->offset = $offset;
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
  $fields = [];
  foreach ($this->fields as $k => $v) {
   $fields[] = (is_numeric($k) || $k == $v) ? $v : "{$v} AS {$k}";
  }
  if ($this->table && count($fields) > 0) {
   $q = "SELECT " . implode(", ", $fields) . " FROM {$this->table}";
   if ($this->join && count($this->join) > 0)
    $q .= " " . implode(" ", array_values($this->join));
   if($this->where && count($this->where) > 0)
    $q .= " WHERE ".implode(" AND ", array_values($this->where));
   if ($this->group_by)
    $q .= " GROUP BY " . $this->group_by;
   //@todo having;
   if ($this->order_by)
    $q .= " ORDER BY " . $this->order_by;
   if ($this->limit)
    $q .= " LIMIT " . $this->limit;
   if ($this->offset)
    $q .= " OFFSET " . $this->offset;
   return $q;
  }
  return "";
 }
}