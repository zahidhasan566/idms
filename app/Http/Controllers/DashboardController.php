<?php

namespace App\Http\Controllers;

use App\Models\DealerReceiveInvoiceDetails;
use App\Models\Invoice;
use App\Models\InvoiceReceiveSurvey;
use App\Models\InvoiceReceiveSurveyAnswers;
use App\Models\OrderInvoiceDetails;
use App\Models\OrderInvoiceMaster;
use App\Services\DealerReceiveInvoice;
use App\Services\SpPaginationService;
use App\Traits\CommonTrait;
use App\Traits\DashboardTrait;
use Carbon\Carbon;
use Dompdf\FrameDecorator\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use DashboardTrait,CommonTrait;
    public function receivables(Request $request)
    {
        $take = $request->take;
        $page = $request->page;
        $offset = SpPaginationService::getOffset($page, $take);
        $search = $request->search;
        $userId = Auth::user()->UserId;
        $sp = "EXEC sp_receive_products '$userId','$take','$offset'";

        return $this->doLoadMyReceivable($sp, $take, $offset);
    }

    public function getReceivableById($invoiceNo)
    {
        $userId = Auth::user()->UserId;
        return $this->doLoadMyReceivableById($userId,$invoiceNo);
//        return $this->doLoadMyReceivableById($invoiceNo)->get();
    }

    public function storeSurvey(Request $request)
    {
        $request->validate([
            'invoiceNo' => 'required'
        ]);
        try {
            $invoiceNo = $request->invoiceNo;
            $surveyAnswerIDs = $request->SurveyAnswerIDs;
            $comment = $request->SurveyComment;
            $userId = Auth::user()->UserId;
            foreach ($surveyAnswerIDs as $key => $survey){
                $id = (int)$survey;
                $survey = new InvoiceReceiveSurvey();
                $survey->InvoiceNo = $invoiceNo;
                $survey->SurveyQuestionID = $key;
                $survey->SurveyAnswerID   = $id;
                $survey->Comment = $comment;
                $survey->EntryBy = $userId;
                $survey->EntryDate = Carbon::now();
                $survey->EntryIPAddress = \request()->ip();
                $survey->save();
            }

            $invoiceUpdate = Invoice::where('InvoiceNo','=',$invoiceNo)->first();
            $invoiceUpdate->IsReceiveSurvey = 'Y';
            $invoiceUpdate->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Survey Submitted Successfully',
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!'.$exception->getMessage()
            ],500);
        }
    }

    public function storeReceivable(Request $request)
    {
        $request->validate([
            'details' => 'required',
            'invoiceNo' => 'required'
        ]);
        try {
            $invoiceNo = $request->invoiceNo;
            $userId = Auth::user()->UserId;
            $orderDetails = $request->details;
            $dealerReceiveInvoice = new DealerReceiveInvoice($userId,$invoiceNo,$orderDetails);
            $data = $dealerReceiveInvoice->doStorePartialReceivable($userId,$invoiceNo,$orderDetails);
            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Receiving failed! Something went wrong!'
                ],500);
            }
//            if (isset($data[0]->rcount) && intval($data[0]->rcount) > 0) {
//                $receiveId = $data[0]->ReceiveID;
//                $receiveDetails = DealerReceiveInvoiceDetails::where('ReceiveID',$receiveId)->get();
//                $invoice = Invoice::where('InvoiceNo',$invoiceNo)->where('Business','P')->first();
//                if ($invoice) {
//                    if (!empty($receiveDetails)) {
//                        foreach ($receiveDetails as $detail) {
//                            $this->doUpdateStock($userId,$detail->ProductCode,$detail->ReceivedQnty);
//                        }
//                    }
//                }
//            }
            return response()->json([
                'status' => 'success',
                'message' => 'Receive Successful',
                'data' => $data
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!',
                'error' => $exception->getMessage() . '-' . $exception->getLine()
            ],500);
        }
    }

    public function invoiceReceiveSurveyData()
    {
        $questions = DB::table('InvoiceReceiveSurveyQuestions')->select('SurveyQuestionID','SurveyQuestion')->get();
        $questions->map(function ($question) {
            $question->SurveyQuestion = mb_convert_encoding($question->SurveyQuestion,'UTF-8', 'UTF-8');
        });
        return response()->json([
            'questions' => $questions,
            'answers' => DB::table('InvoiceReceiveSurveyAnswers')->select('SurveyAnswerID','SurveyAnswer','SurveyQuestionID')->get()
        ]);
    }
    public function getCurrenBalanceData(){

        $spareParts = $this->customerCurrentBalance('P');
        $bike = $this->customerCurrentBalance('C');
//        $userId = Auth::user()->UserId;
//        $sql = "exec usp_doLoadCustomerDueFroDMS '$userId'";
//
//        $conn = DB::connection('sqlsrvread');
//        $pdo = $conn->getPdo()->prepare($sql);
//        $pdo->execute();
//        $res = array();
//        do {
//            $rows = $pdo->fetchAll(\PDO::FETCH_ASSOC);
//            $res[] = $rows;
//        } while ($pdo->nextRowset());

        return response()->json([
            'spareParts' => $spareParts,
            'bike' => $bike,
        ]);
    }

    public function getAllMyOrders(Request $request){
        $masterCode = Auth::user()->UserId;
        $sql ="SELECT  oim.orderno as OrderNo, oim.orderdate as OrderDate, oim.ordertime as OrderTime , Convert(INT,sum((UnitPrice+VAT)*Quantity)) as Total
                FROM OrderInvoiceMaster oim 
                INNER JOIN OrderInvoiceDetails oid ON oim.OrderNo = oid.OrderNo 
                INNER JOIN Customer C ON C.CustomerCode = oim.MasterCode
                LEFT JOIN CustomerType CT ON CT.CustomerType = C.CustomerType
                WHERE oim.MasterCode = '$masterCode'
                GROUP BY oim.OrderNo, oim.orderdate ,oim.ordertime 
                ORDER BY oim.OrderTime DESC";
        $list =DB::select(DB::raw($sql));
        return response()->json([
           'data' => $list
        ]);


    }
    public function getOrderDetails($orderNo){
        $Order =intval($orderNo);
        $sql=$this->doLoadMyOrders($Order);
        return response()->json([
           'data' => $sql
        ]);
    }
    public function pendingOrders(Request $request){
        $UserId = Auth::user()->UserId;
        $RoleId = Auth::user()->RoleId;
        $sql = $this->doLoadPendingOrders($RoleId,$UserId);
        return response()->json([
            'data' => $sql
        ]);
    }
    public function storeApproved(Request $request)
    {
        try {

            $orderNo = $request->orderNo;
            $actionType = $request->actionType;
            $userId = Auth::user()->UserId;
            $roleId = Auth::user()->RoleId;

            $sql = DB::statement("exec usp_OrderInvoiceDetailsLogInsert '$userId', '$orderNo'");

            if ($actionType=='approved'){
                $approval = OrderInvoiceMaster::where('OrderNo',$orderNo)->first();
                if ($roleId==='tm' ||$roleId==='se' ){
                    $approval->Level1Approved='Y';
                    $approval->Level1ApprovedBy=$userId;
                    $approval->Level1ApprovedDate=Carbon::now();
                    $approval->Level1ApprovedIP=\request()->ip();

                }elseif($roleId==='hos' ||$roleId==='hose'){
                    $approval->Level2Approved='Y';
                    $approval->Level2ApprovedBy=$userId;
                    $approval->Level2ApprovedDate=Carbon::now();
                    $approval->Level2ApprovedIP=\request()->ip();
                }else{
                    $approval->Level3Approved='Y';
                    $approval->Level3ApprovedBy=$userId;
                    $approval->Level3ApprovedDate=Carbon::now();
                    $approval->Level3ApprovedIP=\request()->ip();
                }
            }elseif ($actionType=='reject'){
                $approval = OrderInvoiceMaster::where('OrderNo',$orderNo)->first();
                if ($roleId==='tm' ||$roleId==='se' ){
                    $approval->Level1Approved='C';
                    $approval->Level1ApprovedBy=$userId;
                    $approval->Level1ApprovedDate=Carbon::now();
                    $approval->Level1ApprovedIP=\request()->ip();

                }elseif($roleId==='hos' ||$roleId==='hose'){
                    $approval->Level2Approved='C';
                    $approval->Level2ApprovedBy=$userId;
                    $approval->Level2ApprovedDate=Carbon::now();
                    $approval->Level2ApprovedIP=\request()->ip();
                }else{
                    $approval->Level3Approved='C';
                    $approval->Level3ApprovedBy=$userId;
                    $approval->Level3ApprovedDate=Carbon::now();
                    $approval->Level3ApprovedIP=\request()->ip();
                }
            }else{
                DB::beginTransaction();
                $preparedArray = $request->products;
                $unique_check = collect($preparedArray);
                $unique_check = $unique_check->pluck('ProductCode');
                $productCodes = [];
                foreach ($unique_check as $each) {
                    $productCodes[] = $each;
                }
                $unique = array_unique($productCodes);

                $unique_check = $unique_check->toArray();

                $result = array_values(array_diff_key($unique_check, $unique));

                if ($result) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'You have added '.$result[0].' multiple times!'
                    ]);
                }
                $no =$preparedArray[0]['OrderNo'];
                $approval = OrderInvoiceMaster::where('OrderNo',$no)->first();
                $sql = DB::statement("exec usp_OrderInvoiceDetailsLogInsert '$userId', '$no'");
                if (!empty($approval)){
                    if ($roleId==='tm' ||$roleId==='se' ){
                        $approval->Level1Approved='Y';
                        $approval->Level1ApprovedBy=$userId;
                        $approval->Level1ApprovedDate=Carbon::now();
                        $approval->Level1ApprovedIP=\request()->ip();
                    }elseif($roleId==='hos' ||$roleId==='hose'){
                        $approval->Level2Approved='Y';
                        $approval->Level2ApprovedBy=$userId;
                        $approval->Level2ApprovedDate=Carbon::now();
                        $approval->Level2ApprovedIP=\request()->ip();
                    }else{
                        $approval->Level3Approved='Y';
                        $approval->Level3ApprovedBy=$userId;
                        $approval->Level3ApprovedDate=Carbon::now();
                        $approval->Level3ApprovedIP=\request()->ip();
                    }
                }
                foreach ($preparedArray as $key){

                    $details = OrderInvoiceDetails::where('OrderNo',$key['OrderNo'])->where('ProductCode',$key['ProductCode'])->first();

                    if(!empty($details)){
                        OrderInvoiceDetails::where('OrderNo',$key['OrderNo'])->where('ProductCode',$key['ProductCode'])->update([
                            'Quantity'=>$key['Quantity']
                        ]);
                    }else{
                        if ( $key['Quantity'] >0 ){
                            $details = new OrderInvoiceDetails();
                            $details->OrderNo = $key['OrderNo'];
                            $details->ProductCode = $key['ProductCode'];
                            $details->Quantity = $key['Quantity'];
                            $details->UnitPrice = $key['UnitPrice'];
                            $details->Vat = $key['VAT'];
                            $details->save();
                        }
                    }

                }


            }
            $approval->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Successful'
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!',
                'error' => $exception->getMessage()
            ],500);
        }
    }
    public function editApproved(Request $request)
    {
        $request->validate([
            'orderNo' => 'required'
        ]);
        try {
            $orderNo = $request->orderNo;
            $list = DB::table('OrderInvoiceMaster as m')
                ->select('m.OrderNo','d.ProductCode','p.ProductName','d.Quantity','d.UnitPrice','d.VAT')
                ->join('OrderInvoiceDetails as d','d.OrderNo','=','m.OrderNo')
                ->join('Product as p','p.ProductCode','=','d.ProductCode')
                ->where('m.OrderNo',$orderNo)
                ->get();

            return response()->json([
                'data' => $list
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!',
                'error' => $exception->getMessage()
            ],500);
        }
    }
}
