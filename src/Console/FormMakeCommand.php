<?php
namespace Esterisk\Form\Console;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\DB;

class FormMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:form';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Esterisk Form class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Form';

    /**
     * The name of class being generated.
     *
     * @var string
     */
    private $newClass;

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/form.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Forms';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the Form class or the name of a Model class.'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['model', 		'm', InputOption::VALUE_OPTIONAL, 'Create the Form class based on a model that already exists.'],
            ['table', 		't', InputOption::VALUE_OPTIONAL, 'Create the Form class based on a database table.'],
            ['controller', 	'c', InputOption::VALUE_OPTIONAL, 'Create a new Controller for the Form.'],
        ];
    }

	public function fire() 
	{
		return $this->handle();
	}

	public function findModel($modelName, &$tableName)
	{
		$modelPath = $this->qualifyModel($modelName);
		try {
	   		$model = new $modelPath;
		} catch (\Exception $e) {
			$model = false;
			$modelPath = false;
		}
		if ($model && !$tableName) $tableName = $model->getTable();
		return $modelPath;
	}
	
	public function getTableColumns($tableName)
	{
		return DB::select('SHOW COLUMNS FROM `'.$tableName.'`');
	}
	
	public function labelize($value)
	{
		return ucfirst(str_replace('_',' ',(preg_replace('/_(id|fl)$/','',$value))));
	}
	
	public function enumToOptions($enum)
	{
		$values = explode("','",trim($enum,"'"));
		$options = "->options([ ";
		foreach ($values as $value) {
			$options .= "'".$value."' => '".$this->labelize($value)."',";
		}
		$options .= "])";
		return $options;
	}
	
	public function columnsToFields($columns)
	{
		$fields = '';
		foreach ($columns as $column) {
			if (preg_match('|([a-z0-9_]+)\((.+)\)|', $column->Type, $m)) {
				$column->Type = $m[1];
				$column->Options = $m[2];
			} else {
				$column->Options = null;
			}
			
			if ($column->Key == 'PRI') continue;
			if (in_array($column->Field, [ 'created_at','updated_at' ])) continue;
			
			switch ($column->Type) {
				case 'varchar': 
					$field = "Field::text('".$column->Field."')";
					if ($column->Options && ($length = intval($column->Options)) && $length < 255) $field .= "->length(".$length.")";
					break;
				case 'int':
					$field = "Field::integer('".$column->Field."')";
					break;
				case 'datetime':
					$field = "Field::datetime('".$column->Field."')";
					break;
				case 'date':
					$field = "Field::date('".$column->Field."')";
					break;
				case 'time':
					$field = "Field::time('".$column->Field."')";
					break;
				case 'enum':
					$field = "Field::select('".$column->Field."')";
					if ($column->Options) $field .= $this->enumToOptions($column->Options);
					break;
				case 'text':
					$field = "Field::textarea('".$column->Field."')";
					if ($column->Options) $field .= $this->enumToOptions($column->Options);
					break;
				default:
					$field = "Field::text('".$column->Field."')";
					break;
			}
			
			$field .= "->label('".$this->labelize($column->Field)."')";
			if ($column->Default) $field .= "->setDefault('".$column->Default."')";
			$fields .= "            ".$field.",\n";
		}
		return $fields;
	}

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
    	$inputName = $this->getNameInput();
    	$options = $this->options();
    	
    	$formName = null;
    	$modelName = null;
    	$tableName = null;
    	$controllerName = null;
    	$fields = null;
    	$this->fieldsDefinitions = null;

		if ($options['table'] !== null) {
			$tableName = $options['table'];
		}

		if ($options['model'] !== null) {
			$modelName = $this->findModel($options['model'], $tableName);
		}

    	if (preg_match('/Form$/',$inputName)) {
    		$formName = $inputName;
    	} else {
    		if (!$modelName) $modelName = $this->findModel($inputName, $tableName);
    		$formName = $inputName.'Form';
    	}
    	
    	if ($tableName) {
    		$columns = $this->getTableColumns($tableName);
			$this->fieldsDefinitions = $this->columnsToFields($columns);
    	}
    
        $name = $this->qualifyClass($formName);
        $path = $this->getPath($name);
        if ($this->alreadyExists($this->getNameInput())) {
            $this->error($this->type.' already exists!');
            return false;
        }

        $this->makeDirectory($path);
        $this->files->put($path, $this->buildClass($name));
        $this->info($this->type.' created successfully.');
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)->replaceFields($stub)->replaceClass($stub, $name);
    }

    /**
     * Replace the fields for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceFields(&$stub)
    {
    	if ($this->fieldsDefinitions) {
			$stub = str_replace(
				'/* Insert Fields Here */',
				$this->fieldsDefinitions,
				$stub
			);
		}

        return $this;
    }

    /**
     * Qualify the given model class base name.
     *
     * @param  string  $model
     * @return string
     */
    protected function qualifyModel($model)
    {
        $model = ltrim($model, '\\/');

        $model = str_replace('/', '\\', $model);

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($model, $rootNamespace)) {
            return $model;
        }

        return is_dir(app_path('Models'))
                    ? $rootNamespace.'Models\\'.$model
                    : $rootNamespace.$model;
    }

}
