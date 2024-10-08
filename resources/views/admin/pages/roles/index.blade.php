@extends("admin.layouts.master")
@push("title" , $title ?? '')
@section("content")
<div class="content-body">
    <h3>Roles List</h3>
    <p class="mb-2">
        A role provided access to predefined menus and features so that depending <br />
        on assigned role an administrator can have access to what he need
    </p>
    <!-- Role cards -->
    <div class="row" style="margin-bottom:50px;">
        @if(isset($roles) && !empty($roles))
        @foreach($roles as $index => $value)
        <div class="col-xl-4 col-lg-6 col-md-6" style="margin-bottom: 20px;">
            <div class="card" style="height: 100% !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <!-- <div>Total {{count($value->users)}} users</div> <br> -->
                        <ul class="">
                            <li>Total {{count($value->users)}} users</li>
                            <li>Total {{count($value->permissions)}} permissions</li>
                        </ul>
                        <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                            @if(isset($value->users) && !empty($value->users) && count($value->users) > 0)
                            @foreach($value->users as $i => $v)
                            @if($index < 5)
                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="{{getUserName($v)}}" class="avatar avatar-sm pull-up">
                                {!! getWordInitial($v->first_name, "30px", "","") !!}
                                </li>
                                @endif
                                @endforeach
                                @else
                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="N/A" class="avatar avatar-sm pull-up">
                                    {!! getWordInitial("N/A", "30px", "","") !!}
                                </li>
                                @endif
                        </ul>
                    </div>
                    <div class="d-flex justify-content-between align-items-end mt-1 pt-25">
                        <div class="role-heading">
                            <h4 class="fw-bolder">{{$value->name ?? ''}}</h4>
                            <a href="javascript:;" class="role-edit-modal edit-role-modal-btn" data-update-url="{{route('roles.getEditRoleModalContent')}}" data-role-id="{{$value->id}}">
                                <small class="fw-bolder">Edit Role</small>
                            </a>
                        </div>
                        <!-- <a href="javascript:void(0);" class="text-body"><i data-feather="copy" class="font-medium-5"></i></a> -->
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif
        <div class="col-xl-4 col-lg-6 col-md-6" style="margin-bottom: 20px;">
            <div class="card" style="height: 100% !important;">
                <div class="row">
                    <div class="col-sm-5">
                        <div class="d-flex align-items-end justify-content-center h-100" style="margin-top: 22px;">
                            <img src="{{asset('admin/assets/app-assets/images/illustration/faq-illustrations.svg')}}" class="img-fluid mt-2" alt="Image" width="85" />
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="card-body text-sm-end text-center ps-sm-0">
                            <!-- data-bs-target="#addRoleModal" data-bs-toggle="modal"  -->
                            <a href="javascript:void(0)" class="stretched-link text-nowrap " id="add-new-role-btn">
                                <span class="btn btn-primary mb-1">Add New Role</span>
                            </a>
                            <p class="mb-0">Add role, if it does not exist</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!--/ Role cards -->

    <!-- users list  -->
    @include("admin.pages.roles.components.usersList")
    <!-- users list  -->


    <!-- Add Role Modal -->
    @include("admin.pages.roles.components.createNewRoleModal")
    <!--/ Add Role Modal -->

    <!-- Edit Role Modal -->
    @include("admin.pages.roles.components.editRoleModal")
    <!--/ Edit Role Modal -->

    <!-- add user modal  -->
    @include("admin.pages.users.components.createUserModal")
    <!-- add user modal  -->

    <!-- edit user modal -->
    @include("admin.pages.users.components.editUserModal")
    <!-- edit user modal -->


</div>
@endsection
@push("scripts")
@include("admin.pages.roles.components.scripts")
@include("admin.pages.users.components.scripts")
@endpush