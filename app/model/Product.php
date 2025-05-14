<?php
// 商品模型，负责商品相关的数据库操作
namespace app\model;

use think\Model;

class Product extends Model
{
    // 数据库表名
    protected $table = 'products';

    // 是否自动写入时间戳
    protected $autoWriteTimestamp = true;
}
