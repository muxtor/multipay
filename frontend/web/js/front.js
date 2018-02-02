$(document).ready(function(){
    
    filterSetting = {
        sort: '',
        country_id: '',
        country_name: 'Все страны',
        queryString: ''
    };
    
    $('#close-step-three').click(function(){
        window.location.href = "/";
    });

    $('input[name="PayPlanned[pp_type]"]:checked').prop('checked', false);

    $('input[name="PayPlanned[pp_type]"]').on('click', function(){
        var sel_val = $(this).val();
        console.log(sel_val);
        if (sel_val == 1) {
            $('.b-planed-days-wrapper').hide();
            $('#pay_planned_time').hide();
            $('#pay_planned_datetime').show();
        } else if (sel_val == 7) {
            $('.b-planed-days-wrapper').show();
            $('#pay_planned_datetime').hide();
            $('#payplanned-days_of_month').hide();
            $('#pay_planned_time').show();
            $('#payplanned-days_of_week').show();
        } else if (sel_val == 31) {
            $('.b-planed-days-wrapper').show();
            $('#pay_planned_datetime').hide();
            $('#payplanned-days_of_week').hide();
            $('#pay_planned_time').show();
            $('#payplanned-days_of_month').show();
        } else {
            $('#pay_planned_time').hide();
            $('.b-planed-days-wrapper').hide();
            $('#pay_planned_datetime').show();
        }
    });

    $('#payplanned-pp_is_notif').on('click', function(){
        if ($(this).is(':checked')) {
            $('#payplanned-pay_notif_day_amount').prop('disabled', false);
        } else {
            $('#payplanned-pay_notif_day_amount').prop('disabled', true).val('');
        }
    });
    $('form#pay-plan-add, form#pay-plan-update').on('submit', function(){
        var planned_type = $('input[name="PayPlanned[pp_type]"]:checked', this).val();
        if (planned_type != 1 && planned_type != 7 && planned_type != 31) {
            alert ('You have to choose planned period!');
            return false;
        }
        if (planned_type == 1) {
            var planned_date = $('#plan-datetime').val();
            if (planned_date == false) {
                alert ('You have to choose date and time!');
                return false;
            }
        } else if (planned_type == 7) {
            var planned_time = $('#plan-time').val();
            if (planned_time == false) {
                alert ('You have to choose time!');
                return false;
            }
            var choosen_days_of_week = $('#payplanned-days_of_week input:checkbox:checked');
            if (choosen_days_of_week.length == 0) {
                alert ('You have to choose days of week!');
                return false;
            }
        } else if (planned_type == 31) {
            var planned_time = $('#plan-time').val();
            if (planned_time == false) {
                alert ('You have to choose time!');
                return false;
            }
            var choosen_days_of_month = $('#payplanned-days_of_month input:checkbox:checked');
            if (choosen_days_of_month.length == 0) {
                alert ('You have to choose days of month!');
                return false;
            }
        }
        if ($('#payplanned-pp_is_notif').is(':checked') && $('#payplanned-pay_notif_day_amount').val() == false) {
            alert ('You have to choose days for notifications!');
            return false;
        }
        return true;
    });

    $('#payplanned-pay_notif_day_amount').keyup(function(){
        var amount = $(this).val();
        //ввели целое полжительное число
        if ((amount ^ 0) !== parseInt(amount) || amount == 0 || (amount+"").indexOf(".") != -1) {
            $(this).val('');
        }
    });

    $('#planned_once, #planned_week, #planned_month').on('click', function(){
        $('ul.b-planed-nav li a').removeClass('b-active');
        $(this).addClass('b-active');
        $.ajax({
            url: '/pay-planned/choose-period',
            type: 'post',
            data: {period: $(this).attr('data')},
            success: function (data) {
                $('#planned-payments-list').html(data);
            }
        });
        return false;
    });

    //валюта на первом шаге оплаты
    $('span.b-currency').html($('#payment-currency').val());
    if ($('#payments-pay_system').val() == 'WALLET') {
        $('.b-payment-fav-nav a.b-planed-icon').show();
    } else {
        $('.b-payment-fav-nav a.b-planed-icon').hide();
    }
    $('#payments-pay_system').on('change', function(){
        var selected_currency = $(this).val();
        if (selected_currency == 'WMR') {
            $('#payment-currency').val('RUB');
            $('span.b-currency').html('RUB');
            $('.b-payment-fav-nav a.b-planed-icon').hide();
        } else if (selected_currency == 'WALLET') {
            $('#payment-currency').val('AZN');
            $('span.b-currency').html('AZN');
            $('.b-payment-fav-nav a.b-planed-icon').show();
        } else if (selected_currency == 'WMZ') {
            $('#payment-currency').val('USD');
            $('span.b-currency').html('USD');
            $('.b-payment-fav-nav a.b-planed-icon').hide();
        } else {
            $('#payment-currency').val('');
            $('span.b-currency').html('');
            $('.b-payment-fav-nav a.b-planed-icon').hide();
        }
    });
    //изменение валюты в шаблонах
    $('span#pay-template-currency').html($('#payment-template-currency').val());
    $('#paytemplate-pt_system').on('change', function(){
        var selected_currency = $(this).val();
        if (selected_currency == 'WMR') {
            $('#payment-template-currency').val('RUB');
            $('span#pay-template-currency').html('RUB');
        } else if (selected_currency == 'WALLET') {
            $('#payment-template-currency').val('AZN');
            $('span#pay-template-currency').html('AZN');
        } else if (selected_currency == 'WMZ') {
            $('#payment-template-currency').val('USD');
            $('span#pay-template-currency').html('USD');
        } else {
            $('#payment-template-currency').val('');
            $('span#pay-template-currency').html('');
        }
    });

    $('#provider-search-form').on('submit', function(){
        var query = $('#provider-search-input').val();
//        if (query == false) {
//            alert('You should enter minimum 1 character');
//            return false;
//        }
        SendSearch(query);
        return false;
    });
    $('#provider-search-form a.b-search-icon').on('click', function(){
        var query = $('#provider-search-input').val();
//        if (query == false) {
//            alert('You should enter minimum 1 character');
//            return false;
//        }
        SendSearch(query);
        return false;
    });

});


function ChooseProviders(id, self){
    $.ajax({
        url: '/providers/choose-providers',
        type: 'post',
        data: {p_id: id},
        success: function (data) {
            $('.b-side-bar').next().remove();
            $('.b-side-bar').after('<div class="b-categorys"></div>');
            $('.b-categorys').html(data);
            $('.b-categorys ul li:first a').attr('onclick','ChooseProviders('+id+', this)');
        }
    });
    return false;
}
function ChooseProvidersList(id){
    $.ajax({
        url: '/providers/choose-providers',
        type: 'post',
        data: {p_id: id},
        success: function (data) {
            $('.b-side-bar').next().remove();
            $('.b-side-bar').after('<div class="b-categorys"></div>');
            $('.b-categorys').html(data);
            $('.b-categorys ul li:first a').attr('onclick','ChooseProviders('+id+', this)');
        }
    });
    return false;
}
function HomeProviders(){
    $.ajax({
        url: '/providers/home-providers',
        type: 'get',
        data: {},
        success: function (data) {
            $('.b-categorys').html(data);
        }
    });
    return false;
}
function ChangeHistory(id){
    $('.b-home-back').attr('onclick','ChooseProviders('+id+', this)');
}
function ChangeHome(){
    $('.b-home-back').attr('onclick','HomeProviders();');
}

function SendSearch(queryString){
    filterSetting.queryString = queryString;
    GetProvidersContent();
    return false;
}

function ChooseProvidersListCountry(id,name){
    filterSetting.country_id = id;
    filterSetting.country_name = name;
    filterSetting.cfrom = $('.b-categorys ul li:first a').attr('onclick');
    GetProvidersContent();
    return false;
}

function ChooseProvidersListAll(sort){
    filterSetting.sort = sort;
    GetProvidersContent();
    return false;
}

function GetProvidersContent(){
    $.ajax({
        url: '/providers/choose-all-providers',
        type: 'post',
        data: filterSetting,
        success: function (data) {
            $('.b-side-bar').next().remove();
            $('.b-side-bar').after('<div class="b-categorys"></div>');
            $('.b-categorys').html(data);
            $('#country-filter-heder').html(filterSetting.country_name);
            
        }
    });
    return false;
}

function AddPaymentTemplate(id, type)
{
    if (type == 'template') {
        var link = '/pay-templates/add-template';
    } else if (type == 'planned') {
        var link = '/pay-planned/add-planned-data';
    } else {
        alert('Internal Error');
        return false;
    }
    var account = $('#payments-pay_pc_provider_account').val();
    var currency = $('#payment-currency').val();
    var pay_system = $('#payments-pay_system').val();
    var amount = $('#payments-pay_summ').val();
    if (account == false && currency == false && amount == false) {
        alert('Nothing to save');
        return false;
    }
    $.ajax({
        url: link,
        type: 'post',
        data: {id: id, account: account, currency: currency, amount: amount, pay_system: pay_system},
        success: function (data) {
            alert(data.message);
        }
    });
    return false;
}