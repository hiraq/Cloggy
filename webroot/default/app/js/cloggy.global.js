/*
 * Cloggygy javascript loaders
 */ 
function CloggyYepNope() {		
	this.bootstrapJs;
	this.jquery;	
}

CloggyYepNope.prototype.setHost = function(host) {	
	this.bootstrapJs = host.bootstrapJs;
	this.jquery = host.jquery;		
};

CloggyYepNope.prototype.main = function(completeHandler) {
		
	var bootstrapjs = this.bootstrapJs;
	var jquery = this.jquery;		
		
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

CloggyYepNope.prototype.captureJQuery = function(handler) {
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
