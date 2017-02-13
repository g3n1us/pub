<?php	
namespace G3n1us\Pub\Services;
	
use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;	
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;

	
class SmartyS3Resource extends \Smarty_Resource_Custom {
    public $filesystem;
    protected $mtime;

    public function __construct($bucket = null, $prefix = null, $s3config = null) {
	    if(!$s3config) {
		    $s3config = [
			    'region' => env('S3_SMARTY_REGION', 'us-east-1'), 
			    'version' => env('S3_SMARTY_API_VERSION', 'latest'),
		    ];
		      // credentials should be provided via instance metadata or environment variables. Otherwise, include the following to the $s3config argument

			  //  'credentials' => [
			  //      'key'    => 'AKIAJXXXXXXXXXXXXXXX',
			  //      'secret' => 'EjO3Af0XXXXXXXXXXXXXXXXXXXXXXXX',
			  //  ],
	    }
	    $production = env('APP_ENV', 'production') == 'production';
		$client = S3Client::factory($s3config);
		$adapter = $production ? 
			new AwsS3Adapter($client, env('S3_SMARTY_BUCKET', $bucket), env('S3_SMARTY_PREFIX', $prefix))
			:
			new Local(resource_path('smarty_views'));
		$this->filesystem = new Filesystem($adapter);
    }
    
    /**
     * Fetch a template and its modification time from database
     *
     * @param string $name template name
     * @param string $source template source
     * @param integer $mtime template modification timestamp (epoch)
     * @return void
     */
    protected function fetch($name, &$source, &$mtime)
    {
	    $mtime = $this->filesystem->getTimestamp($name);	    

	    $source = $this->filesystem->read($name);

    }
    
    /**
     * Fetch a template's modification time from S3
     *
     * @note implementing this method is optional. Only implement it if modification times can be accessed faster than loading the comple template source.
     * @param string $name template name
     * @return integer timestamp (epoch) the template was modified
     */
    protected function fetchTimestamp($name) {

        return $this->filesystem->getTimestamp($name);
    }
}