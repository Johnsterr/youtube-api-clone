<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MakeModule extends Command
{
    private Filesystem $obFiles;

    public function __construct(Filesystem $obFilesystem)
    {
        parent::__construct();

        $this->obFiles = $obFilesystem;
    }

    protected $signature = 'make:module {name} {--all} {--migration} {--vue} {--react} {--view} {--controller} {--model} {--api}';

    protected $description = 'Command description';

    /**
     * @throws FileNotFoundException
     */
    public function handle()
    {
        if ($this->option('all')) {
            $this->input->setOption('migration', true);
            $this->input->setOption('vue', true);
            $this->input->setOption('react', true);
            $this->input->setOption('view', true);
            $this->input->setOption('controller', true);
            $this->input->setOption('model', true);
            $this->input->setOption('api', true);
        }

        if ($this->option('migration')) {
            $this->createMigration();
        }

        if ($this->option('vue')) {
            $this->createVueComponent();
        }

        if ($this->option('react')) {
            $this->createReactComponent();
        }

        if ($this->option('view')) {
            $this->createView();
        }

        if ($this->option('controller')) {
            $this->createController();
        }

        if ($this->option('model')) {
            $this->createModel();
        }

        if ($this->option('api')) {
            $this->createApiController();
        }
    }

    private function createMigration()
    {
        // See https://laravel.com/docs/9.x/helpers#strings-method-list
        $sTableNameAsNamespace = $this->argument('name');
        $sTableName = Str::plural(Str::snake(class_basename($sTableNameAsNamespace)));

        try {
            $this->call('make:migration', [
                'name' => "create_{$sTableName}_table",
                '--create' => $sTableName,
                '--path' => 'App\\Modules\\'.trim($sTableNameAsNamespace).'\\Migrations',
            ]);
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * @throws FileNotFoundException
     */
    private function createVueComponent()
    {
        $sVueComponentNameAsNamespace = $this->argument('name');
        $sVueComponentPath = $this->getVueComponentPath($sVueComponentNameAsNamespace);

        $sVueComponentName = Str::studly(class_basename($sVueComponentNameAsNamespace));

        if ($this->alreadyExists($sVueComponentPath)) {
            $this->error('Vue Component already exists!');
        } else {
            $this->makeDirectory($sVueComponentPath);

            $fileStub = $this->obFiles->get(base_path('resources/stubs/vue.component.stub'));

            $fileStub = str_replace(
                [
                    'DummyClass',
                ],
                [
                    $sVueComponentName,
                ],
                $fileStub
            );

            $this->obFiles->put($sVueComponentPath, $fileStub);
            $this->info('Vue Component created successfully.');
        }
    }

    /**
     * @throws FileNotFoundException
     */
    private function createReactComponent()
    {
        $sReactComponentNameAsNamespace = $this->argument('name');
        $sReactComponentPath = $this->getReactComponentPath($sReactComponentNameAsNamespace);

        $sReactComponentName = Str::studly(class_basename($sReactComponentNameAsNamespace));

        if ($this->alreadyExists($sReactComponentPath)) {
            $this->error('React Component already exists!');
        } else {
            $this->makeDirectory($sReactComponentPath);

            $fileStub = $this->obFiles->get(base_path('resources/stubs/react.component.stub'));

            $fileStub = str_replace(
                [
                    'DummyClass',
                ],
                [
                    $sReactComponentName,
                ],
                $fileStub
            );

            $this->obFiles->put($sReactComponentPath, $fileStub);
            $this->info('React Component created successfully.');
        }
    }

    /**
     * @throws FileNotFoundException
     */
    private function createView()
    {
        $sViewNameAsNamespace = $this->argument('name');
        $arPaths = $this->getViewPath($sViewNameAsNamespace);

        foreach ($arPaths as $obPath) {
            $sViewName = Str::studly(class_basename($sViewNameAsNamespace));

            if ($this->alreadyExists($obPath)) {
                $this->error('View already exists!');
            } else {
                $this->makeDirectory($obPath);

                $fileStub = $this->obFiles->get(base_path('resources/stubs/view.stub'));

                $fileStub = str_replace(
                    [
                        '',
                    ],
                    [
                    ],
                    $fileStub
                );

                $this->obFiles->put($obPath, $fileStub);
                $this->info('View created successfully.');
            }
        }
    }

    /**
     * @throws FileNotFoundException
     */
    private function createController()
    {
        // See https://laravel.com/docs/9.x/helpers#strings-method-list
        $sControllerNameAsNamespace = $this->argument('name');
        $sControllerName = Str::studly(class_basename($sControllerNameAsNamespace));

        $sModelNameAsNamespace = $this->argument('name');
        $sModelName = Str::singular(Str::studly(class_basename($sModelNameAsNamespace)));

        $sControllerPath = $this->getControllerPath($sControllerNameAsNamespace);

        if ($this->alreadyExists($sControllerPath)) {
            $this->error('Controller already exists!');
        } else {
            $this->makeDirectory($sControllerPath);

            $fileStub = $this->obFiles->get(base_path('resources/stubs/controller.model.api.stub'));

            $fileStub = str_replace(
                [
                    'DummyNamespace',
                    'DummyRootNamespace',
                    'DummyClass',
                    'DummyFullModelClass',
                    'DummyModelClass',
                    'DummyModelVariable',
                ],
                [
                    'App\\Modules\\'.trim($this->argument('name')).'\\Controllers',
                    $this->laravel->getNamespace(),
                    $sControllerName.'Controller',
                    'App\\Modules\\'.trim($this->argument('name'))."\\Models\\$sModelName",
                    $sModelName,
                    lcfirst(($sModelName)),
                ],
                $fileStub
            );

            $this->obFiles->put($sControllerPath, $fileStub);
            $this->info('Controller created successfully.');
            //$this->updateModularConfig();
        }

        $this->createRoutes($sControllerName, $sModelName);
    }

    private function createModel()
    {
        // Example name in command "Admin\User"
        $sModelNameAsNamespace = $this->argument('name'); // Admin\User

        // See https://laravel.com/docs/9.x/helpers#strings-method-list
        $sModelName = Str::singular(Str::studly(class_basename($sModelNameAsNamespace))); // User

        $this->call('make:model', [
            'name' => 'App\\Modules\\'.trim($sModelNameAsNamespace).'\\Models\\'.$sModelName,
        ]);
    }

    /**
     * @throws FileNotFoundException
     */
    private function createApiController()
    {
        $sControllerNameAsNamespace = $this->argument('name');
        $sControllerName = Str::studly(class_basename($sControllerNameAsNamespace));

        $sModelNameAsNamespace = $this->argument('name');
        $sModelName = Str::singular(Str::studly(class_basename($sModelNameAsNamespace)));

        $sControllerPath = $this->getApiControllerPath($sControllerNameAsNamespace);

        if ($this->alreadyExists($sControllerPath)) {
            $this->error('Controller already exists!');
        } else {
            $this->makeDirectory($sControllerPath);

            $fileStub = $this->obFiles->get(base_path('resources/stubs/controller.model.api.stub'));

            $fileStub = str_replace(
                [
                    'DummyNamespace',
                    'DummyRootNamespace',
                    'DummyClass',
                    'DummyFullModelClass',
                    'DummyModelClass',
                    'DummyModelVariable',
                ],
                [
                    'App\\Modules\\'.trim($this->argument('name')).'\\Controllers\\Api',
                    $this->laravel->getNamespace(),
                    $sControllerName.'Controller',
                    'App\\Modules\\'.trim($this->argument('name'))."\\Models\\$sModelName",
                    $sModelName,
                    lcfirst(($sModelName)),
                ],
                $fileStub
            );

            $this->obFiles->put($sControllerPath, $fileStub);
            $this->info('Controller created successfully.');
            //$this->updateModularConfig();
        }

        $this->createApiRoutes($sControllerName, $sModelName);
    }

    /**
     * Возвращает абсолютный путь до файла создаваемого контроллера
     *
     * @param  bool|array|string|null  $sControllerNameAsNamespace
     * @return string
     */
    private function getControllerPath(bool|array|string|null $sControllerNameAsNamespace): string
    {
        $sControllerClassBaseName = Str::studly(class_basename($sControllerNameAsNamespace));

        return $this->laravel['path'].'/Modules/'.str_replace(
            '\\',
            '/',
            $sControllerNameAsNamespace
        ).'/Controllers/'."{$sControllerClassBaseName}Controller.php";
    }

    /**
     * Возвращает абсолютный путь до файла создаваемого Api контроллера
     *
     * @param  bool|array|string|null  $sControllerNameAsNamespace
     * @return string
     */
    private function getApiControllerPath(bool|array|string|null $sControllerNameAsNamespace): string
    {
        $sControllerClassBaseName = Str::studly(class_basename($sControllerNameAsNamespace));

        return $this->laravel['path'].'/Modules/'.str_replace(
            '\\',
            '/',
            $sControllerNameAsNamespace
        ).'/Controllers/Api/'."{$sControllerClassBaseName}Controller.php";
    }

    /**
     * Проверяет существует ли файл по переданному пути.
     *
     * @param  string  $sPath <p>Абсолютный путь до файла.</p>
     * @return bool
     */
    protected function alreadyExists(string $sPath): bool
    {
        return $this->obFiles->exists($sPath);
    }

    /**
     * Функция создает директорию, в которой будет храниться файл по переданному пути.
     *
     * @param  string  $sPath <p>Абсолютный путь до файла.</p>
     * @return void
     */
    private function makeDirectory(string $sPath): void
    {
        if (! $this->obFiles->isDirectory(dirname($sPath))) {
            $this->obFiles->makeDirectory(dirname($sPath), 0755, true, true);
        }
    }

    /**
     * @throws FileNotFoundException
     */
    private function createRoutes(string $sControllerName, string $sModelName)
    {
        $sRoutePath = $this->getRoutesPath($this->argument('name'));

        if ($this->alreadyExists($sRoutePath)) {
            $this->error('Routes already exists!');
        } else {
            $this->makeDirectory($sRoutePath);

            $fileStub = $this->obFiles->get(base_path('resources/stubs/routes.web.stub'));

            $fileStub = str_replace(
                [
                    'DummyClass',
                    'DummyRoutePrefix',
                    'DummyModelVariable',
                ],
                [
                    $sControllerName.'Controller',
                    Str::plural(Str::snake(lcfirst($sModelName), '-')),
                    lcfirst($sModelName),
                ],
                $fileStub
            );

            $this->obFiles->put($sRoutePath, $fileStub);
            $this->info('Routes created successfully.');
        }
    }

    private function getRoutesPath(bool|array|string|null $sModuleName): string
    {
        return $this->laravel['path'].'/Modules/'.str_replace('\\', '/', $sModuleName).'/Routes/web.php';
    }

    /**
     * @throws FileNotFoundException
     */
    private function createApiRoutes(string $sControllerName, string $sModelName)
    {
        $sRoutePath = $this->getApiRoutesPath($this->argument('name'));

        if ($this->alreadyExists($sRoutePath)) {
            $this->error('Routes already exists!');
        } else {
            $this->makeDirectory($sRoutePath);

            $fileStub = $this->obFiles->get(base_path('resources/stubs/routes.api.stub'));

            $fileStub = str_replace(
                [
                    'DummyClass',
                    'DummyRoutePrefix',
                    'DummyModelVariable',
                ],
                [
                    'Api\\'.$sControllerName.'Controller',
                    Str::plural(Str::snake(lcfirst($sModelName), '-')),
                    lcfirst($sModelName),
                ],
                $fileStub
            );

            $this->obFiles->put($sRoutePath, $fileStub);
            $this->info('Routes created successfully.');
        }
    }

    private function getApiRoutesPath(bool|array|string|null $sModuleName): string
    {
        return $this->laravel['path'].'/Modules/'.str_replace('\\', '/', $sModuleName).'/Routes/api.php';
    }

    private function getVueComponentPath(bool|array|string|null $sModuleName): string
    {
        return base_path('resources/js/components/'.str_replace('\\', '/', $sModuleName).'.vue');
    }

    private function getReactComponentPath(bool|array|string|null $sModuleName): string
    {
        return base_path('resources/js/components/'.str_replace('\\', '/', $sModuleName).'.jsx');
    }

    private function getViewPath(bool|array|string|null $sViewNameAsNamespace): Collection
    {
        $arFiles = collect([
            'create',
            'edit',
            'index',
            'show',
        ]);

        //str_replace("\\", "/", $sViewNameAsNamespace)
        $arPaths = $arFiles->map(function ($path) use ($sViewNameAsNamespace) {
            return base_path(
                'resources/views/'.str_replace('\\', '/', $sViewNameAsNamespace).'/'.$path.'.blade.php'
            );
        });

        return $arPaths;
    }
}
