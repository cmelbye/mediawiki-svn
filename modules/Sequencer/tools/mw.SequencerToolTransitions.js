/**
 * Extend the base tools prototype with transition tool
 */
$j.extend( mw.SequencerTools.prototype, {
	tools : {
		'transitions' : {
			'editWidgets' : ['editTransitions'],
			'contentTypes': ['video', 'img', 'cdata_html', 'mwtemplate' ]
		}
	},
	editWidgets:{
		'editTransitions' : {
			'transitionTypes' : {
				'fade':{
					'type' : {
						'value' : 'fade',
						'editType' : 'hidden'
					},
					'dur' : {
						'value' : '0:02',
						'editType' : 'time'
					}
				},
				'fadeColor':{
					'extends':'fade',
					'fadeColor' : {
						'value' : '#000',
						'editType' : 'color'
					}
				},
				// Set high level select attribute default
				'fadeFromColor' : {
					'extends': 'fadeColor',
					'selectable' : ['transIn'],
					'subtype' : {
						'value' : 'fadeFromColor',
						'editType' : 'hidden'
					}
				},
				'fadeToColor' : {
					'extends': 'fadeColor',
					'selectable' : ['transOut'],
					'subtype' : {
						'value' : 'fadeToColor',
						'editType' : 'hidden'
					}
				}
				// crossfade presently not supported
				/*,
				'crossfade' : {
					'extends': 'fade',
					'selectable' : ['transIn', 'transOut'],
					'subtype' : {
						'value' : 'crossfade',
						'editType' : 'hidden'
					}
				}*/
			},
			buildAttributeSet: function( transitionType ){
				var attributes = {};
				for( var i in this.transitionTypes[ transitionType ] ){
					if( i == 'extends' ){
						$j.extend( attributes, this.buildAttributeSet( this.transitionTypes[ transitionType ][i] ) );
					} else {
						attributes[ i ] = this.transitionTypes[ transitionType ][i];
					}
				}
				return attributes;
			},
	
			getTransitionId: function( smilElement, transitionType ){
				// Transition name is packed from attributeValue via striping the smilElement id
				// This is a consequence of smil's strange transition dom placement in the head of the
				// document instead of as child nodes. The idea with smil is the transition can be 'reused'
				// but in the sequencer context we want unique transitions so that each can be customized
				// independently.
				return $j( smilElement ).attr('id') + '_' + transitionType;
			},
	
			// Get a transition element ( if it does not exist add it )
			getTransitionElement: function( _this, smilElement, transitionType ){
				var $smilDom = _this.sequencer.getSmil().$dom;
				var transId = this.getTransitionId( smilElement, transitionType );
				if( $smilDom.find( '#' + transId ).length == 0 ){
					$smilDom.find('head').append(
						$j('<transition />')
						.attr('id', transId )
					);
				}
				return $smilDom.find( '#' + transId );
			},
	
			getSelectedTransitionType: function(smilElement, transitionDirection ){
				var attributeValue = $j( smilElement ).attr( transitionDirection );
				if( !attributeValue )
					return '';
				return attributeValue.replace( $j( smilElement ).attr('id') + '_', '' );
			},
	
			getBindedTranstionEdit: function( _this, smilElement, transitionType ){
				var _editTransitions = this;
				var $editTransitionsSet = $j('<div />');
				// Return the empty div on empty transtionType
				if( transitionType == '' ){
					return $editTransitionsSet
				}
	
				// Get the smil transition element
				var $smilTransitionElement = this.getTransitionElement( _this, smilElement, transitionType )
				// Get all the editable attributes for transitionName
				var attributeSet = this.buildAttributeSet( transitionType );
	
				$j.each( attributeSet, function( attributeKey, transitionAttribute ){
					// Skip setup attributes
					if( attributeKey == 'extends' || attributeKey == 'selectable' ){
						return true;
					}
					var initialValue = $smilTransitionElement.attr( attributeKey );
					// Set to the default value if the transition attribute has no attribute key
					if( !initialValue){
						initialValue = transitionAttribute.value
						$smilTransitionElement.attr( attributeKey, transitionAttribute.value )
					}
	
					if( transitionAttribute.editType == 'time' ){
						$editTransitionsSet.append(
							_this.getInputBox({
								'title' : gM('mwe-sequencer-tools-duration'),
								'inputSize' : 5,
								'initialValue' :initialValue,
								'change': function(){
									// parse smil time
									var time = _this.sequencer.getSmil().parseTime( $j(this).val() );
	
									// Check if time > clip duration
									if( time > $j( smilElement ).attr('dur') ){
										time = $j( smilElement ).attr('dur');
									}
									if( time < 0 )
										time = 0;
	
									// Update the input value
									$j( this ).val( mw.seconds2npt( time ) );
									// Update the smil attribute
									$smilTransitionElement.attr( attributeKey, time );
									// run the onChange action
									_editTransitions.onChange( _this, smilElement );
								}
							})
						)
					} else if ( transitionAttribute.editType == 'color' ){
						// Add the color picker:
						$editTransitionsSet.append(
							_this.getInputBox({
								'title' : gM('mwe-sequencer-tools-transitions-color'),
								'inputSize' : 8,
								'initialValue' : initialValue
							})
							.addClass("jColorPicker")
						);
						$editTransitionsSet.find('.jColorPicker input').jPicker(
								_editTransitions.colorPickerConfig,
								function(color){
									// commit color ( update undo / redo )
	
								},
								function(color){
									// live preview of selection ( update player )
									//mw.log('update: ' + attributeKey + ' set to: #' + color.val('hex'));
									$smilTransitionElement.attr( attributeKey, '#' + color.val('hex') );
									_editTransitions.onChange( _this, smilElement );
								}
							)
						// adjust the position of the color button:
						$editTransitionsSet.find('.jColorPicker .Icon').css('top', '-5px');
					}
				})
				return $editTransitionsSet;
			},
			/**
			 * Could move to a more central location if we use the color picker other places
			 */
			colorPickerConfig: {
				'window' : {
				 	'expandable': true,
					'effects' : {
						'type' : 'show'
					},
					'position' : {
						'x' : '10px',
						'y' : 'bottom'
					}
				},
				'images' : {
					'clientPath' : mw.getMwEmbedPath() + 'modules/Sequencer/tools/jPicker/images/'
				},
				'localization' : {
					 'text' : {
						'title' : gM('mwe-sequencer-color-picker-title'),
						'newColor' : gM('mwe-sequencer-menu-sequence-new'),
						'currentColor' : gM('mwe-sequencer-color-picker-current'),
						'ok' : gM('mwe-ok'),
						'cancel': gM('mwe-cancel')
					},
					'tooltips':{
						'colors':
						{
							'newColor': gM('mwe-sequencer-color-picker-new-color'),
							'currentColor': gM('mwe-sequencer-color-picker-currentColor')
						},
						'buttons':
						{
							ok: gM('mwe-sequencer-color-picker-commit' ),
							cancel: gM('mwe-sequencer-color-picker-cancel-desc')
						},
						'hue':
						{
							radio: gM('mwe-sequencer-color-picker-hue-desc'),
							textbox: gM('mwe-sequencer-color-picker-hue-textbox')
						},
						'saturation':
						{
							radio: gM('mwe-sequencer-color-picker-saturation-desc'),
							textbox: gM('mwe-sequencer-color-picker-saturation-textbox')
						},
						'value':
						{
							radio: gM('mwe-sequencer-color-picker-value-desc'),
							textbox: gM('mwe-sequencer-color-picker-value-textbox')
						},
						'red':
						{
							radio: gM('mwe-sequencer-color-picker-red-desc'),
							textbox: gM('mwe-sequencer-color-picker-red-textbox')
						},
						'green':
						{
							radio: gM('mwe-sequencer-color-picker-green-desc'),
							textbox: gM('mwe-sequencer-color-picker-green-textbox')
						},
						'blue':
						{
							radio: gM('mwe-sequencer-color-picker-blue-desc'),
							textbox: gM('mwe-sequencer-color-picker-blue-textbox')
						},
						'alpha':
						{
							radio: gM('mwe-sequencer-color-picker-alpha-desc'),
							textbox: gM('mwe-sequencer-color-picker-alpha-textbox')
						},
						'hex':
						{
							textbox: gM('mwe-sequencer-color-picker-hex-desc'),
							alpha: gM('mwe-sequencer-color-picker-hex-textbox')
						}
					}
				}
			},
			'onChange': function( _this, smilElement ){
				// Update the sequence duration :
				_this.sequencer.getEmbedPlayer().getDuration( true );
	
				// xxx we should re-display the current time
				_this.sequencer.getEmbedPlayer().setCurrentTime(
					$j( smilElement ).data('startOffset')
				);
			},
			'draw': function( _this, target, smilElement ){
				// draw the two attribute types
				var _editTransitions = this;
				var $transitionWidget = $j('<div />');
	
				var transitionDirections = ['transIn', 'transOut'];
				$j.each(transitionDirections, function( inx, transitionDirection ){
					$transitionWidget.append(
						$j('<div />').css('clear', 'both')
						,
						$j('<h3 />').text( gM('mwe-sequencer-tools-transitions-' + transitionDirection ))
					)
					// Output the top level empty select
					$transSelect = $j('<select />').append(
						$j('<option />')
						.attr('value', '')
					);
					var selectedTransitionType = _editTransitions.getSelectedTransitionType( smilElement, transitionDirection);
					for( var transitionType in _editTransitions.transitionTypes ){
						if( _editTransitions.transitionTypes[ transitionType ].selectable
							&&
							$j.inArray( transitionDirection, _editTransitions.transitionTypes[transitionType].selectable ) !== -1 )
						{
							// Output the item if its selectable for the current transitionType
							var $option = $j("<option />")
							.attr('value', transitionType )
							.text( transitionType )
							// Add selected attribute if selected:
							if( selectedTransitionType == transitionType ){
								$option.attr('selected', 'true');
							}
							$transSelect.append( $option );
						}
					}
					$transSelect.change( function(){
						var transitionType = $j(this).val();
						$transitionWidget.find( '#' + transitionDirection + '_attributeContainer' ).html(
							_editTransitions.getBindedTranstionEdit(
								_this, smilElement, transitionType
							)
						)
						// Update the smil attribute:
						$j( smilElement ).attr(
							transitionDirection,
							_editTransitions.getTransitionId( smilElement, transitionType )
						)
						// Update the player on select change
						_editTransitions.onChange( _this, smilElement );
					});
	
					// Add the select to the $transitionWidget
					$transitionWidget.append( $transSelect );
	
					// Set up the transConfig container:
					var $transConfig = $j('<span />')
						.attr('id', transitionDirection + '_attributeContainer');
	
					// If a given transition type is selected output is editable attributes
					if( selectedTransitionType != '' ) {
						$transConfig.append(
							_editTransitions.getBindedTranstionEdit(
								_this, smilElement, selectedTransitionType
							)
						)
					}
					$transitionWidget.append( $transConfig );
	
					// update the player for the default selected set.
					_editTransitions.onChange( _this, smilElement );
				});
				// add the transition widget to the target
				$j( target ).append( $transitionWidget );
			}
		}
	}
});