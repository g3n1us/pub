<?php
namespace G3n1us\Pub\Services;

use \PhpOffice\PhpWord\PhpWord;
use \PhpOffice\PhpWord\Style\Font;
use \PhpOffice\PhpWord\IOFactory;
use \PhpOffice\PhpWord\Shared\Converter;
use Storage;

use Article;

class WordProcessor{
	public function __construct($article = null){
		$this->output_dir = storage_path("word");
		$this->word = new PhpWord();
		$this->word->setDefaultFontName('Helvetica');
		$this->word->setDefaultFontSize(12);	
		$this->paragraph_style = [
			'spaceAfter' => Converter::pointToTwip(12),
			'lineHeight' => 1.2,
		];
		$this->word->addTitleStyle(1, ['size' => 26, 'name' => 'Garamond'], $this->paragraph_style);
		
		$this->article = $article ?: Article::first();
		
	}
	
	
	public function make(){
				
		/* Note: any element you append to a document must reside inside of a Section. */
		
		// Adding an empty Section to the document...
		$section = $this->word->addSection();
		// Adding Text element to the Section having font styled by default...
		$section->addTitle($this->article->title, 1);			
		
		$section->addText('By ' . $this->article->author_display, [],[
			'spaceAfter' => Converter::pointToTwip(11),
			'lineHeight' => 1.5,
		
		]);
		if($this->article->lead_photo){
			// $src = $this->article->lead_photo->url;
			// $section->addImage($src);			
		}
		
		$content = trim(strip_tags( $this->article->content->body ));
		$contents = explode("\n", $content);
		foreach($contents as $c){
			$section->addText($c, [],[
				'spaceAfter' => Converter::pointToTwip(12),
				'lineHeight' => 1.5,
			]);			
		}
		
	}
	

	
	public function download(){
		$this->make();
		$objWriter = IOFactory::createWriter($this->word, 'Word2007');
		$filename = str_slug($this->article->title) . '.docx';
		header("Content-Description: File Transfer");
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Expires: 0');
		$objWriter->save("php://output");

	}
	
	public function write(){
		$this->make();
		// Saving the document as OOXML file...
		$objWriter = IOFactory::createWriter($this->word, 'Word2007');
		$filename = $this->article->id . '-' . str_slug($this->article->title) . '.docx';
		$filename = "G3n1usPub/$filename";
		ob_start();
		$objWriter->save("php://output");
		Storage::disk('dropbox')->put($filename, ob_get_clean());
		return ['success' => Storage::disk('dropbox')->exists($filename), 'message' => 'The article has been exported as a Word document and saved to Dropbox at: '. $filename];
	}
}