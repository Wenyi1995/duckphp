# DNMVCS
## DNMVCS 是什么
一个 PHP Web 简单框架 比通常的Model Controller View 多了 Service 。

拟补了 常见 Web 框架少的缺层。

这个缺层导致了很糟糕的境地。你会发现很多人在 Contorller 里写一堆代码，或者在 Model 里写一堆代码。

使得网站开发者专注于业务逻辑

## DNMVCS 做了什么
* 简单可扩展灵活的路由方式
* 简单的数据库类
* 扩展接管默认错误处理
* 简单的加载类
* 简单的配置类
所有这些仅仅是在主类里耦合。

## DNMVCS 不做什么
* ORM ，和各种屏蔽 sql 的行为，根据日志查 sql 方便多了。
* 模板引擎，PHP本身就是模板引擎
* Widget ， 和 MVC 分离违背
* 系统行为 ，接管替代默认的POST，GET。

## DNMVCS 使用理念
* model 按数据库表走
* view 按页面走
* controller 按url入口走
* service 按业务走

----
* controller 调用 view 和service。
* service 调用 model 和其他第三方代码。
* model 只实现和当前表相关的操作。
* controller ,service ,model 都可能抛出异常。

如果 service 相互调用怎么办?

添加后缀为 LibService 用于 service 共享调用，不对外，如MyLibService

如果跨表怎么办?

两种解决方案
1. 在主表里附加
2. 添加后缀为 XModel 用于表示这个 Model 是多个表的，如 MyXModel。

库 service  和 联表 model 并没有单独目录。

## DNMVCS 的简化调用流程
简化的 DNMVC 层级关系图
```
		   /-> View
Controller --> Service ---------------------------------------------------> Model   
					  \                \             /
					   \-> LibService --> ExModel --/
```
Controller 目录是 处理url 路由的，调用 Service

一般来说 一个 Controller 的方法调用一个 Service 方法

例外的情况是 展示的内容的时候，可能要灵活拆分

Service 用来作为单元测试，业务核心

Service 之间不能相互调用， 为此，LibService 就是供各个 Service 调用的

如 Serice1 和 Service2 的差别很小，但应用不同怎么办， 就构造一个 lib service 供这两个 service 调用
## DNMVCS 还要做什么
* 符合 psr 标准的 log 类，尽管很多项目会自己写，带一个简单的无妨.
* 调试类，同上面原因。
* composer 安装模式，本人还没学会
* 范例，例子还太简单了
* 脚手架，决定写在 DNMVCSEx 里够了。
* Namespace , 使用命名空间，这

## DNMVCS 的目录结构约定
sample 目录就是一般典型目录
```
+---DNMVCS  系统目录，这里面的内容不要修改
+---sample  站点名称
|   +---config 配置目录
|   |   +---config.php 配置文件
|   |   \---setting.sample.php 设置文件
|   +---controller 控制器目录
|   +---lib 用到的库目录
|   +---model Model 目录
|   +---service Service 目录
|   +---view
|   |   \---_sys 系统模版目录
|   |       +---error_404.php
|   |       +---error_500.php
|   |       +---error_debug.php
|   |       +---error_exception.php
|   \---www  Web 入口
|           +---index.php
```
config 目录 
config.php 是各种配置，无敏感信息

setting.sample.php 改名为 setting.php  是不存放在版本管理系统里的

setting.sample.php 演示了 数据库的配置，和设置是否在调试状态

www 目录

index.php 就两句话
```
//DNView::G(new MyView()); //替换默认组件,其他类也这么处理
DNMVCS::G()->init($path); //初始化
DNMVCS::G()->run(); //运行
```
## DNMVCS 使用方法
1. 把 sample/www 配置为你的站点目录。
2. 将 sample/config/setting.sample.php 改为 sample/config/setting.php
3. 用浏览器打开网站，出现 Hello DNMVCS 就成功了。
4. 接下来细心看 sample 目录的代码，怎么用就基本了解了。

* 为什么要步骤2 ？ 设置文件放在版本管理里不安全。 如果没有做步骤二会怎么养？，会有一个报错提示
* 建议做新站点的时候不要更改 sample 目录的文件，而是把 sample 目录内容复制新的目录。
* 我不想做全站，只是做子目录， 这也可以。把 www/index.php 的文件引用调整好就行。


## DNMVCS 的各个类说明
### DnSingleton 单例基类
各个类基本都要继承的类。写Model,Service 的时候可以方便的扩展。
```
class DNSingleton
        public static function G($url=null)

        如果没有这个 G 方法 你可能会怎么写代码：
        (new MyClass())->foo();
        继承了 DNSingleton 后，这么写
        MyClass::G()->foo();

        另一个隐藏功能：
        MyBaseClass::G(new MyClass())->foo();
        MyClass 把 MyBaseClass 的 foo 方法替换了。

        接下来后面这样的代码，其实也是 MyClass 的 foo2.
        MyBaseClass::G()->foo2();

        为什么不是 GetInstance ? 因为太长，这个方法太经常用。
```
### DnAutoLoad 自动加载类
自动加载函数的类
```
class DNAutoLoad extends DNSingleton
        public function init($path,$path_common='')
        初始化
        设定 mvc 的目录， 和共享目录
        共享目录主要用于多网站配合
        目录中有
        model
        后缀 CommonModel
        service
        后缀 CommonService
        为什么名字这么长
        因为经常用到这么长名字说明你错了
        你应该在子网站的类里扩展这些共享类。
        public function run()
        执行。
```
### DnRoute 路由类
核心的路由类。

```
class DNRoute extends DNSingleton

        public static function URL($url=null)
        路由了，要找到相对的 URL 用这个静态函数
        顺便，这也写成全局函数，方便在 view 里调用。
        尽管我不太支持在 view 里写代码，但这里是为了方便起见
        
        public function _url($url=null)
        静态函数 URL 的实现函数。

        public function init($path)
        初始化，设定目录

        public function set404($callback)
        设置 404 的回调函数

        public function run()
        运行
        这才开始

        public function defaltRouteHandle()
        默认的路由方法，公开是为了回调支持下面几种

/index 
/Test => Test::index  Main::Test
/Test/index  Test/index::index
/Test/Method1  	Test::Method1 Test\Method1::index
/Test/Class2/index
DNRoute 选择支持了多级路由，而不是二级路由，那么
AA/view/123456?foo 这样的就不容易处理了

默认的路由实现中，也实现了
POST ，添加定位到 do_*上。

        public function addDefaultRoute($callback)
        添加其他路由方式

        public function defaltDispathHandle()
        默认的分发型路由，类似 nodejs 那种
        
        public function addDispathRoute($key,$callback)
        添加 分发路由形式的路由
```
### DNView 视图类
```
class DNView extends DNSingleton
View 类
        public static function Show($view,$data=array(),$use_wrapper=true)
        显示数据，第一个为不带 .php 结尾的 view 文件，第二个为传递过去的数据，第三个参数是是否使用页眉页脚

        public static function return_json($ret)
        返回 json 数据，自带 exit

        public static function return_redirect($url)
        跳转结束，自带 exit

        public static function return_route_to($url)
        跳转到 DnRoute::URL 自带 exit;——这是唯一破坏耦合性的函数

        public function _Show($view,$data=array(),$use_wrapper=true)
        Show 静态方法的实现，你也可以替换他

        public function init($path)
        初始化， view 的路径

        public function setBeforeShow($callback)
        设置在显示前的回调，在 DNMVCS 类中，设置成开始输出前关闭 mysql

        public function showBlock($view,$data)
        显示一小块 view，用于调试

        public function _assign($key,$value)
        设置 key-value 模式的数据，不推荐使用，你应该传入整个数组

	public function setWrapper($head_file,$foot_file)
        设置页眉页脚

```
### DNConfig 配置类
```
class DNConfig extends DNSingleton
配置类
        public static function Setting($key)
        读取 设置, 不用 set 是避免和 get 对称

        public static function Get($key,$file_basename='config')
        获取配置

        public static function Load($file_basename)
        加载配置文件

        public function init($path,$path_common=null)
        初始化

        public function _Setting($key)
        setting 的实现函数

        public function _Get($key,$file_basename='config')
        get 的实现函数

        public function _Load($file_basename='config')
        load  的实现函数

```
### DNException 异常处理类
```
异常处理。
class DNException extends Exception
        public static function ThrowOn($flag,$message,$code=0)
        如果 $flag为真，则抛出异常。 用于减少 if 语句
        如 MyException::ThrowOn(true,"test",-110);

        public static function SetDefaultAllExceptionHandel($callback)
        公用，用于设置默认的异常

        public static function HandelAllException()
        接管异常

        public static function ManageException($ex)
        给扩展类默认的异常方法

        public static function SetErrorHandel($error_handel)
        设置错误 

        public static function OnException($ex)
        默认异常，扩展类里重载这个静态方法以实现自己的异常处理方式
```
### DNDB 数据库类
```

class DNDB extends DNSingleton
数据库类，只有开始查询才会连接
主从服务器，不在这里处理， 推荐用 MyCat 处理主从服务器
        public function init($config)
        初始化数据库
        如果 config 有 dsn ，那么用 dsn ，否则按配置来
        public function check_connect()
        检查是否连接
        public function getPDO()
        获得 pdo
        public function setPDO($pdo)
        设置 pdo 当你另外有自己 pdo 的时候
        public function close()
        关闭数据库，在输出 view 前关闭
        public function quote($string)
        编码
        public function quote_array($array)
        对一系列数组编码，注意 key 是没转码的
        public function fetchAll($sql)
        读取全部数据
        public function fetch($sql)
        读取一行数据
        public function fetchColumn($sql)
        读取一个数据
        public function exec($sql)
        执行sql
        public function rowCount()
        上一结果的行数
        
        public function lastInsertId()
        public function get($table_name,$id,$key='id')
        public function insert($table_name,$data,$return_last_id=true)
        public function delete($table,$id,$key='id')
        public function update($table_name,$id,$data,$key='id')
```
### DNMVCS 入口类
```
class DNMVCS extends DNSingleton
把所有函数粘合的主类
        public function onShow404()
        public function onException($ex)
        public function onOtherException($ex)
        public function onDebugError($errno, $errstr, $errfile)
        public function onBeforeShow()
        public function onErrorHandler($errno, $errstr, $errfile, $errline)
        public function init($path='',$path_common='')
        public function run()
        public function isDev()
```
### DNMVSEx 扩展类
额外对 DNMVCS 的扩展类
```
class DNMVCSEx extends DNMVCS
额外功能类，目前实现了 API 接口的模式
        public static function Service($name)
        public static function Model($name)
        public function _load($name,$type)
        public static function CallAPI($service,$method,$input)
class DNController
class DNService extends DNSingleton
class DNModel extends DNSingleton
```





## 还有什么要说的

使用它，鼓励我，让我有写下去的动力