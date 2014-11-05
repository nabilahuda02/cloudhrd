;(function ($) {
    "use strict";

    var TaskHeading = function (options) {
        this.init('taskheading', options, TaskHeading.defaults);
    };
    $.fn.editableutils.inherit(TaskHeading, $.fn.editabletypes.abstractinput);
    $.extend(TaskHeading.prototype, {
        render: function() {
            this.$input = this.$tpl.find('input, select');
        },
        value2html: function(value, element) {
            if(!value) {
                $(element).empty();
                return; 
            }
            $(element).text(value.name); 
        },
        html2value: function(html) {        
            return null;  
        },
        value2str: function(value) {
            var str = '';
            if(value) {
                for(var k in value) {
                    str = str + k + ':' + value[k] + ';';  
                }
            }
            return str;
        }, 
        str2value: function(str) {
            return str;
        },                
        value2input: function(value) {
            if(!value) {
                return;
            }
            this.$input.filter('[name="name"]').val(value.name);
            this.$input.filter('[name="label"]').val(value.label);
        },        
        input2value: function() { 
            var values = {
                name: this.$input.filter('[name="name"]').val(), 
                label: this.$input.filter('[name="label"]').val()
            };
            this.$input.parents('.panel')
                .removeClass('panel-default')
                .removeClass('panel-success')
                .removeClass('panel-primary')
                .removeClass('panel-info')
                .removeClass('panel-warning')
                .removeClass('panel-danger')
                .addClass('panel-' + values.label);
            return values;
        },     
        activate: function() {
            this.$input.filter('[name="name"]').focus();
            $('.editable-buttons').append('<button type="button" class="btn btn-danger btn-sm editable-remove"><i class="glyphicon glyphicon-trash"></i></button>')
        },     
        autosubmit: function() {
            this.$input.keydown(function (e) {
                if (e.which === 13) {
                    $(this).closest('form').submit();
                }
            });
        }       
    });

TaskHeading.defaults = $.extend({}, $.fn.editabletypes.abstractinput.defaults, {
    tpl: '<div class="editable-taskheading"><label><span>Title: </span><input type="text" name="name" class="input-small"></label></div>'+
    '<div class="editable-taskheading"><label><span>Label: </span><select type="text" name="label" class="input-small form-control"><option value="default">Default</option><option value="primary">Primary</option><option value="success">Success</option><option value="info">Info</option><option value="warning">Warning</option><option value="danger">Danger</option></select></label></div>',
    inputclass: ''
});

$.fn.editabletypes.taskheading = TaskHeading;

}(window.jQuery));