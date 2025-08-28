$(document).ready(function(){
	window.addEventListener('scroll', function() {
        var logo = document.getElementById('logo');
        var header = document.getElementById('header');
        var scrollTop = $(this).scrollTop();

        // Cambia el tamaño de la imagen cuando el header llega a la parte superior de la página
        if (scrollTop == 0) {
            logo.classList.remove('sticky');
            header.classList.remove('stickyh');
        } else {
            logo.classList.add('sticky');
            header.classList.add('stickyh');
        }
    });

	$('.btn-up').click(function(){
		$('body, html').animate({
			scrollTop: '0px'
		}, 300);
	});

	$(window).scroll(function(){
	var st = $(this).scrollTop();
	if (st > 0){
		// downscroll code
		document.getElementById("wizard").style.display = "inline"
	}else{
		// upscroll code
		document.getElementById("wizard").style.display = "none"
	}
	});
});

function generarNuevoColor() {
	var letters = '0123456789ABCDEF';
	var color = '#';
	for (var i = 0; i < 6; i++) {
		color += letters[Math.floor(Math.random() * 16)];
	}
	return color;
}