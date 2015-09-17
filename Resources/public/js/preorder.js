// Send contract
var showSendContractModalSuccess = function(data)
{
    var modalContent = $('#sendContractModal .modal-body');
    if (data.error == false)
    {
        modalContent.html(data.html);
    }else
    {
        modalContent.html("Ha ocurrido un error");
    }
};

var sendContract = function()
{
    $('.new_price').val(  $('.new_price').val()*100 );
    var modalContent = $('#sendContractModal .modal-body');
    var loadingContent = $('#sendContractModal .loading-content');
    modalContent.append(loadingContent.html());
    $('#odiseo_preorder_send_contract_form').hide();

    $("#odiseo_preorder_send_contract_form").ajaxSubmit({
        success: showSendContractModalSuccess
    });

    return false;
};

// Show send contract
var showSendContractModalSuccess = function(data)
{
    var modalContent = $('#sendContractModal .modal-body');
    if (data.error == false)
    {
        modalContent.html(data.html);
        $('.edit_price').click(function( event){
            event.stopPropagation();
            $('.contract_price').hide();
            $('.new_price').show();
            $('.new_price').val( $('.new_price').val().substring(1));

        });
    }else
    {
        modalContent.html("Ha ocurrido un error");
    }
    $('.terms_and_conditions').click(function(event){
        if (this.checked) {
            $( document ).trigger( "product:creation:domReady", [ ] );
        }
    });
    $('#odiseo_preorder_send_contract_form').submit(sendContract);
};

var showSendContractModal = function()
{
   $('#sendContractModal').modal('show');
    var form = $("form[name='odiseo_preorder_show_send_contract']");
    form.ajaxSubmit({
        success: showSendContractModalSuccess

    });

    return false;
};

// Show pay contract
var showPayContractModalSuccess = function(data)
{
    var modalContent = $('#payContractModal .modal-body');
    if (data.error == false)
    {
        modalContent.html(data.html);
    }else
    {
        modalContent.html("Ha ocurrido un error");
    }
};

var showPayContractModal = function()
{
    $('#payContractModal').modal('show');
    var form = $("form[name='odiseo_preorder_show_pay_contract']");
    form.ajaxSubmit({
        success: showPayContractModalSuccess
    });

    return false;
};

$(function()
{
    $("#odiseo_preorder_show_vendor_buttons_contract").click(showSendContractModal);
    $("#odiseo_preorder_show_buyer_buttons_pay").click(showPayContractModal);
});