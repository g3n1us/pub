<?php
namespace G3n1us\Pub\Services;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;	
use League\Flysystem\Filesystem;

class SmartyView{
	
	public function __construct($template, $data = []){
		$this->smarty = new \Smarty;
		$class = new \ReflectionClass(SmartyS3Resource::class);
		$filesystem = $class->getProperty('filesystem');
		$this->smarty->registerResource('s3', new SmartyS3Resource);
		$this->smarty->assign('filesystem', $filesystem);
		foreach($data as $k => $v) $this->smarty->assign($k, $v);
		$this->smarty->assign('errors', session('errors') ?: []);

	}
	
	public static function fetch($template, $data = []){
		$self = new SmartyView($template, $data);
		return $self->smarty->fetch("s3:$template");
	}
	
	
	public static function display($template, $data = []){
		$self = new SmartyView($template, $data);
		return $self->smarty->display("s3:$template");
	}
	
}