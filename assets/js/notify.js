$.notify = function (options) {
	var settings = $.extend({
		type: 'info',
		title: 'Notificación',
		message: 'Lorem Ipsum is simply dummy text',
		delay: 5000,
		icon: '',
		showViewMore: false,
		textViewMore: 'Ver más',
		showDismiss: true,
		textDismiss: 'Descartar',
		onClick: () => { }
	}, options);

	// Colors
	let colorText, colorBorder, colorBorder2, colorBg, colorBg2, colorBg2Hover, colorRing;
	switch (settings.type) {
		case 'success':
			colorText = 'text-green-800';
			colorBorder = 'border-green-300';
			colorBorder2 = 'border-green-800';
			colorBg = 'bg-green-50';
			colorBg2 = 'bg-green-800';
			colorBg2Hover = 'hover:bg-green-900';
			colorRing = 'focus:ring-green-200';
			icon = 'ms-Icon ms-Icon--AcceptMedium';
			break;
		case 'warning':
			colorText = 'text-yellow-800';
			colorBorder = 'border-yellow-300';
			colorBorder2 = 'border-yellow-800';
			colorBg = 'bg-yellow-50';
			colorBg2 = 'bg-yellow-800';
			colorBg2Hover = 'hover:bg-yellow-900';
			colorRing = 'focus:ring-yellow-200';
			icon = 'ms-Icon ms-Icon--Warning';
			break;
		case 'danger':
			colorText = 'text-red-800';
			colorBorder = 'border-red-300';
			colorBorder2 = 'border-red-800';
			colorBg = 'bg-red-50';
			colorBg2 = 'bg-red-800';
			colorBg2Hover = 'hover:bg-red-900';
			colorRing = 'focus:ring-red-200';
			icon = 'ms-Icon ms-Icon--AlertSolid';
			break;
		case 'info':
			colorText = 'text-black';
			colorBorder = 'border-yellow-400';
			colorBorder2 = 'border-black';
			colorBg = 'bg-yellow-400';
			colorBg2 = 'bg-black';
			colorBg2Hover = 'hover:bg-black';
			colorRing = 'focus:ring-black';
			icon = 'ms-Icon ms-Icon--Info';
			break;
		default:
			colorText = 'text-black';
			colorBorder = 'border-yellow-400';
			colorBorder2 = 'border-black';
			colorBg = 'bg-yellow-400';
			colorBg2 = 'bg-black';
			colorBg2Hover = 'hover:bg-black';
			colorRing = 'focus:ring-black';
			icon = 'ms-Icon ms-Icon--Info';
			break;
	}

	const ramdomId = Math.floor(Math.random() * 1000);

	var $notify = $('<div>').addClass(`p-4 ${colorText} border ${colorBorder} rounded-lg ${colorBg} min-w-[10rem] max-w-[300px] relative overflow-hidden animate-slide-bounce`).append(
		$('<div>').addClass('flex items-center').append(
			$('<div>').addClass('flex-shrink-0 w-4 h-4 me-2 flex').append(
				settings.icon == '' ? $('<i>').addClass(icon + ' leading-none') : $('<i>').addClass(settings.icon + ' leading-none'),
			),
			$('<h3>').addClass('text-lg leading-none font-medium').text(settings.title)
		),
		$('<div>').addClass(`mt-2 ${(settings.showViewMore || settings.showDismiss) ? 'mb-4' : ''} text-sm`).append(
			$('<p>').html(settings.message)
		),
		(settings.showViewMore || settings.showDismiss) && $('<div>').addClass('flex').append(
			settings.showViewMore && $('<button>').attr('type', 'button').addClass(`text-white ${colorBg2} ${colorBg2Hover} focus:ring-4 focus:outline-none ${colorRing} font-medium rounded text-xs px-3 py-1.5 me-2 text-center inline-flex items-center`).text(settings.textViewMore).on('click', settings.onClick),
			settings.showDismiss && $('<button>').attr('type', 'button').addClass(`${colorText} bg-transparent border ${colorBorder2} ${colorBg2Hover} hover:text-white focus:ring-4 focus:outline-none ${colorRing} font-medium rounded text-xs px-3 py-1.5 text-center`).text(settings.textDismiss).on('click', function () {
				$notify.remove();
			})
		),
		$('<div>').attr('id', 'progress-bar-' + ramdomId).addClass('absolute bottom-0 left-0 w-full h-1 ' + colorBg2).css({
			width: '100%',
			transition: `width ${settings.delay}ms linear`
		})
	);

	let timeoutId = setTimeout(() => {
		$notify.remove();
	}, settings.delay);

	setTimeout(() => {
		$('#progress-bar-' + ramdomId).css({
			width: '0%',
			transition: `width ${settings.delay}ms linear` // Aplica la animación con duración
		});
	}, 10);

	$notify.on("mouseenter",
		function () {
			clearTimeout(timeoutId);
			$('#progress-bar-' + ramdomId).width($('#progress-bar-' + ramdomId).width()).css({
				transition: 'none'
			});
		},
	).on("mouseleave",
		function () {
			const remainingTime = parseFloat($('#progress-bar-' + ramdomId).css('width')) / $('#progress-bar-' + ramdomId).parent().width() * settings.delay;
			$('#progress-bar-' + ramdomId).css({
				width: '0%',
				transition: `width ${remainingTime}ms linear`,
			});
			timeoutId = setTimeout(() => {
				$notify.remove();
			}, remainingTime);
		}
	);

	var $notifyContainer = $('#notify-container');
	if ($notifyContainer.length === 0) {
		$notifyContainer = $('<div>', {
			id: 'notify-container',
			class: 'fixed bottom-[2rem] right-[2rem] z-[100000] flex flex-col items-end p-4 space-y-4'
		}).appendTo('body');
	}

	$notifyContainer.append($notify);
}

jQuery(document).ready(() => {
	// Insertar en el BODY una hoja de estilos para los estilos de las notificaciones
	$('body').append(`
		<style>
			@keyframes slide-bounce {
				0% {
					transform: translateX(100%);
				}
				80% {
					transform: translateX(-10px);
				}
				100% {
					transform: translateX(0);
				}
			}

			.animate-slide-bounce {
				animation: slide-bounce .1s ease-out;
			}
		</style>
	`);
})