<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ModularProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $arModules = config("modular.modules");

        $sPath = config("modular.path");

        if ($arModules) {
            Route::group([
                "prefix" => "",
            ], function () use ($arModules, $sPath) {
                foreach ($arModules as $sModuleName => $arSubModules) {
                    foreach ($arSubModules as $sSubmoduleName => $arSubmoduleValue) {
                        $sRelativePath = "/$sModuleName/$arSubmoduleValue";

                        Route::middleware("web")
                            ->group(function () use ($sModuleName, $arSubmoduleValue, $sRelativePath, $sPath) {
                                $this->getWebRoutes($sModuleName, $arSubmoduleValue, $sRelativePath, $sPath);
                            });

                        Route::prefix("api")
                            ->middleware("api")
                            ->group(function () use ($sModuleName, $arSubmoduleValue, $sRelativePath, $sPath) {
                                $this->getApiRoutes($sModuleName, $arSubmoduleValue, $sRelativePath, $sPath);
                            });
                    }
                }
            });
        }

        $this->app["view"]->addNamespace("Public", base_path() . "/resources/views/Public");
    }

    private function getWebRoutes(int|string $sModuleName, mixed $arSubmoduleValue, string $sRelativePath, mixed $sPath)
    {
        $sRoutesPath = $sPath . $sRelativePath . "/Routes/web.php";

        if (file_exists($sRoutesPath)) {
            if ($sModuleName != config("modular.groupWithoutPrefix")) {
                Route::group([
                    "prefix" => strtolower($sModuleName),
                    "middleware" => $this->getMiddleware($sModuleName),
                ],
                    function () use ($sModuleName, $arSubmoduleValue, $sRoutesPath) {
                        Route::namespace("App\Modules\\$sModuleName\\$arSubmoduleValue\Controllers")->group(
                            $sRoutesPath
                        );
                    }
                );
            } else {
                Route::namespace("App\Modules\\$sModuleName\\$arSubmoduleValue\Controllers")
                    ->middleware($this->getMiddleware($sModuleName))
                    ->group($sRoutesPath);
            }
        }
    }

    private function getApiRoutes(int|string $sModuleName, mixed $arSubmoduleValue, string $sRelativePath, mixed $sPath)
    {
        $sRoutesPath = $sPath . $sRelativePath . "/Routes/api.php";

        if (file_exists($sRoutesPath)) {
            Route::group([
                "prefix" => strtolower($sModuleName),
                "middleware" => $this->getMiddleware($sModuleName, "api"),
            ],
                function () use ($sModuleName, $arSubmoduleValue, $sRoutesPath) {
                    Route::namespace("App\Modules\\$sModuleName\\$arSubmoduleValue\Controllers")->group($sRoutesPath);
                }
            );
        }
    }

    private function getMiddleware(int|string $sModuleName, $sType = "web"): array
    {
        $arMiddleware = [];

        $arConfig = config("modular.groupMiddleware");

        if (isset($arConfig[$sModuleName])) {
            if (array_key_exists($sType, $arConfig[$sModuleName])) {
                $arMiddleware = array_merge($arMiddleware, $arConfig[$sModuleName][$sType]);
            }
        }

        return $arMiddleware;
    }
}
