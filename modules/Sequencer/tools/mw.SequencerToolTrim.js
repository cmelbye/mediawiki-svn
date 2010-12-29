
/**
 * Extend the base tools prototype with Trim function: 
 * @dependency ToolDuration
 */
$j.extend( mw.SequencerTools.prototype, {
	tools: {
		'trim': {
			'editWidgets' : [ 'trimTimeline' ],
			'editableAttributes' : ['clipBegin','dur' ],
			'contentTypes': ['video', 'audio']
		}
	},
	editableAttributes : {
		'dur' :{
			'type': 'time',
			'title' : gM('mwe-sequencer-clip-duration' )
		},
		'clipBegin': {
			'type': 'time',
			'title' : gM('mwe-sequencer-start-time' )
		}
	},
	editWidgets: {
		'trimTimeline' : {
			'onChange': function( _this, smilElement ){
				var smil = _this.sequencer.getSmil();
				// Update the preview thumbs
				var $target = $j( '#editWidgets_trimTimeline' );
				/**
				 * Currently we disable thumb preview updates since multiple seeks are costly
				 */
				/*var updateDurationThumb = function(){
					// Check the duration:
					var clipDur = $j('#editTool_trim_dur').val();
					if( clipDur ){
						// Render a thumbnail for the updated duration
						smil.getLayout().drawSmilElementToTarget(
							smilElement,
							$target.find('.trimEndThumb'),
							clipDur
						);
					}
				}
	
				var clipBeginTime = $j('#editTool_trim_clipBegin').val();
				if( !clipBeginTime ){
					$target.find('.trimStartThumb').hide();
				} else {
					mw.log("Should update trimStartThumb::" + $j(smilElement).attr('clipBegin') );
					// Render a thumbnail for relative start time = 0
					smil.getLayout().drawSmilElementToTarget(
						smilElement,
						$target.find('.trimStartThumb'),
						0,
						updateDurationThumb()
					)
				}
				*/
	
				// Register the edit state for undo / redo
				_this.sequencer.getActionsEdit().registerEdit();
			},
			// Return the trimTimeline edit widget
			'draw': function( _this, target, smilElement ){
				var smil = _this.sequencer.getSmil();
				var sliderScale = 2000 // assume slider is never more than 2000 pixles wide.
				// check if thumbs are supported
				/*if( _this.sequencer.getSmil().getRefType( smilElement ) == 'video' ){
					$j(target).append(
						$j('<div />')
						.addClass( 'trimStartThumb ui-corner-all' ),
						$j('<div />')
						.addClass( 'trimEndThumb ui-corner-all' ),
						$j('<div />').addClass('ui-helper-clearfix')
					)
				}*/
				// The local scope fullClipDuration
				var fullClipDuration = null;
	
				// Some slider functions
				var sliderToTime = function( sliderval ){
					return parseInt( fullClipDuration * ( sliderval / sliderScale ) );
				}
				var timeToSlider = function( time ){
					return parseInt( ( time / fullClipDuration ) * sliderScale );
				}
	
				// Special flag to prevent slider updates from propgating if the change was based on user input
				var onInputChangeFlag = false;
				var onInputChange = function( sliderIndex, timeValue ){
					onInputChangeFlag = true;
					if( fullClipDuration ){
						// Update the slider
						var sliderTime = ( sliderIndex == 0 )? timeToSlider( timeValue ) :
							timeToSlider( timeValue + smil.parseTime( $j('#' + _this.getEditToolInputId( 'trim', 'clipBegin') ).val() ) );
	
						$j('#'+_this.sequencer.id + '_trimTimeline' )
							.slider(
								"values",
								sliderIndex,
								sliderTime
							);
					}
					// restore the onInputChangeFlag
					onInputChangeFlag = false;
	
					// Directly update the smil xml from the user Input
					if( sliderIndex == 0 ){
						// Update clipBegin
						_this.editableTypes['time'].update( _this, smilElement, 'clipBegin', timeValue );
					} else {
						// Update dur
						_this.editableTypes['time'].update( _this, smilElement, 'dur', timeValue );
					}
					mw.log(' should update inx:' + sliderIndex + ' set to: ' + timeValue);
	
					// Register the change
					_this.editWidgets.trimTimeline.onChange( _this, smilElement );
				};
	
				// Add a trim binding:
				$j('#' + _this.getEditToolInputId( 'trim', 'clipBegin') )
				.change( function(){
					var timeValue = smil.parseTime( $j(this).val() );
					onInputChange( 0, timeValue);
				});
	
				 $j('#' + _this.getEditToolInputId( 'trim', 'dur') )
				.change( function(){
					var timeValue = smil.parseTime( $j(this).val() );
					onInputChange( 1, timeValue );
				});
	
				// Update the thumbnails:
				_this.editWidgets.trimTimeline.onChange( _this, smilElement );
	
				// Get the clip full duration to build out the timeline selector
				smil.getBody().getClipAssetDuration( smilElement, function( clipDuration ) {
					// update the local scope global
					fullClipDuration = clipDuration;
	
					var startSlider = timeToSlider( smil.parseTime( $j('#editTool_trim_clipBegin').val() ) );
					var sliderValues = [
						startSlider,
						startSlider + timeToSlider( smil.parseTime( $j('#editTool_trim_dur').val() ) )
					];
					// Return a trim tool binded to smilElement id update value events.
					$j(target).append(
						$j('<div />')
						.attr( 'id', _this.sequencer.id + '_trimTimeline' )
						.css({
							'position': 'absolute',
							'left' : '25px',
							'right' : '35px',
							'margin': '5px'
						})
						.slider({
							range: true,
							min: 0,
							max: sliderScale,
							values: sliderValues,
							slide: function(event, ui) {
	
								$j('#' + _this.getEditToolInputId( 'trim', 'clipBegin') ).val(
									mw.seconds2npt( sliderToTime( ui.values[0] ), true )
								);
								$j('#' + _this.getEditToolInputId( 'trim', 'dur') ).val(
									mw.seconds2npt( sliderToTime( ui.values[1] - ui.values[0] ), true )
								);
							},
							change: function( event, ui ) {
								if( ! onInputChangeFlag ){
									// Update clipBegin
									_this.editableTypes['time'].update( _this, smilElement, 'clipBegin', sliderToTime( ui.values[ 0 ] ) );
	
									// Update dur
									_this.editableTypes['time'].update( _this, smilElement, 'dur', sliderToTime( ui.values[ 1 ]- ui.values[0] ) );
	
									// update the widget display
									_this.editWidgets.trimTimeline.onChange( _this, smilElement );
								}
							}
						})
					);
				});
			}
		}
	}
});
