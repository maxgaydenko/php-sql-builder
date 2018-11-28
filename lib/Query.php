<?php

namespace MaxGaydenko\SqlBuilder;

abstract class Query
{
 public $table;
 public $params = [];

 abstract public function query();

 public function params()
 {
  return $this->params;
 }
}