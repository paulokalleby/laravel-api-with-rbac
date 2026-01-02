<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeApiResource extends Command
{
    protected $signature = 'api:resource
        {name : Nome do recurso (ex: User, Product)}
        {--with= : Nome do relacionamento many-to-many (ex: roles)}';

    protected $description = 'Cria Model, Service, Controller, Request, Resource, Migration, Factory e Test seguindo o padrão da API';

    public function handle(): int
    {
        $name     = Str::studly($this->argument('name'));
        $relation = $this->option('with');

        $table = Str::snake(Str::pluralStudly($name));

        $replacements = [
            'model'         => $name,
            'modelVariable' => Str::camel($name),
            'modelPlural'   => Str::pluralStudly($name),
            'table'         => $table,

            // relacionamento (quando existir)
            'relation'            => $relation,
            'relationStudly'      => $relation ? Str::studly(Str::singular($relation)) : null,
            'relationModel'       => $relation ? Str::studly(Str::singular($relation)) : null,
            'relationLabel'       => $relation ? Str::singular($relation) : null,
            'relationLabelPlural' => $relation ? Str::plural($relation) : null,
            'pivotTable'          => $relation
                ? Str::snake(Str::singular($relation)) . '_' . Str::snake($name)
                : null,
        ];

        $modelStub    = $relation ? 'model.with-relation.stub' : 'model.stub';
        $serviceStub  = $relation ? 'service.with-relation.stub' : 'service.stub';
        $requestStub  = $relation ? 'request.with-relation.stub' : 'request.stub';
        $resourceStub = $relation ? 'resource.with-relation.stub' : 'resource.stub';

        $this->generate(
            $modelStub,
            app_path("Models/{$name}.php"),
            $replacements
        );

        $this->generate(
            $serviceStub,
            app_path("Services/{$name}Service.php"),
            $replacements
        );

        $this->generate(
            'controller.stub',
            app_path("Http/Controllers/Api/{$name}Controller.php"),
            $replacements
        );

        $this->generate(
            $requestStub,
            app_path("Http/Requests/{$name}Request.php"),
            $replacements
        );

        $this->generate(
            $resourceStub,
            app_path("Http/Resources/{$name}Resource.php"),
            $replacements
        );

        // migration padrão (sem pivot)
        $timestamp = now()->format('Y_m_d_His');
        $migration = "{$timestamp}_create_{$table}_table.php";

        $this->generate(
            'migration.stub',
            database_path("migrations/{$migration}"),
            $replacements
        );

        $this->generate(
            'factory.stub',
            database_path("factories/{$name}Factory.php"),
            $replacements
        );

        $this->generate(
            'test.stub',
            base_path("tests/Feature/Api/{$name}Test.php"),
            $replacements
        );

        $this->info('API Resource criada com sucesso.');

        return self::SUCCESS;
    }

    protected function generate(string $stub, string $path, array $replacements): void
    {
        if (file_exists($path)) {
            $this->warn("Ignorado (já existe): {$path}");

            return;
        }

        $stubPath = resource_path("stubs/api/{$stub}");

        if (! file_exists($stubPath)) {
            $this->error("Stub não encontrado: {$stub}");

            return;
        }

        $content = file_get_contents($stubPath);

        foreach ($replacements as $key => $value) {
            $content = str_replace("{{ {$key} }}", (string) $value, $content);
        }

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, $content);

        $this->line("Criado: {$path}");
    }
}
