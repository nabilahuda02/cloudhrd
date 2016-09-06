@extends('layouts.module')
@section('style')
<style type="text/css">
    .card {
        margin-bottom: 12px;
        padding: 4px;
        border: 1px solid #ccc;
        cursor: pointer;
        height: 190px;
        overflow: hidden;
    }
    .card.new {
        border-style: dashed;
    }
    .card.new h3 {
        font-family: Roboto;
        margin-top: 80px;
        font-weight: normal;
    }
    .card .claim-type, .card .claim-title {
        margin: 8px 4px 4px;
    }
    .card .claim-title {
        height: 48px;
        overflow: hidden;
    }
    .card .receipt {
        background-image: url('/images/no-file.png');
        background-size: cover;
        height: 180px;
        background-position: center center;
        margin-right: -15px;
    }
    .card .meta * {
        margin: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    #claim-entry h1 {
        margin-top: 0px;
    }
    #claim-entry .thumb {
        display: block;
        width: 200px;
        height: auto;
    }
</style>
@stop
@section('content')
<section id="general" ng-controller="GeneralClaimController">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h2>
                    Create New Claims
                    <button class="btn btn-link help-btn" onclick="HelpFile.show('USER_GC.md', 'General Claims')">
                        <i class="fa fa-question-circle"></i>
                    </button>
                </h2>
            </div>
            <div class="col-xs-6 section-drop-menu" >
                @include('generalclaims.menu')
            </div>
        </div>
    </div>
    <hr/>
    <div class="container">
        @include('html.notifications')
        <div class="row">
            <div class="col-md-3">
                {{ Former::vertical_open(action('GeneralClaimsController@store'))
                    ->name('GeneralClaim')
                    ->method('POST') }}
                {{Former::hidden('noonce', Helper::noonce())}}
                @if(count(Auth::user()->getDownline(GeneralClaim__Main::$moduleId)) > 0)
                {{ Former::select('user_id')
                    ->label('For User')
                    ->options(Helper::userArray(Auth::user()->getDownline(GeneralClaim__Main::$moduleId, true)), null)
                    ->required() }}
                @endif
                {{ Former::text('title')
                    ->ng_model('name')
                    ->required() }}
                {{ Former::number('value')
                    ->readonly()
                    ->ng_model('value')
                    ->required() }}
                {{ Former::textarea('remarks') }}
                {{ Former::close() }}
            </div>
            <div class="col-md-9">
                <div class="row claim-items">
                    <div class="col-sm-6">
                        <div class="card new" ng-click="newClaim()">
                            <h3 class="text-center text-muted">
                                &plus; Add New Receipt
                            </h3>
                        </div>
                    </div>
                    <div class="col-sm-6" ng-repeat="entry in entries" ng-click="$parent.open(entry)">
                        <div class="card">
                            <div class="row">
                                <div class="col-xs-6">
                                    <div ng-if="entry.uploadable.thumb_url" class="receipt" style="background-image:url('@{{$parent.entry.uploadable.thumb_url || ''}}')!important"></div>
                                    <div ng-if="!entry.uploadable.thumb_url" class="receipt"></div>
                                </div>
                                <div class="col-xs-6">
                                    <h3 class="claim-type">
                                        @{{entry.type.name}}
                                    </h3>
                                    <h4 class="claim-title">
                                        @{{entry.receipt_number || entry.description}}
                                    </h4>
                                    <div class="meta">
                                        <h4>RM @{{entry.amount | currency}}</h4>
                                        <p>@{{entry.receipt_date}}</p>
                                        <p>#@{{entry.tag.name}}</p>
                                        <div>GST: {{$user_locale->currency_symbol}} @{{entry.gst_amount | currency}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <hr>
                <div class="well">
                    <input class="btn-large btn-primary btn click-once" type="submit" value="Submit">
                </div>
            </div>
        </div>
    </div>
    <div id="claim-entry" ng-controller="GeneralClaimEntryController" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Claim Entry</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            {{ Former::vertical_open(action('GeneralClaimsController@store'))
                                ->name('EntryForm')
                                ->method('POST') }}
                            <div class="row">
                                <div class="col-sm-4">
                                    {{ Former::select('claim_type_id')
                                        ->id('form_type')
                                        ->ng_model('claim_type_id')
                                        ->label('Type')
                                        ->required()
                                        ->options(GeneralClaim__Type::all()->lists('name', 'id'))
                                        ->placeholder('Choose a claim type') }}
                                </div>
                                <div class="col-sm-4">
                                    {{ Former::select('general_claim_tag_id')
                                        ->ng_model('general_claim_tag_id')
                                        ->label('Tag')
                                        ->required()
                                        ->options(GeneralClaim__Tag::all()->lists('name', 'id'))
                                        ->placeholder('Tag a claim') }}
                                </div>
                                <div class="col-sm-4">
                                    {{ Former::text('receipt_date')
                                        ->ng_model('receipt_date')
                                        ->label('Receipt Date')
                                        ->required() }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group required">
                                        <label ng-if="!claim_type.unit" for="receipt_number" class="control-label">Receipt Number <sup>*</sup>
                                        </label>
                                        <label ng-if="claim_type.unit" for="receipt_number" class="control-label">Title <sup>*</sup>
                                        </label>
                                        <input class="form-control" ng-model="receipt_number" required="true" id="receipt_number" type="text" name="receipt_number">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div ng-if="!type.unit">
                                    {{ Former::number('amount')
                                        ->ng_model('$parent.amount')
                                        ->required()
                                        ->step(1 / pow(10, app()->user_locale->decimal_places))
                                        ->label('Receipt Total') }}
                                    </div>
                                    <div ng-if="type.unit">
                                        <div class="form-group">
                                            <label for="quantity" class="control-label">Quantity (@{{type.unit}}) <sup>*</sup></label>
                                            <input ng-model="$parent.quantity" min="1" required class="form-control" step="{{1 / pow(10, app()->user_locale->decimal_places)}}" id="quantity" type="number" name="quantity">
                                            <span class="help-block">{{$user_locale->currency_symbol}} @{{type.unit_price}} / @{{type.unit}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4" ng-if="type.has_gst">
                                    {{ Former::text('gst_amount')
                                        ->ng_model('$parent.gst_amount')
                                        ->step(1 / pow(10, app()->user_locale->decimal_places))
                                        ->label('GST Total') }}
                                </div>
                                <div class="col-sm-4">
                                    {{ Former::text('description')
                                        ->id('form_title')
                                        ->ng_model('description')
                                        ->label('Description') }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label">Total</label>
                                        <h1>{{$user_locale->currency_symbol}} @{{amount | currency}}</h1>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div ng-show="!uploadable.id">
                                        {{ Former::file('upload')
                                            ->data_file_upload()
                                            ->title('Upload') }}
                                    </div>
                                    <div ng-if="uploadable.id">
                                        <button type="button" ng-click="removeFile()" class="btn btn-danger">
                                            <i class="fa fa-remove"></i>
                                        </button>
                                        <img class="thumb" ng-src="@{{$parent.uploadable.thumb_url}}" alt="">
                                    </div>
                                </div>
                            </div>
                            {{ Former::hidden('amount')}}
                            {{ Former::close() }}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" ng-click="EntryForm.$valid && save()" ng-show="!uploading" ng-disabled="!EntryForm.$valid" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- {{Asset::push('js', 'general_claims')}} --}}
@stop
@section('script')
<script type="text/javascript">
    var claimTypes = {{json_encode(GeneralClaim__Type::all())}};
    var claimTags = {{json_encode(GeneralClaim__Tag::all())}};
    $('#receipt_date').datetimepicker({
        format: app_locale.short_date
    });
    angular.module('app')
        .factory('uploader', function ($http) {
            return function (mask, data, callback) {
                $http({
                    url: '/upload/general-claims-entry/' + mask,
                    method: "POST",
                    data: data,
                    headers: {'Content-Type': undefined}
                }).success(function (response) {
                    callback(response);
                });
            };
        })
        .directive('fileUpload', function (uploader) {
            return {
                restrict: 'A',
                scope: true,
                link: function (scope, element, attr) {
                    element.bind('change', function () {
                        var formData = new FormData();
                        formData.append('file', element[0].files[0]);
                        scope.$parent.uploading = true;
                        uploader(scope.file_mask, formData, function (response) {
                            try{
                                element[0].value = '';
                                if(element[0].value){
                                    element[0].type = "text";
                                    element[0].type = "file";
                                }
                            }catch(e){}
                            scope.$parent.uploading = false;
                            scope.$parent.uploadable = response;
                        });
                    });
                }
            };
        })
        .controller('GeneralClaimController', function($scope){
            $scope.entries = [];
            $scope.editing = {};
            $scope.newClaim = function(){
                setTimeout(function() {
                    $scope.editing = {};
                    $('#claim-entry').modal('show');
                });
            }
            $scope.open = function(entry) {
                setTimeout(function() {
                    $scope.editing = angular.copy(entry);
                    $('#claim-entry').modal('show');
                });
            }
        })
        .controller('GeneralClaimEntryController', function($scope, $filter, $http) {
            $scope.file_mask = Date.now();
            $scope.claim_type_id = null;
            $scope.general_claim_tag_id = null;
            $scope.type = {};
            $scope.tag = {};
            $scope.uploadable = {};
            $scope.amount = 0;
            $scope.gst_amount = 0;
            $scope.quantity = 0;
            $scope.uploading = false;
            $('#receipt_date').on('dp.change', function(event){
                $scope.receipt_date = event.date.format(app_locale.short_date);
                $scope.$digest();
            })
            $scope.$watch('claim_type_id', function(claim_type_id){
                if(claim_type_id) {
                    var claimType = claimTypes.filter(function(claimType){
                        return claimType.id == claim_type_id;
                    }).pop();
                    if(claimType) {
                        $scope.type = claimType;
                    } else {
                        $scope.type = {};
                    }
                }
            });
            $scope.$watch('general_claim_tag_id', function(general_claim_tag_id){
                if(general_claim_tag_id) {
                    var claimTag = claimTags.filter(function(claimTag){
                        return claimTag.id == general_claim_tag_id;
                    }).pop();
                    if(claimTag) {
                        $scope.tag = claimTag;
                    } else {
                        $scope.tag = {};
                    }
                }
            });
            $scope.$watch('quantity', function(quantity){
                if(quantity) {
                    $scope.amount = $filter('currency')(parseFloat($scope.quantity) * parseFloat($scope.type.unit_price));
                }
            });
            $scope.$watch('amount', function(amount){
                if(amount && $scope.type.has_gst) {
                    var gst = amount - (amount / 1.06);
                    $scope.gst_amount = $filter('currency')(gst);
                } else {
                    $scope.gst_amount = 0;
                }
            });
            $scope.removeFile = function() {
                if($scope.uploadable.id) {
                    $http.get('/upload/remove/' + $scope.uploadable.id)
                    $scope.uploadable = {};
                }
            }
            $scope.save = function() {
                var data = {
                    id: $scope.file_mask,
                    claim_type_id: $scope.claim_type_id,
                    type: $scope.type,
                    general_claim_tag_id: $scope.general_claim_tag_id,
                    tag: $scope.tag,
                    description: $scope.description,
                    quantity: $scope.quantity,
                    amount: $scope.amount,
                    uploadable: $scope.uploadable,
                    receipt_date: moment($scope.receipt_date, app_locale.short_date).format('YYYY/MM/DD'),
                    receipt_number: $scope.receipt_number,
                    gst_amount: $scope.gst_amount,
                };
                var entry = $scope.$parent.entries.filter(function(entry){
                    return entry.id == data.id;
                }).pop();
                if(entry) {
                    $scope.$parent.entries.splice($scope.$parent.entries.indexOf(entry), 1, data);
                } else {
                    $scope.$parent.entries.push(data);
                }
                setTimeout(function() {
                    $scope.$parent.$digest();
                    $('#claim-entry').modal('hide');
                });
            }
            $('#claim-entry').on('show.bs.modal', function(){
                $scope.file_mask = $scope.$parent.editing.id || Date.now();
                $scope.type = $scope.$parent.editing.type || {};
                $scope.tag = $scope.$parent.editing.tag || {};
                $scope.claim_type_id = $scope.$parent.editing.claim_type_id || null;
                $scope.description = $scope.$parent.editing.description || null;
                $scope.quantity = $scope.$parent.editing.quantity || null;
                $scope.amount = $scope.$parent.editing.amount || null;
                $scope.uploadable = $scope.$parent.editing.uploadable || null;
                $scope.receipt_date = ($scope.$parent.editing.receipt_date ? moment($scope.$parent.editing.receipt_date, 'YYYY/MM/DD').format(app_locale.short_date) : null);
                $scope.receipt_number = $scope.$parent.editing.receipt_number || null;
                $scope.general_claim_tag_id = $scope.$parent.editing.general_claim_tag_id || null;
                $scope.gst_amount = $scope.$parent.editing.gst_amount || null;
                $scope.$digest();
            });
        });
    setTimeout(function() {
        $('#claim-entry').modal('show');
    });
</script>
@stop
