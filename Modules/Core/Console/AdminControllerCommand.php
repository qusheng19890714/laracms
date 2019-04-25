<?php

namespace Modules\Core\Console;

use Encore\Admin\Console\ResourceGenerator;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Nwidart\Modules\Support\Stub;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Nwidart\Modules\Commands\GeneratorCommand;
use Illuminate\Database\Eloquent\Model;

class AdminControllerCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of argument being used.
     *
     * @var string
     */
    protected $argumentName = 'controller';

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'module:make-admin-controller
                            {controller : The name of the admin controller class.}
                            {module : The name of module will be used.}
                            {--style=resource : The style of controller.}
                            {--model=}
                            {--force : Overwrite any existing files.}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new admin controller for the module';

    /**
     * @var ResourceGenerator
     */
    protected $generator;


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        if (!$this->modelExists()) {
            $this->error('Model does not exists !');

            return false;
        }

        $this->generator = new ResourceGenerator($this->getModelName());

        parent::handle();

    }


    /**
     * Determine if the model is exists.
     *
     * @return bool
     */
    protected function modelExists()
    {
        $model = $this->option('model');


        if (empty($model)) {
            return true;
        }

        return class_exists($model) && is_subclass_of($model, Model::class);
    }


    /**
     * 控制器实际路径
     *
     * @return string
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        return $path . 'Http/Controllers/Admin/' . $this->getControllerName() . '.php';
    }

    /**
     * 渲染模板
     *
     * @return string
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());
        $path   = $this->laravel['modules']->getModulePath('Core');

        $getModel = explode("\\", $this->option('model'));

        $modelName = last($getModel);

        $stub = new Stub($this->getStubName(), [
            'MODULENAME'       => $module->getStudlyName(),
            'CONTROLLERNAME'   => $this->getControllerName(),
            'LOWER_CONTROLLER' => $this->getLowerControllerShortName(),
            'NAMESPACE'        => $module->getStudlyName(),
            'CLASS_NAMESPACE'  => $this->getClassNamespace($module),
            'CLASS'            => $this->getControllerName(),
            'LOWER_NAME'       => $module->getLowerName(),
            'MODULE'           => $this->getModuleName(),
            'NAME'             => $this->getModuleName(),
            'STUDLY_NAME'      => $module->getStudlyName(),
            'MODULE_NAMESPACE' => $this->laravel['modules']->config('namespace'),
            'MODEL'            => $modelName,
            'LOWER_MODEL'      => $this->getLowerModelName(),
            'PLURAL_MODEL'     => $this->getPluralModelName(),
            "SHOW"             => $this->indentCodes($this->generator->generateShow()),
            "GRID"             => $this->indentCodes($this->generator->generateGrid()),
            "FORM"             => $this->indentCodes($this->generator->generateForm()),

        ]);

        $stub->setBasePath($path.'Console/stubs');

        return $stub->render();
    }

    /**
     * @param string $code
     *
     * @return string
     */
    protected function indentCodes($code)
    {
        $indent = str_repeat(' ', 8);

        return rtrim($indent.preg_replace("/\r\n/", "\r\n{$indent}", $code));
    }

    //获取ControllerName
    protected function getControllerName()
    {
        $controller = studly_case($this->argument('controller'));

        if (str_contains(strtolower($controller), 'controller') === false) {
            $controller .= 'Controller';
        }

        return $controller;
    }

    /**
     * 获取Controller短名称，不含Conroller
     *
     * @return array|string
     */
    protected function getControllerShortName()
    {
        $controller = $this->getControllerName();
        $controller = substr($controller, 0, -10);

        return $controller;
    }

    /**
     * 获取Controller名称，不含Conroller
     *
     * @return array|string
     */
    protected function getLowerControllerShortName()
    {
        return strtolower($this->getControllerShortName());
    }

    /**
     * 获取默认命名空间
     *
     * @return string
     */
    public function getDefaultNamespace() : string
    {
        return 'Http\Controllers\Admin';
    }

    /**
     * 获取默认使用的数据模型
     *
     * @return string
     */
    public function getModelName()
    {
        $model = $this->option('model');

        if ($model) {
            return studly_case($model);
        }

        return $this->getControllerShortName();
    }

    /**
     * 获取默认使用的数据模型小写名称
     *
     * @return string
     */
    public function getLowerModelName()
    {
        return strtolower($this->getModelName());
    }

    /**
     * 获取复数的模型名称
     *
     * @return string
     */
    public function getPluralModelName()
    {
        return str_plural($this->getLowerModelName());
    }

    /**
     *
     * 获取对应风格的控制器stub文件
     *
     * @return string
     */
    private function getStubName()
    {
        $style = strtolower($this->option('style'));

        return "/admin-controller-{$style}.stub";
    }
}
