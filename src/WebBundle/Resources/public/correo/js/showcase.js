$(document).ready(function() {
	hoverBotones(".contrasenas button");
});


function hoverBotones($selector) {
	$($selector).mouseover(function() {
		$(this).transition({ y: '-5px', opacity: 1 }, 600, 'ease');
	});
	$($selector).mouseleave(function() {
		$(this).transition({ y: '0px', opacity: 1 }, 600, 'ease');
	});
}