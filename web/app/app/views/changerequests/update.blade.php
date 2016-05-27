@extends('layouts.module')
@section('content')
<div class="col-md-12">
    @include('html.notifications')
    <div class="col-md-12">
        <div class="page-header">
            <a href="/wall/profile" class="btn btn-small btn-primary pull-right"><i class="fa fa-undo"></i> Back</a>
            <h2>Update Profile</h2>
        </div>
        <div class="form-group">
        {{Former::open(action('ProfileController@doUpdateProfile'))}}
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="text-center">Update</th>
                    <th class="text-center">Column</th>
                    <th class="text-center">Current Value</th>
                    <th class="text-center">New Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <input type="checkbox" value="Email Address" name="update[email]" class="enable-control">
                    </td>
                    <td>
                        Email
                    </td>
                    <td>
                        <input type="email" class="current-value form-control" name="old_value[email]" readonly value="{{@$currentuser->email}}">
                    </td>
                    <td>
                        <input disabled readonly type="email" class="form-control" name="new_value[email]">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" value="First Name" name="update[profile.first_name]" class="enable-control">
                    </td>
                    <td>
                        First Name
                    </td>
                    <td>
                        <input type="text" class="current-value form-control" name="old_value[profile][first_name]" readonly value="{{@$currentuser->profile->first_name}}">
                    </td>
                    <td>
                        <input disabled readonly type="text" class="form-control" name="new_value[profile][first_name]">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" value="Last Name" name="update[profile.last_name]" class="enable-control">
                    </td>
                    <td>
                        Last Name
                    </td>
                    <td>
                        <input type="text" class="current-value form-control" name="old_value[profile][last_name]" readonly value="{{@$currentuser->profile->last_name}}">
                    </td>
                    <td>
                        <input disabled readonly type="text" class="form-control" name="new_value[profile][last_name]">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" value="Address" name="update[profile.address]" class="enable-control">
                    </td>
                    <td>
                        Address
                    </td>
                    <td>
                        <textarea class="form-control current-value" rows="4" name="old_value[profile][address]" readonly>{{@$currentuser->profile->address}}</textarea>
                    </td>
                    <td>
                        <textarea disabled readonly class="form-control" rows="4" name="new_value[profile][address]"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" value="Position" name="update[profile.position]" class="enable-control">
                    </td>
                    <td>
                        Position
                    </td>
                    <td>
                        <input type="text" class="current-value form-control" name="old_value[profile][position]" readonly value="{{@$currentuser->profile->position}}">
                    </td>
                    <td>
                        <input disabled readonly type="text" class="form-control" name="new_value[profile][position]">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" value="Bank Name" name="update[profile.bank_name]" class="enable-control">
                    </td>
                    <td>
                        Bank Name
                    </td>
                    <td>
                        <input type="text" class="current-value form-control" name="old_value[profile][bank_name]" readonly value="{{@$currentuser->profile->bank_name}}">
                    </td>
                    <td>
                        <input disabled readonly type="text" class="form-control" name="new_value[profile][bank_name]">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" value="Bank Account Number" name="update[profile.bank_account]" class="enable-control">
                    </td>
                    <td>
                        Bank Account Number
                    </td>
                    <td>
                        <input type="text" class="current-value form-control" name="old_value[profile][bank_account]" readonly value="{{@$currentuser->profile->bank_account}}">
                    </td>
                    <td>
                        <input disabled readonly type="text" class="form-control" name="new_value[profile][bank_account]">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" value="EPF Account Number" name="update[profile.kwsp_account]" class="enable-control">
                    </td>
                    <td>
                        EPF Account Number
                    </td>
                    <td>
                        <input type="text" class="current-value form-control" name="old_value[profile][kwsp_account]" readonly value="{{@$currentuser->profile->kwsp_account}}">
                    </td>
                    <td>
                        <input disabled readonly type="text" class="form-control" name="new_value[profile][kwsp_account]">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" value="SOCSO Account Number" name="update[profile.socso_account]" class="enable-control">
                    </td>
                    <td>
                        SOCSO Account Number
                    </td>
                    <td>
                        <input type="text" class="current-value form-control" name="old_value[profile][socso_account]" readonly value="{{@$currentuser->profile->socso_account}}">
                    </td>
                    <td>
                        <input disabled readonly type="text" class="form-control" name="new_value[profile][socso_account]">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" value="Tax Account Number" name="update[profile.lhdn_account]" class="enable-control">
                    </td>
                    <td>
                        Tax Account Number
                    </td>
                    <td>
                        <input type="text" class="current-value form-control" name="old_value[profile][lhdn_account]" readonly value="{{@$currentuser->profile->lhdn_account}}">
                    </td>
                    <td>
                        <input disabled readonly type="text" class="form-control" name="new_value[profile][lhdn_account]">
                    </td>
                </tr>
            </tbody>
        </table>
        <button class="btn btn-primary">Submit For Approval</button>
        {{Former::close()}}
        <hr>
    </div>
</div>
@stop

@section('script')
    <script>
        $('.enable-control').change(function(e){
            var target = $(e.target).parents('tr').find('td:last').find('input,select,textarea');
            if(e.target.checked) {
                target.removeAttr('disabled').removeAttr('readonly');
            } else {
                target.attr({
                    disabled: true,
                    readonly: true,
                });
            }
        });
        $('.current-value').each(function(index, el){
            var el = $(el);
            if(!el.val()) {
                el.parents('tr').find('.enable-control').attr('checked', true).trigger('change');
            }
        })
    </script>
@stop

<!-- email
first_name
last_name
address
unit_id
bank_name
bank_account
kwsp_account
socso_account
lhdn_account -->
