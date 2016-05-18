
var decimal_places = parseInt(app_locale.decimal_places);
var divisor = Math.pow(10, decimal_places);
var targettbody = $('#targettbody');
var calculateTotal = function() {
    var total = 0;
    $('.amount_col').each(function(){
        total += Math.round(parseFloat($(this).text()) * divisor) / divisor;
    });
    $('#value').val(total.toFixed(decimal_places));
};
$(document).on('click', '.removerow', function(){
        var target = $(this);
    bootbox.confirm('Are your sure you want to remove this row?', function(val){
        if(val) {
            target.parents('tr').remove();
            calculateTotal();
        }
    })
});
bootbox.form = function(template, callback) {
    var modal = bootbox.confirm(template, function(res){
        if(!res) {
            callback(null);
        }
    });
    var form = $('form', modal).on('submit', function(e){
        e.preventDefault();
        return false;
    });
    $('[data-bb-handler="confirm"]').on('click', function(e){
        var isValid = true;
        $('input,textarea,select', form).each(function(index, field){
            if(!field.checkValidity()) {
                $(field).trigger('invalid');
                isValid = false;
            }
        });
        if(isValid) {
            callback(form.serializeJSON());
            return;
        }
        e.preventDefault();
        return false;
    });
}
$(document).on('click', '#newrow', function() {
    bootbox.form($('#claimform').html(), createNewRow);
    init_datepicker();
    var amountRow = $('#form_amount').parents('.form-group');
    var amountInput = $('input', amountRow);
    var resetForm = function() {
        $('.quantities').each(function(idx, quantity){
            $(quantity).parents('.form-group').hide();
        });
        amountRow.hide();
        amountInput.attr({
            readonly: true
        }).val('');
    }
    $('#form_type').change(function(){
        var sel = $(this);
        var type = sel.val();
        resetForm();
        if(type) {
            amountRow.show();
            if($('#form_quantity_' + type).length) {
                var quantityRow = $('#form_quantity_' + type)
                    .parents('.form-group')
                    .show();
                $('#receipt_number')
                    .attr('required', null)
                    .parents('.form-group')
                    .hide();
                var quantityInput = $('input', quantityRow)
                    .attr('required', true)
                    .keyup(function(e){
                        var target = $(this);
                        var val = parseFloat(target.val());
                        if(!val || isNaN(val)) {
                            amountInput.val(0);
                            this.checkValidity();
                            e.preventDefault();
                            return false;
                        }
                        amountInput.val((Math.round(val * parseFloat(quantityInput.data('unitprice')) * divisor) / divisor).toFixed(decimal_places))
                    });
            } else {
                $('#receipt_number')
                    .attr('required', true)
                    .parents('.form-group')
                    .show();
                $('.quantities').removeAttr('required');
                amountInput.removeAttr('readonly');
            }
        } else {
            amountRow.hide();
        }
    }).trigger('change');
});