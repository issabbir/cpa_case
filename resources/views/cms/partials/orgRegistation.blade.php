@if(isset($caseEdit))
    {{$editData = null}}
@endif
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Organization Type</label>
                    <select class="custom-select select2 orgClass" name="org_type"
                            id="org_type">
                        <option value="">-- Please select an option --</option>
                        @foreach($org_types as $data))
                        <option value="{{$data->organization_type_id}}"
                        @if(!empty($editData)) @if($data->organization_type_id == $editData['organization_type_id']) {{'selected="selected"'}} @endif @endif
                        >{{$data->organization_type_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="required">Organization Name</label>
                    <input required type="text" id="org_name" name="org_name"
                           class="form-control"
                           value="{{old('org_name',($editData)?$editData['organization_name']:'')}}"
                           autocomplete="off">
                    @if(!empty($editData))
                        <input type="hidden" id="org_id" name="org_id"
                               class="form-control"
                               value="{{($editData)?$editData['organization_id']:''}}">
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Organization Name(Bangla)</label>
                    <input type="text" id="org_bangla" name="org_bangla"
                           class="form-control"
                           value="{{old('org_bangla',($editData)?$editData['organization_name_bng']:'')}}"
                           autocomplete="off">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="email" name="email"
                           class="form-control"
                           value="{{old('email',($editData)?$editData['email']:'')}}"
                           autocomplete="off">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Fax</label>
                    <input type="number" id="fax" name="fax"
                           class="form-control"
                           value="{{old('fax',($editData)?$editData['faxno']:'')}}"
                           autocomplete="off">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Contact No.</label>
                    <input type="number" id="contact_no" name="contact_no"
                           class="form-control"
                           value="{{old('contact_no',($editData)?$editData['contact_no']:'')}}"
                           autocomplete="off">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{--                                            <div class="col-md-4">--}}
                    <div class="form-group">
                        <label class="required">Address</label>
                        <input required type="text" id="address" name="address"
                               class="form-control"
                               value="{{old('address',($editData)?$editData['address']:'')}}"
                               autocomplete="off">
                    </div>
                    {{--                                            </div>--}}
                </div>
            </div>
        </div>
        <input type="hidden" name="outsider" id="outsider" value="Y">
        <input type="hidden" name="active" id="active" value="Y">
    </div>
</div>
@if($editData)
    <div class="row">
        <div class="col-md-12 text-right" id="cancel">
            <button type="submit" id="update"
                    class="btn btn btn-dark shadow mb-1 btn-secondary">
                Update
            </button>
            <a href="{{url('/organization-registration')}}">
                <button type="button" id="cancel"
                        class="btn btn btn-outline shadow mb-1 btn-secondary">
                    Cancel
                </button>
            </a>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-md-12 text-right" id="add">
            <button type="submit" id="add"
                    class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary">Save
            </button>
            <button type="reset" id="reset"
                    class="btn btn btn-outline shadow mb-1 btn-secondary">Reset
            </button>

        </div>
    </div>
@endif
