<?php

use Illuminate\Console\Command;

class DBSync extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'db:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Databases';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->info('Syncing Databases');

        $from = app_path() . '/database/automigrations/';
        $to = app_path() . '/database/donemigrations/';
        $dbs = array_filter(Master__User::all()->lists('database'));
        // $dbs[] = 'cloudhrd_app';
        $response = [];

        foreach (scandir($from) as $file) {
            if (!in_array($file, ['.', '..']) && !file_exists($to . $file)) {
                $response[$from . $file] = [];
                foreach ($dbs as $db) {
                    $this->info('Syncing ' . $db . ' ' . $file);
                    shell_exec('mysql -u root ' . $db . ' < ' . $from . $file);
                }
                touch($to . $file);
            }
        }

        $this->info('Done');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array();
    }

}
