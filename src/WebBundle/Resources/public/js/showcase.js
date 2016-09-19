$(document).ready(function() {

	$("#carrusel").cycle({
		fx: 'fadeout',
		slides: 'img',
		swipe: true,
		timeout: 3000,
		speed:  2500
	});
	
	$("section.vistaEstable div.carrusel").cycle({
		fx: 'fadeout',
		slides: 'img',
		swipe: true,
		timeout: 3000,
		speed:  2500
	});

	$("#header a.menumovil").click(function() {
		$("#menuPrincipal").slideToggle();
		return false;
	});

	if ($("#header a.menumovil").is(":visible")) {
		$("#menuPrincipal").slideUp();
	}

	$("section.categorias a.submenumovil").click(function() {
		$("section.categorias ul.menu").slideToggle();
		return false;
	});

	if ($("section.categorias a.submenumovil").is(":visible")) {
		$("section.categorias ul.menu").slideUp();
	}

	hoverLista("div.contenedor ul.establecimientos li", "img", "h3");
	hoverLista("div.destacados ul.articulos li", "img", "div.informacion");
	hoverLista("div.contArticulos ul.articulos li", "img", "div.informacion");	

	hoverRedes("facebook");
	hoverRedes("twitter");
	hoverRedes("instagram");

	hoverBotones("div.franjaverde div.informacion a");
	hoverBotones("div.contenedor ul.paginador li a");
	hoverBotones("#contacto button");
	hoverBotones("#planes li a.meInteresa");
	hoverBotones("section.vistaPedido .confirmacion a");
	hoverBotones("section.vistaEstable div.redes ul li.facebook a");
	hoverBotones("section.vistaEstable div.redes ul li.twitter a");
	hoverBotones("section.vistaPedido form.pedido div.contDer a.ingFacebook");
	hoverBotones("section.vistaPedido form.pedido div.contDer a.ingShowcase");
	hoverBotones("form.pedido div.contDer button");
	hoverBotones("div.reservas a.pedir");
	hoverBotones("#login button");

	$("#header div.centrar div.contInf a.reservas").click(function() {
		$("#header div.centrar div.contInf div.reservas").slideToggle();
		return false;
	});

	$("section.vistaPedido form.pedido div.contDer button").click( function() {
		$("section.vistaPedido form.pedido").hide();
		$("section.vistaPedido .confirmacion").show();
		smoothScroll.animateScroll(null, '#marcaPedido');
		return false;
	});

	//despliega el enlace de cerrar sesión
	var $enlaceCerrar = $("#header div.centrar div.contSup .usuario a.salir");
	$("#header div.centrar div.contSup .usuario .flecha").hover(function() {
		$enlaceCerrar.show();
	});
	$enlaceCerrar.mouseleave(function() {
		$(this).hide();
	});
	$("#header div.centrar div.contSup .usuario .flecha").click(function() {		
		if(!$enlaceCerrar.hasClass('oculto')){
			$enlaceCerrar.show().addClass('oculto');
			$enlaceCerrar.show();
		} else {
			$enlaceCerrar.show().removeClass('oculto');
			$enlaceCerrar.hide();
		}
	});

	// Selector con el cual se hace el llamado a la ventana de cancelar pedido
	var $enlaceCancelar = $("section.vistaPedido form.pedido div.contDer a.cancelar");
	//Llama a la ventana modal
	$enlaceCancelar.click(function() {
		$string='<div class="cancelar"><p>¿Desea cancelar este pedido?</p><div class="enlaces"><a href="index.html">Si</a><a href="">No</a></div></div>';

		modalCancelar($string);
		return false;
	});
	// Selector con el cual se hace el llamado a la ventana de iniciar sesion
	var $enlaceSesion = $("section.vistaPedido form.pedido div.contDer a.ingShowcase");
	//Llama a la ventana modal
	$enlaceSesion.click(function() {
		$string1='<div class="login"><h2>Iniciar sesión</h2><form class="login" action="" method="post"><div class="form"><label for="email" >Correo electrónico</label><input id="email" type="email" placeholder="Correo electrónico" /></div><div class="form"><label for="password">Contraseña</label><input id="password" type="password" placeholder="Contraseña" /></div><div class="form enlace"><a href="#">¿Olvidó su contraseña?</a><button type="submit">Ingresar</button></div></form></div>';

		modalLogin($string1);
		return false;
	});



});




function hoverRedes($red) {
	$("#redes li." + $red + "").mouseover(function() {
		$(this).transition({ y: '-10px', opacity: 1 }, 600, 'ease');
	});
	$("#redes li." + $red + "").mouseleave(function() {
		$(this).transition({ y: '0px', opacity: 1 }, 600, 'ease');
	});
}
function hoverBotones($selector) {
	$($selector).mouseover(function() {
		$(this).transition({ y: '-5px', opacity: 1 }, 600, 'ease');
	});
	$($selector).mouseleave(function() {
		$(this).transition({ y: '0px', opacity: 1 }, 600, 'ease');
	});
}
function hoverLista($sel, $chil1, $chil2) {
	$($sel).mouseenter(function() {
		$(this).children($chil1).transition({ y: '-20px', opacity: 1 }, 600, 'ease');
		$(this).children($chil2).transition({ y: '-20px', opacity: 1 }, 600, 'ease');
	});
	$($sel).mouseleave(function() {
		$(this).children($chil1).transition({ y: '0px', opacity: 1 }, 600, 'ease');
		$(this).children($chil2).transition({ y: '0px', opacity: 1 }, 600, 'ease');
	});
}
function estrellas() {
	/*
	votar.php es el nombre del script que va a capturar el voto usando POST
	maxvalue: es la cantidad de estrellas
	curvalue: es el valor actual (opcional)
	id: es el identificador (opcional)
	*/
	$('#star').rating('votar.php', {maxvalue: 5, curvalue:2, id:10});
}
// Función que muestra la ventana modal de cancelar el pedido
function modalCancelar(cadena) {
	/*
	El colorbox puede mostrar contenido:
	de otra pagina con los atributos "href" y "iframe"
	de la misma página con "inline"
	por javascript con "html"
	Para más información consulte http://www.jacklmoore.com/colorbox/
	*/
	if($(window).width() <= 480) {
		$.colorbox({
			width: "300px", // Ancho de la ventana puede se auto, un % o un tamaño fijo
			height: "210px", // Alto de la ventana puede se auto, un % o un tamaño fijo
			html: cadena // html que se muestra en la ventana
		});
	} else {
		$.colorbox({
			width: "340px", // Ancho de la ventana puede se auto, un % o un tamaño fijo
			height: "210px", // Alto de la ventana puede se auto, un % o un tamaño fijo
			html: cadena // html que se muestra en la ventana
		});
	}
	hoverBotones("div.cancelar div.enlaces a");
}

function modalLogin(cadena) {

	if($(window).width() <= 480) {
		$.colorbox({
			width: "300px", // Ancho de la ventana puede se auto, un % o un tamaño fijo
			height: "360px", // Alto de la ventana puede se auto, un % o un tamaño fijo
			html: cadena // html que se muestra en la ventana
		});
	} else {
		$.colorbox({
			width: "550px", // Ancho de la ventana puede se auto, un % o un tamaño fijo
			height: "390px", // Alto de la ventana puede se auto, un % o un tamaño fijo
			html: cadena // html que se muestra en la ventana
		});
	}
	hoverBotones("div.login form.login div.enlace button");
}