<?php
namespace G3n1us\Pub\Controllers;

use Illuminate\Http\Request;
use Mail;

use App\Http\Requests;
use App\Http\Requests\StoreFormRequest;
// use App\Http\Requests\StoreFormRequest as StoreFormRequest;
use App\Http\Controllers\Controller;

class FormController extends Controller
{
	
    public function store(StoreFormRequest $request, $domain){
		$submission = new \App\Form;
		$submission->ip = $request->ip;
		$submission->fields = $request->all();
		if($submission->save()){
			$this->send_email($submission);
			return redirect()->back()->with('status', 'Thank you! We will be in touch with you shortly.');
		}
			
		else
			return redirect()->back()->with('error', "I'm sorry, an error occurred. Please try again.");
	}
	
	public function send_email(\App\Form $form){
        Mail::send('emails.form_submitted', ['form' => $form], function ($m) use ($form) {
			$m->subject('Contact Form Submission on MediaDC Advertising');
			foreach(config('app.mail-recipients') as $recipient)
	            $m->to($recipient['address'], $recipient['name']);
        });		
	}
	
}
