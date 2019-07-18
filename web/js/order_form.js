function set_delivery_type()
    {
    type = $('#delivery_sel').val();
    if (type == 'self')
        {
        $('.field-orders-address_delivery').hide();
        }
    else
        {
        $('.field-orders-address_delivery').show();
        }
    }

function hide_promo_alert()
    {
    if ($('#pay_type_sel').val() === 'promo')
        {
        $(".alert").alert('close');
        }
    }

$('.address_item').click(function(){
    $('#orders-address_delivery').val( $( this ).text() );
    $('#delivery_list').modal('hide');
});