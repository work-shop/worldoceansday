
$(document).ready( function() {

	acf.add_filter('color_picker_args', function( args, $field ){

		var brand = getComputedStyle(document.documentElement).getPropertyValue('--brand'); 
		var brandSecondary = getComputedStyle(document.documentElement).getPropertyValue('--brand-secondary'); 
		var dark = getComputedStyle(document.documentElement).getPropertyValue('--dark'); 
		var medium = getComputedStyle(document.documentElement).getPropertyValue('--medium'); 
		var light = getComputedStyle(document.documentElement).getPropertyValue('--light'); 
		var white = '#ffffff';

		args.palettes = [brand, brandSecondary, dark, medium, light, white];

		return args;

	});	

});

