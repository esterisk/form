<?php
namespace Esterisk\Form;

use Illuminate\Support\ServiceProvider;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/views/form', 'form');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/esterisk/form'),
        ]);
		$this->publishes([
        	__DIR__.'/esterisk-form.css' => public_path('vendor/esterisk/css/form.css'),
    	], 'public');        
		$this->publishes([
        	__DIR__.'/esterisk-form.js' => public_path('vendor/esterisk/js/form.js'),
    	], 'public');        
        \Blade::directive('form', function($expression) {
			return "<?php echo view('esterisk.form.form', [ 'form' => $expression ]); ?>";
		});

        $this->commands([
            Console\FormMakeCommand::class,
        ]);

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
