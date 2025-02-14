<?php

namespace App\Console\Commands;

use App\Http\Resources\PaymentFormResource;
use App\Mail\MonthlyPettyCashEmail;
use App\Models\Log;
use App\Models\PaymentForm;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log as FacadesLog;
use Illuminate\Support\Facades\Mail;

class PettyCashEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'petty-cash-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will send the users their monthly calculation based on petty cash entries';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        try {
            $users = User::all();
            $month = Carbon::now()->format("m");
            $year = Carbon::now()->format("Y");
            $month_year = $month . "/" . $year;


            // Step 1: Gather Data
            $data = [
                'title' => 'Invoice',
                'date' => now()->toDateString(),
                'items' => [
                    ['name' => 'Item 1', 'price' => 50],
                    ['name' => 'Item 2', 'price' => 30],
                ],
            ];

            // Step 2: Generate the PDF
            $pdf = Pdf::loadView('pdf.pettyCash', compact('data'));

            // Save the PDF temporarily
            $pdfPath = storage_path('app/public/document.pdf');
            $pdf->save($pdfPath);




            foreach ($users as $i => $v) {





                $records = PaymentForm::whereYear('date', $year)->whereMonth('date', $month)
                    ->where(function ($query) use ($v) {
                        return $query->whereJsonContains("divided_in", $v->id)->orWhere("paid_by", $v->id);
                    })
                    ->orderBy("id", "desc")->get();
                $resource = PaymentFormResource::collection($records);
                $userCalculation = myCalculation($month_year, $v->id);
                $totalPaid = $userCalculation->myTotalPaid ?? 0;
                $totalUnPaid = $userCalculation->myTotalUnPaid ?? 0;
                $settlement = $userCalculation->total ?? 0;
                $checkSettlement  = checkValueInNegative($settlement);
                $monthYear = Carbon::now()->format("F Y");
                $message = "";
                $message .= "We hope you're doing well. This is to inform you that your petty cash report for " . $monthYear . " has been generated.\n\n <br> <br>";
                if ($checkSettlement === true) {
                    $message .= "⚠️ *Action Required: You need to pay <strong>Rs." . number_format(abs($settlement), 2) . "</strong> to settle your balance.\n\n";
                } elseif ($checkSettlement === false) {
                    $message .= "🎉 *Good News: You are earning <strong>Rs." . number_format($settlement, 2) . "</strong> this month!\n\n";
                } elseif ($settlement == 0) {
                    $message .= "✅ *No action required: Your balance is settled at <strong>Rs.0</strong> for this month.\n\n";
                }

                $data = [
                    "dashboardUrl" => route('dashboard.index'),
                    'userName' => getUserName($v),
                    'Subject' => 'PETTY CASH',
                    'message' => $message ?? '',
                    'supportEmail' => 'sm.ali10@yahoo.com',
                    'projectName' => 'PETTY CASH',
                    'logo' =>  getLogos()->logo_white ?? '',
                    'monthYear' => $monthYear,
                    'records' => $resource ?? [],
                ];
                Mail::to($v->email)->send(new MonthlyPettyCashEmail($data, $pdfPath));
            }
        } catch (Exception $e) {
            FacadesLog::info("ERROR WHILE SENDING EMAIL:" . $e->getMessage() .  $e->getFile() . ' on line #' . $e->getLine());
        }
    }
}
