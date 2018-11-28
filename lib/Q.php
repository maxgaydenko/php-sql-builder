<?php

namespace MaxGaydenko\SqlBuilder;

class Q
{
 /**
  * @param $table
  * @return Select
  */
 public static function select($table)
 {
  return new Select($table);
 }

 /**
  * @param string $table
  * @param array $fields
  * @return Insert
  */
 public static function insert($table, array $fields = [])
 {
  return new Insert($table, $fields);
 }

 /**
  * @param $table
  * @param array $fields
  * @param $where
  * @return Update
  */
 public static function update($table, array $fields = [], $where = null)
 {
  return new Update($table, $fields, $where);
 }

 /**
  * @param $table
  * @param $where
  * @return Delete
  */
 public static function delete($table, $where = null)
 {
  return new Delete($table, $where);
 }

 /**
  * @param $value
  * @return Func
  */
 public static function func($value)
 {
  return new Func($value);
 }

 /**
  * @param $func
  * @return Func
  */
 public static function f($func)
 {
  return new Func($func);
 }

 /**
  * @return Func
  */
 public static function now()
 {
  return new Func("NOW()");
 }
}