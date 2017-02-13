<?php

namespace G3n1us\Pub\Services;	
/**
 * Pull object versions from S3
 */
class S3Versions  {
	
	public function __construct($options = []){
		$this->maxresults = 100;
		$this->brand = 'WEX';
		$this->bucket = 'content.washingtonexaminer.biz';
		$this->filepath = 'wex15/css/site.css';
		// defaults above, will overwrite if set in argument
		foreach($options as $opt => $val) $this->{$opt} = $val;		
		$this->lcbrand = strtolower($this->brand);		
		$resultsarray = $this->getresults();
		$versions = [];
		$versions = array_merge($versions, $resultsarray['Versions']);
		while(isset($resultsarray['NextToken']) && count($versions) < $this->maxresults){
			$resultsarray = $this->getresults($resultsarray['NextToken']);
			$versions = array_merge($versions, $resultsarray['Versions']);
		}
		$this->versions = $versions;
// 		echo count($versions) . "\n";
// 		file_put_contents(dirname(__DIR__)."/versions3.json", json_encode($versions));
	}
	
	private function getresults($nextmarker = null){
		$markerstring = $nextmarker ? "--starting-token \"$nextmarker\"" : null;
		$command = "/usr/local/aws/bin/aws s3api list-object-versions --max-items {$this->maxresults} --page-size {$this->maxresults} --profile {$this->lcbrand} --bucket {$this->bucket} $markerstring --prefix \"{$this->filepath}\"";
		
		exec($command, $commandoutput, $returnvalue);
		$resultsarray = json_decode(implode("\n",$commandoutput), true);
		return $resultsarray;
	}
	
	public function get(){
		echo $this->filepath . ": " . count($this->versions) . " versions\n";
		return $this->versions;
	}
	
	public function __toString(){
		return json_encode($this->get());
// 		return (string)count($this->get());
	}
}
	
