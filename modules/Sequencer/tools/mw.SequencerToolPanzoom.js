/**
 * Extend the base tools prototype with pan zoom tool
 */
$j.extend( mw.SequencerTools.prototype, {
	tools : {
		'panzoom' : {
			'editWidgets' : ['panzoom'],
			'editableAttributes' : [ 'panZoom' ],
			'contentTypes': [ 'img', 'video' ], 
			'supportsKeyFrames' : 'true'
		}
	},
	editableAttributes: {
		'panZoom' :{
			'type' : 'string',
			'inputSize' : 15,
			'title' : gM('mwe-sequencer-clip-panzoom' ),
			'defaultValue' : '0%, 0%, 100%, 100%'
		}
	},
	editWidgets: {
		'panzoom' : {
			'onChange': function( _this, smilElement ){
				var panZoomVal = $j('#' +_this.getEditToolInputId( 'panzoom', 'panZoom')).val();
				mw.log("panzoom change:" + panZoomVal );
	
				// Update on the current smil clip display:
				_this.sequencer.getSmil()
				.getLayout()
				.panZoomLayout(
					smilElement
				);
				var $thumbTraget = $j( '#' + _this.sequencer.getTimeline().getTimelineClipId( smilElement ) ).find('.thumbTraget');
				// Update the timeline clip display
				// xxx this should be abstracted to timeline handler for clip updates
				_this.sequencer.getSmil()
				.getLayout()
				.panZoomLayout(
					smilElement,
					$thumbTraget,
					$thumbTraget.find('img').get(0)
				)
				// Register the change for undo redo
				_this.sequencer.getActionsEdit().registerEdit();
			},			
			'draw': function( _this, target, smilElement ){
				var orginalHelperCss = {
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
				
				// Add a input box binding:
				$j('#' +_this.getEditToolInputId( 'panzoom', 'panZoom'))
				.change(function(){
					_this.editWidgets.panzoom.onChange( _this, smilElement, target );
				})
	
				$j( target ).append(
					$j('<h3 />').html(
						gM('mwe-sequencer-tools-panzoomhelper-desc')
					)
				);				
				var $playerUI = _this.sequencer.getEmbedPlayer().$interface;
				// Remove any old layout helper:
				$playerUI.find('.panzoomHelper').remove();
				
				// Append the resize helper as an overlay on the player:
				$playerUI.append(
					$j('<div />')
					.css( orginalHelperCss )
					.addClass("ui-widget-content panzoomHelper")
					.text( gM('mwe-sequencer-tools-panzoomhelper') )				
				);
				
				// Only show when the panzoom tool is selected
				if( _this.getCurrentToolId() != 'panzoom'){
					$playerUI.find('.panzoomHelper').hide()
				}				
				$j(_this).bind('toolSelect', function(){
					if( _this.getCurrentToolId() == 'panzoom'){
						$playerUI.find('.panzoomHelper').fadeIn('fast')
					} else {
						$playerUI.find('.panzoomHelper').fadeOut('fast')
					}
				});	
				// Bind to resize player events to keep the helper centered
				$j( _this.sequencer.getEmbedPlayer() ).bind('onResizePlayer', function(event, size){
					$playerUI.find('.panzoomHelper').css( {
						'left' : size.width/2 - 60,
						'top' : size.height/2 - 50
					});
				});
				
				
				/*xxx Keep aspect button ?*/
				// Rest layout button ( restores default position )
				/*$j.button({
					'icon' : 'arrow-4',
					'text' : gM( 'mwe-sequencer-tools-panzoomhelper-resetlayout' )
				})
				.attr('id', 'panzoomResetLayout')
				.css('float', 'left')
				.hide()
				.click(function(){
					// Restore default SMIL setting
					_this.editableTypes['display'].update(
						_this,
						smilElement,
						'panzoom',
						_this.editableAttributes['panzoom'].defaultValue
					)
				})*/
								
				var startPanZoomVal = '';
				var setStartPanZoomVal = function(){
					 startPanZoomVal = $j( smilElement ).attr( 'panZoom');
					if(! startPanZoomVal ){
						startPanZoomVal = _this.editableAttributes['panZoom'].defaultValue;
					}
				}
	
				var updatePanZoomFromUiValue = function( layout ){
					var pz = startPanZoomVal.split(',');
					// Set the new percent offset to x/2
					if( layout.left ){
						pz[0] = ( parseInt( pz[0] ) - ( layout.left / 4 ) ) + '%';
					}
	
					if( layout.top ){
						pz[1] = ( parseInt( pz[1] ) - ( layout.top / 4 ) )+ '%';
					}
	
					if( layout.width ) {
						pz[2] = ( parseInt( pz[2] ) - ( layout.width / 2) ) + '%'
	
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
					$j( smilElement ).attr( 'panZoom', smilPanZoomValue );
	
					// Update the user input tool input value:
					$j('#' +_this.getEditToolInputId( 'panzoom', 'panZoom')).val( smilPanZoomValue );
	
					// Animate the update on the current smil clip display:
					_this.sequencer.getSmil()
					.getLayout()
					.panZoomLayout(
						smilElement
					);
				}
				// Add bindings
				$playerUI.find('.panzoomHelper')
				.draggable({
					containment: 'parent',
					start: function( event, ui){
						setStartPanZoomVal();
					},
					drag: function( event, ui){
						updatePanZoomFromUiValue({
							'top' : ( orginalHelperCss.top - ui.position.top ),
							'left' : ( orginalHelperCss.left - ui.position.left )
						});
					},
					stop: function( event, ui){
						// run the onChange ?
						// Restore original css for the layout helper
						$j(this).css( orginalHelperCss )
						// trigger the 'change'
						_this.editWidgets.panzoom.onChange( _this, smilElement );
					}
				})
				.css('cursor', 'move')
				.resizable({
					handles : 'all',
					maxWidth : 250,
					maxHeight: 180,
					aspectRatio: 4/3,
					start: function( event, ui){
						setStartPanZoomVal();
					},
					resize : function(event, ui){
						updatePanZoomFromUiValue({
							'width' : ( orginalHelperCss.width - ui.size.width ),
							'height' : ( orginalHelperCss.top - ui.size.height )
						});
					},
					stop: function( event, ui){
						// Restore original css
						$j(this).css( orginalHelperCss )
						// trigger the change
						_this.editWidgets.panzoom.onChange( _this, smilElement);
					}
				})
	
			}
		}
	}
});
