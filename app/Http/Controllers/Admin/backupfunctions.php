<?php



$record = new MonthlyCalculation();
if ($role != "Super Admin") {
    $record =  $record->where("user_id", $user_id);
}
$records = $record->where("month_year", $month_year)->orderBy("id", "desc")->select("*");
return DataTables::of($records)
    ->addIndexColumn()
    ->addColumn("paid_by", function ($model) {
        return isset($model->form->paidBy->name) && !empty($model->form->paidBy->name) ?  objectWithHtml($model->form->paidBy->name) : '-';
    })
    ->addColumn("food_item", function ($model) {
        $title = "";
        if (isset($model->form->title) && !empty($model->form->title)) {
            $title .= '<h4>';
            $title .= $model->form->title;
            $title .= '</h4>';
        }
        return $title;
    })
    ->addColumn("transaction_type", function ($model) {
        if (isset($model->transaction_type) && !empty($model->transaction_type)) {
            $img = '';
            $trans_type = '';

            if ($model->transaction_type == 1) {
                $img = asset('icons/trending-up.svg');
                $trans_type = '<span class="badge bg-warning" style="color:black">
                                       Credit
                                   </span>';
            } elseif ($model->transaction_type == 2) {
                $img = asset('icons/trending-down.svg');
                $trans_type = '<span class="badge bg-success" style="color:black">
                                       Contribute
                                   </span>';
            }
            return $trans_type;
        } else {
            return "-";
        }
    })
    ->addColumn("divided_in", function ($model) {
        $data = $model->form->divided_in;
        $data = json_decode($model->form->divided_in);
        $span = "<ul>";
        foreach ($data as $value) {
            $class = "";
            if ($model->form->paid_by == $value) {
                $class = 'text-success';
            } else {
                $class = 'text-danger';
            }
            $span .= "<li>";
            $span .= '<span class="' . $class . '">';
            $span .= getUserName(getUser($value));
            $span .= '</span>';
            $span .= "</li>";
        }
        $span .= "</ul>";
        return $span;
    })
    ->addColumn("total_amount", function ($model) {
        return "Rs." . number_format($model->form->total_amount, 2)  ?? 0;
    })
    ->addColumn("amount", function ($model) {
        return "Rs." . number_format($model->amount, 2) ?? 0;
    })

    ->addColumn("entry_date", function ($model) {
        return isset($model->date) && !empty($model->date) ? formatDate($model->date) : '-';
    })
    ->addColumn("created_at", function ($model) {
        return isset($model->created_at) && !empty($model->created_at) ? formatDate($model->created_at) : '-';
    })
    ->rawColumns(['paid_by', 'total_amount', 'divided_in', 'transaction_type', 'amount', 'date', 'created_at', 'food_item'])
    ->make(true);