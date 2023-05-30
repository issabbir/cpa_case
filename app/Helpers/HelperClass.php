<?php
//app/Helpers/HelperClass.php
namespace App\Helpers;

use App\Entities\Admin\LGeoDistrict;
use App\Entities\Admin\LGeoThana;
use App\Entities\Ams\LPriorityType;
use App\Entities\Ams\OperatorMapping;
use App\Entities\Security\Menu;
use App\Enums\ModuleInfo;
use App\Managers\Authorization\AuthorizationManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class HelperClass
{

    public $id;
    public $links;

    public static function menuSetup()
    {
        if (Auth::user()->hasGrantAll()) {
            $moduleId = ModuleInfo::WMS_CASE_ID;
            $menus = Menu::where('module_id', $moduleId)->orderBy('menu_order_no')->get();

            return $menus;
        } else {
            $allMenus = Auth::user()->getRoleMenus();
            $menus = [];

            if($allMenus) {
                foreach($allMenus as $menu) {
                    if($menu->module_id == ModuleInfo::WMS_CASE_ID) {
                        $menus[] = $menu;
                    }
                }
            }

            return $menus;
        };
    }

    public static function breadCrumbs($routeName)
    {
        if (in_array($routeName, ['cms.case-category.case-category-edit'])) {
            return [
                ['submenu_name' => 'Setup', 'action_name' => ''],
                ['submenu_name' => ' Case Category', 'action_name' => '']
            ];
        } else if (in_array($routeName, ['cms.court-info.org-edit'])) {
            return [
                ['submenu_name' => 'Setup', 'action_name' => ''],
                ['submenu_name' => 'Organization Registration', 'action_name' => 'cms.court-info.organization-registration'],
            ];
        } else if (in_array($routeName, ['cms.court-info.court-info-edit'])) {
            return [
                ['submenu_name' => 'Setup', 'action_name' => ''],
                ['submenu_name' => ' Court Information', 'action_name' => '']
            ];
        } else if (in_array($routeName, ['cms.rate-chart.rate-chart-edit'])) {
            return [
                ['submenu_name' => 'Setup', 'action_name' => ''],
                ['submenu_name' => ' Rate Chart', 'action_name' => '']
            ];
        } else if (in_array($routeName, ['cms.lawyer-info.lawyer-info-edit'])) {
            return [
                ['submenu_name' => ' Lawyer Information', 'action_name' => '']
            ];
        } else if (in_array($routeName, ['cms.case-info.case-info-edit'])) {
            return [
                ['submenu_name' => ' Case Information', 'action_name' => '']
            ];
        } else if (in_array($routeName, ['cms.lawyer-assignment.lawyer-assignment-edit'])) {
            return [
                ['submenu_name' => ' Lawyer Case Assignment', 'action_name' => '']
            ];
        } else if (in_array($routeName, ['cms.case-info-update.case-info-update-edit'])) {
            return [
                ['submenu_name' => ' Case Update', 'action_name' => '']
            ];
        } else if (in_array($routeName, ['cms.lawyer-assignment.getLawyerAssignmentSearchData','cms.lawyer-assignment.lawyer-assignment-edit'])) {
            return [
                ['submenu_name' => ' Lawyer Case Assignment', 'action_name' => '']
            ];
        }else if (in_array($routeName, ['cms.lawyer-bill-info.getLawyerBillInfoSearchData'])) {
            return [
                ['submenu_name' => ' Lawyer bill', 'action_name' => '']
            ];
        }else {
            $breadMenus = [];

            try {
                $authorizationManager = new AuthorizationManager();
                $getRouteMenuId = $authorizationManager->findSubMenuId($routeName);
                if ($getRouteMenuId && !empty($getRouteMenuId)) {
                    $breadMenus[] = $bm = $authorizationManager->findParentMenu($getRouteMenuId);
                    if ($bm && isset($bm['parent_submenu_id']) && !empty($bm['parent_submenu_id'])) {
                        $breadMenus[] = $authorizationManager->findParentMenu($bm['parent_submenu_id']);
                    }
                }
            } catch (\Exception $e) {
                return false;
            }

            return is_array($breadMenus) ? array_reverse($breadMenus) : false;
        }
    }

    public static function getActiveRouteNameWrapping($routeName)
    {
        if (in_array($routeName, ['cms.case-category.case-category-edit'])) {
            return 'cms.case-category.case-category-index';
        } else if (in_array($routeName, ['cms.court-info.court-info-edit'])) {
            return 'cms.court-info.court-info-index';
        } else if (in_array($routeName, ['cms.rate-chart.rate-chart-edit'])) {
            return 'cms.rate-chart.rate-chart-index';
        }else if (in_array($routeName, ['cms.lawyer-info.lawyer-info-edit'])) {
            return 'cms.lawyer-info.lawyer-info-index';
        }else if (in_array($routeName, ['cms.case-info.case-info-edit'])) {
            return 'cms.case-info.case-info-index';
        }else if (in_array($routeName, ['cms.lawyer-assignment.lawyer-assignment-edit', 'cms.lawyer-assignment.getLawyerAssignmentSearchData'])) {
            return 'cms.lawyer-assignment.lawyer-assignment-index';
        }else if (in_array($routeName, ['cms.case-info-update.case-info-update-edit'])) {
            return 'cms.case-info-update.case-info-update-index';
        }else if (in_array($routeName, ['cms.lawyer-bill-info.getLawyerBillInfoSearchData'])) {
            return 'cms.lawyer-bill-info.lawyer-bill-info-index';
        } else {
            return [
                [
                    'submenu_name' => $routeName,
                ]
            ];
        }
    }

    public static function activeMenus($routeName)
    {
        //$menus = [];
        try {
            $authorizationManager = new AuthorizationManager();
            $menus[] = $getRouteMenuId = $authorizationManager->findSubMenuId(self::getActiveRouteNameWrapping($routeName));
            //dd($routeName);
            if ($getRouteMenuId && !empty($getRouteMenuId)) {
                $bm = $authorizationManager->findParentMenu($getRouteMenuId);
                $menus[] = $bm['parent_submenu_id'];
                if ($bm && isset($bm['parent_submenu_id']) && !empty($bm['parent_submenu_id'])) {
                    $m = $authorizationManager->findParentMenu($bm['parent_submenu_id']);
                    if (!empty($m['submenu_id'])) {
                        $menus[] = $m['submenu_id'];
                    }
                }
            }
        } catch (\Exception $e) {
            $menus = [];
        }
        return is_array($menus) ? $menus : false;
    }

    public static function hasChildMenu($routeName)
    {
        $authorizationManager = new AuthorizationManager();
        $getRouteMenuId = $authorizationManager->findSubMenuId($routeName);
        return $authorizationManager->hasChildMenu($getRouteMenuId);
    }


    public function implodeValue($types)
    {
        $strTypes = implode(",", $types);
        return $strTypes;
    }

    public function explodeValue($types)
    {
        $strTypes = explode(",", $types);
        return $strTypes;
    }

    public function random_code()
    {
        return rand(1111, 9999);
    }

    public function remove_special_char($text)
    {
        $t = $text;
        $specChars = array(
            ' ' => '-', '!' => '', '"' => '',
            '#' => '', '$' => '', '%' => '',
            '&amp;' => '', '\'' => '', '(' => '',
            ')' => '', '*' => '', '+' => '',
            ',' => '', '₹' => '', '.' => '',
            '/-' => '', ':' => '', ';' => '',
            '<' => '', '=' => '', '>' => '',
            '?' => '', '@' => '', '[' => '',
            '\\' => '', ']' => '', '^' => '',
            '_' => '', '`' => '', '{' => '',
            '|' => '', '}' => '', '~' => '',
            '-----' => '-', '----' => '-', '---' => '-',
            '/' => '', '--' => '-', '/_' => '-',
        );

        foreach ($specChars as $k => $v) {
            $t = str_replace($k, $v, $t);
        }

        return $t;
    }

    public function datatable($datas, $table_id = null, $link = [])
    {
        $this->id = $table_id;
        $this->links = $link;
        return Datatables::of($datas)
            ->addColumn('action', function ($data) {
                if ($this->id) {
                    $icon = ['bx-edit', 'bx-trash', 'bx-show'];   // ['edit','delete','view']
                    $str = '';
                    foreach ($this->links as $key => $link) {
                        if (isset($link) && $link != '')
                            $str .= '<a href="' . $link . '/' . $data[$this->id] . '"><i class="bx ' . $icon[$key] . ' cursor-pointer"></i></a>&nbsp;';
                    }
                    return $str;
                } else {
                    return '';
                }
            })
            ->make(true);
    }

    public static function fileUpload($request)
    {
        // $upload_handler = new UploadHandler();
        $filename = [];
        foreach ($request->file("attachment") as $file) {
            $extension = $file->getClientOriginalExtension();
            Storage::disk('public')->put($file->getFilename() . '.' . $extension, File::get($file));
            $filename[] = $file->getFilename() . '.' . $extension;
        }
        return $filename;
    }

    public static function getStatusName($statusTag = '')
    {
        switch (strtoupper($statusTag)) {
            case 'A':
                $name = 'Approved';
                break;
            case 'I':
                $name = 'Inactive';
                break;
            case 'P':
                $name = 'Pending';
                break;
            case 'D':
                $name = 'Delete';
                break;
            case 'C':
                $name = 'Complete';
                break;
            case 'R':
                $name = 'Cancel';
                break;
            case 'Y':
                $name = 'Yes';
                break;
            case 'N':
                $name = 'No';
                break;
            default:
                $name = 'Unknown';
                break;
        }
        return $name;
    }

    public static function getColorCode($priorityID = '')
    {

        $code = '';
        if (!empty($priorityID)) {
            $colorCode = LPriorityType::where('priority_id', $priorityID)->get('color_code');
            $code = $colorCode[0]->color_code;
        }
        return $code;
    }

    public static function customDateFormat($datetime)
    {
        return date("d-m-Y", strtotime($datetime));
    }

    public static function customTimeFormat($datetime)
    {
        return date("h:i A", strtotime($datetime));
    }

    public static function customDateTimeFormat($datetime)
    {
        return date("d-m-Y h:i A", strtotime($datetime));
    }

    public static function customDateTimeDiff($startDateTime, $endDateTime)
    {
        try {
            if (strtotime($startDateTime) >= strtotime($endDateTime)) {
                $res = false;
            } else {
                $res = true;
            }
        } catch (\Exception $e) {
            $res = false;
        }

        return $res;

    }

    public static function customDateDiff($startDateTime, $endDateTime)
    {
        try {
            if (strtotime($startDateTime) > strtotime($endDateTime)) {
                $res = false;
            } else {
                $res = true;
            }
        } catch (\Exception $e) {
            $res = false;
        }

        return $res;

    }

    public static function operatorUser()
    {
        try {
            $res = [];
            // operator_user_id secretary
            // operator_for_user_id  chairman
            $users = OperatorMapping::where('operator_user_id', Auth::id())->where('status', 'A')->get();
            foreach ($users as $user) {
                if ($user->operator_for_user_id) {
                    $res[] = $user->operator_for_user_id;
                }
            }
        } catch (\Exception $e) {
            $res = [];
        }
        return $res;
    }

    public static function findDistrictByDivision($divisionId)
    {
        return LGeoDistrict::where('geo_division_id', $divisionId)->get();
    }

    public static function findDivisionByThana ($districtId)
    {
        return LGeoThana::where('geo_district_id', $districtId)->get();
    }

    public static function getRole($roleName)
    {
        $roles = [];
        foreach (Auth::user()->getRoles() as $role) {
            $roles [] = $role->role_name;
        }
        if (in_array($roleName, $roles)) {
            return true;
        }
        return false;
    }

}
