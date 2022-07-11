<?php

namespace Jhavenz\NovaExtendedFields\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\File;
use Jhavenz\NovaExtendedFields\NovaExtendedFields;
use Laravel\Nova\Fields\Text;
use RuntimeException;

class ExtendedFieldMakeCommand extends GeneratorCommand
{
    protected $name = 'Nova extended field';
    protected $description = 'Generate a new Nova extended field';
    protected $signature = 'nova:extended-field
                                                {class : Class name of the field}
                                                {category : The directory the field will be placed in. (Relative to your root Nova directory)}
                                                {parent-field? : The Nova field you would like to extend}
                                                {--nova-path= : Absolute Path to the [Nova] directory in your project}
                                                {--force-overwrite : Overwrite this file if it exists}
    ';
    private string $errorMessage;
    private string $targetDir;

    public function handle(): int
    {
        $stub = $this->extendParentField(
            $this->buildClass($class = $this->argument('class'))
        );

        if (!empty($this->errorMessage)) {
            $this->output->error($this->errorMessage);

            return self::FAILURE;
        }

        $filename = str($class)->studly()->append('.php');
        $filePath = $filename->prepend($this->targetDir.DIRECTORY_SEPARATOR);

        if (File::exists($filePath) && !$this->option('force-overwrite')) {
            $this->output->error("Unauthorized attempt to overwrite an existing Resource, bailing out...");

            return self::FAILURE;
        }

        try {
            if (!File::put($filePath, $stub)) {
                throw new RuntimeException("Could not write to $filePath");
            }

            $this->output->success($filename->basename('.php')->append(' field written successfully'));

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->output->error('Error while writing file: '.$e->getMessage());

            return self::FAILURE;
        }
    }

    protected function getStub(): string
    {
        return __DIR__.'/stubs/extended-field.php.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'Nova';
    }

    protected function replaceCategory(string $stub): string
    {
        return str_replace(
            '{{ category }}',
            $this->category(),
            $stub
        );
    }

    protected function category(): string
    {
        return str($this->argument('category'))->studly()->toString();
    }

    protected function replaceNamespace(&$stub, $name): static
    {
        $dirSlash = DIRECTORY_SEPARATOR;
        $this->targetDir = tap(
            $this->option('nova-path') ?: app_path('Nova'),
            fn($path) => !is_dir($path) &&
                $this->errorMessage = 'Invalid Nova directory. Use the [--nova-path=] option to specify this'
        );

        if (!empty($this->errorMessage)) {
            return $this;
        }

        $ns = $this->getDefaultNamespace($this->laravel->getNamespace());

        if (is_dir($resourcesDir = $this->targetDir.$dirSlash.'Resources')) {
            $ns = $ns.'\\Resources';
            $this->targetDir = $resourcesDir;
        }

        if (!is_dir($categoryDir = $this->targetDir.$dirSlash.$this->category())) {
            File::makeDirectory($categoryDir, 0775, force: true);
        }

        $this->targetDir = $this->targetDir.$dirSlash.$this->category();

        $stub = str_replace(
            '{{ rootNamespace }}',
            $ns.'\\'.$this->category(),
            $stub
        );

        return $this;
    }

    private function extendParentField(string $stub): string
    {
        $parentNamespace = NovaExtendedFields::getNamespacedNovaField($this->argument('parent-field') ?: Text::class);

        $stub = str_replace(
            '{{ parentNamespace }}',
            $parentNamespace,
            $stub
        );

        return str_replace(
            '{{ parentBasename }}',
            class_basename($parentNamespace),
            $stub
        );
    }
}
