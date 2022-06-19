<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class MakeModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {name} {--all} {--migration} {--vue} {--react} {--view} {--controller} {--model} {--api}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        if ($this->option("all")) {
            $this->input->setOption("migration", true);
            $this->input->setOption("vue", true);
            $this->input->setOption("react", true);
            $this->input->setOption("view", true);
            $this->input->setOption("controller", true);
            $this->input->setOption("model", true);
            $this->input->setOption("api", true);
        }

        if ($this->option("migration")) {
            $this->createMigration();
        }

        if ($this->option("vue")) {
            $this->createVueComponent();
        }

        if ($this->option("react")) {
            $this->createReactComponent();
        }

        if ($this->option("view")) {
            $this->createView();
        }

        if ($this->option("controller")) {
            $this->createController();
        }

        if ($this->option("model")) {
            $this->createModel();
        }

        if ($this->option("api")) {
            $this->createApiController();
        }
    }

    private function createMigration()
    {

    }

    private function createVueComponent()
    {

    }

    private function createReactComponent()
    {

    }

    private function createView()
    {

    }

    private function createController()
    {

    }

    private function createModel()
    {
        // Example name in command "Admin\User"
        $sModelNameAsNamespace = $this->argument("name"); // Admin\User

        // See https://laravel.com/docs/9.x/helpers#strings-method-list
        $sModelName = Str::singular(Str::studly(class_basename($sModelNameAsNamespace))); // User

        $this->call("make:model", [
            "name" => "App\\Modules\\". trim($sModelNameAsNamespace) . "\\Models\\" . $sModelName
        ]);
    }

    private function createApiController()
    {

    }
}
