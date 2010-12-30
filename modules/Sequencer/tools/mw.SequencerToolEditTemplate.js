/**
 * Extend the base tools prototype with Edit Template tool
 */
$j.extend( true, mw.SequencerTools.prototype, {
	tools : {
		'templateedit' : {
			'editWidgets' : ['editTemplate'],
			'editableAttributes' : [ 'apititlekey' ],
			'contentTypes' : ['mwtemplate']
		}
	},
	editableAttributes: {
		'apititlekey' : {
			'type' : 'string',
			'inputSize' : 30,
			'title' : gM('mwe-sequencer-template-name' )
		}
	},
	editWidgets : {
		'editTemplate':{
			'onChange' : function( _this, smilElement ){
				// Clear the smilElement template cache:
				$j( smilElement ).data('templateHtmlCache', null);
				// Re draw the smilElement in the player
				var smil = _this.sequencer.getSmil();
				$playerTarget = $j('#' + smil.getSmilElementPlayerID( smilElement ) );
				$playerTarget.loadingSpinner();
				smil.getLayout().getSmilTemplateHtml( smilElement, $playerTarget, function(){
					mw.log("SequencerTools::editWidgets: smil template updated");
				});
			},
			'draw': function( _this, target, smilElement ){
				// Parse the set of templates from the template text cache
				$j( target ).loadingSpinner();
	
				if( ! $j( smilElement).attr('apititlekey') ){
					mw.log("Error: can't grab template without title key");
					return ;
				}
				// Get the template wikitext
				_this.sequencer
				.getServer()
				.getTemplateText( $j( smilElement).attr('apititlekey'), function( templateText ){
					//mw.log("GotTemplateText: " + templateText );
					if( ! templateText || typeof templateText != 'string' ){
						mw.log("Error: could not get wikitext form titlekey: " + $j( smilElement).attr('apititlekey'));
						return ;
					}
					$j( target ).empty().append(
						$j('<h3 />').text( gM('mwe-sequencer-edittemplate-params') )
					);
	
					// This is not supposed to be perfect ..
					// just get you 'most' of the input vars 'most' of the time via the greedy regEx:
					var templateVars = templateText.match(/\{\{\{([^\}]*)\}\}\}/gi);
					var cleanTemplateParams = {};
	
					for( i =0;i<templateVars.length; i++ ){
						var tVar = templateVars[i];
						// Remove all {{{ and }}}
						tVar = tVar.replace(/\{\{\{/, '' ).replace( /\}\}\}/, '');
						// Check for | operator
						if( tVar.indexOf("|") != -1 ){
							// Only the first version of the template var
							tVar = tVar.split( '|')[0];
						}
						cleanTemplateParams[ tVar ] = true;
					}
					// Output input boxes for each template var as a param
					for( var paramName in cleanTemplateParams ){
						$j( target ).append(
							_this.getEditableAttribute(
									smilElement,
									'editTemplate',
									'param',
									paramName
							)
							.find('input')
							// Bind the change event:
							.change(function(){
								_this.editWidgets.editTemplate.onChange(
									_this,
									smilElement
								);
							})
							.parent()
							,
							$j('<div />')
							.css('clear', 'both')
						);
					}
	
	
				});
			}
		}
	}
});