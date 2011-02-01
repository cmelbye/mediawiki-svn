/**
 * Handles the "tools" window top level component driver
 */

// Wrap in mw closure to avoid global leakage
( function( mw ) {

mw.SequencerTools = function( sequencer ) {
	return this.init( sequencer );
};

// Set up the mvSequencer object
mw.SequencerTools.prototype = {
	init: function(	sequencer ){	
		this.sequencer = sequencer;
	},

	// The current smil clip ( lazy init )
	currentsmilElement: null,

	// The current selected tool ( lazy init )
	currentToolId: null,

	// Tools config ( extended by tool classes ) 
	tools: { },
	
	// Editable attributes ( extended by tool classes ) 
	editableAttributes: {
		// Special child node type ( shared )
		'param' : {
			'type' : 'childParam',
			'inputSize' : 30
		}
	},
	// EditWidgets config ( extended by tool classes ) 
	editWidgets: { },
	
	/**
	 * Edit types shared across all tools
	 */
	editableTypes: {
		'childParam': {
			update: function( _this, smilElement, paramName, value){
				// Check if the param already exists
				$paramNode = $( smilElement ).find( "[name='"+ paramName + "']" );
				if( $paramNode.length == 0 ){
					$( smilElement ).append(
						$('<param />').attr({
							'name': paramName,
							'value' : value
						})
					);
				} else {
					// Update the param value
					$paramNode.attr( 'value', value);
				}
				mw.log("editableTypes::Should have updated smilElement param: " + paramName
						+ ' to : ' + $( smilElement ).find( "[name='"+ paramName + '"]' ).attr( 'value') );
			},
			getSmilVal: function( _this, smilElement, paramName ){
				$paramNode = $( smilElement ).find( "[name='"+ paramName + "']" );
				if( $paramNode.length == 0){
					return '';
				}
				return $paramNode.attr('value');
			}
		},
		'string': {
			update: function( _this, smilElement, attributeName, value){
				$( smilElement ).attr( attributeName, value);
				// update the display
			},
			getSmilVal : function( _this, smilElement, attributeName ){
				if( $( smilElement ).attr( attributeName ) ){
					return $( smilElement ).attr( attributeName );
				}
				// Check for a default value
				if( _this.editableAttributes[ attributeName ].defaultValue ){
					return _this.editableAttributes[ attributeName ].defaultValue;
				}
				return '';
			}
		},
		'time' : {
			update : function( _this, smilElement, attributeName, value){
				// Validate time
				var seconds = _this.sequencer.getSmil().parseTime( value );
				$( smilElement ).attr( attributeName, mw.seconds2npt( seconds ) );
				// Update the clip duration :
				_this.sequencer.getEmbedPlayer().getDuration( true );

				// Seek to "this clip"
				_this.sequencer.getEmbedPlayer().setCurrentTime(
					$( smilElement ).data('startOffset')
				);
			},
			getSmilVal : function( _this, smilElement, attributeName ){
				var smil = _this.sequencer.getSmil();
				return mw.seconds2npt(
						smil.parseTime(
							$( smilElement ).attr( attributeName )
						)
					);
			}
		}
	},
	editActions: {
		'sourcePage':{
			'displayCheck': function( _this, smilElement ){
				if( _this.sequencer.getSmil().getTitleKey( smilElement )
					&&
					_this.sequencer.getServer().isConfigured()
				){
					return true;
				}
				return false;
			},
			'icon': 'info',
			'title': gM('mwe-sequencer-asset-source'),
			'action' : function( clickButton, _this, smilElement ){
				// Update the link
				$( clickButton )
				.attr({
					'href': _this.sequencer.getServer().getAssetViewUrl(
							_this.sequencer.getSmil().getTitleKey( smilElement )
						)
					,
					'target' : '_new'
				});
				// follow the link the link
				return true;
			}
		},
		'preview' : {
			'icon' : 'play',
			'title' : gM('mwe-sequencer-preview'),
			'action': function( clickButton, _this, smilElement ){
				_this.sequencer.getPlayer().previewClip( smilElement, function(){
					// preview done, restore original state:
					$(clickButton).replaceWith (
						_this.getEditAction( smilElement, 'preview' )
					);
				});
				// xxx todo update preview button to "pause" / "play"
				var doPause = function(){
					$( clickButton ).find( '.ui-icon')
						.removeClass( 'ui-icon-pause' )
						.addClass( 'ui-icon-play' );
					$( clickButton ).find('.btnText').text(
						gM('mwe-sequencer-preview-continue')
					);
					_this.sequencer.getEmbedPlayer().pause();
				};
				var doPlay = function(){
					// setup pause button:
					$( clickButton ).find( '.ui-icon')
						.removeClass( 'ui-icon-play' )
						.addClass( 'ui-icon-pause' )
					$( clickButton ).find('.btnText').text(
						gM('mwe-sequencer-preview-pause')
					);
					// keep the target preview end time:
					// xxx should probably refactor this.. a bit of abstraction leak here:
					_this.sequencer.getEmbedPlayer().play(
						_this.sequencer.getEmbedPlayer().playSegmentEndTime
					);
				};
				$( clickButton ).unbind().click(function(){
					if( _this.sequencer.getEmbedPlayer().paused ){
						doPlay();
					} else {
						doPause();
					}
				})
				doPlay();
			}
		},
		'cancel' : {
			'icon': 'close',
			'title' : gM('mwe-sequencer-clip-cancel-edit'),
			'action' : function(clickButton, _this, smilElement ){
				$j.each(
					_this.getToolSet(
						_this.sequencer.getSmil().getRefType( smilElement )
					),
					function( inx, toolId ){
						var tool = _this.tools[toolId];
						for( var i=0; i < tool.editableAttributes.length ; i++ ){
							var attributeName = tool.editableAttributes[i];
							var $editToolInput = $('#' + _this.getEditToolInputId( toolId, attributeName ) );
							// Restore all original attribute values
							smilElement.attr( attributeName, $editToolInput.data('initialValue') );
						}
					}
				);

				// Update the clip duration :
				_this.sequencer.getEmbedPlayer().getDuration( true );

				// Update the embed player
				_this.sequencer.getEmbedPlayer().setCurrentTime(
					$( smilElement ).data('startOffset')
				);

				// Close / empty the toolWindow
				_this.setDefaultText();
			}
		}
	},
	getDefaultText: function(){
		return gM('mwe-sequencer-no_selected_resource');
	},
	setDefaultText: function(){
		this.sequencer.getEditToolTarget().html(
			this.getDefaultText()
		);
	},
	getEditToolInputId: function( toolId, attributeName){
		return 'editTool_' + toolId + '_' + attributeName.replace('/\s/', '');
	},
	/**
	 * update the current displayed tool ( when an undo, redo or history jump changes smil state )
	 */
	updateToolDisplay: function(){
		var _this = this;

		// If tools are displayed update them
		if( this.sequencer.getEditToolTarget().find('.editToolsContainer').length ){
			this.drawClipEditTools();
		};

	},
	getToolSet: function( refType ){
		var toolSet = [];
		for( var toolId in this.tools){
			if( this.tools[ toolId ].contentTypes){
				if( $j.inArray( refType, this.tools[ toolId ].contentTypes) != -1 ){
					toolSet.push( toolId );
				}
			}
		}
		return toolSet;
	},
	drawClipEditTools: function( smilElement, selectedToolId ){
		var _this = this;
		mw.log( "SequencerTool:: drawClipEditTools:" + smilElement + ' :' + selectedToolId );
		// Update the current clip and tool :
		if( smilElement ){
			this.setCurrentsmilElement( smilElement );
		}
		if( selectedToolId ){
			this.setCurrentToolId( selectedToolId );
		}

		$toolsContainer = $('<div />')
		.addClass( 'editToolsContainer' )
		.css( {
			'overflow': 'auto',
			'position':'absolute',
			'top' : '0px',
			'left': '0px',
			'right': '0px',
			'bottom': '37px'
		})
		.append(
			$('<ul />')
		);

		this.sequencer.getEditToolTarget().empty().append(
			$toolsContainer
		);
		// Get the entire tool set based on what "ref type" smilElement is:
		var toolSet = this.getToolSet(
							this.sequencer.getSmil().getRefType(
								this.getCurrentsmilElement()
							)
						);
		mw.log( 'SequencerTools::drawClipEditTools: Adding ' + toolSet.length + ' tools for ' +
				this.sequencer.getSmil().getRefType( this.getCurrentsmilElement() ) +
				' current tool: ' + _this.getCurrentToolId()
			);
		
		var toolTabIndex = 0;
		$j.each( toolSet, function( inx, toolId ){
			var tool = _this.tools[ toolId ];
			if( _this.getCurrentToolId() == toolId){
				toolTabIndex = inx;
			}
			// Append the title to the ul list
			$toolsContainer.find( 'ul').append(
				$('<li />').append(
					$('<a />')
					.attr('href', '#tooltab_' + toolId )
					.text( gM('mwe-sequencer-tools-' + toolId) )
				)
			);

			// Append the tooltab container
			$toolsContainer.append(
				$('<div />')
				.attr('id', 'tooltab_' + toolId )
				.append(
					$('<h3 />').text( gM('mwe-sequencer-tools-' + toolId + '-desc') )
				)
			);
			var $toolContainer = $toolsContainer.find( '#tooltab_' + toolId );

			// Build out the attribute list for the given tool ( if the tool has directly editable attributes )
			if( tool.editableAttributes ){
				for( var i=0; i < tool.editableAttributes.length ; i++ ){
					attributeName = tool.editableAttributes[i];
					$toolContainer.append(
						_this.getEditableAttribute( smilElement, toolId, attributeName )
					);
				}
			}

			// Output a float divider:
			$toolContainer.append( $('<div />').addClass('ui-helper-clearfix') );

			// Build out tool widgets
			if( tool.editWidgets ){
				for( var i =0 ; i < tool.editWidgets.length ; i ++ ){
					var editWidgetId = tool.editWidgets[i];
					if( ! _this.editWidgets[editWidgetId] ){
						mw.log("Error: not recogonized widget: " + editWidgetId);
						continue;
					}
					// Append a target for the edit widget:
					$toolContainer.append(
						$('<div />')
						.attr('id', 'editWidgets_' + editWidgetId)
					);
					// Draw the binded widget:
					_this.editWidgets[ editWidgetId ].draw(
						_this,
						$( '#editWidgets_' + editWidgetId ),
						smilElement
					);
					// Output a float divider:
					$toolContainer.append( $('<div />').addClass( 'ui-helper-clearfix' ) );
				}
			}
		});
		
		// Add tab bindings
		$toolsContainer.tabs({
			select: function( event, ui ) {
				_this.setCurrentToolId( $( ui.tab ).attr('href').replace('#tooltab_', '') );
			},
			selected : toolTabIndex
		});

		// Update the selected tool
		_this.setCurrentToolId(	toolSet[ toolTabIndex ] );
		
		var $editActions = $('<div />')
		.css({
			'position' : 'absolute',
			'bottom' : '0px',
			'height' : '37px',
			'left' : '0px',
			'right' : '0px',
			'overflow' : 'auto'
		});
		
		// Build out global edit Actions buttons after the container
		for( var editActionId in this.editActions ){
			// Check if the edit action has a conditional display:
			var displayEidtAction = true;

			if( this.editActions[ editActionId ].displayCheck ){
				displayEidtAction = this.editActions[ editActionId ].displayCheck( _this, smilElement );
			}
			if( displayEidtAction ){
				$editActions.append(
					this.getEditAction( smilElement, editActionId )
				)
			}
		}
		$( this.sequencer.getEditToolTarget() ).append( $editActions )
	},
	getCurrentsmilElement: function(){
		return this.currentsmilElement;
	},
	setCurrentsmilElement: function( smilElement ){
		this.currentsmilElement = smilElement;
	},
	getCurrentToolId: function(){
		return this.currentToolId;
	},
	setCurrentToolId: function( toolId ){
		this.currentToolId = toolId;
		$( this ).trigger( 'toolSelect' );
	},

	getEditAction: function( smilElement, editActionId ){
		if(! this.editActions[ editActionId ]){
			mw.log("Error: getEditAction: " + editActionId + ' not found ');
			return ;
		}
		var _this = this;
		var editAction = this.editActions[ editActionId ];
		$actionButton = $j.button({
				icon: editAction.icon,
				text: editAction.title
			})
			.css({
				'float': 'left',
				'margin': '5px'
			})
			.click( function(){
				return editAction.action( this, _this, smilElement );
			});
		return $actionButton;
	},
	/* get the editiable attribute input html */
	getEditableAttribute: function( smilElement, toolId, attributeName, paramName ){
		if( ! this.editableAttributes[ attributeName ] ){
			mw.log("Error: editableAttributes : " + attributeName + ' not found');
			return;
		}


		var _this = this;
		var editAttribute = this.editableAttributes[ attributeName ];
		var editType = editAttribute.type;
		if( !_this.editableTypes[ editType ] ){
			mw.log(" Error: No editableTypes interface for " + editType);
			return ;
		}

		// Set the update key to the paramName if provided:
		var updateKey = ( paramName ) ? paramName : attributeName;

		var initialValue = _this.editableTypes[ editType ].getSmilVal(
			_this,
			smilElement,
			updateKey
		);
		// Set the default input size
		var inputSize = ( _this.editableAttributes[ attributeName ].inputSize)?
				_this.editableAttributes[ attributeName ].inputSize : 6;

		// Set paramName based attributes:
		var attributeTitle = ( editAttribute.title ) ? editAttribute.title : paramName + ':';

		return _this.getInputBox({
			'title' : attributeTitle,
			'inputId' : _this.getEditToolInputId( toolId, updateKey ),
			'inputSize': inputSize,
			'initialValue' : initialValue,
			'change': function(){
				// Run the editableType update function:
				_this.editableTypes[ editType ].update(
						_this,
						smilElement,
						updateKey,
						$( this ).val()
				);
			}
		});
	},
	getInputBox: function( config ){
		var _this = this;
		return $( '<div />' )
		.css({
			'float': 'left',
			'font-size': '12px',
			'border': 'solid thin #999',
			'background-color': '#EEE',
			'padding' : '2px',
			'margin' : '5px'
		})
		.addClass('ui-corner-all')
		.append(
			$('<span />')
			.css('margin', '5px')
			.text( config.title ),

			$('<input />')
			.attr( {
				'id' : config.inputId ,
				'size': config.inputSize
			})
			.data('initialValue', config.initialValue )
			.sequencerInput( _this.sequencer )
			.val( config.initialValue )
			.change( config.change )
		);
	}
};

} )( window.mw );
