<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {

	/**
	 * Creates the application.
	 *
	 * @return \Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$unitTesting = true;
		$testEnvironment = 'testing';
		return require __DIR__.'/../../bootstrap/start.php';
	}

    public function resetDb()
    {
        $unitTesting = true;
        $testEnvironment = 'testing';
        $this->refreshApplication();
        Artisan::call('db:seed', ['--class' => 'TestSeeder']); 
        Mail::pretend(true);
    }

}
