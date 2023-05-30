<?php

namespace App\Http\Controllers\cms\setup;

use App\Entities\Cms\CaseCategory;
use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CaseCategoryController extends Controller
{
    private $flashMessageManager;

    public function __construct(FlashMessageManager $flashMessageManager)
    {
        $this->flashMessageManager = $flashMessageManager;
    }

    public function index(Request $request)
    {
        return view('cms.setup.casecategory.caseCategory', ['editData' => '']);
    }

    public function edit(Request $request, $id)
    {
        $editData = CaseCategory::find((int)$id)->toArray();
        return view('cms.setup.casecategory.caseCategory', ['editData' => $editData]);
    }

    public function storeCaseCategory(Request $request)
    {
        $response = $this->storeCaseCategoryInfo($request);
        $message = $response['o_status_message'];

        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', $message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect('/case-category');
    }

    public function updateCaseCategory(Request $request)
    {
        $response = $this->storeCaseCategoryInfo($request);

        $message = $response['o_status_message'];

        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', $message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect('/case-category');
    }

    public function caseCategoryDatatable(Request $request)
    {
        $querys = "SELECT CPACMS.CASE_CATEGORY_GRID_LIST FROM DUAL" ;
        $caseCategory = DB::select($querys);

        return datatables()->of($caseCategory)
            ->addColumn('action', function ($query) {
                return '<a href="' . route('cms.case-category.case-category-edit', $query->category_id) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function storeCaseCategoryInfo($request = [])
    {
        $category_id = '';
        if($request->get("category_id"))
        {
            $category_id = $request->get("category_id");
        }

        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "p_category_id" => $category_id,
                "p_category_name" => $request->get("category_name"),
                "p_category_name_bng" => $request->get("category_name_bng"),
                "p_active_yn" => $request->get("active_yn"),
                "p_description" => $request->get("cat_desc"),
                "p_insert_by" => auth()->id(),
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message,
            ];
            DB::executeProcedure("CPACMS.CASE_CATEGORY_CREATION", $params);
        } catch (\Exception $e) {
            $params = ["status" => false, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

}
