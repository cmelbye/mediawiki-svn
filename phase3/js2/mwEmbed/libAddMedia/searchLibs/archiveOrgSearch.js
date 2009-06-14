//archive.org uses solr engine: 
//more about solr here: 
//http://lucene.apache.org/solr/

var archiveOrgSearch = function ( iObj){
	return this.init( iObj );
}
archiveOrgSearch.prototype = {
	//archive.org constants: 
	dnUrl:'http://www.archive.org/download/',
	dtUrl:'http://www.archive.org/details/',
	init:function( iObj ){
		//init base class and inherit: 
		var baseSearch = new baseRemoteSearch( iObj );
		for(var i in baseSearch){
			if(typeof this[i] =='undefined'){
				this[i] = baseSearch[i];
			}else{
				this['parent_'+i] =  baseSearch[i];
			}
		}
		//inherit the cp settings for 
	},
	getSearchResults:function(){
		//call parent: 
		this.parent_getSearchResults();
		
		var _this = this;		
		this.loading=true;
		js_log('f:getSearchResults for:' + $j('#rsd_q').val() );		
		//build the query var
		var q = $j('#rsd_q').val();
		//@@todo check advanced options: include audio and images media types
		//for now force (Ogg video) & a creativecommons license
		q+=' format:(Ogg video)';
		q+=' licenseurl:(http\\:\\/\\/*)';
		var reqObj = {
			'q': q, //just search for video atm
			'fl':"description,title,identifier,licenseurl,format,license,thumbnail",			
			'wt':'json',			
			'rows':'30',
			'indent':'yes'			
		}									
		do_api_req( {
			'data':reqObj, 
			'url':this.cp.api_url,
			'jsonCB':'json.wrf'
			}, function(data){				
				_this.addResults( data);
				_this.loading = false;
			}
		);
	},
	addResults:function( data ){		
		var _this = this;			
		if(data.response && data.response.docs){
			//set result info: 
			this.num_results = data.response.numFound;
		
			for(var resource_id in data.response.docs){
				var resource = data.response.docs[resource_id];				
				var rObj = {
					//@@todo we should add .ogv or oga if video or audio:
					'titleKey'	 :  resource.identifier + '.ogg',
					'resourceKey':  resource.identifier,				
					'link'		 : _this.dtUrl + resource.identifier,				
					'title'		 : resource.title,
					'poster'	 : _this.dnUrl + resource.identifier+'/format=thumbnail',
					'poster_ani' : _this.dnUrl + resource.identifier+'/format=Animated+Gif',
					'thumbwidth' : 160,
					'thumbheight': 110,			
					'desc'		 : resource.description,
					'src'		  : _this.dnUrl + resource.identifier+'/format=Ogg+video',
					'mime'		  : 'application/ogg',
					//set the licence: (rsd is a pointer to the parent remoteSearchDriver )		 
					'license'	  : this.rsd.getLicenceFromUrl( resource.licenseurl ),
					'pSobj'		 :_this				
					
				};																										 
				this.resultsObj[ resource_id ] = rObj;
				
				//likely a audio clip if no poster and type application/ogg 
				//@@todo we should return audio/ogg for the mime type or some other way to specify its "audio" 
						
				//this.num_results++;	
				//for(var i in this.resultsObj[page_id]){
				//	js_log('added: '+ i +' '+ this.resultsObj[page_id][i]);
				//}
			}
		}		
	},
	getEmbedTimeMeta:function(rObj, callback){
		var _this = this;
		do_api_req( {
			'data':{'avinfo':1},
			'url':_this.dnUrl + rObj.resourceKey + '/format=Ogg+video'
		},function(data){			
			var cat = data;
			if(data['length'])
				rObj.duration = data['length'];
			if(data['width'])
				rObj.width = data['width'];
			if(data['height'])
				rObj.height = data['height'];
								   
			callback();
		});
	},
	getEmbedHTML: function( rObj , options) {
		js_log('getEmbedHTML:: ' + rObj.poster );
		var id_attr = (options['id'])?' id = "' + options['id'] +'" ': '';
		var src = rObj.src + '?t=0:0:0/'+ seconds2npt( rObj.duration );
		if(rObj.mime == 'application/ogg' || rObj.mime == 'audio/ogg' || rObj.mime=='video/ogg'){
			return '<video ' + id_attr + ' src="' + src + '" poster="' + rObj.poster + '" type="video/ogg"></video>';
		}
	}
}