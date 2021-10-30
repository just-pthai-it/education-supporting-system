<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;

class ServiceCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $name = 'make:service';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Create a new service';

    /**
     * The type of class being generated.
     * @var string
     */
    protected $type = 'Service';

    public function handle ()
    {
        shell_exec('php artisan make:service-contract ' .
                   ucwords(strtolower($this->argument('name'))));
        parent::handle();
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @param string $name
     *
     * @return string
     */
    protected function replaceClass ($stub, $name) : string
    {
        $model = ucwords(strtolower($this->argument('name')));

        if (!$this->argument('name'))
        {
            throw new InvalidArgumentException("Missing required argument model name");
        }

        $stub = parent::replaceClass($stub, $name);

        return str_replace('Dummy', $model, $stub);
    }

    protected function getNameInput () : string
    {
        return parent::getNameInput() . 'Service';
    }

    /**
     * Get the stub file for the generator.
     * @return string
     */
    protected function getStub () : string
    {
        return base_path('stubs/Service/Service.stub');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace ($rootNamespace) : string
    {
        return $rootNamespace . '/Services';
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments () : array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the model class.'],
        ];
    }
}
