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
"
🍀  🍀  🍀  🍀  🍀  🍀  🍀  🍀  🍀  🍀  
oooooo   ooo    ooo  oooooo
ooo  oo  ooo    ooo  ooo  oo
ooo  oo  ooo    ooo  oo   oo
ooooooo  ooo    ooo  oooooo
oooooo   ooo    ooo  ooooooo
ooo      ooo    ooo  ooo   oo
ooo      ooo    ooo  ooo   oo
ooo        oooooo    ooooooo
🍀  🍀  🍀  🍀  🍀  🍀  🍀  🍀  🍀  🍀
";
	
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
	
	private $cleanup = false;
	
	
	private $required_steps = [
		    'VERSIONS_BUCKET'        => "• Create a bucket to be used for storing versions of your stories and archival copies of your work", 
		    'AWS_BUCKET'             => "• Create a bucket to save files, and archives of your publications's work", 
		    'DB_SETUP_COMPLETE'      => "• Create database tables and fill with starter data", 
		    'DB_USERNAME'            => "• Enter you database credentials.",
	    ];
	    
	
	private $optional_steps = [
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
	    $this->cleanup = true;
	    $this->comment($this->logo);
	    $fn = $this->argument('fn');
	    return $this->{$fn}();
    }


	private function mock_progress(){
		echo "\n";
		
		$bar = $this->output->createProgressBar(3);
		$i = 2;
		while($i > 0){
			$bar->advance();
			sleep(1);
			$i--;
		}
		$bar->finish();
		echo "\n\n";
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
		    if(starts_with($k, 'DB_')) continue;
		    if(env($k)) $this->done[$k] = env($k);
	    }
	    foreach($this->optional_steps as $k => $v){
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
		$this->comment('Cheers! 🍺 🍺 Let\'s get started with setup...');
		$this->comment('...');
		$this->comment("Just answer the questions that follow to:  \n • create a bucket for files, \n • setup an Elastic Beanstalk environment to host your production site \n • create your Lambda functions that will handle image resizing and some other tasks");
		$this->info('FYI These values will be written to your .env file at the root of your project.');
	    if (!$this->confirm('Ready?')) {
		    $this->cleanup = false;
		    exit;
	    }
	    
	    $this->create_files_bucket();
	    
	    $this->create_archives_bucket();	
	    
	    $this->configure_db();    
	    
	    $this->install_db();
	    
	    $this->setup_google();
	    
	    $this->setup_dropbox();
	    
	}

// 1.
    private function create_files_bucket(){
	    if(!array_key_exists('AWS_BUCKET', $this->done)){
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
		    if($created) {
			    $this->done['AWS_BUCKET'] = $bucketname;
			    $this->done['AWS_REGION'] = 'us-east-1';
		    }
		    else{
			    $this->error('Something went wrong!');
			    return;
		    }
		    
	    }
		else $this->comment("Public file storage Bucket setup complete. Moving on...\n");
	    
    }


// 2.    
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

// 3.
    private function configure_db(){
	    if(!array_key_exists('DB_USERNAME', $this->done)){
	    
		    $this->comment("Enter your database credentials next. Your database should be created, and the user you specify should have permissions for this table. This step will save your credentials, then you will have the opportunity to create the necessary tables for the application.");
		    $this->done['DB_HOST'] = $this->ask('MySQL Host');
		    config(['database.connections.mysql.host' => $this->done['DB_HOST']]);
		    
		    $this->done['DB_DATABASE'] = $this->ask('MySQL Database Name');
		    config(['database.connections.mysql.database' => $this->done['DB_DATABASE']]);
		    
		    $this->done['DB_USERNAME'] = $this->ask('MySQL User');
		    config(['database.connections.mysql.username' => $this->done['DB_USERNAME']]);
		    
		    $this->done['DB_PASSWORD'] = $this->ask('MySQL Password');
		    config(['database.connections.mysql.password' => $this->done['DB_PASSWORD']]);
		    
			$this->mock_progress();
	    }
	    else {
		    config(['database.connections.mysql.host' => $this->done['DB_HOST']]);
		    config(['database.connections.mysql.database' => $this->done['DB_DATABASE']]);
		    config(['database.connections.mysql.username' => $this->done['DB_USERNAME']]);
		    config(['database.connections.mysql.password' => $this->done['DB_PASSWORD']]);
		    $this->comment("Database credentials have been entered. Moving on...\n");
	    }
		    
    }

    
// 4.    
    private function install_db(){
	    
	    if(!array_key_exists('DB_SETUP_COMPLETE', $this->done)){
		    if ($this->confirm('You are now ready to set up the database. Do you wish to continue?')) {
				$sql = file_get_contents(__DIR__.'/build.sql');
				DB::connection()->reconnect();
// 				dd(DB::connection());
				$pdo = DB::connection()->getPdo();
				$pdo->exec($sql);
				
				$brand = new Brand;
				$brand->slug = 'def';
				$brand->logo = '/vendor/pub/images/SpringfieldShopper.png';
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
   
    
// 5.      
	private function setup_google(){
	    if(!array_key_exists('GOOGLE_CLIENT_ID', $this->done)){
		    $this->comment("(optional) You'll need to setup a Google API project via the developer console in order to use Google for login. Go to https://console.developers.google.com/apis/library to create one.");
		    $this->comment("After the project is setup, you'll receive a 'Client ID' and 'Client Secret'. You'll also be asked to enter the site's domain name for use with redirects after the login prompt. \n Enter these next:");
		    if(!$this->confirm("Would you like to continue with this step?")) return;
		    $this->done['GOOGLE_CLIENT_ID'] = $this->ask('Client ID');
		    $this->done['GOOGLE_CLIENT_SECRET'] = $this->ask('Client Secret');
		    $site_domain = $this->ask("Enter this site's domain name, including http(s)://");
		    $this->done['GOOGLE_CLIENT_REDIRECT_URL'] = str_finish($site_domain, '/') . 'oauth/google/callback';
			$this->mock_progress();
		    
		    
		}
		else $this->comment("Dropbox setup complete. Moving on...\n");
		
	} 	
	
// 6.	
	private function setup_dropbox(){
	    if(!array_key_exists('DROPBOX_CLIENT_ID', $this->done)){
		    $this->comment("(optional) You'll need to setup a Dropbox app via the developer console in order to use Dropbox as part of your print workflow. Go to https://www.dropbox.com/developers/apps to create one.");
		    $this->comment("After the app is made, you will get an 'App Key', 'App Secret', and 'App Token'. \n Enter these next:");
		    if(!$this->confirm("Would you like to continue with this step?")) return;
		    $this->done['DROPBOX_CLIENT_ID'] = $this->ask('App key');
		    $this->done['DROPBOX_APP_SECRET'] = $this->ask('App secret');
		    $this->done['DROPBOX_ACCESS_TOKEN'] = $this->ask('Access Token');
			$this->mock_progress();		    
		    
		}
		else $this->comment("Dropbox setup complete. Moving on...\n");
		
	}    

    
   

    

    private function finish(){
	    $required_steps = $this->required_steps;
	    $done = array_diff(array_keys($required_steps), array_keys($this->done));
	    if (empty($done)) {
		    if($this->confirm('Setup is complete! Do you wish to continue and write your setup values to the .env file?'))
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
		if(!copy(base_path('.env'), base_path('.env_backups/env' . time()))){
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
		    if(!env($k) || starts_with($k, 'DB_'))
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

You should now run `php artisan vendor:publish` to copy the required assets from Pub into Laravel.

Also, you can run `php artisan db:seed --class=PubDatabaseSeeder`. This will load the site with some placeholder content so you can get an idea of how things work.

Now you're on your way. This calls for a toast!! 🍻
");
				
			}
				
		}
				
    }
	
}	
	