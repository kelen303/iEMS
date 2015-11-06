window.addEvent('domready', function(){
if($('randomtext')){
	var url = 'randomtext.php';
	// refresh time (in seconds)
	var timer = 8; 
	var periodical, dummy; 
	log = $('randomtext');
	var fx = new Fx.Styles(log, {duration:600, wait:false});
	var ajax = new Ajax(url, { 
		update: log,
		method: 'get',
		onRequest:function(){
			fx.start({
				'margin-left': [0,200]
			});	
			},
		onComplete: function() {
			log.setStyle('margin-left',-200);
			fx.start({
				'margin-left': [-200,0]
			});	
		}
	});
	 
	var refresh = (function() {
		dummy = $time() + $random(0, 100);
		ajax.request(dummy); 
	}); 
	 
	periodical = refresh.periodical(timer * 1000, this); 
	ajax.request($time()); 
}
});