<?php declare(strict_types=1);
/**
 * DuckPHP
 * From this time, you never be alone~
 */
namespace DuckPhp\Ext;

use DuckPhp\Core\SingletonEx;

class PluginForSwooleHttpd // impelement SwooleExtAppInterface
{
    use SingletonEx;
    
    public $options = [
        'swoole_ext_class' => 'SwooleHttpd\\SwooleExt',
    ];
    protected $context_class;
    public function init($options, $context)
    {
        $this->context_class = get_class($context);
        $SwooleExt = $this->options['swoole_ext_class'];
        $SwooleExt::G()->init($options, $this);
    }
    public function run()
    {
        $this->context_class::G()->run();
    }
    // @interface SwooleExtAppInterface
    public function onSwooleHttpdInit($SwooleHttpd, $InCoroutine = false, ?callable $RunHandler)
    {
        $app = $this->context_class::G();
        $app->options['use_super_global'] = true;
        if ($InCoroutine) {
            $app::SG($SwooleHttpd::SG());
            return;
        }
        
        $SwooleHttpd->set_http_exception_handler([$this->appClass,'OnException']);  // TODO
        $SwooleHttpd->set_http_404_handler([$this->appClass, 'On404']);             // 接管 404 处理。
        
        $flag = $SwooleHttpd->is_with_http_handler_root();                         // 如果还有子文件，做404后处理
        $app->options['skip_404_handler'] = $app->options['skip_404_handler'] ?? false;
        $app->options['skip_404_handler'] = $app->options['skip_404_handler'] || $flag;
        
        $app::system_wrapper_replace($SwooleHttpd::system_wrapper_get_providers());
        
        $app->setBeforeRunHandler($RunHandler);
    }
    
    // @interface SwooleExtAppInterface
    public function getStaticComponentClasses()
    {
        return $this->context_class::G()->getStaticComponentClasses();
    }
    // @interface SwooleExtAppInterface
    public function getDynamicComponentClasses()
    {
        return $this->context_class::G()->getDynamicComponentClasses();
    }
}
