/*
 * JS2-style edit.js
 */

// Setup configuration vars (if not set already)
if( !mwAddMediaConfig )
  var mwAddMediaConfig = {};

var mvTimedTextEdit = {};

js2AddOnloadHook( function() {
  function getSubtitle(f) {
      var name = f.files[0].name;
      var srtData = f.files[0].getAsBinary();
      srtData = srtData.replace('\r\n', '\n');
      return srtData;
  }
  function uploadSubtitles() {
    do_api_req({
      'data': {
        'meta' : 'siteinfo',
        'siprop' : 'languages'
      }
      }, function( langDataRaw ) {
        var langData = {};
        var languageSelect = '<select id="timed_text_language">';

        var lagRaw = langDataRaw.query.languages;
        for(var j in lagRaw){
          var code = lagRaw[j].code;
          var language = lagRaw[j]['*'];
          langData[ code ] = language;
          languageSelect += '<option value="'+code+'">'+language+'('+code+')</option>';
        }
        languageSelect += '/</select>';
		    var cBtn = {};
		    cBtn[ gM('mwe-cancel') ] = function(){
			    $j(this).dialog('close');
		    }
		    cBtn[ gM('mwe-ok') ] = function(){
			    var file = $j('#timed_text_file_upload');
			    var langKey = file[0].files[0].name.split('.');
			    var extension = langKey.pop();
			    langKey = langKey.pop();
			    var mimeTypes = {
			        'srt': 'text/x-srt',
			        'cmml': 'text/cmml'
			    }
			    if( !mimeTypes[ extension ] ){
				    js_log('Error: unknown extension:'+ extension);
			    }
			    //get language from form
    		  langKey = $j('#timed_text_language').val();

			    if(extension == "srt") {
      	    var srt = getSubtitle(file[0]);
			      $j(this).html("saving...");
			      $j('.ui-dialog-buttonpane').remove();

      		  var editToken = $j('input[name=wpEditToken]').val();
      		  var title = wgTitle.split('.');
      		  title.pop();
      		  title.pop();
      		  title = title.join('.') + '.' + langKey + '.srt';
            do_api_req({
              'data': {
                'action' : 'edit',
                'title' : title,
                'text' : srt,
                'token': editToken
              }
            }, function(dialog) {
                return function( result ) {
                  document.location.href = wgArticlePath.replace('/$1', '?title=' + title + '&action=edit');
                  $j(dialog).dialog('close');
               }}(this)
            );
			    } else {
    			  $j(this).html("error only srt works right now.");
			    }			  
		    }
        $j.addDialog("Select Subtitle to upload",
           '<input type="file" id="timed_text_file_upload"></input><br>' + languageSelect,
           cBtn);
        $j('#timed_text_file_upload').change(function(ev) {
			    var langKey = this.files[0].name.split('.');
			    var extension = langKey.pop();
			    langKey = langKey.pop();
    		  $j('#timed_text_language').val( langKey );
        });
    });
  }
  var button = $j('<button>');
  button.click(uploadSubtitles)
  button.text("Upload Subtitle");
  $j('#toolbar').append(button);
});

