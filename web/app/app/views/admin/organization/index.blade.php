@extends('layouts.module')
@section('content')
<section id="units">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h2>
                    Organization Config
                </h2>
                <hr/>
            </div>
        </div>
    </div>
    <div class="container">
        @include('html.notifications')
        <div class="row">
            <div class="col-md-12">
            {{Former::populate($master_user)}}
            {{Former::open_horizontal(action('AdminOrganizationController@store'))}}
                {{ Former::text('name')
                    ->label('Company Name')
                    ->required() }}
                {{ Former::text('company_registration')
                    ->label('Company Registration No')
                    ->required()
                    ->value($locale->company_registration) }}
                {{ Former::textarea('company_address')
                    ->label('Company Address')
                    ->required()
                    ->value($locale->company_address) }}
                {{ Former::email('support_email')
                    ->label('Support Email')
                    ->required() }}
                <div class="form-group required" id="currency-format">
                    <label class="control-label col-lg-2 col-sm-4">Working Days<sup>*</sup></label>
                    <div class="col-lg-10 col-sm-8">
                        <div class="row">
<?php
$dow = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
for ($i = 0; $i < 7; $i++):
?>
                            <div class="col-sm-3">
                                <input type="hidden" name="working_days[{{$i}}]" value="0">
                                <label>
                                    <input type="checkbox" name="working_days[{{$i}}]" value="1" {{($locale->working_days[$i] == 1 ? 'checked' : '')}}>
                                    &nbsp;{{$dow[$i]}}
                                </label>
                            </div>
<?php endfor;?>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group required" id="currency-format" ng-controller="NumberLocaleController">
                    <label class="control-label col-lg-2 col-sm-4">Currency Localization<sup>*</sup></label>
                    <div class="col-lg-10 col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">
                                <b>Decimal Places</b>
                                <input class="form-control" required="true" ng-model="decimal_places" id="decimal_places" type="text" name="decimal_places" placeholder="Decimal Places" value="{{$locale->decimal_places}}">
                            </div>
                            <div class="col-sm-4">
                                <b>Decimal Separator</b>
                                <input class="form-control" required="true" ng-model="decimal_separator" id="decimal_separator" type="text" name="decimal_separator" placeholder="Decimal Separator" value="{{$locale->decimal_separator}}">
                            </div>
                            <div class="col-sm-4">
                                <b>Thousand Separator</b>
                                <input class="form-control" required="true" ng-model="thousand_separator" id="thousand_separator" type="text" name="thousand_separator" placeholder="Thousand Separator" value="{{$locale->thousand_separator}}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-4">
                                <b>Currency Symbol</b>
                                <input class="form-control" required="true" ng-model="currency_symbol" id="currency_symbol" type="text" name="currency_symbol" placeholder="Currency Symbol" value="{{$locale->currency_symbol}}">
                            </div>
                            <div class="col-sm-4">
                                <b>Symbol Location</b>
                                <div class="clearfix"></div>
                                <label for="before" class="radio-inline">
                                    <input type="radio" name="symbol_location" ng-model="symbol_location" value="before" id="before" {{($locale->symbol_location === 'before' ? 'checked' : '')}}> Before
                                </label>
                                <label for="after" class="radio-inline">
                                    <input type="radio" name="symbol_location" ng-model="symbol_location" value="after" id="after" {{($locale->symbol_location === 'after' ? 'checked' : '')}}> After
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <br>
                            <div class="col-md-12">
                                Sample Output:
                                <span ng-show="symbol_location == 'after'">@{{format()}} @{{currency_symbol}}</span>
                                <span ng-show="symbol_location == 'before'">@{{currency_symbol}} @{{format()}}</span>
                            </div>
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
                                <label for="MMMM DD,YYYY__F j, Y" class="radio">
                                    <input type="radio" name="long_date" value="MMMM DD,YYYY__F j, Y" id="MMMM DD,YYYY__F j, Y" {{($locale->long_date === 'MMMM DD,YYYY__F j, Y' ? 'checked' : '')}}> December 31, 2014
                                </label>
                                <label for="MMMM DD YYYY__d F Y" class="radio">
                                    <input type="radio" name="long_date" value="MMMM DD YYYY__d F Y" id="MMMM DD YYYY__d F Y" {{($locale->long_date === 'MMMM DD YYYY__d F Y' ? 'checked' : '')}}> 31 December 2014
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <b>Short Date</b>
                                <div class="clearfix"></div>
                                <label for="MM/DD/YYYY__m/d/Y" class="radio">
                                    <input type="radio" name="short_date" value="MM/DD/YYYY__m/d/Y" id="MM/DD/YYYY__m/d/Y" {{($locale->short_date === 'MM/DD/YYYY__m/d/Y' ? 'checked' : '')}}> 12/31/2014
                                </label>
                                <label for="DD-MM-YYYY__d-m-Y" class="radio">
                                    <input type="radio" name="short_date" value="DD-MM-YYYY__d-m-Y" id="DD-MM-YYYY__d-m-Y" {{($locale->short_date === 'DD-MM-YYYY__d-m-Y' ? 'checked' : '')}}> 31-12-2014
                                </label>
                                <label for="YYYY-MM-DD__Y-m-d" class="radio">
                                    <input type="radio" name="short_date" value="YYYY-MM-DD__Y-m-d" id="YYYY-MM-DD__Y-m-d" {{($locale->short_date === 'YYYY-MM-DD__Y-m-d' ? 'checked' : '')}}> 2014-12-31
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
                <hr>
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
        angular.module('app')
            .controller('NumberLocaleController', function($scope){
                $scope.decimal_places = app_locale.decimal_places;
                $scope.decimal_separator = app_locale.decimal_separator;
                $scope.currency_symbol = app_locale.currency_symbol;
                $scope.symbol_location = app_locale.symbol_location;
                $scope.thousand_separator = app_locale.thousand_separator;
                function number_format(number, decimals, dec_point, thousands_sep) {
                    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
                    var n = !isFinite(+number) ? 0 : +number,
                        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                        s = '',
                        toFixedFix = function(n, prec) {
                            var k = Math.pow(10, prec);
                            return '' + (Math.round(n * k) / k).toFixed(prec);
                        };
                        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
                        if (s[0].length > 3) {
                            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
                        }
                        if ((s[1] || '').length < prec) {
                            s[1] = s[1] || '';
                            s[1] += new Array(prec - s[1].length + 1).join('0');
                        }
                    return s.join(dec);
                }
                $scope.format = function() {
                    return number_format(12345.6789, $scope.decimal_places, $scope.decimal_separator, $scope.thousand_separator);
                }
            })
    </script>
@stop
