/**
 * Extend the base tools prototype with pan zoom tool
 */
$j.extend( true, mw.SequencerTools.prototype, {
	tools : {
		'layout': {
			'editWidgets' : ['layout'],
			'editableAttributes' : [ 'panZoom', 'rotate' ],
			'contentTypes': [ 'img', 'video' ], 
			'supportsKeyFrames' : 'true'
		}
	},
	editableAttributes: {
		'panZoom': {
			'type' : 'string',
			'inputSize' : 15,
			'title' : gM('mwe-sequencer-clip-panzoom' ),
			'defaultValue' : '0%, 0%, 100%, 100%'
		},
		'rotate': {
			'type' : 'string',
			'inputSize' : 5,
			'title' : gM('mwe-sequencer-clip-rotate' ),
			'defaultValue' : '0'
		}
	},
	editWidgets: {
		'layout' : {
			'onPanzoomChange': function( _this, smilElement ){
				var panZoomVal = $('#' +_this.getEditToolInputId( 'layout', 'panZoom')).val();
				mw.log("panzoom change:" + panZoomVal );
	
				// Update on the current smil clip display:
				_this.sequencer.getSmil()
				.getLayout()
				.panZoomLayout(
					smilElement
				);	
				
				// Update the timeline clip display
				// xxx this should be abstracted to timeline handler for smil clip updates above
				var $thumbTraget = $( '#' + _this.sequencer.getTimeline().getTimelineClipId( smilElement ) ).find('.thumbTraget');
				_this.sequencer.getSmil()
				.getLayout()
				.panZoomLayout(
					smilElement,
					$thumbTraget,
					$thumbTraget.find('img').get(0)
				);
				// Register the change for undo redo
				_this.sequencer.getActionsEdit().registerEdit();
			},
			/**
			 * TODO should combine with onPanzoomChange above
			 */
			'onRotateChange':  function( _this, smilElement ){
				var rotateVal = $('#' +_this.getEditToolInputId( 'layout', 'rotate')).val();
				// Update smil value: 
				$(smilElement).attr( 'rotate', rotateVal );
				
				// Update rotate display
				_this.sequencer.getSmil()
				.getLayout()
				.rotateLayout(
					smilElement
				);
				
				// Update the timeline clip display
				var $thumbTraget = $( '#' + _this.sequencer.getTimeline().getTimelineClipId( smilElement ) ).find('.thumbTraget');
				_this.sequencer.getSmil()
				.getLayout()
				.rotateLayout(
					smilElement,
					$thumbTraget,
					$thumbTraget.find('img').get(0)
				);
				
				// Register the change for undo redo
				_this.sequencer.getActionsEdit().registerEdit();				
			},
			
			//Rest layout button? ( restores default position )
			// presently NOT CALLED.
			'getRestLayoutBtn': function( _this, smilElement ){
				$j.button({
					'icon' : 'arrow-4',
					'text' : gM( 'mwe-sequencer-tools-panzoomhelper-resetlayout' )
				})
				.attr('id', 'panzoomResetLayout')
				.css('float', 'left')
				.hide()
				.click(function(){
					// TODO: Restore default SMIL setting				
				});
			},
			'getOrginalHelperCss': function( _this ){
				return  {
					'position' : 'absolute',
					'width' : 120,
					'height' : 100,				
					'color' : 'red',
					'font-size' : 'x-small',
					'opacity' : .6,
					'border' : 'dashed',
					'left' : _this.sequencer.getEmbedPlayer().getPlayerWidth()/2 - 60,
					'top' : _this.sequencer.getEmbedPlayer().getPlayerHeight()/2 - 50
				};
			},
			'addLayoutHelperBindings': function( _this, smilElement, $layoutHelper ){
				var _thisEditWidget = this;
				var orginalHelperCss = _thisEditWidget.getOrginalHelperCss( _this );
				// Add layout helper bindings bindings for panzoom
				var startPanZoomVal = '';
				
			
				$layoutHelper
				.draggable({
					containment: 'parent',
					start: function( event, ui){
						startPanZoomVal = _thisEditWidget.getPanZoomVal(  _this, smilElement );
					},
					drag: function( event, ui){
						_thisEditWidget.updatePanZoomFromUiValue(_this, smilElement, startPanZoomVal, {
							'top' : ( orginalHelperCss.top - ui.position.top ),
							'left' : ( orginalHelperCss.left - ui.position.left )
						});
					},
					stop: function( event, ui){
						// run the onChange ?
						// Restore original css for the layout helper
						$(this).css( orginalHelperCss );
						// trigger the 'change'
						_thisEditWidget.onPanzoomChange( _this, smilElement );
					}
				})
				.css('cursor', 'move')
				.resizable({
					handles : 'all',
					maxWidth : 250,
					maxHeight: 180,
					aspectRatio: 4/3,
					start: function( event, ui){
						startPanZoomVal = _thisEditWidget.getPanZoomVal(  _this, smilElement );
					},
					resize : function(event, ui){
						_thisEditWidget.updatePanZoomFromUiValue(_this, smilElement, startPanZoomVal, {
							'width' : ( orginalHelperCss.width - ui.size.width ),
							'height' : ( orginalHelperCss.top - ui.size.height )
						});
					},
					stop: function( event, ui){
						// Restore original css
						$(this).css( orginalHelperCss );
						// trigger the change
						_thisEditWidget.onPanzoomChange( _this, smilElement);
					}
				});
				// Add binding for rotate helper
				$layoutHelper.find( '.rotateHelper' )
				.disableSelection()
				.mousedown( function( event ){
					rotateMouseUpHandler = false;
					// track mouse movement at a set interval
					var rotateStart = ( $( smilElement ).attr( 'rotate' ) )? $( smilElement ).attr( 'rotate' ) : 0;
					var mouseStartX = event.pageX;
					var prevMouseDiffDeg = 0;
					$(document).bind('mousemove.rotateHelper', function( event ){
						// Apply css transform 
						var mouseDiffDeg = ( mouseStartX - event.pageX ) % 360;
						
						// Save some computing costs on identical horizontal pixel moves
						if( prevMouseDiffDeg != mouseDiffDeg){
							// Update the user input tool input value:
							$('#' +_this.getEditToolInputId( 'layout', 'rotate' ) ).val( rotateStart - mouseDiffDeg);
							// Update the smil DOM: 
							$( smilElement ).attr( 'rotate', rotateStart - mouseDiffDeg);	
							// Update display: 
							_this.sequencer.getSmil()
							.getLayout()
							.rotateLayout(
								smilElement
							);
						}
						prevMouseDiffDeg = mouseDiffDeg;
					})
					.bind('mouseup.rotateHelper', function( event ){
						// unbind the document events:
						$(document).unbind('mousemove.rotateHelper mouseup.rotateHelper');
						// Trigger the change:
						_thisEditWidget.onRotateChange(  _this, smilElement );
					});
					return false;
				});				
			},
			'getRotateHelper' : function( _this, smilElement){
				return $('<div />')
					.attr('title', gM('mwe-sequencer-clip-rotate-desc') )
					.addClass( 'rotateHelper ui-state-default ui-corner-all')
					.css({
						'position' : 'absolute',
						'top' : '40%',
						'left': '40%',
						'width': '20px',
						'height': '20px',
						'cursor': 'crosshair'
					})
					.append(
						$('<span />').addClass( 'ui-icon ui-icon-arrowrefresh-1-e' )
					);
			},
			'updatePanZoomFromUiValue': function( _this, smilElement, startPanZoomVal, layout ){
				var _thisEditWidget = this;
				var pz = startPanZoomVal.split(',');
				// Set the new percent offset to x/2
				if( layout.left ){
					pz[0] = ( parseInt( pz[0] ) - ( layout.left / 4 ) ) + '%';
				}

				if( layout.top ){
					pz[1] = ( parseInt( pz[1] ) - ( layout.top / 4 ) )+ '%';
				}

				if( layout.width ) {
					pz[2] = ( parseInt( pz[2] ) - ( layout.width / 2) ) + '%';

					// right now only keep aspect is supported do size hack::
					pz[3] = parseInt( pz[2] ) * _this.sequencer.getSmil().getLayout().getTargetAspectRatio();
					// only have 2 significant digits

				}
				// Trim and round all % values
				for(var i=0; i < pz.length; i++){
					pz[i] = ( Math.round( parseInt( pz[i] ) * 1000 ) / 1000 ) + '%';
					pz[i] = $j.trim( pz[i] );
				}
				var smilPanZoomValue = pz.join(', ');

				// Update the smil DOM:
				$( smilElement ).attr( 'panZoom', smilPanZoomValue );

				// Update the user input tool input value:
				$('#' +_this.getEditToolInputId( 'layout', 'panZoom')).val( smilPanZoomValue );

				// Animate the update on the current smil clip display:
				_this.sequencer.getSmil()
				.getLayout()
				.panZoomLayout(
					smilElement
				);
			},
			
			'getPanZoomVal' : function( _this, smilElement ){
				if( $( smilElement ).attr( 'panZoom') ){
					return $( smilElement ).attr( 'panZoom');
				}
				return _this.editableAttributes['panZoom'].defaultValue;
			},
			/**
			 * we should really rename "_this" to "sequenceTools"
			 */
			'draw': function( _this, target, smilElement ){
				var _thisEditWidget = this;
				
				// Add a input box binding:
				$('#' +_this.getEditToolInputId( 'layout', 'panZoom'))
				.change(function(){
					_thisEditWidget.onPanzoomChange( _this, smilElement, target );
				});
				
				$('#' +_this.getEditToolInputId( 'layout', 'rotate'))
				.change(function(){
					_thisEditWidget.onRotateChange( _this, smilElement, target );
				});
				
				// Add descriptive text: 
				$( target ).append(
					$('<h3 />').html(
						gM('mwe-sequencer-tools-panzoomhelper-desc')
					)
				);				
				
				// Get a reference to the player interface
				var $playerUI = _this.sequencer.getEmbedPlayer().$interface;
				
				// Remove any old layout helper:
				$playerUI.find('.layoutHelper').remove();

				// Append the resize helper as an overlay on the player:
				$playerUI.append(
					$('<div />')
					.css( _thisEditWidget.getOrginalHelperCss( _this ) )
					.addClass("ui-widget-content layoutHelper")
					.text( gM('mwe-sequencer-tools-panzoomhelper') )
					// Get the rotate helper: s
					.append( 
						_thisEditWidget.getRotateHelper(_this, smilElement)
					)
				);
				
				// Only show when the panzoom tool is selected
				if( _this.getCurrentToolId() != 'layout'){
					$playerUI.find('.layoutHelper').hide();
				}				
				
				$( _this ).bind('toolSelect.seqTools', function(){
					if( _this.getCurrentToolId() == 'layout'){
						$playerUI.find('.layoutHelper').fadeIn('fast');
					} else {
						$playerUI.find('.layoutHelper').fadeOut('fast');
					}
				});
				
				// Bind to resize player events to keep the helper centered
				$( _this.sequencer.getEmbedPlayer() ).bind('onResizePlayer', function(event, size){
					$playerUI.find('.layoutHelper').css( {
						'left' : size.width/2 - 60,
						'top' : size.height/2 - 50
					});
				});
				
				_thisEditWidget.addLayoutHelperBindings(_this, smilElement, $playerUI.find('.layoutHelper') );				
			}
		}
	}
});
