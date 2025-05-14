<?php
// 购买队列类，异步处理购买请求
namespace app\queue;

use think\queue\Job;
use think\facade\Db;
use think\facade\Log;
use app\model\Product;
use think\facade\Cache;

class PurchaseQueue
{
    // 最大重试次数
    const MAX_RETRY = 5;

    public function fire(Job $job, $data)
    {
        $productId = $data['productId'];
        $quantity = $data['quantity'];

        Db::startTrans();
        try {
            // 在事务内获取锁（重要！）
            $product = Product::where('id', $productId)
                ->lock(true)
                ->find();

            // 双重检查库存
            if (!$product || $product->stock < $quantity) {
                Db::commit(); // 提交事务释放锁
                $job->delete();
                Log::error("库存不足 product:{$productId}");
                return;
            }

            // 使用原子操作减少库存
            $affected = Db::name('product')
                ->where('id', $productId)
                ->where('stock', '>=', $quantity)
                ->setDec('stock', $quantity);

            if (!$affected) {
                Db::rollback();
                throw new \Exception("库存扣减失败");
            }

            Db::commit();

            // 先删缓存再设置,防旧数据
            $cacheKey = "product_stock_{$productId}";
            Cache::delete($cacheKey);
            Cache::set($cacheKey, $product->stock - $quantity, 3600);

            $job->delete();
            Log::info("购买成功 product:{$productId} quantity:{$quantity}");
        } catch (\Exception $e) {
            Db::rollback();
            Log::error("处理失败: " . $e->getMessage());

            // 重试控制
            if ($job->attempts() >= self::MAX_RETRY) {
                $job->delete();
                Log::error("超过最大重试次数 product:{$productId}");
            } else {
                $job->release(5); // 5秒后重试
            }
        }
    }
}
