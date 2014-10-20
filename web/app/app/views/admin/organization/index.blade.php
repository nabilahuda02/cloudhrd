@extends('layouts.module')
@section('content')
    <div class="col-md-10 col-sm-8">
        @include('html.notifications')
        <div class="page-header">
            <h3>Organization Config</h3>
        </div>
        {{Former::populate($master_user)}}
        {{Former::open_horizontal(action('AdminOrganizationController@index'))}}
            {{ Former::email('support_email')
                ->label('Support Email')
                ->required() }}
            <div class="form-group required" id="currency-format">
                <label class="control-label col-lg-2 col-sm-4">Localization<sup>*</sup></label>
                <div class="col-lg-10 col-sm-8">
                    <div class="row">
                        <div class="col-sm-4">
                            <b>Decimal Places</b>
                            <input class="form-control" required="true" id="decimal_places" type="number" name="decimal_places" placeholder="Decimal Places" value="{{$locale->decimal_places}}">
                        </div>
                        <div class="col-sm-4">
                            <b>Decimal Separator</b>
                            <input class="form-control" required="true" id="decimal_separator" type="text" name="decimal_separator" placeholder="Decimal Separator" value="{{$locale->decimal_separator}}">
                        </div>
                        <div class="col-sm-4">
                            <b>Thousand Separator</b>
                            <input class="form-control" required="true" id="thousand_separator" type="text" name="thousand_separator" placeholder="Thousand Separator" value="{{$locale->thousand_separator}}">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-4">
                            <b>Currency Symbol</b>
                            <input class="form-control" required="true" id="currency_symbol" type="text" name="currency_symbol" placeholder="Currency Symbol" value="{{$locale->currency_symbol}}">
                        </div>
                        <div class="col-sm-4">
                            <b>Symbol Location</b>
                            <div class="clearfix"></div>
                            <label for="before" class="radio-inline">
                                <input type="radio" name="symbol_location" value="before" id="before" {{($locale->symbol_location === 'before' ? 'checked' : '')}}> Before
                            </label>
                            <label for="after" class="radio-inline">
                                <input type="radio" name="symbol_location" value="after" id="after" {{($locale->symbol_location === 'after' ? 'checked' : '')}}> After
                            </label>
                        </div>
                        <div class="col-sm-4">
                            <b>Distance</b>
                            <div class="clearfix"></div>
                            <label for="km" class="radio-inline">
                                <input type="radio" name="distance" value="km" id="km" {{($locale->distance === 'km' ? 'checked' : '')}}> KM
                            </label>
                            <label for="mi" class="radio-inline">
                                <input type="radio" name="distance" value="mi" id="mi" {{($locale->distance === 'mi' ? 'checked' : '')}}> Miles
                            </label>
                        </div>
                    </div>
                    <div class="help-block">
                        <b>Sample Output: <span id="sample-output"></span></b>
                    </div>
                </div>
            </div>
            <div class="well">
                <button class="pull-right btn btn-primary">Submit</button>
                <div class="clearfix"></div>
            </div>
        {{Former::close()}}
    </div>
@stop
@section('script')
    <script>
    new (Backbone.View.extend({
        events: {
            'keyup input': 'update_sample',
            'change input': 'update_sample'
        },
        update_sample: function() {
            if($('[name=symbol_location]:checked').val() === 'before')
                $('#sample-output').text($('#currency_symbol').val() + ' ' + this.format(12345.6789, $('#decimal_places').val(), $('#decimal_separator').val(), $('#thousand_separator').val()));
            else
                $('#sample-output').text(this.format(12345.6789, $('#decimal_places').val(), $('#decimal_separator').val(), $('#thousand_separator').val()) + ' ' + $('#currency_symbol').val());
        },
        format: function number_format(number, decimals, dec_point, thousands_sep) {
          //  discuss at: http://phpjs.org/functions/number_format/
          number = (number + '')
            .replace(/[^0-9+\-Ee.]/g, '');
          var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
              var k = Math.pow(10, prec);
              return '' + (Math.round(n * k) / k)
                .toFixed(prec);
            };
          // Fix for IE parseFloat(0.55).toFixed(0) = 0;
          s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
            .split('.');
          if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
          }
          if ((s[1] || '')
            .length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1)
              .join('0');
          }
          return s.join(dec);
        }
    }))({
        el: '#currency-format'
    });
    $('#decimal_separator').trigger('keyup');
    </script>
@stop