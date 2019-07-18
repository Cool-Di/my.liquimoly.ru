function AddBasket(code, value){
	$.ajax({
	  method: "POST",
	  url: "/index.php/ajax/addbasket",
	  data: { code: code, count: value }
	}).done(function(data) {
		if (data.addbasket == 'ok'){
	        $('#basket_count').html(data.b_count);
    	    p = $( document.getElementById('count_'+code) ).offset();
			$('.information_basket').css('top', p.top+30);
			$('.information_basket').css('left', p.left);
			$('#i_code').html(code);
			$('#i_count').html(value);
    	    $('.information_basket').css('display', 'table');
		}
	});
}

function UpdateBasket(){
	data_send = {};
	$.map($('.p_count'), function(value){
		key = $(value).attr('art_id');
		val = $(value).val();
		data_send[key] = val;
	});
	$.ajax({
	  method: "POST",
	  url: "/index.php/ajax/updatebasket",
	  data: { data: data_send }
	}).done(function(data) {
		if (data.addbasket == 'ok'){
			location.reload();
		}
	});
}

function UpdateOrder(order_id){
	data_send = {};
	$.map($('.p_count'), function(value){
		key = $(value).attr('art_id');
		val = $(value).val();
		data_send[key] = val;
	});
	$.ajax({
	  method: "POST",
	  url: "/index.php/ajax/updateorder",
	  data: { data: data_send, order_id: order_id}
	}).done(function(data) {
		if (data.updateorder == 'ok'){
			location.reload();
		}
	});
}

jQuery(function($){
	$(document).mouseup(function (e){ // событие клика по веб-документу
		var div = $(".information_basket"); // тут указываем ID элемента
		if (!div.is(e.target) // если клик был не по нашему блоку
		    && div.has(e.target).length === 0) { // и не по его дочерним элементам
			div.hide(); // скрываем его
		}
	});
});