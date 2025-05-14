<?php
// 商品业务逻辑层
namespace app\service;

use think\facade\Db;
use think\facade\Cache;
use think\queue\Job;
use app\model\Product;

class ProductService
{
    // 获取商品库存
    public function getStock($productId)
    {
        // 先从Redis缓存中获取库存
        $stock = Cache::get('product_stock_' . $productId);

        if ($stock === false) {
            // 如果缓存中没有，再从数据库查询
            $product = Product::find($productId);
            $stock = $product ? $product->stock : 0;
            // 将库存存入Redis缓存
            Cache::set('product_stock_' . $productId, $stock, 3600);
        }

        return $stock;
    }

    // 将购买请求加入队列
    public function addPurchaseToQueue($productId, $quantity)
    {
        // 将购买请求加入队列，异步处理
        \think\facade\Queue::push('app\\queue\\PurchaseQueue', [
            'productId' => $productId,
            'quantity'  => $quantity
        ]);
    }
    public function getProductById($id)
    {
        return Product::find($id);
    }
}
