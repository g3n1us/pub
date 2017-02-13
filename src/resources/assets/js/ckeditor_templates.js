CKEDITOR.addTemplates("default",{
	imagesPath:CKEDITOR.getUrl(CKEDITOR.plugins.getPath("templates")+"templates/images/"),
	templates:[
		{
			title:"Inline Ad",
			image:"template1.gif",
			description:"An in-article ad unit.",
			html:'<div class="mdc--ad">ADVERTISEMENT</div>'
		},
		{
			title:"Image and Title",
			image:"template1.gif",
			description:"One main image with a title and text that surround the image.",
			html:'\x3ch3\x3e\x3cimg src\x3d" " alt\x3d"" style\x3d"margin-right: 10px" height\x3d"100" width\x3d"100" align\x3d"left" /\x3eType the title here\x3c/h3\x3e\x3cp\x3eType the text here\x3c/p\x3e'
		},
	]
});


// {title:"Image and Title",image:"template1.gif",description:"One main image with a title and text that surround the image.",html: