// JavaScript Document

jQuery(document).ready(function(){
	
	jQuery('.toggle').click(function(){
		var id = jQuery(this).attr('id');
		if( jQuery("#"+id).is(':checked') ) {
			jQuery( "#"+id+'-p' ).removeClass( "check-no" );
			jQuery( "#"+id+'-p' ).addClass( "check-yes" );
			jQuery( "#"+id+'-p' ).html( "Yes" );
		} else {
			jQuery( "#"+id+'-p' ).removeClass( "check-yes" );
			jQuery( "#"+id+'-p' ).addClass( "check-no" );
			jQuery( "#"+id+'-p' ).html( "No" );
		}
	});
});