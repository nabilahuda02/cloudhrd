<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RemoteUpload extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'remoteupload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process Uploads to S3';

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
        $databases = User::whereNotNull('database')->where('confirmed', 1)->lists('database');
        foreach ($databases as $database) {
            $conn = array(
                'driver'    => 'mysql',
                'host'      => '127.0.0.1',
                'database'  => $database,
                'username'  => 'root',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
            );
            Config::set('database.connections.' . $database, $conn);
            $images = DB::connection($database)->table('user_profiles')->lists('user_image', 'id');
            $s3 = AWS::get('s3');
            foreach ($images as $id => $image) {
                if($image && !stristr($image, 'cloudhrd-dev.s3-website-ap-southeast-1.amazonaws.com')) {

                    // Upload to S3
                    $avatar = str_replace('original', 'avatar', $image);
                    $s3->putObject(array(
                        'ACL'        => 'public-read',
                        'Bucket'     => 'cloudhrd-dev',
                        'Key'        => $database . '/' . $image,
                        'SourceFile' => app_path() . '/../../app/public' . $image,
                    ));
                    $s3->putObject(array(
                        'ACL'        => 'public-read',
                        'Bucket'     => 'cloudhrd-dev',
                        'Key'        => $database . '/' . $avatar,
                        'SourceFile' => app_path() . '/../../app/public' . $avatar,
                    ));

                    // Update DB Values
                    DB::connection($database)
                        ->table('user_profiles')
                        ->where('id', $id)
                        ->update(array('user_image' => 'http://cloudhrd-dev.s3-website-ap-southeast-1.amazonaws.com/' . $database . $image));

                    // Cleanup
                    if(file_exists(app_path() . '/../../app/public' . $image)) {
                        unlink(app_path() . '/../../app/public' . $image);
                    }
                    if(file_exists(app_path() . '/../../app/public' . $avatar)) {
                        unlink(app_path() . '/../../app/public' . $avatar);
                    }
                    File::deleteDirectory(dirname(realpath(app_path() . '/../../app/public' . $image)), false);
                }
            }

            $uploads = DB::connection($database)->table('uploads')->get();
            foreach ($uploads as $upload) {
                if(!stristr($upload->file_url, 'cloudhrd-dev.s3-website-ap-southeast-1.amazonaws.com')) {
                    if(file_exists($upload->file_path)) {
                        // Upload to S3
                        $s3->putObject(array(
                            'ACL'        => 'public-read',
                            'Bucket'     => 'cloudhrd-dev',
                            'Key'        => $database . $upload->file_url,
                            'SourceFile' => $upload->file_path,
                        ));

                        // Update DB Values
                        DB::connection($database)
                            ->table('uploads')
                            ->where('id', $upload->id)
                            ->update(array('file_path' => '', 'file_url' => 'http://cloudhrd-dev.s3-website-ap-southeast-1.amazonaws.com/' . $database . $upload->file_url));

                        // Delete the file
                        unlink($upload->file_path);
                    }
                    if(file_exists($upload->thumb_path)) {
                        // Upload to S3
                        $s3->putObject(array(
                            'ACL'        => 'public-read',
                            'Bucket'     => 'cloudhrd-dev',
                            'Key'        => $database . $upload->thumb_url,
                            'SourceFile' => $upload->thumb_path,
                        ));

                        // Update DB Values
                        DB::connection($database)
                            ->table('uploads')
                            ->where('id', $upload->id)
                            ->update(array('thumb_path' => '', 'thumb_url' => 'http://cloudhrd-dev.s3-website-ap-southeast-1.amazonaws.com/' . $database . $upload->thumb_url));

                        // Delete the file
                        unlink($upload->thumb_path);
                    }
                }
            }

        }
        return ;
        
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
