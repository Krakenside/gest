//[editor Javascript]

//Project:	Apro Admin - Responsive Admin Template
//Version:	1.1
//Last change:  10/10/2017
//Primary use:   Used only for the wysihtml5 Editor 


//Add text editor
    $(function () {
    "use strict";

    // Replace the <textarea id="editor1"> with a CKEditor
	// instance, using default configuration.
	CKEDITOR.replace('ditor1')
	//bootstrap WYSIHTML5 - text editor
	$('.textarea').wysihtml5();		
	
  });

