/*
 * Clog javascript loaders
 */ 
function ClogYepNope() {	
	this.bootstrapCss;
	this.bootstrapJs;
	this.jquery;	
}

ClogYepNope.prototype.setHost = function(host) {
	this.bootstrapCss = host.bootstrap;
	this.bootstrapJs = host.bootstrapJs;
	this.jquery = host.jquery;		
};

ClogYepNope.prototype.main = function(completeHandler) {
	
	var bootstrap = this.bootstrapCss;
	var bootstrapjs = this.bootstrapJs;
	var jquery = this.jquery;		
	
	yepnope.injectCss(bootstrap);
	yepnope({
		load : {
			'jquery' : jquery,
			'bootstrap.js' : bootstrapjs
		},		
		complete: function() {
			
			if(typeof(completeHandler) == 'function') {
				completeHandler();							
			}						
			
		}
	});
	
};

ClogYepNope.prototype.captureJQuery = function(handler) {
	var obj = this;
	yepnope({
		'load': obj.jquery,
		complete: function() {
			jQuery(document).ready(function() {
				if(typeof(handler) == 'function') {
					handler();
				}
			});
		}
	});
};
