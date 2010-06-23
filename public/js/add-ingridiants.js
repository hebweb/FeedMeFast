document.addEvent('domready',function(){

	$('add-manu').addEvent('click',function(){
		new Element('dd').adopt(new Element('input',{'type':'text','name':'manufactors[]'})).inject(this,'before');
	});
	
});