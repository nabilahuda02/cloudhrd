<?php

class AdminPayrollController extends \BaseController
{

    public function getGenerated()
    {
        $downlines = [];
        return View::make('payrolls.admin.generated', compact('downlines'));
    }

    public function getDetails($payroll_id)
    {
        $payroll = Payroll__Main::findOrFail($payroll_id);
        $epfTotal = '0.00';
        $socsoTotal = '0.00';
        $pcbTotal = '0.00';
        if ($payroll->payrollUsers) {
            $epfTotal = DB::select(DB::raw('select sum(employee_contribution) + sum(employer_contribution) as total  from epf_contributions where payroll_user_id in (' . implode(',', $payroll->payrollUsers->lists('id')) . ')'))[0]->total;
            $socsoTotal = DB::select(DB::raw('select sum(employee_contribution) + sum(employer_contribution) as total  from socso_contributions where payroll_user_id in (' . implode(',', $payroll->payrollUsers->lists('id')) . ')'))[0]->total;
            $pcbTotal = DB::select(DB::raw('select sum(employee_contribution) as total  from pcb_contributions where payroll_user_id in (' . implode(',', $payroll->payrollUsers->lists('id')) . ')'))[0]->total;
        }
        return View::make('payrolls.admin.show', compact('payroll', 'epfTotal', 'socsoTotal', 'pcbTotal'));
    }

    public function getUserDetails($payroll_user_id)
    {
        $payrollUser = Payroll__User::findOrFail($payroll_user_id);
        return View::make('payrolls.admin.user-show', compact('payrollUser'));
    }

    public function getApplyMedicalClaim($payroll_user_id, $medical_claim_id)
    {
        $payrollUser = Payroll__User::findOrFail($payroll_user_id);
        $medicalClaim = MedicalClaim__Main::findOrFail($medical_claim_id);
        $payrollUser->items()->create([
            'name' => $medicalClaim->ref,
            'payrollable_type' => MedicalClaim__Main::class,
            'payrollable_id' => $medicalClaim->id,
            'amount' => $medicalClaim->total,
        ]);
        $medicalClaim->update(['is_paid' => 1]);
        $payrollUser->updateTotal();
        $payrollUser->payroll->updateTotal();
        return Redirect::back()
            ->with('NotifySuccess', 'Medical Claim Applied');
    }

    public function getRemoveMedicalClaim($payroll_item_id)
    {
        $payrollItem = Payroll__Item::findOrFail($payroll_item_id);
        $medicalClaim = MedicalClaim__Main::findOrFail($payrollItem->payrollable_id);
        $medicalClaim->update(['is_paid' => 0]);
        $payrollUser = $payrollItem->payrollUser;
        $payrollItem->delete();
        $payrollUser->updateTotal();
        $payrollUser->payroll->updateTotal();
        return Redirect::back()
            ->with('NotifySuccess', 'Medical Claim Removed');
    }

    public function getApplyGeneralClaim($payroll_user_id, $general_claim_id)
    {
        $payrollUser = Payroll__User::findOrFail($payroll_user_id);
        $generalClaim = GeneralClaim__Main::findOrFail($general_claim_id);
        $payrollUser->items()->create([
            'name' => $generalClaim->ref . ' (' . $generalClaim->title . ')',
            'payrollable_type' => GeneralClaim__Main::class,
            'payrollable_id' => $generalClaim->id,
            'amount' => $generalClaim->value,
        ]);
        $generalClaim->update(['is_paid' => 1]);
        $payrollUser->updateTotal();
        $payrollUser->payroll->updateTotal();
        return Redirect::back()
            ->with('NotifySuccess', 'General Claim Applied');
    }

    public function getRemoveGeneralClaim($payroll_item_id)
    {
        $payrollItem = Payroll__Item::findOrFail($payroll_item_id);
        $generalClaim = GeneralClaim__Main::findOrFail($payrollItem->payrollable_id);
        $generalClaim->update(['is_paid' => 0]);
        $payrollUser = $payrollItem->payrollUser;
        $payrollItem->delete();
        $payrollUser->updateTotal();
        $payrollUser->payroll->updateTotal();
        return Redirect::back()
            ->with('NotifySuccess', 'General Claim Removed');
    }

    public function getDownload($payroll_id)
    {

    }

    public function getPublish($payroll_id)
    {
        $payroll = Payroll__Main::findOrFail($payroll_id);
        $payroll->update(['status_id' => 7]);
        return Redirect::back()
            ->with('NotifySuccess', 'Payroll Published');
    }

    public function getUnpublish($payroll_id)
    {
        $payroll = Payroll__Main::findOrFail($payroll_id);
        $payroll->update(['status_id' => 6]);
        return Redirect::back()
            ->with('NotifySuccess', 'Payroll Unpublished');
    }

    public function getRemove($payroll_id)
    {
        $payroll = Payroll__Main::findOrFail($payroll_id);
        $payroll->delete();
        return Redirect::back()
            ->with('NotifySuccess', 'Payroll Deleted');
    }

    public function __construct()
    {
        View::share('controller', 'Payroll Admin');
    }

}
