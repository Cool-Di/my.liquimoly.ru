var files;
$('input[type=file]').change(function(){
    files = this.files;
});
$('.submit.button').click(function( event ){
    event.stopPropagation(); // ��������� �������������
    event.preventDefault();  // ������ ��������� �������������

    // �������� ������ ����� � ������� � ��� ������ ������ �� files

    var data = new FormData();
    $.each( files, function( key, value ){
        data.append( key, value );
    });

    // ���������� ������

    $.ajax({
        url: './submit.php?uploadfiles',
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false, // �� ������������ ����� (Don't process the files)
        contentType: false, // ��� jQuery ������ ������� ��� ��� ��������� ������
        success: function( respond, textStatus, jqXHR ){

            // ���� ��� ��

            if( typeof respond.error === 'undefined' ){
                // ����� ������� ���������, ������ ��� ������ �����

                // ������� ���� � ����������� ������ � ���� '.ajax-respond'

                var files_path = respond.files;
                var html = '';
                $.each( files_path, function( key, val ){ html += val +'<br>'; } )
                $('.ajax-respond').html( html );
            }
            else{
                console.log('������ ������ �������: ' + respond.error );
            }
        },
        error: function( jqXHR, textStatus, errorThrown ){
            console.log('������ AJAX �������: ' + textStatus );
        }
    });
});