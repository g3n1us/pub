<?php
namespace G3n1us\Pub;	

use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

use DB;
use Page;
use Brand;

use Aws\S3\S3Client;

/**
 * Installer for the package
 */
class Install  extends Command{
	
private $logo = 
'
oooooo   ooo    ooo  oooooo
ooo  oo  ooo    ooo  ooo  oo
ooo  oo  ooo    ooo  oo   oo
ooooooo  ooo    ooo  oooooo
oooooo   ooo    ooo  ooooooo
ooo      ooo    ooo  ooo   oo
ooo      ooo    ooo  ooo   oo
ooo        oooooo    ooooooo

';
	
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pub {fn=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Pub';


	private $env = [];

	private $start_string = "# <<PUB_SETUP - Do not remove this line";

	private $end_string = "# Do not remove this line - PUB_SETUP>>";
	
	private $write_to_env = false;
	
	private $cleanup = true;
	
	
	private $required_steps = [
		    'VERSIONS_BUCKET'        => "• Create a bucket to be used for storing versions of your stories and archival copies of your work", 
		    'S3_BUCKET'              => "• Create a bucket to save files, and archives of your publications's work", 
		    'DB_SETUP_COMPLETE'      => "• Create database tables and fill with starter data", 
		    'DROPBOX_CLIENT_ID'      => "• Setup your Dropbox app that will house your Indesign copy for the print workflow",
		    'GOOGLE_CLIENT_ID'       => "• Setup Google login so your users can securely access to application with their Google credentials", 
	    ];
	    
	    private $bucket_policy = '{
  "Version":"2012-10-17",
  "Statement":[
    {
      "Sid":"AddPerm",
      "Effect":"Allow",
      "Principal": "*",
      "Action":["s3:GetObject"],
      "Resource":["arn:aws:s3:::examplebucket/*"]
    }
  ]
}';
	
	
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	    $this->comment($this->logo);
	    $fn = $this->argument('fn');
	    return $this->{$fn}();
    }


	private function mock_progress(){
		$bar = $this->output->createProgressBar(3);
		$i = 2;
		while($i > 0){
			$bar->advance();
			sleep(1);
			$i--;
		}
		$bar->finish();
	}


    public function __construct(){
		parent::__construct();
	    
	    $this->s3client = new S3Client([
		    'version' => 'latest',
		    'region'  => 'us-east-1',
	    ]);

	    $this->setupfile = base_path('.pubsetup');
	    
	    $this->env_contents = @file_get_contents($this->setupfile);
	    $this->done = [];   
	    foreach(explode("\n", trim($this->env_contents)) as $line){
		    $line = trim($line);
		    if(empty($line)) continue;
		    if(!str_contains($line, '=')) continue;
		    $parts = explode('=', $line);
		    $this->done[$parts[0]] = $parts[1];
	    }
	    foreach($this->required_steps as $k => $v){
		    if(env($k)) $this->done[$k] = env($k);
	    }
    }
    
    
    private function revert(){
	    $this->error('You are about to erase your configuration');
	    if ($this->confirm('Do you wish to continue?')) {
		    $this->backup_env();
		    $c = file_get_contents(base_path('.env'));
		    $c = preg_replace('/# <<PUB_SETUP(.*?)PUB_SETUP>>/s', '', $c);
		    file_put_contents(base_path('.env'), $c);
		}
		$this->cleanup = false;
    }
    
    
	private function all(){
		$this->comment('Greetings! Let\'s get started with setup...');
		$this->comment('...');
		$this->comment("Just answer the questions that follow to:  \n • create a bucket for files, \n • setup an Elastic Beanstalk environment to host your production site \n • create your Lambda functions that will handle image resizing and some other tasks");
		$this->info('FYI These values will be written to your .env file at the root of your project.');
	    if (!$this->confirm('Ready?')) {
		    $this->cleanup = false;
		    exit;
	    }
	    
	    $this->create_files_bucket();
	    
	    $this->create_archives_bucket();	    
	    
	    $this->install_db();
	    
	}

    private function create_archives_bucket(){
	    if(!array_key_exists('VERSIONS_BUCKET', $this->done)){
		    $this->comment("You need to create a bucket to be used for storing versions of your stories and archival copies of your work.");
		    if(!$this->confirm("Would you like to continue with creating this bucket?")) return;
		    $bucketname = $this->ask('What should your bucket be named?');
		    $created = $this->s3client->createBucket([
			    'Bucket' => $bucketname,
		    ]);
		    $this->mock_progress();
		    if($created) $this->done['VERSIONS_BUCKET'] = $bucketname;
		    else{
			    $this->error('Something went wrong!');
			    return;
		    }
		    
	    }
		else $this->comment("Archival Bucket setup complete. Moving on...\n");
	    
    }


    private function create_files_bucket(){
	    if(!array_key_exists('S3_BUCKET', $this->done)){
		    $this->comment("You need to create a bucket to be used for storing your site's files");
		    if(!$this->confirm("Would you like to continue with creating this bucket?")) return;
		    $bucketname = $this->ask('What should your bucket be named?');
		    $created = $this->s3client->createBucket([
			    'Bucket' => $bucketname,
		    ]);
		    
			$policy = str_replace('examplebucket', $bucketname, $this->bucket_policy);
		    
			$result = $this->s3client->putBucketPolicy([
			    'Bucket' => $bucketname, // REQUIRED
			    'Policy' => $policy, // REQUIRED
			]);
		    
		    $this->mock_progress();
		    if($created) $this->done['S3_BUCKET'] = $bucketname;
		    else{
			    $this->error('Something went wrong!');
			    return;
		    }
		    
	    }
		else $this->comment("Public file storage Bucket setup complete. Moving on...\n");
	    
	    
    }

    
    private function install_db(){
	    if(!array_key_exists('DB_SETUP_COMPLETE', $this->done)){
		    if ($this->confirm('You are now ready to set up the database. Do you wish to continue?')) {
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
					   
			   $this->done['DB_SETUP_COMPLETE'] = "true"; 
			   $this->mock_progress();
			}
		}
		else $this->comment("Database setup complete. Moving on...\n");
		
    }
    

    private function finish(){
	    $required_steps = $this->required_steps;
	    $done = array_diff(array_keys($required_steps), array_keys($this->done));

	    if (empty($done) && $this->confirm('Setup is complete! Do you wish to continue and write your setup values to the .env file?')) {
		    
		    $this->write_to_env = true;
		    
	    }
	    else {
		    $donetable = array_map(function($s) use($required_steps){
			    return [$required_steps[$s]];
		    }, $done);
		    $this->table(['Remaining Steps'], $donetable);
		    $this->comment("Your progress is saved. You can come back any time to continue.\n\n");
	    }
    }
    
    
    private function backup_env(){
		if(!is_dir(base_path('.env_backups')))
			mkdir(base_path('.env_backups'));
		if(!copy(base_path('.env'), base_path('env_backups/env' . time()))){
			throw new \Exception("Backup couldn't be made.");
			die();
		}
    }
    
    
    public function __destruct(){
	    if(!$this->cleanup) return;
	    $this->finish();
	    $mapped = [];
	    $table = [];
	    foreach($this->done as $k => $v){
		    if(!env($k))
			    $mapped[] = "$k=$v";
		    $table[] = [$k, $v];
	    }

		$mapped = implode("\n", $mapped);
		file_put_contents($this->setupfile, $mapped);
		
		if($this->write_to_env){
			if(preg_match('/# <<PUB_SETUP(.*?)PUB_SETUP>>/s', file_get_contents(base_path('.env')))){
				$this->error('ERROR -- You appear to already have configuration values in .env. Delete these manually to continue.');
				exit;
			}
			else{
				$this->backup_env();
				
				$existing_env = file_get_contents(base_path('.env'));
				file_put_contents(base_path('.env'), "\n" . $this->start_string . "\n\n$mapped\n\n" . $this->end_string . "\n\n$existing_env");
				
				$this->comment('The following values are now available:');
				$this->table(['Key', 'Value'], $table);
$this->comment("DONE!! Your configuration has been written and is available to Laravel.
In the file, config/filesystems.php, change the value of the bucket to 'env(\"S3_BUCKET\")'
Now you are good to go. Enjoy!!
");
				
			}
				
		}
				
    }
	
}	
	