<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 4/20/20
 * Time: 11:13 AM
 */

namespace App\Managers\Secdbms\Ims;


use App\Contracts\Secdbms\Ims\CommonContract;
use Illuminate\Support\Facades\DB;


class CommonManager implements CommonContract
{
    public function commonDropDownLookupsList($look_up_type = null,$column_selected = null){
        $query = "Select common_lookups_list('".$look_up_type."') from dual" ;
        $entityList = DB::select($query);
        $entityOption = [];
        $entityOption[] = "<option value=''>Please select an option</option>";
        foreach ($entityList as $item) {
            $entityOption[] = "<option value='".$item->passing_value."'".($column_selected == $item->passing_value ? 'selected':'').">".$item->show_velue."</option>";
        }
        return $entityOption;
    }

    public function commonDropDownLookupsListWithMethod($method_name, $look_up_type = null,$column_selected = null,$pass_value = null,$show_value = null){
        if($look_up_type){
            //$query = "Select ".$method_name."('".$look_up_type."') from dual" ;
            $query=DB::SELECT("SELECT account_payables.".$method_name."('".$look_up_type."')) from dual");
        }else{
            //$query = "Select ".$method_name."() from dual ";
            $query=DB::SELECT("SELECT account_payables.".$method_name."() from dual");
        }

        //$entityList = DB::select($query);
        $entityList=$query;
        $entityOption = [];
        $entityOption[] = "<option value=''>Please select an option</option>";
        foreach ($entityList as $item) {
            if($pass_value){
                $item->pass_value = $pass_value;
            }
            if($show_value){
                $item->show_value = $show_value;
            }
            $entityOption[] = "<option value='".$item->pass_value."'".($column_selected == $item->pass_value ? 'selected':'').">".$item->show_value."</option>";
        }
        return $entityOption;
    }

}
