<?php

namespace App\Http\Controllers\cms\setup;

use App\Entities\Cms\RateChart;
use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RateChartController extends Controller
{
    private $flashMessageManager;

    public function __construct(FlashMessageManager $flashMessageManager)
    {
        $this->flashMessageManager = $flashMessageManager;
    }

    public function index(Request $request)
    {
        $editData   = '';

        $querys1 = "SELECT CPACMS.act_inc_list FROM DUAL" ;
        $active_yn = DB::select($querys1);

        $querys2 = "SELECT CPACMS.rate_chart_service_name_list FROM DUAL" ;
        $serviceNameList = DB::select($querys2);

        return view('cms.setup.ratechart.rateChart', [
            'editData' => $editData,
            'serviceNameList' => $serviceNameList,
            'active_yn' => $active_yn]);
    }

    public function edit(Request $request, $id)
    {
        $editData = RateChart::find($id)->toArray();

        $querys1 = "SELECT CPACMS.act_inc_list FROM DUAL" ;
        $active_yn = DB::select($querys1);

        $querys2 = "SELECT CPACMS.rate_chart_service_name_list FROM DUAL" ;
        $serviceNameList = DB::select($querys2);

        return view('cms.setup.ratechart.rateChart', [
            'editData' => $editData,
            'serviceNameList' => $serviceNameList,
            'active_yn' => $active_yn]);
    }

    public function storeRateChart(Request $request)
    {
        $response = $this->storeRateChartInfo($request);

        $message = $response['o_status_message'];

        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', $message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect('/rate-chart');
    }

    public function updateRateChart(Request $request)
    {
        $response = $this->storeRateChartInfo($request);

        $message = $response['o_status_message'];

        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', $message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect('/rate-chart');
    }

    public function rateChartDatatable(Request $request)
    {
        $querys = "SELECT CPACMS.rate_chart_grid_list FROM DUAL" ;
        $rateChart = DB::select($querys);

        return datatables()->of($rateChart)
            ->addColumn('action', function ($query) {
                return '<a href="' . route('cms.rate-chart.rate-chart-edit', $query->rate_chart_id) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function storeRateChartInfo($request = [])
    {
        $rate_chart_id = '';
        if($request->get("rate_chart_id"))
        {
            $rate_chart_id = $request->get("rate_chart_id");
        }

        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "p_rate_chart_id" => $rate_chart_id,
                "p_assignment_type_id" => $request->get("service_name"),
                "p_minimum_rate" => $request->get("rate_from"),
                "p_maximum_rate" => $request->get("rate_to"),
                "p_active_yn" => $request->get("active_yn"),
                "p_active_from" => $request->get("active_from"),
                "p_active_to" => $request->get("active_to"),
                "p_rate_description" => $request->get("description"),
                "p_insert_by" => auth()->id(),
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message,
            ];
            DB::executeProcedure("CPACMS.case_rate_chart_creation", $params);
        } catch (\Exception $e) {
            $params = ["status" => false, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }
}
