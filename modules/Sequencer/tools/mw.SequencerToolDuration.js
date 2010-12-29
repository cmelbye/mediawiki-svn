/**
 * Extend the base tools prototype with duration attribute edit: 
 */
$j.extend( mw.SequencerTools.prototype, {
	tools : {
		'duration':{
			'editableAttributes' : [ 'dur' ],
			'contentTypes': ['img', 'cdata_html', 'mwtemplate']
		}
	},
	editableAttributes : {
		'dur' :{
			'type': 'time',
			'title' : gM('mwe-sequencer-clip-duration' )
		}
	}
});