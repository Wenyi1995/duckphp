# DuckPhp\Ext\MyMiddlewareManager

## 简介

简单的中间件组件

## 选项
middleware => []

    middelware 放的是回调列表

## 公开属性

response, request， 替换 Response ,Request 对象。

## 说明

中间件，以钩子模式，放在最后一个前路由钩子

DuckPhp 不推荐使用中间件。这个扩展只是为了让中间件能运行。

路由前后钩子比中间件灵活多了。（我在找把中间件的一个调用改成两个调用的方法，没找到 :(