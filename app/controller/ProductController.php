<?php
//  商品控制器，负责处理购买请求
namespace app\controller;

use app\controller\BaseController;
use app\service\ProductService;
use think\facade\Cache;
use think\facade\View;

class ProductController extends BaseController
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function getStock($productId)
    {
        $stock = $this->productService->getStock($productId);
        return json(['stock' => $stock]);
    }

    public function purchase($productId, $quantity)
    {
        $this->productService->addPurchaseToQueue($productId, $quantity);
        return json(['message' => '请求已加入购买队列，正在处理中...']);
    }
        public function detail($id)
    {
        // 假设 ProductService 中有一个方法可以获取商品详情
        $product = $this->productService->getProductById($id);

        // 返回商品详情页面或者JSON数据
        if ($product) {
            return View::fetch('product/detail', ['product' => $product]);
        } else {
            return json(['error' => '商品未找到'], 404);
        }
    }
}
