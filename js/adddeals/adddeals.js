Event.observe(window,'load',function(){
	
	var pathArray = window.location.pathname.split( '/' );
	var newPathname = "";
for ( i = 0; i < pathArray.length; i++ ) {

  	if( pathArray[i] == 'index.php')
	{
		break;
	}

  
  newPathname += pathArray[i];
  newPathname += "/";
}

		$$('#nav li span').each(function(s){
		var image_path = window.location.protocol+'//'+window.location.hostname+newPathname+'skin/frontend/default/grey_theme/images/side_sprite.png';							 
			
			if(s.innerHTML == 'Contus GroupClone'){
				var classgpc = s.up(1).getAttribute('class');
				s.up(0).setAttribute('class','groupbuying'+classgpc);
				
				s.innerHTML ='<img src="'+image_path+'" />' }			
		});
	});