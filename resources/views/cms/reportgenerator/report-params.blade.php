<div class="col-12">
    <div class="row">
        @if($report)
            @if($report->params)
                @foreach($report->params as $reportParam)
                    @if($reportParam->component == 'case_status')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}
                            </label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">-- Please select an option --</option>
                                @if(isset($caseStatusList))
                                    @foreach($caseStatusList as $list)
                                        <option value="{{$list->pass_value}}">{{$list->show_value}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @elseif($reportParam->component == 'lawyer_id')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}
                            </label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control lawyer select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">-- Please select an option --</option>

                            </select>
                        </div>
                    @elseif($reportParam->component == 'department_list')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}
                            </label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">-- Please select an option --</option>
                                @if(isset($departmentList))
                                    @foreach($departmentList as $list)
                                        <option value="{{$list->department_id}}">{{$list->department_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @elseif($reportParam->component == 'months')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}
                            </label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">-- Please select an option --</option>
                                @if(isset($months))
                                    @foreach($months as $list)
                                        <option value="{{$list->month_id}}">{{$list->month_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @elseif($reportParam->param_name == 'case_year')
                            <div class="col-md-3">
                                <label for="{{$reportParam->param_name}}"
                                       class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                                <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                        class="form-control"
                                        @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>

                                </select>
                            </div>
                    @elseif($reportParam->component == 'court_category_list')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}
                            </label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">-- Please select an option --</option>
{{--                                @if(isset($caseCategoryList))--}}
{{--                                    @foreach($caseCategoryList as $category)--}}
{{--                                        <option value="{{$category->category_id}}">{{$category->category_name}}</option>--}}
{{--                                    @endforeach--}}
{{--                                @endif--}}
                                @if(isset($courtCategoryList))
                                    @foreach($courtCategoryList as $category)
                                        <option value="{{$category->court_category_id}}">{{$category->court_category_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @elseif($reportParam->component == 'court_id')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}
                            </label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">-- Please select an option --</option>
                                @if(isset($courtList))
                                    @foreach($courtList as $court)
                                        <option value="{{$court->pass_value}}">{{$court->show_value}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @elseif($reportParam->component == 'case_id')

                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}
                            </label>
                            <select name="{{$reportParam->param_name}}" id="case_no"
                                    class="form-control select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>

                            </select>
                        </div>
                    @elseif($reportParam->component == 'location')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <select name="{{$reportParam->param_name}}" id="p_geo_division_id"
                                    class="form-control"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">Select One</option>
                                @if(isset($divisions))
                                    @foreach($divisions as $division)
                                        <option
                                            value="{{$division->geo_division_id}}">{{$division->geo_division_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="p_geo_district_id">District</label>
                            <select name="p_district_id" id="p_geo_district_id" class="form-control">
                                <option value="">Select One</option>
                            </select>
                        </div>
                        {{--<div class="col-md-3">
                            <label for="thana">Thana</label>
                            <select name="p_geo_thana_id" id="p_geo_thana_id" class="form-control">
                                <option value="">Select One</option>
                            </select>
                        </div>--}}
                    @elseif($reportParam->component == 'watchman_status')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">Status</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">Select One</option>
                                @if($watchmanStatuses)
                                    @foreach($watchmanStatuses as $watchmanStatus)
                                        <option
                                            value="{{$watchmanStatus->status_id}}">{{$watchmanStatus->status_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @elseif($reportParam->component == 'datepicker')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}
                            </label>
                            {{--<div class="input-group date datePiker" id="datepicker"
                                 data-target-input="nearest">
                                <input type="text" autocomplete="off"
                                       class="form-control datetimepicker-input"
                                       value="" name="{{$reportParam->param_name}}"
                                       data-toggle="datetimepicker"
                                       data-target="#datepicker"
                                       @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                       @endif onautocomplete="off"/>
                                <div class="input-group-append" data-target="#datepicker"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="bx bxs-calendar"></i></div>
                                </div>
                            </div>--}}
                            <div class="input-group date datePiker" id="datepicker" data-target-input="nearest">
                                <input type="text"
                                       value=""
                                       @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                       @endif name="{{$reportParam->param_name}}"
                                       class="form-control datetimepicker-input"
                                       data-toggle="datetimepicker" data-target="#datepicker"
                                       placeholder="Input Date"
                                       autocomplete="off"
                                />
                            </div>
                        </div>
                    @elseif($reportParam->component == 'active_status')
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="{{$reportParam->param_name}}"
                                       class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}
                                </label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="{{$reportParam->param_name}}"
                                               required id="active_yn" value="Y" checked
                                        />
                                        <label class="form-check-label"
                                               for="reporter_cpa_yes">Active</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="{{$reportParam->param_name}}"
                                               required id="active_yn" value="N"
                                        />
                                        <label class="form-check-label"
                                               for="reporter_cpa_no">In-Active</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @elseif($reportParam->component == 'number_data')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <input type="number" name="{{$reportParam->param_name}}" value="0"
                                   id="{{$reportParam->param_name}}"
                                   class="form-control"
                                   @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif />
                        </div>
                    @elseif($reportParam->component == 'date_range')
                        <div class="col-md-3">
                            <label for="p_date_from"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">From
                                Date</label>
                            {{--<div class="input-group date datePiker" id="p_date_from"
                                 data-target-input="nearest">
                                <input type="text" autocomplete="off"
                                       class="form-control datetimepicker-input"
                                       value="" name="p_date_from"
                                       data-toggle="datetimepicker"
                                       data-target="#p_date_from"
                                       @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                       @endif onautocomplete="off"/>
                                <div class="input-group-append" data-target="#p_date_from"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="bx bxs-calendar"></i></div>
                                </div>
                            </div>--}}
                            <div class="input-group date datePiker" id="datepicker_from" data-target-input="nearest">
                                <input type="text"
                                       value=""
                                       @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                       @endif name="p_date_from"
                                       class="form-control datetimepicker-input"
                                       data-toggle="datetimepicker" data-target="#datepicker_from"
                                       placeholder="From Date"
                                       autocomplete="off"
                                />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="p_end_date"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">To
                                Date</label>
                            {{--<div class="input-group date datePiker" id="p_date_to"
                                 data-target-input="nearest">
                                <input type="text" autocomplete="off"
                                       class="form-control datetimepicker-input"
                                       value="" name="p_date_to"
                                       data-toggle="datetimepicker"
                                       data-target="#p_date_to"
                                       @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                       @endif onautocomplete="off"/>
                                <div class="input-group-append" data-target="#p_date_to"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="bx bxs-calendar"></i></div>
                                </div>
                            </div>--}}
                            <div class="input-group date datePiker" id="datepicker_to" data-target-input="nearest">
                                <input type="text"
                                       value=""
                                       @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                       @endif name="p_date_to"
                                       class="form-control datetimepicker-input"
                                       data-toggle="datetimepicker" data-target="#datepicker_to"
                                       placeholder="To Date"
                                       autocomplete="off"
                                />
                            </div>
                        </div>
                    @elseif($reportParam->component == 'date_range2')
                        <div class="col-md-3">
                            <label for="p_bill_date_from"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">From
                                Date</label>
                            {{--<div class="input-group date datePiker" id="p_bill_date_from"
                                 data-target-input="nearest">
                                <input type="text" autocomplete="off"
                                       class="form-control datetimepicker-input"
                                       value="" name="p_bill_date_from"
                                       data-toggle="datetimepicker"
                                       data-target="#p_bill_date_from"
                                       @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                       @endif onautocomplete="off"/>
                                <div class="input-group-append" data-target="#p_bill_date_from"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="bx bxs-calendar"></i></div>
                                </div>
                            </div>--}}
                            <div class="input-group date datePiker" id="bill_datepicker_from" data-target-input="nearest">
                                <input type="text"
                                       value=""
                                       @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                       @endif name="p_bill_date_from"
                                       class="form-control datetimepicker-input"
                                       data-toggle="datetimepicker" data-target="#bill_datepicker_from"
                                       placeholder="From Date"
                                       autocomplete="off"
                                />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="p_bill_date_to"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">To
                                Date</label>
                            {{--<div class="input-group date datePiker" id="p_bill_date_to"
                                 data-target-input="nearest">
                                <input type="text" autocomplete="off"
                                       class="form-control datetimepicker-input"
                                       value="" name="p_bill_date_to"
                                       data-toggle="datetimepicker"
                                       data-target="#p_bill_date_to"
                                       @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                       @endif onautocomplete="off"/>
                                <div class="input-group-append" data-target="#p_bill_date_to"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="bx bxs-calendar"></i></div>
                                </div>
                            </div>--}}
                            <div class="input-group date datePiker" id="bill_datepicker_to" data-target-input="nearest">
                                <input type="text"
                                       value=""
                                       @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                       @endif name="p_bill_date_to"
                                       class="form-control datetimepicker-input"
                                       data-toggle="datetimepicker" data-target="#bill_datepicker_to"
                                       placeholder="To Date"
                                       autocomplete="off"
                                />
                            </div>
                        </div>
                    @elseif($reportParam->component == 'active_date_range')
                        <div class="col-md-3">
                            <label for="p_active_from"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">From
                                Date</label>
                            {{--<div class="input-group date datePiker" id="p_active_from"
                                 data-target-input="nearest">
                                <input type="text" autocomplete="off"
                                       class="form-control datetimepicker-input"
                                       value="" name="p_active_from"
                                       data-toggle="datetimepicker"
                                       data-target="#p_active_from"
                                       @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                       @endif onautocomplete="off"/>
                                <div class="input-group-append" data-target="#p_active_from"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="bx bxs-calendar"></i></div>
                                </div>
                            </div>--}}
                            <div class="input-group date datePiker" id="active_datepicker_from" data-target-input="nearest">
                                <input type="text"
                                       value=""
                                       @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                       @endif name="p_active_from"
                                       class="form-control datetimepicker-input"
                                       data-toggle="datetimepicker" data-target="#active_datepicker_from"
                                       placeholder="From Date"
                                       autocomplete="off"
                                />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="p_active_to"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">To
                                Date</label>
                            {{--<div class="input-group date datePiker" id="p_active_to"
                                 data-target-input="nearest">
                                <input type="text" autocomplete="off"
                                       class="form-control datetimepicker-input"
                                       value="" name="p_active_to"
                                       data-toggle="datetimepicker"
                                       data-target="#p_active_to"
                                       @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                       @endif onautocomplete="off"/>
                                <div class="input-group-append" data-target="#p_active_to"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="bx bxs-calendar"></i></div>
                                </div>
                            </div>--}}
                            <div class="input-group date datePiker" id="active_datepicker_to" data-target-input="nearest">
                                <input type="text"
                                       value=""
                                       @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                       @endif name="p_active_from"
                                       class="form-control datetimepicker-input"
                                       data-toggle="datetimepicker" data-target="#active_datepicker_to"
                                       placeholder="To Date"
                                       autocomplete="off"
                                />
                            </div>
                        </div>
                    @elseif($reportParam->component == 'individual_lawyer_id')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}
                            </label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2 individual_lawyer_id"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">-- Please select an option --</option>

                                @if(isset($lawyerList))
                                    @foreach($lawyerList as $lawyer)
                                        <option value="{{$lawyer->lawyer_id}}">{{$lawyer->lawyer_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @elseif($reportParam->component == 'loadCaseIdByLawyer')

                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}
                            </label>
                            <select name="{{$reportParam->param_name}}" id="loadCaseIdByLawyer"
                                    class="form-control loadCaseIdByLawyer"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>

                            </select>
                        </div>
                    @elseif($reportParam->component == 'p_case_year')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">Year</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">Select One</option>
                                @if($yearList)
                                    @foreach($yearList as $yearList)
                                        <option
                                            value="{{$yearList->year}}">{{$yearList->year}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                    @elseif($reportParam->component == 'month_list')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">Month</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">Select One</option>
                                @if($monthList)
                                    @foreach($monthList as $monthList)
                                        <option
                                            value="{{$monthList->month}}">{{$monthList->month}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @elseif($reportParam->component == 'org_status_list')
                        <div class="col-md-3">
                            <label for="p_status_list"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">Organization</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2 org-status-list"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">Select One</option>
                                @if($orgStatusList)
                                    @foreach($orgStatusList as $orgStatusList)
                                        <option
                                            value="{{$orgStatusList->status_id}}">{{$orgStatusList->status}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @elseif($reportParam->component == 'org_list')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}
                            </label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2 orgList"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">-- Please select an option --</option>
                            </select>
                        </div>
                    @elseif($reportParam->component == 'case_party_types')
                        <div class="col-md-3">
                            <label for="p_organization_status"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">Complainant/Defendant</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">Select One</option>
                                @if($casePartyTypes)
                                    @foreach($casePartyTypes as $casePartyTypes)
                                        <option
                                            value="{{$casePartyTypes->party_type_id}}">{{$casePartyTypes->party_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @else
                        <div class="col">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <input type="text" name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                   class="form-control"
                                   @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif />
                        </div>
                    @endif
                @endforeach
            @endif
            <div class="col-md-3">
                <label for="type">Report Type</label>
                <select name="type" id="type" class="form-control">
                    <option value="pdf">PDF</option>
                    <option value="xlsx">Excel</option>
                </select>
                <input type="hidden" value="{{$report->report_xdo_path}}" name="xdo"/>
                <input type="hidden" value="{{$report->report_id}}" name="rid"/>
                <input type="hidden" value="{{$report->report_name}}" name="filename"/>
            </div>
            <div class="col-md-3 mt-2">
                <button type="submit" class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary">Generate Report</button>
            </div>
        @endif
    </div>
</div>
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/pickers/daterange/daterangepicker.css')}}">
<script src="{{asset('assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/scripts/forms/select/form-select2.min.js')}}"></script>
<script type="text/javascript">

    $('#active_datepicker_to').datetimepicker({

        format: 'DD-MM-YYYY',
        //maxDate: new Date(),
        // format: 'L',
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        },
        icons: {
            time: 'bx bx-time',
            date: 'bx bxs-calendar',
            up: 'bx bx-up-arrow-alt',
            down: 'bx bx-down-arrow-alt',
            previous: 'bx bx-chevron-left',
            next: 'bx bx-chevron-right',
            today: 'bx bxs-calendar-check',
            clear: 'bx bx-trash',
            close: 'bx bx-window-close'
        }
    });
    $('#active_datepicker_from').datetimepicker({

        format: 'DD-MM-YYYY',
        //maxDate: new Date(),
        // format: 'L',
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        },
        icons: {
            time: 'bx bx-time',
            date: 'bx bxs-calendar',
            up: 'bx bx-up-arrow-alt',
            down: 'bx bx-down-arrow-alt',
            previous: 'bx bx-chevron-left',
            next: 'bx bx-chevron-right',
            today: 'bx bxs-calendar-check',
            clear: 'bx bx-trash',
            close: 'bx bx-window-close'
        }
    });
    $('#datepicker').datetimepicker({

        format: 'DD-MM-YYYY',
        //maxDate: new Date(),
        // format: 'L',
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        },
        icons: {
            time: 'bx bx-time',
            date: 'bx bxs-calendar',
            up: 'bx bx-up-arrow-alt',
            down: 'bx bx-down-arrow-alt',
            previous: 'bx bx-chevron-left',
            next: 'bx bx-chevron-right',
            today: 'bx bxs-calendar-check',
            clear: 'bx bx-trash',
            close: 'bx bx-window-close'
        }
    });
    $('#datepicker_to').datetimepicker({

        format: 'DD-MM-YYYY',
        //maxDate: new Date(),
        // format: 'L',
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        },
        icons: {
            time: 'bx bx-time',
            date: 'bx bxs-calendar',
            up: 'bx bx-up-arrow-alt',
            down: 'bx bx-down-arrow-alt',
            previous: 'bx bx-chevron-left',
            next: 'bx bx-chevron-right',
            today: 'bx bxs-calendar-check',
            clear: 'bx bx-trash',
            close: 'bx bx-window-close'
        }
    });
    $('#datepicker_from').datetimepicker({

        format: 'DD-MM-YYYY',
        //maxDate: new Date(),
        // format: 'L',
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        },
        icons: {
            time: 'bx bx-time',
            date: 'bx bxs-calendar',
            up: 'bx bx-up-arrow-alt',
            down: 'bx bx-down-arrow-alt',
            previous: 'bx bx-chevron-left',
            next: 'bx bx-chevron-right',
            today: 'bx bxs-calendar-check',
            clear: 'bx bx-trash',
            close: 'bx bx-window-close'
        }
    });
    $('#bill_datepicker_to').datetimepicker({

        format: 'DD-MM-YYYY',
        //maxDate: new Date(),
        // format: 'L',
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        },
        icons: {
            time: 'bx bx-time',
            date: 'bx bxs-calendar',
            up: 'bx bx-up-arrow-alt',
            down: 'bx bx-down-arrow-alt',
            previous: 'bx bx-chevron-left',
            next: 'bx bx-chevron-right',
            today: 'bx bxs-calendar-check',
            clear: 'bx bx-trash',
            close: 'bx bx-window-close'
        }
    });

    $('#bill_datepicker_from').datetimepicker({

        format: 'DD-MM-YYYY',
        //maxDate: new Date(),
        // format: 'L',
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        },
        icons: {
            time: 'bx bx-time',
            date: 'bx bxs-calendar',
            up: 'bx bx-up-arrow-alt',
            down: 'bx bx-down-arrow-alt',
            previous: 'bx bx-chevron-left',
            next: 'bx bx-chevron-right',
            today: 'bx bxs-calendar-check',
            clear: 'bx bx-trash',
            close: 'bx bx-window-close'
        }
    });


        $('#case_no').select2({
            placeholder: "Select",
            allowClear: true,
            ajax: {
                url: '/get-case-data',
                data: function (params) {
                    if (params.term) {
                        if (params.term.trim().length < 1) {
                            return false;
                        }
                    } else {
                        return false;
                    }

                    return params;
                },
                dataType: 'json',
                processResults: function (data) {
                    var formattedResults = $.map(data, function (obj, idx) {
                        obj.id = obj.case_id;
                        obj.text = obj.case_no;
                        return obj;
                    });
                    return {
                        results: formattedResults,
                    };
                }
            }
        });


    $("#case_no").change(function () {
        let case_no = ($('#case_no').val());
        if (case_no !== null) {
            $.ajax({
                type: 'GET',
                url: '/get-lawyer',
                data: {case_no: case_no},
                success: function (msg) {
                    $(".lawyer").html(msg);
                }
            });

        }
    });

    $(".org-status-list").change(function () {

        let status = ($('.org-status-list').val());
        if (status == 'Y') {
            $.ajax({
                type: 'GET',
                url: '/get-organization-option',
                data: {status: status},
                success: function (msg) {
                    $(".orgList").html(msg);

                }
            });
        } else {
            $(".orgList").html('');
        }
    });

    $('#p_geo_division_id').change(function () {
        var division = $(this).val();
        $.ajax({
            type: 'get',
            url: '/get-district-ajax',
            data: {division: division},
            success: function (msg) {
                $("#p_geo_district_id").html(msg);
            }
        });
    });

    $(".individual_lawyer_id").change(function () {
        let lawyer_id = $('.individual_lawyer_id').val();//alert(lawyer_id);

        if (lawyer_id !== null) {
            $.ajax({
                type: 'get',
                url: '/get--lawyer-case-ajax',
                data: {lawyer_id: lawyer_id},
                success: function (msg) {
                    $("#loadCaseIdByLawyer").html(msg);
                }
            });

        }
    });

    function datepicker(elem) {
        $(elem).datetimepicker({
            format: 'DD-MM-YYYY', //YYYY-MM-DD
            widgetPositioning: {
                horizontal: 'left',
                vertical: 'bottom'
            },
            icons: {
                date: 'bx bxs-calendar',
                previous: 'bx bx-chevron-left',
                next: 'bx bx-chevron-right'
            }
        });
    }
    function populateYear() {
        var year = 2022;
        var till = 2099;
        var options = "";
        for (var y = year; y <= till; y++) {
            options += "<option " + "value='" + y + "'" + ">" + y + "</option>";
        }
        document.getElementById("case_year").innerHTML = options;
    }

    $(document).ready(function () {
        populateYear();
        datepicker('#datepicker');
        datepicker('#p_date_to');
        datepicker('#p_date_from');
        datepicker('#p_bill_date_from');
        datepicker('#p_bill_date_to');
        datepicker('#p_active_to');
        datepicker('#p_active_from');
        //getCaseData();
        //districts("#p_geo_division_id", '#p_geo_district_id', APP_URL+'/ajax/get-district-ajax/', '#p_geo_thana_id');
        //thanas('#p_geo_district_id', APP_URL+'/ajax/get-thana-ajax/', '#p_geo_thana_id');
    });
</script>
