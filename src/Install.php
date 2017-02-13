<?php
namespace G3n1us\Pub;	

use Illuminate\Console\Command;

use DB;
use Page;
use Brand;

use Aws\S3\S3Client;

/**
 * Installer for the package
 */
class Install  extends Command{
	
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pub:install {fn=install} {arg=null}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Pub';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	    $fn = $this->argument('fn');
	    $arg = $this->argument('arg');
	    $arg = $arg === 'null' ? null : $arg;
	    return $this->{$fn}($arg);
    }


    private function create_bucket($bucketname){
	    $client = new S3Client([
		    'version' => 'latest',
		    'region'  => 'us-east-1',
	    ]);
	     
	    $client->createBucket([
		    'Bucket' => $bucketname,
	    ]);
    }

    
    private function install(){
		$sql = file_get_contents(__DIR__.'/build.sql');
		$pdo = DB::connection()->getPdo();
		$pdo->exec($sql);
		
		$brand = new Brand;
		$brand->slug = 'def';
		$brand->name = 'Default';
		$brand->handle = 'default';
		$brand->save();

		$homepage = new Page;
		$homepage->url = '/';
		$homepage->name = 'Home';
		$homepage->description = 'The home page';
		$homepage->config = [];
		$homepage->save();
    }
	
}	
	