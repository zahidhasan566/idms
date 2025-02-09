<?php

namespace App\Http\Controllers\Sap;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerMapping;
use App\Models\Product;
use App\Models\Sap\SapUserLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class CommonSapController extends Controller
{
    protected $id = 0;

    public function __construct()
    {
        $this->createSapUserLog();
    }

    public function createSapUserLog()
    {
        $sapUser = new SapUserLog();
        $sapUser->EntryBy = Auth::user()->UserId;
        $sapUser->EntryDate = Carbon::now();
        $sapUser->EntryIpAddress = $_SERVER['REMOTE_ADDR'];
        $sapUser->Status = 'Pending';
        $sapUser->save();
        $this->id = $sapUser->Id;

    }

    public function storeSapProduct(Request $request)
    {
        $dt = date('Y-m-d H-i-s A');
        try {
            $requestProducts = $request->all();
            $productCount = 0;


            foreach ($requestProducts as $singleProduct) {
                if (!empty($singleProduct['ProductCode']) && !empty($singleProduct['ProductName'])
                    && !empty($singleProduct['UnitPrice']) && !empty($singleProduct['VAT']) && !empty($singleProduct['MRP'])
                    && !empty($singleProduct['Business'])) {

                    //Check Already Exist Or Not
                    $checkExisting = Product::where('ProductCode', $singleProduct['ProductCode'])->first();
                    if (!empty($checkExisting->ProductCode)) {
//                        file_put_contents(public_path('log/sap/sap_product_file_already_exist-') . $dt . '.txt', json_encode($singleProduct) . "\n", FILE_APPEND);
                        $sapUserLog = SapUserLog::where('Id', $this->id)->update(['ApiType' => 'Product', 'Status' => 'Failed','Reason'=>'Already Exist']);
                        $this->updateSapProduct($checkExisting,$singleProduct);

                        return response()->json([
                            'status' => 'Success',
                            'message' => 'Product Updated Successfully',
                            'ProductCode' => $requestProducts[0]['ProductCode']
                        ], 200);
                    } else {
                        $productCount += 1;
                        DB::beginTransaction();

                        //Product Create
                        $product = new Product();
                        $product->ProductCode = $singleProduct['ProductCode'];
                        $product->ProductName = $singleProduct['ProductName'];
                        $product->PackSize = $singleProduct['PackSize'];
                        $product->Brand = $singleProduct['Brand'];
                        $product->UnitPrice = $singleProduct['UnitPrice'];
                        $product->VAT = $singleProduct['VAT'];
                        $product->MRP = $singleProduct['MRP'];
                        $product->Color = $singleProduct['InspMemo'];
                        $product->SalesUnit = $singleProduct['SalesUnit'];
                        $product->MaterialGroup = $singleProduct['MaterialGroup'];
                        $product->MaterialGroupTwo = $singleProduct['MaterialGroupTwo'];
                        $product->MaterialGroupThree = $singleProduct['MaterialGroupThree'];
                        $product->MaterialGroupFour = $singleProduct['MaterialGroupFour'];
                        $product->MaterialGroupFive = $singleProduct['MaterialGroupFive'];
                        $product->OldMaterialNumber = $singleProduct['OldMaterialNumber'];
                        $product->Division = $singleProduct['Division'];
                        $product->GrossWeight = $singleProduct['GrossWeight'];
                        $product->NetWeight = $singleProduct['NetWeight'];
                        $product->VolumeUnit = $singleProduct['VolumeUnit'];
                        $product->Size = $singleProduct['Size'];
                        $product->EAN = $singleProduct['EAN'];
                        $product->InspMemo = $singleProduct['InspMemo'];
                        $product->IndStdDesc = $singleProduct['IndStdDesc'];
                        $product->Business = $singleProduct['Business'];
                        $product->Active = 'Y';
                        $product->MaterialType = $singleProduct['MaterialType'];

                        $product->FuelUsed = $singleProduct['FuelUsed'];
                        $product->HorsePower = $singleProduct['HorsePower'];
                        $product->RPM = $singleProduct['RPM'];
                        $product->CubicCapacity = $singleProduct['CubicCapacity'];
                        $product->WheelBase = $singleProduct['WheelBase'];
                        $product->Weight = $singleProduct['Weight'];
                        $product->WeightMax = $singleProduct['WeightMax'];
                        $product->Standee = $singleProduct['Standee'];
                        $product->TireSizeFront = $singleProduct['TireSizeFront'];
                        $product->TireSizeRear = $singleProduct['TireSizeRear'];
                        $product->Seats = $singleProduct['Seats'];
                        $product->NoOfTyre = $singleProduct['NoOfTyre'];
                        $product->NoOfAxel = $singleProduct['NoOfAxel'];
                        $product->ClassOfVehicle = $singleProduct['ClassOfVehicle'];
                        $product->Manufacturer = $singleProduct['Manufacturer'];
                        $product->ManufacturingCountry = $singleProduct['ManufacturingCountry'];
                        $product->EngineType = $singleProduct['EngineType'];
                        $product->NumberOfCylinders = $singleProduct['NumberOfCylinders'];
                        $product->ImportYear = $singleProduct['ImportYear'];
                        $product->ManufacturingYear = $singleProduct['ManufacturingYear'];

                        $product->save();
                        DB::commit();
                    }
                } else {
                    DB::rollBack();
                    file_put_contents(public_path('log/sap/sap_product_file_not_acceptable_create-') . $dt . '.txt', json_encode($singleProduct) . "\n", FILE_APPEND);
                    //SAP USER  LOG
                    $sapUserLog = SapUserLog::where('Id', $this->id)->update(['ApiType' => 'Product', 'Status' => 'Failed','Reason'=>'Format Or Empty Or Existing Issue']);

                    return response()->json([
                        'status' => 'error',
                        'message' => 'Not Acceptable ! Check Format Or Empty Or Existing Value',
                        'ErrorProduct' => $singleProduct
                    ], 406);
                }
            }

            //SAP USER LOG
            $sapUserLog = SapUserLog::where('Id', $this->id)->update(['ApiType' => 'Product', 'Status' => 'Done']);
            return response()->json([
                'status' => 'Success',
                'message' => 'Product Added Successfully',
                'ProductCode' => $requestProducts[0]['ProductCode']
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            file_put_contents(public_path('log/sap/sap_product_error-') . $dt . '.txt', $exception->getMessage() . '-' . $exception->getLine() . "\n", FILE_APPEND);
            $sapUserLog = SapUserLog::where('Id', $this->id)->update(['ApiType' => 'Product', 'Status' => 'Failed','Reason'=>$exception->getMessage() . '-' . $exception->getLine()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!' . $exception->getMessage() . '-' . $exception->getLine()
            ], 500);
        }
    }

    public function storeSapCustomer(Request $request)
    {
        $dt = date('Y-m-d H-i-s A');
        try {
            $requestCustomers = $request->all();
            $customerCount = 0;

            foreach ($requestCustomers as $singleCustomer) {
                if (!empty($singleCustomer['CustomerCode']) && !empty($singleCustomer['CustomerName'])  && !empty($singleCustomer['Email'])
                    && !empty($singleCustomer['Mobile'])) {
                    //Check Already Exist Or Not


                    $existCustomerCheck = Customer::where('CustomerCode', $singleCustomer['CustomerCode'])->first();
                    if ($existCustomerCheck) {
//                        file_put_contents(public_path('log/sap/sap_customer_file_already_exist-') . $dt . '.txt', json_encode($singleCustomer) . "\n", FILE_APPEND);
                        $sapUserLog = SapUserLog::where('Id', $this->id)->update(['ApiType' => 'Customer', 'Status' => 'Failed','Reason'=>'Already Exist']);
                        $this->updateSapCustomer($existCustomerCheck,$singleCustomer);
                        return response()->json([
                            'status' => 'Success',
                            'message' => 'Customer Updated Successfully',
                            'CustomerCode' => $requestCustomers[0]['CustomerCode']
                        ], 200);
                    } else {
                        $customerCount += 1;
                        DB::beginTransaction();

                        //Customer Create
                        $customer = new Customer();
                        $customer->Title = $singleCustomer['Title'];
                        $customer->CustomerCode = $singleCustomer['CustomerCode'];
                        $customer->CustomerName = $singleCustomer['CustomerName'];
                        $customer->CustomerNameTwo = $singleCustomer['CustomerNameTwo'];
                        $customer->CustomerNameThree = $singleCustomer['CustomerNameThree'];
                        $customer->CustomerNameFour = $singleCustomer['CustomerNameFour'];
                        $customer->SearchTermOne = $singleCustomer['SearchTermOne'];
                        $customer->SearchTermTwo = $singleCustomer['SearchTermTwo'];


                        $customer->Gender = $singleCustomer['Gender'];
                        $customer->Add1 = $singleCustomer['Add1'];
                        $customer->Add2 = $singleCustomer['Add2'];
                        $customer->Add3 = $singleCustomer['Add3'];
                        $customer->Add4 = $singleCustomer['Add4'];
                        $customer->Add5 = $singleCustomer['Add5'];
                        $customer->Add6 = $singleCustomer['Add6'];
                        $customer->DistrictCode = $singleCustomer['DistrictCode'];
                        $customer->DistrictName = $singleCustomer['DistrictName'];

                        $customer->PostalCode = $singleCustomer['PostalCode'];
                        $customer->Country = $singleCustomer['Country'];
                        $customer->CountryName = $singleCustomer['CountryName'];
                        $customer->Region = $singleCustomer['Region'];
                        $customer->TimeZone = $singleCustomer['TimeZone'];

                        $customer->ThanaCode = $singleCustomer['Thana'];
                        $customer->Email = $singleCustomer['Email'];
                        $customer->ContactPerson = $singleCustomer['ContactPerson'];
                        $customer->Phone = '';
                        $customer->Mobile = $singleCustomer['Mobile'];
                        $customer->ThanaCode = $singleCustomer['Thana'];
                        $customer->NID = $singleCustomer['NID'];
                        $customer->Business = '';
                        $customer->DepotCode = '';
                        $customer->CustomerType = '';
                        $customer->PaymentMode = '';
                        $customer->CreditDays = '';
                        $customer->CreditLimit = '';
                        $customer->Metro = '';
                        $customer->RouteCode = '';
                        $customer->TTYCode = '';
                        $customer->UnionCode = '';
                        $customer->HatBazarCode = '';
                        $customer->CreateDate = '';
                        $customer->EditDate = '';
                        $customer->DiscountStatus = '';
                        $customer->Active = 'Y';
                        $customer->Fifo = 'Y';
                        $customer->Message = '';
                        $customer->Category = '';
                        $customer->RegistrationName = '';
                        $customer->RegistrationAdd1 = '';
                        $customer->RegistrationAdd2 = '';
                        $customer->FathersName = '';
                        $customer->DOB = '';
                        $customer->OwnerType = '';
                        $customer->SubBusinessCode = '';
                        $customer->save();


                        $customerCode= $singleCustomer['CustomerCode'];
                        $password = '123456@';

                        $user = new User();
                        $user->UserId = $singleCustomer['CustomerCode'];
                        $user->UserName = $singleCustomer['CustomerName'];
                        $user->Designation = 'Dealer';
                        $password = DB::select("SELECT dbo.ufn_PasswordEncode(?) as EncodedPassword", [$password])[0]->EncodedPassword;
                        $password = str_replace('?','',$password);
                        $user->Password = $password;
                        $user->RoleId = 'customer';
                        $user->Active = 0;
                        $user->save();


                        $customerMapping = new CustomerMapping();
                        $customerMapping->CustomerMasterCode = $singleCustomer['CustomerCode'];
                        $customerMapping->CustomerCode = $singleCustomer['CustomerCode'];
                        $customerMapping->Business = 'C';
                        $customerMapping->save();
                        DB::commit();

                    }
                } else {
                    DB::rollBack();
                    file_put_contents(public_path('log/sap/sap_customer_file_not_acceptable_create-') . $dt . '.txt', json_encode($singleCustomer) . "\n", FILE_APPEND);
                    $sapUserLog = SapUserLog::where('Id', $this->id)->update(['ApiType' => 'Customer', 'Status' => 'Failed','Reason'=>'Format Or Empty Or Existing Issue']);
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Not Acceptable ! Check Format Or Empty Or Existing Value',
                        'ErrorProduct' => $singleCustomer
                    ], 406);
                }
            }
            $sapUserLog = SapUserLog::where('Id', $this->id)->update(['ApiType' => 'Customer', 'Status' => 'Done']);
            return response()->json([
                'status' => 'Success',
                'message' => 'Customer Added Successfully',
                'CustomerCode' => $requestCustomers[0]['CustomerCode']
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            file_put_contents(public_path('log/sap/sap_customer_error-') . $dt . '.txt', $exception->getMessage() . '-' . $exception->getLine() . "\n", FILE_APPEND);
            $sapUserLog = SapUserLog::where('Id', $this->id)->update(['ApiType' => 'Customer', 'Status' => 'Failed','Reason'=>$exception->getMessage() . '-' . $exception->getLine()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!' . $exception->getMessage() . '-' . $exception->getLine()
            ], 500);
        }
    }

    public function updateSapProduct($checkExisting,$singleProduct){

        $checkExisting->ProductName = $singleProduct['ProductName'];
        $checkExisting->PackSize = $singleProduct['PackSize'];
        $checkExisting->Brand = $singleProduct['Brand'];
        $checkExisting->UnitPrice = $singleProduct['UnitPrice'];
        $checkExisting->VAT = $singleProduct['VAT'];
        $checkExisting->MRP = $singleProduct['MRP'];
        $checkExisting->Color = $singleProduct['InspMemo'];
        $checkExisting->SalesUnit = $singleProduct['SalesUnit'];
        $checkExisting->MaterialGroup = $singleProduct['MaterialGroup'];
        $checkExisting->MaterialGroupTwo = $singleProduct['MaterialGroupTwo'];
        $checkExisting->MaterialGroupThree = $singleProduct['MaterialGroupThree'];
        $checkExisting->MaterialGroupFour = $singleProduct['MaterialGroupFour'];
        $checkExisting->MaterialGroupFive = $singleProduct['MaterialGroupFive'];
        $checkExisting->OldMaterialNumber = $singleProduct['OldMaterialNumber'];
        $checkExisting->Division = $singleProduct['Division'];
        $checkExisting->GrossWeight = $singleProduct['GrossWeight'];
        $checkExisting->NetWeight = $singleProduct['NetWeight'];
        $checkExisting->VolumeUnit = $singleProduct['VolumeUnit'];
        $checkExisting->Size = $singleProduct['Size'];
        $checkExisting->EAN = $singleProduct['EAN'];
        $checkExisting->InspMemo = $singleProduct['InspMemo'];
        $checkExisting->IndStdDesc = $singleProduct['IndStdDesc'];
        $checkExisting->Business = $singleProduct['Business'];
        $checkExisting->Active = 'Y';
        $checkExisting->MaterialType = $singleProduct['MaterialType'];

        $checkExisting->FuelUsed = $singleProduct['FuelUsed'];
        $checkExisting->HorsePower = $singleProduct['HorsePower'];
        $checkExisting->RPM = $singleProduct['RPM'];
        $checkExisting->CubicCapacity = $singleProduct['CubicCapacity'];
        $checkExisting->WheelBase = $singleProduct['WheelBase'];
        $checkExisting->Weight = $singleProduct['Weight'];
        $checkExisting->WeightMax = $singleProduct['WeightMax'];
        $checkExisting->Standee = $singleProduct['Standee'];
        $checkExisting->TireSizeFront = $singleProduct['TireSizeFront'];
        $checkExisting->TireSizeRear = $singleProduct['TireSizeRear'];
        $checkExisting->Seats = $singleProduct['Seats'];


        $checkExisting->NoOfTyre = $singleProduct['NoOfTyre'];
        $checkExisting->NoOfAxel = $singleProduct['NoOfAxel'];
        $checkExisting->ClassOfVehicle = $singleProduct['ClassOfVehicle'];
        $checkExisting->Manufacturer = $singleProduct['Manufacturer'];
        $checkExisting->ManufacturingCountry = $singleProduct['ManufacturingCountry'];
        $checkExisting->EngineType = $singleProduct['EngineType'];
        $checkExisting->NumberOfCylinders = $singleProduct['NumberOfCylinders'];
        $checkExisting->ImportYear = $singleProduct['ImportYear'];
        $checkExisting->ManufacturingYear = $singleProduct['ManufacturingYear'];
        $checkExisting->save();
    }

    public function updateSapCustomer($existCustomerCheck,$singleCustomer){

        $existCustomerCheck->Title = $singleCustomer['Title'];
        $existCustomerCheck->CustomerCode = $singleCustomer['CustomerCode'];
        $existCustomerCheck->CustomerName = $singleCustomer['CustomerName'];
        $existCustomerCheck->CustomerNameTwo = $singleCustomer['CustomerNameTwo'];
        $existCustomerCheck->CustomerNameThree = $singleCustomer['CustomerNameThree'];
        $existCustomerCheck->CustomerNameFour = $singleCustomer['CustomerNameFour'];
        $existCustomerCheck->SearchTermOne = $singleCustomer['SearchTermOne'];
        $existCustomerCheck->SearchTermTwo = $singleCustomer['SearchTermTwo'];


        $existCustomerCheck->Gender = $singleCustomer['Gender'];
        $existCustomerCheck->Add1 = $singleCustomer['Add1'];
        $existCustomerCheck->Add2 = $singleCustomer['Add2'];
        $existCustomerCheck->Add3 = $singleCustomer['Add3'];
        $existCustomerCheck->Add4 = $singleCustomer['Add4'];
        $existCustomerCheck->Add5 = $singleCustomer['Add5'];
        $existCustomerCheck->Add6 = $singleCustomer['Add6'];
        $existCustomerCheck->DistrictCode = $singleCustomer['DistrictCode'];
        $existCustomerCheck->DistrictName = $singleCustomer['DistrictName'];

        $existCustomerCheck->PostalCode = $singleCustomer['PostalCode'];
        $existCustomerCheck->Country = $singleCustomer['Country'];
        $existCustomerCheck->CountryName = $singleCustomer['CountryName'];
        $existCustomerCheck->Region = $singleCustomer['Region'];
        $existCustomerCheck->TimeZone = $singleCustomer['TimeZone'];

        $existCustomerCheck->ThanaCode = $singleCustomer['Thana'];
        $existCustomerCheck->Email = $singleCustomer['Email'];
        $existCustomerCheck->ContactPerson = $singleCustomer['ContactPerson'];
        $existCustomerCheck->Phone = '';
        $existCustomerCheck->Mobile = $singleCustomer['Mobile'];
        $existCustomerCheck->ThanaCode = $singleCustomer['Thana'];
        $existCustomerCheck->NID = $singleCustomer['NID'];
        $existCustomerCheck->Business = '';
        $existCustomerCheck->DepotCode = '';
        $existCustomerCheck->CustomerType = '';
        $existCustomerCheck->PaymentMode = '';
        $existCustomerCheck->CreditDays = '';
        $existCustomerCheck->CreditLimit = '';
        $existCustomerCheck->Metro = '';
        $existCustomerCheck->RouteCode = '';
        $existCustomerCheck->TTYCode = '';
        $existCustomerCheck->UnionCode = '';
        $existCustomerCheck->HatBazarCode = '';
        $existCustomerCheck->CreateDate = '';
        $existCustomerCheck->EditDate = '';
        $existCustomerCheck->DiscountStatus = '';
        $existCustomerCheck->Active = 'Y';
        $existCustomerCheck->Fifo = 'Y';
        $existCustomerCheck->Message = '';
        $existCustomerCheck->Category = '';
        $existCustomerCheck->RegistrationName = '';
        $existCustomerCheck->RegistrationAdd1 = '';
        $existCustomerCheck->RegistrationAdd2 = '';
        $existCustomerCheck->FathersName = '';
        $existCustomerCheck->DOB = '';
        $existCustomerCheck->OwnerType = '';
        $existCustomerCheck->SubBusinessCode = '';
        $existCustomerCheck->save();


        $user = User::where('UserId', $singleCustomer['CustomerCode'])->first();
        $user->UserName = $singleCustomer['CustomerName'];
        $user->save();
    }
}
