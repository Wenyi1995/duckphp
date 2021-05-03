# DuckPhp\Ext\Misc
[toc]

## 简介
`组件类` 杂项扩展， 提供了一批函数。

## 选项
    public $options = [
        'path' => '',
        'path_lib' => 'lib',
        'misc_auto_method_extend' => true,

    ];
##  扩充方法。
### AppHelper 扩充

- Import
- DI

### ControllerHelper 扩充

- RecordsetUrl
- RecordsetH
- CallAPI

## 方法

    public function init(array $options, object $context = null)
初始化

    public function __construct()
空构造函数

    public function init(array $options, object $context = null)
初始化

    public static function Import($file)
导入文件

    public static function RecordsetUrl($data, $cols_map = [])
转换 Recordset 的 URL

    public static function RecordsetH($data, $cols = [])
转换 Recordset 的 HTML 编码

    public static function DI($name, $object = null)
DI 函数

    public function CallAPI($class, $method, $input, $interface = '')
调用一个 API

    public function _DI($name, $object = null)
相应静态函数的实现

    public function _Import($file)
相应静态函数的实现

    public function _RecordsetUrl($data, $cols_map = [])
相应静态函数的实现

    public function _RecordsetH($data, $cols = [])
相应静态函数的实现

    public function _CallAPI($class, $method, $input, $interface = '')
相应静态函数的实现

    protected function initOptions(array $options)
相应函数重写
    protected function initContext(object $context)
相应函数重写
## 详解

Import [DuckPhp\Ext\Misc::Import](Ext-Misc.md#Import)

    // 导入文件
DI

    DuckPhp\Ext\Misc::DI
RecordsetUrl
    
    DuckPhp\Ext\Misc::RecordsetUrl
RecordsetH
    
    DuckPhp\Ext\Misc::RecordsetH
CallAPI
    
    DuckPhp\Ext\Misc::CallAPI




