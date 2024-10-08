<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\EmailToCustomer;
use App\Models\CustomerForm;
use App\Models\CustomerFormStatus;
use App\Models\Followup;
use Google\Service\CloudControlsPartnerService\Customer;
use Google\Service\Monitoring\Custom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

class CustomerFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize("coorporate-form-list");
        $data['title'] = "Coorporate Forms";
        if ($request->ajax()) {
            $forms = CustomerForm::with("files")->orderBy("id", "desc")->select("*");
            return DataTables::of($forms)
                ->addIndexColumn()
                ->addColumn("name", function ($model) {
                    return view('admin.pages.customer_forms.components.userView', ['record' => $model])->render();
                })
                ->addColumn("ip", function ($model) {
                    return $model->ip ?? '-';
                })
                ->addColumn("color", function ($model) {
                    $column = "";
                    $column .= '<div class="row">';
                    $column .= '<div class="col-md-6">';
                    $column .= isset($model->hasColor->name) && !empty($model->hasColor->name) ? strtoupper($model->hasColor->name) : "-";
                    $column .= '</div>';
                    if (isset($model->hasColor->code) && !empty($model->hasColor->code)) {
                        $column .= '<div class="col-md-2">';
                        $column .= '<div style="width: 10px;height:10px;border-radius: 100%;background:' . $model->hasColor->code . '"></div>';
                        $column .= '</div>';
                    }
                    $column .= '</div>';
                    return $column;
                })
                ->addColumn("phone", function ($model) {
                    $column = "";
                    $column .=  isset($model->country->phonecode) && !empty($model->country->phonecode) ? '+' . $model->country->phonecode . "-"  : '';
                    $column .=  $model->phone_number ?? '';
                    return $column;
                })
                ->addColumn("country", function ($model) {
                    $column = "";
                    $column .=  isset($model->country->name) && !empty($model->country->name) ?  $model->country->name : '';
                    return $column;
                })
                ->addColumn("status", function ($model) {
                    $form = $model;
                    if ($form->status != 6) {
                        $statuses = CustomerFormStatus::where("id", "!=", 6)->get();
                    }
                    if ($form->status == 5) {
                        $statuses = CustomerFormStatus::where("id", 6)->get();
                    }
                    if ($form->status == 6) {
                        $statuses = CustomerFormStatus::where("id", "!=", 6)->get();
                    }

                    return view("admin.pages.customer_forms.components.changeStatusDropdown", compact("form", "statuses"));
                })
                ->addColumn("status_id", function ($model) {
                    return $model->status ?? null;
                })
                ->addColumn("eye", function ($form) {
                    $route = route('coorporate-forms.show', $form->id);
                    $column = "";
                    $column .= '<a href="javascript:;" class="view-customer-btn" data-form-id="' . $form->id . '" data-route="' . $route . '">';
                    $column .= '<span class="badge  bg-primary">';
                    $column .= 'View';
                    $column .= '</span>';
                    $column .= '</a>';
                    return $column;
                })
                ->addColumn('actions', function ($model) {
                    $form = $model;
                    return view("admin.pages.customer_forms.components.actions", compact("form"));
                })
                ->rawColumns(['name', 'color', 'eye', 'status', 'actions'])
                ->make(true);
        }
        return view("admin.pages.customer_forms.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize("coorporate-form-create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize("coorporate-form-create");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $this->authorize("coorporate-form-view");
        $data['form'] = CustomerForm::where("id", $id)->first();
        if ($request->ajax()) {
            if (isset($data['form']) && !empty($data['form'])) {
                $data['title'] = "View " . getUserName($data['form']);
                $data['ip_data'] = isset($data['form']->ip_data) && !empty($data['form']->ip_data) ? json_decode($data['form']->ip_data)  : null;
                $view =  view("admin.pages.customer_forms.components.show", $data)->render();
                return jsonResponse(true, $view, "View Coorporate Form", 200);
            } else {
                return jsonResponse(false, [], "Form not found!", 200);
            }
        } else {
            if (isset($data['form']) && !empty($data['form'])) {
                $data['title'] = "View " . getUserName($data['form']);
                $data['followups'] = Followup::where("model_name", "\App\Models\CustomerForm")->where("model_id", $id)->orderby("id", "desc")->get();
                $data['ip_data'] = isset($data['form']->ip_data) && !empty($data['form']->ip_data) ? json_decode($data['form']->ip_data)  : null;
                return view("admin.pages.customer_forms.show", $data);
            } else {
                return redirect()->route("coorporate-forms.index")->with("error", "Form not found!");
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize("coorporate-form-edit");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize("coorporate-form-edit");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize("coorporate-form-delete");
    }

    public function addFollowupModalContent(Request $request)
    {
        $this->authorize("coorporate-form-addfollowup");
        $form = CustomerForm::where("id", $request->form_id)->first();
        $route = $request->route;
        $statuses = CustomerFormStatus::all();
        $view = view("admin.pages.customer_forms.components.addFollowupModalContent", compact("form", "route", "statuses"))->render();
        return jsonResponse(true, $view, "Add Followup Modal Content", 200);
    }
    public function addFollowup(Request $request)
    {
        $this->authorize("coorporate-form-addfollowup");
        if (isset($request->form_id) && !empty($request->form_id)) {
            if (isset($request->remarks) && !empty($request->remarks)) {
                $followup = saveFollowup([
                    'title' => 'Followup Added',
                    'causer_id' => getUser()->id,
                    'model_id' => $request->form_id ?? null,
                    'model_name' => "\App\Models\CustomerForm" ?? null,
                    'remarks' => $request->remarks ?? null,
                    'status' => $request->add_followup_status ?? null,
                    'type' => 1,
                ]);
                if ($followup->id) {
                    $form = CustomerForm::where("id", $request->form_id)->first();
                    if (isset($form) && !empty($form)) {
                        $update = $form->update([
                            "status" => $request->add_followup_status,
                            "last_followup_id" => $followup->id,
                        ]);
                        if ($update > 0) {
                            return jsonResponse(true, [], "Followup has been saved", 200);
                        }
                    }
                }
            } else {
                return jsonResponse(false, [], "Remarks can not be empty not found", 200);
            }
        } else {
            return jsonResponse(false, [], "Form ID not found", 200);
        }
    }

    public function viewFollowups(Request $request)
    {
        $this->authorize("coorporate-form-viewfollowups");
        $followups = Followup::where("model_name", "\App\Models\CustomerForm")->where("model_id", $request->form_id)->orderby("id", "desc")->get();
        if (isset($followups) && !empty($followups)) {
            $view = view("admin.pages.customer_forms.components.viewFollowups", compact("followups"))->render();
            return jsonResponse(true, $view, "Followups List", 200);
        } else {
            return jsonResponse(false, [], "No Followups Found", 200);
        }
    }
    public function sendEmailToCustomer(Request $request)
    {
        $this->authorize("coorporate-form-sendemail");
        if (isset($request->email_form_id) && !empty($request->email_form_id)) {
            $form = CustomerForm::where("id", $request->email_form_id)->first();
            if (!empty($form)) {
                $data = [
                    "customerName" => getUserName($form) ?? '',
                    'storeName' => $form->store_name ?? '-',
                    "subject" => $request->subject ?? '',
                    "message" => $request->message ?? '',
                    "logo" => asset('logos/black.png'),
                ];
                $followup = saveFollowup([
                    'title' => "Email send",
                    'causer_id' => getUser()->id,
                    'model_id' => $form->id ?? null,
                    'model_name' => "\App\Models\CustomerForm" ?? null,
                    'remarks' =>  $request->message ?? null,
                    'status' => $form->status ?? null,
                    'type' => 2,
                    'data' => [
                        'email' => $request->to_email ?? null,
                        'causer_id' => getUser()->id,
                        'form_id' => $form->id,
                    ]
                ]);
                Mail::to($request->to_email)->send(new EmailToCustomer($data));
                return jsonResponse(true, $followup, "Email has been sent", 200);
            } else {
                return jsonResponse(false, [], "Invalid Form Data", 200);
            }
        }
    }
    public function getChangeStatusModalContent(Request $request)
    {
        $this->authorize("coorporate-form-status");
        $form = CustomerForm::where("id", $request->form_id)->first();
        $status_id = $request->status_id;
        $view = view("admin.pages.customer_forms.components.changeStatusRemarksModalContent", compact("form", "status_id"))->render();
        return jsonResponse(true, $view, "Edit Modal Content", 200);
    }
    public function updateFormStatus(Request $request)
    {
        $this->authorize("coorporate-form-status");
        if (isset($request->customer_form_id) && !empty($request->customer_form_id)) {
            $form = CustomerForm::where("id", $request->customer_form_id)->first();
            if (!empty($form)) {
                $update = $form->update([
                    "status" => $request->new_status_id,
                ]);
                if ($update > 0) {
                    $followup = saveFollowup([
                        "title" => "Status Update",
                        "causer_id" => getUser()->id ?? null,
                        "model_id" => $form->id ?? null,
                        "model_name" => "\App\Models\CustomerForm",
                        "status" => $request->new_status_id ?? null,
                        "remarks" => $request->remarks ?? null,
                        'type' => 3,
                    ]);
                    if ($followup->id) {
                        $form = CustomerForm::where("id", $request->customer_form_id)->first();
                        $updateForm = $form->update(['last_followup_id' => $followup->id]);
                        if ($updateForm > 0) {
                            return jsonResponse(true, [], "Status has been updated", 200);
                        } else {
                            return jsonResponse(false, [], "Status has been updated but failed to make history ", 200);
                        }
                    } else {
                        return jsonResponse(false, [], "Status has been updated but failed to make history ", 200);
                    }
                } else {
                    return jsonResponse(false, [], "Failed to update the status ", 200);
                }
            }
        }
    }



    public function getFormFiles(Request $request)
    {
        if (isset($request->form_id) && !empty($request->form_id)) {
            $form = CustomerForm::where("id", $request->form_id)->first();
            if (!empty($form) && !empty($form->files)) {
                $filesArray = [];
                $files = $form->files;
                if (isset($files) && !empty($files)) {
                    foreach ($files as $value) {
                        $file = "";
                        $file .= '<div class="' . $request->col . '">';
                        $file .= '<a href="' . asset(config('project.upload_path.customer_forms') . $value->file) . '" target="_blank">';
                        $file .= '<div class="card">';
                        $file .= '<div class="card-body" style="padding: 0px;">';
                        if (isset($value->file) && !empty($value->file)) {
                            if (checkFileExtension($value->file) == "image") {
                                $file .= '<img src="' . asset(config('project.upload_path.customer_forms_thumb') . $value->file) . '" alt="" class="lazy-loading-img">';
                            } elseif (checkFileExtension($value->file) == "pdf") {
                                $file .= '<img src="' . asset('doc-icon.png') . '" alt="" class="lazy-loading-img">';
                            }
                        }
                        $file .=   '</div>';
                        $file .= '</div>';
                        $file .= '</a>';
                        $file .= '</div>';
                        $filesArray[] = $file;
                    }
                    return jsonResponse(true, $filesArray, "Files", 200);
                } else {
                    return jsonResponse(false, [], "No Files Found", 200);
                }
            } else {
                return jsonResponse(false, [], "No Files Found", 200);
            }
        } else {
            return jsonResponse(false, [], "FORM ID NOT FOUND", 200);
        }
    }
}
