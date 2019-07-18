var files;
$('input[type=file]').change(function(){
    files = this.files;
});
$('.submit.button').click(function( event ){
    event.stopPropagation(); // Остановка происходящего
    event.preventDefault();  // Полная остановка происходящего

    // Создадим данные формы и добавим в них данные файлов из files

    var data = new FormData();

    data.append('file', files[0]);
    data.append('f_name', $('#f_name').val());
    data.append('m_id', $('#m_id').val());

    // Отправляем запрос

    $.ajax({
        url: '/index.php/admin/meropriyatiya/imageupload',
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false, // Не обрабатываем файлы (Don't process the files)
        contentType: false, // Так jQuery скажет серверу что это строковой запрос
        success: function( respond, textStatus, jqXHR ){

            // Если все ОК

            if( typeof respond.error === 'undefined' ){
                // Файлы успешно загружены, делаем что нибудь здесь

                // выведем пути к загруженным файлам в блок '.ajax-respond'

                var files_path = respond.files;
                $('#myTable').show();
                $.each( files_path, function( key, val ){
                	$('#myTable > tbody:last-child').append('<tr id="tr_'+val['id']+'"><td>' + val['f_name'] + '</td><td>' + val['url'] +'</td><td><a onclick="delete_file('+$('#m_id').val()+', '+val['id']+')" href="javascript://">удалить</a></td></tr>');
                } )
            }
            else{
                console.log('ОШИБКИ ОТВЕТА сервера: ' + respond.error );
            }
        },
        error: function( jqXHR, textStatus, errorThrown ){
            console.log('ОШИБКИ AJAX запроса: ' + textStatus );
        }
    });
});

function delete_file(r_id, f_id){    var data = new FormData()
    data.append('id', r_id);
    data.append('f_id', f_id);

    $.ajax({
        url: '/index.php/admin/meropriyatiya/imagedelete',
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false, // Не обрабатываем файлы (Don't process the files)
        contentType: false, // Так jQuery скажет серверу что это строковой запрос
        success: function( respond, textStatus, jqXHR ){			$('#tr_'+respond.f_del_id).hide();
    	}, error: function( jqXHR, textStatus, errorThrown ){			console.log('ОШИБКИ AJAX запроса: ' + textStatus );
        }
    });
}