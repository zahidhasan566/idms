<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\DealarReceiveInvoiceDetails;
use App\Models\DealerStock;
use App\Models\FlagshipStockAdjustmentMaster;
use App\Models\Logistics\DealerDocument;
use App\Models\SpareParts\DealerStockAdjustmentDetails;
use App\Models\SpareParts\DealerStockAdjustmentMaster;
use App\Traits\CommonTrait;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Gd\Shapes\RectangleShape;

class StockController extends Controller
{
    use CommonTrait;
    public function sparePartsindex( Request $request) {

        $take = $request->take;
        $search = $request->search;
        $customerCode = Auth::user()->UserId;
        $customer = $request->customer;

            $business = 'P';
            $list = DB::table('DealarStock as d')
                ->select(DB::raw("ROW_NUMBER() Over (Order by P.ProductCode) As SL "),
                    'd.ProductCode',
                    'd.MasterCode',
                    'customer.CustomerName',
                    'p.ProductName','p.PartNo','r.RackName',
                    DB::raw("Convert(numeric(18,2), p.MRP) as MRP,
                    Convert(numeric(18,2), d.CurrentStock) as CurrentStock,
                    Convert(numeric(18,2), p.UnitPrice) as UnitPrice,
                    Convert(numeric(18,2), ((p.UnitPrice +p.Vat)*d.CurrentStock)) as TotalPrice"))
                ->join('Product as p','p.ProductCode','=','d.ProductCode')
                ->join('Customer','Customer.CustomerCode','d.MasterCode')
                ->leftjoin("ProductRackAllocation as r",function($join){
                    $join->on("r.CustomerCode","=","d.MasterCode")
                        ->on("r.ProductCode","=","p.ProductCode");
                });

                if (!empty($customer)){
                    $list->where('d.MasterCode','=', $customer);
                }else{
                    $list->where('d.MasterCode','=', $customerCode);
                }

                $list->where('p.business' , '=' , $business);

              if ($search){
                  $list ->where(function ($q) use ($search) {
                      $q->where('P.ProductCode', 'like', '%' . $search . '%');
                      $q->Orwhere('P.PartNo', 'like', '%' . $search . '%');
                      $q->Orwhere('P.ProductName', 'like', '%' . $search . '%');
                  });
              }
            if ($request->type === 'export') {
                return response()->json([
                    'data' => $list->get(),
                ]);
            } else {
                return $list->paginate($take);
            }
    }
    public function bikeIndex(Request $request){
        $userId = Auth::user()->UserId;
        $productCode = 'C';
        $customer = $request->customer ? $request->customer : $userId ;
        $list = DB::select("exec usp_reportProductStock '$customer','','$productCode','$userId','20','%'");


        if ($request->type === 'export') {
            return response()->json([
                'data' => $list,
            ]);
        } else {
            return response()->json([
                'data' => $list,
            ]);
        }


    }
    public function allocationList(Request $request) {
        $take = $request->take;
        $search = $request->search;
        $customerCode=Auth::user()->UserId;
        $allocation = DB::table("ProductRackAllocation as A")
            ->select("A.ProductCode","P.ProductName","A.RackName","A.BinNumber")
            ->join("Product as P ","P.ProductCode","=","A.ProductCode")
            ->where("A.CustomerCode","=",$customerCode)
            ->where(function ($q) use ($search) {
                $q->where('P.ProductCode', 'like', '%' . $search . '%');
                $q->Orwhere('A.RackName', 'like', '%' . $search . '%');
                $q->Orwhere('P.ProductName', 'like', '%' . $search . '%');
                $q->Orwhere('A.BinNumber', 'like', '%' . $search . '%');

            });

        if ($request->type === 'export') {
            return response()->json([
                'data' => $allocation->get(),
            ]);
        } else {
            return $allocation->paginate($take);
        }

    }
    public function getAllStockProduct($code){
        $product = $this->getBusinessWiseProduct('P',$code,'0');
        return response()->json([
           'data'=>$product
        ]) ;
    }
    public function storeRackAllocation(Request $request){

        $validator = Validator::make($request->all(), [
            'productCode' => 'required',
            'rackName' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 401, 'error' => $validator->errors()]);
        }

        try{

            $userId = Auth::user()->UserId;
            $product = $request->productCode;
            $productCode = $product['productcode'];

            $rackName = $request->rackName;
            $binNo = $request->binNo;
//            return "exec usp_doInsertRackAllocation '$userId', '$productCode', '$rackName'";
            $rack =DB::select(DB::raw("exec usp_doInsertRackAllocation '$userId', '$productCode', '$rackName','$binNo' "));

            return response()->json([
                'status' => 'Success',
                'message' => 'Rack Allocate Added Successfully'
            ],200);
        }
        catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!' . $exception->getMessage()
            ], 500);
        }
    }

    public function mslIndex(Request $request)
    {
        $search = $request->search;
        $userId = Auth::user()->UserId;
        $customer = $request->customer;
        if (!empty($customer)){

            $msl = DB::select(" exec usp_MSLStockList '$customer' ,'$search'");

        }else{
            $msl = DB::select(" exec usp_MSLStockList '$userId' ,'$search'");
        }

        if ($request->type === 'export') {
            return response()->json([
                'data' => $msl,
            ]);
        } else {
            return response()->json([
                'data' => $msl,
            ]);
        }
    }

    public function getSparePartsHistory(Request $request){

        $receiveHistory = DealarReceiveInvoiceDetails::select('DealarReceiveInvoiceMaster.*','DealarReceiveInvoiceDetails.*',)
            ->join('DealarReceiveInvoiceMaster','DealarReceiveInvoiceMaster.ReceiveID','DealarReceiveInvoiceDetails.ReceiveID')
            ->where('DealarReceiveInvoiceDetails.ProductCode', '=', $request->ProductCode)
            ->where('DealarReceiveInvoiceMaster.MasterCode', '=', $request->MasterCode)
           ->get();

        return response()->json([
            'receiveHistory' => $receiveHistory,
        ],200);
    }

    public function getDemoExcelFile()
    {
        $excel_url = url('/') . '/assets/file/stock_file_upload_format.xls';
        return $excel_url;
    }

    public function storeFlagshipSpareParts(Request $request){
        $importData = $request->ExcelData;
        try {

            DB::beginTransaction();
            //Adjustment master
            $dealerAdjustmentMaster = new  FlagshipStockAdjustmentMaster();
            $dealerAdjustmentMaster->AdjustmentDate = Carbon::now()->format('Y-m-d');
            $dealerAdjustmentMaster->MasterCode = 'FlagshipDealer';
            $dealerAdjustmentMaster->EntryDate = Carbon::now();
            $dealerAdjustmentMaster->EntryBy = Auth::user()->UserId;
            $dealerAdjustmentMaster->IPAddress = $request->ip();
            $dealerAdjustmentMaster->save();

            foreach ($importData as $singleData) {
                if ($singleData['Product_Code'] && $singleData['Current_Stock']) {
                    //Dealer Stock table
                    $existingDealerStock = DealerStock::where('MasterCode', 'FlagshipDealer')
                        ->where('ProductCode', $singleData['Product_Code'])->first();

                    if ($existingDealerStock) {
                        DealerStock::where('MasterCode', 'FlagshipDealer')
                            ->where('ProductCode', $singleData['Product_Code'])->update([
                                'AdjustmentQuantity' => intval($existingDealerStock->AdjustmentQuantity) + intval($singleData['Adjustment_Stock']),
                                'CurrentStock' => !empty($singleData['Adjustment_Stock'])? (intval($existingDealerStock->CurrentStock) - intval($singleData['Adjustment_Stock'])) : intval($existingDealerStock->CurrentStock)+intval($singleData['Current_Stock'])
                            ]);
                    }
                    else{
                        $newDealerStock = new DealerStock();
                        $newDealerStock->MasterCode= 'FlagshipDealer';
                        $newDealerStock->ProductCode= $singleData['Product_Code'];
                        $newDealerStock->ReceiveQuantity= 0;
                        $newDealerStock->SalesQuantity= 0;
                        $newDealerStock->ReturnQuantity= 0;
                        if(!empty($singleData['Adjustment_Stock'])){
                            $newDealerStock->AdjustmentQuantity= intval($singleData['Adjustment_Stock']);
                            $newDealerStock->CurrentStock= intval($singleData['Adjustment_Stock'])<0 ? -1*intval($singleData['Adjustment_Stock']):0;
                        }
                        else{
                            $newDealerStock->AdjustmentQuantity =0;
                            $newDealerStock->CurrentStock= $singleData['Current_Stock'];
                        }


                        $newDealerStock->save();

                    }

                }

            }
            Db::commit();


            return response()->json([
                'status' => 'Success',
                'message' => 'Stock Added Successfully'
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!' . $exception->getMessage()
            ], 500);
        }
    }
}
