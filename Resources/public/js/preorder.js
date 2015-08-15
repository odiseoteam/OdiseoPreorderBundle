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
    }else
    {
        modalContent.html("Ha ocurrido un error");
    }

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