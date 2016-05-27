@extends('layouts.module')
@section('content')
<section id="units">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h2>
                    Organization Config
                    {{-- <button class="btn btn-link help-btn" onclick="HelpFile.show('MANAGE_USER.md', 'Manage Users')">
                        <i class="fa fa-question-circle"></i>
                    </button> --}}
                </h2>
                <hr/>
            </div>
            <div class="col-sm-6 section-drop-menu" >
                {{-- @include('admin.units.menu') --}}
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            {{Former::populate($master_user)}}
            {{Former::open_horizontal(action('AdminOrganizationController@index'))}}
                {{ Former::email('support_email')
                    ->label('Support Email')
                    ->required() }}
                <hr>
                <div class="form-group required" id="currency-format">
                    <label class="control-label col-lg-2 col-sm-4">Currency Localization<sup>*</sup></label>
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
                        </div>
                        <div class="help-block">
                            <b>Sample Output: <span id="sample-output"></span></b>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group required">
                    <label class="control-label col-lg-2 col-sm-4">Date Localization<sup>*</sup></label>
                    <div class="col-lg-10 col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">
                                <b>Long Date</b>
                                <div class="clearfix"></div>
                                <label for="MM d, yy__F j, Y" class="radio">
                                    <input type="radio" name="long_date" value="MM d, yy__F j, Y" id="MM d, yy__F j, Y" {{($locale->long_date === 'MM d, yy__F j, Y' ? 'checked' : '')}}> December 31, 2014
                                </label>
                                <label for="d MM yy__d M Y" class="radio">
                                    <input type="radio" name="long_date" value="d MM yy__d M Y" id="d MM yy__d M Y" {{($locale->long_date === 'd MM yy__d M Y' ? 'checked' : '')}}> 31 December 2014
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <b>Short Date</b>
                                <div class="clearfix"></div>
                                <label for="mm/dd/yy__m/d/Y" class="radio">
                                    <input type="radio" name="short_date" value="mm/dd/yy__m/d/Y" id="mm/dd/yy__m/d/Y" {{($locale->short_date === 'mm/dd/yy__m/d/Y' ? 'checked' : '')}}> 12/31/2014
                                </label>
                                <label for="dd-mm-yy__d-m-Y" class="radio">
                                    <input type="radio" name="short_date" value="dd-mm-yy__d-m-Y" id="dd-mm-yy__d-m-Y" {{($locale->short_date === 'dd-mm-yy__d-m-Y' ? 'checked' : '')}}> 31-12-2014
                                </label>
                                <label for="yy-mm-dd__Y-m-d" class="radio">
                                    <input type="radio" name="short_date" value="yy-mm-dd__Y-m-d" id="yy-mm-dd__Y-m-d" {{($locale->short_date === 'yy-mm-dd__Y-m-d' ? 'checked' : '')}}> 2014-12-31
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <b>Time Format</b>
                                <div class="clearfix"></div>
                                <label for="12h" class="radio">
                                    <input type="radio" name="time_format" value="12h" id="12h" {{($locale->time_format === '12h' ? 'checked' : '')}}> 12 Hours
                                </label>
                                <label for="24h" class="radio">
                                    <input type="radio" name="time_format" value="24h" id="24h" {{($locale->time_format === '24h' ? 'checked' : '')}}> 24 Hours
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group required">
                    <label class="control-label col-lg-2 col-sm-4">Distance Localization<sup>*</sup></label>
                    <div class="col-lg-10 col-sm-8">
                        <div class="row">
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
                    </div>
                </div>
                <div class="well">
                    <button class="pull-right btn btn-primary">Submit</button>
                    <div class="clearfix"></div>
                </div>
            {{Former::close()}}
            </div>
        </div>
    </div>
</section>
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
