<?php

namespace App\Http\Controllers\JobCard;

use App\Http\Controllers\Controller;
use App\Models\TestRide\TestRideAgents;
use App\Models\UserCustomer;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Exception\UnableToBuildUuidException;

class UserCustomerController extends Controller
{
    use CommonTrait;
    public function index(Request $request){
        $take = $request->take;
        $search = $request->search;
        $roleId = Auth::user()->RoleId;
        $userId = Auth::user()->UserId;

        $user =DB::table('UserCustomer as u')
            ->select(
                'u.UserCustomerId',
                        'u.UserId',
                        'u.CustomerCode',
                        'c.CustomerName',
                DB::raw("
                CASE 
                WHEN u.UserType ='SE' THEN 'Service Engineer'
                 WHEN u.UserType ='SEZ' THEN 'Zonal Service Engineer'
                 WHEN u.UserType ='TO' THEN 'Territory Officer'
                 WHEN u.UserType ='TM' THEN 'Territory Manager'
                 WHEN u.UserType ='HOS' THEN 'Head Of Sales'
                 WHEN u.UserType ='HOSE' THEN 'Head Of Service Engineer'
                 WHEN u.UserType ='RSM' THEN 'Regional Sales Manager'
                 ELSE 'None' END  as UserType"),
                        'u.RegionName',
                        'u.RegionName',
                DB::raw("CASE WHEN ActiveStatus ='Y' THEN 'YES' ELSE 'NO' END  as Active"))
            ->leftjoin('Customer as c','c.CustomerCode','=','u.CustomerCode')
//            ->where('c.Business','=','C')
            ->where(function ($q) use ($search) {
                $q->where('u.UserId', 'like', '%' . $search . '%');
                $q->Orwhere('c.CustomerName', 'like', '%' . $search . '%');
                $q->Orwhere('c.CustomerCode', 'like', '%' . $search . '%');
            });
        if ($roleId !== 'admin') {
            $user->where('u.CustomerCode', $userId);
        }
        if ($request->type === 'export') {
            return response()->json([
                'data' => $user->get(),
            ]);
        } else {
            return $user->orderByDesc('UserCustomerId')->paginate($take);
        }
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'userId' => 'required',
            'customerCode' => 'required',
            'region' => 'required',
            'active' => 'required',
            'userType' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 401, 'error' => $validator->errors()]);
        }

        $userId = $request->userId;
        $customerCode = $request->customerCode;
        $region = $request->region;
        $active = $request->active;
        $userType = $request->userType;

        try{
            //Store USER CUSTOMER
            $exist = UserCustomer::where('UserId','=',$userId)->where('CustomerCode','=',$customerCode)->where('RegionName','=',$region)->first();
        if (empty($exist)){
            $user_customer = new UserCustomer();
            $user_customer->UserId = $userId;
            $user_customer->CustomerCode = $customerCode;
            $user_customer->RegionName = $region;
            $user_customer->ActiveStatus = $active;
            $user_customer->UserType = $userType;
            $user_customer->save();

            return response()->json([
                'status' => 'Success',
                'message' => 'User Customer Added Successfully'
            ],200);
        }else{
            return response()->json([
                'status' => 'Error',
                'message' => 'User Customer Already Exist'
            ],500);
        }
        }
        catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!' . $exception->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request)
    {
            $id = $request->row['UserCustomerId'];
            UserCustomer::where('UserCustomerId', $id)->delete();
            return response()->json(['message' => "User Customer deleted successfully"]);
    }

    public function getAllRole()
    {
        $roles = $this->roleList();
        return response()->json([
            'data'=>$roles
        ]);
    }
}
