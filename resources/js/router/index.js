import Vue from 'vue'
import VueRouter from 'vue-router'
import Login from '../views/auth/Login.vue';
import Main from '../components/layouts/Main';
import Dashboard from '../views/dashboard/Index.vue';
import Users from '../views/users/Index';
import AdjustmentsUnadjusted from "../views/reports/general/Unadjusted";
import AdjustmentsAdjusted from "../views/reports/general/Adjusted";
import ProductIndex from "../views/product/ProductIndex.vue";
import EditApproved from "../components/dashboard/pendingOrderEditApproved.vue";
import PostDeliveryPrint from "../views/post-delivery/PostDeliveryPrint.vue";
import ReportPreBookAllocation from "../views/reports/reportPreBookAllocation.vue";
import FlagShipStockAddEdit from "../views/stock/FlagShipStockAddEdit.vue";
import PreBookAllocationAddEdit from "../views/prebook/PreBookAllocationAddEdit.vue";
import ReportFlagshipBikeSales from "../views/reports/reportFlagshipBikeSales.vue";
import PrintInvoice from "../views/invoice/PrintInvoice.vue"

//JOB CARD
import BayIndex from "../views/jobcard/BayIndex.vue";
import WorkIndex from "../views/jobcard/WorkIndex.vue";
import TechnicianIndex from "../views/jobcard/TechnicianIndex.vue";
import JobCardIndex from "../views/jobcard/JobCardIndex.vue";
import OrderSpareParts from "../views/orders/spareparts.vue";
import JobCardDisplay from "../views/jobcard/DisplayBoard.vue";
import JobCardReport from "../views/jobcard/JobCardReport.vue";
import TechnicianWiseReport from "../views/jobcard/TechnicianWiseReport.vue";
import JobCardBookingReport from "../views/jobcard/JobCardBookingReport.vue";
import JobCardEstimationIndex from "../views/jobcard/JobCardEstimationIndex.vue";
import JobCardEstimationAddEdit from "../components/jobCard/JobCardEstimationAddEdit.vue";
import JobCardEstimationPrint from "../views/jobcard/JobCardEstimationPrint.vue";
import UserCustomer from "../views/jobcard/UserCustomerIndex.vue";
import PaidServiceSchedule from "../views/jobcard/PaidServiceSchedule.vue";
import ServiceHistory from "../views/jobcard/ServiceHistory.vue";
import adjustmentInvoiceIndex from "../views/invoice/adjustmentInvoiceIndex.vue";
import adjustmentInvoiceAddEdit from "../views/invoice/adjustmentInvoiceAddEdit.vue";
import evaluationSalesDetails from "../views/evaluation/SalesDetails.vue";
import evaluationSalesIndex from "../views/evaluation/SalesIndex.vue";
import reportInvoiceReceiveSurvey from "../views/reports/reportInvoiceReceiveSurvey.vue";
import reportDealerInvoiceReceiveSurvey from "../views/reports/reportDealerInvoiceReceiveSurvey.vue";
import brtaRegistrationIndex from "../views/logistics/brtaRegistrationIndex.vue";
import Service4pIndex from "../views/evaluation/Service4pIndex.vue";
import Service4pDetails from "../views/evaluation/Service4pDetails.vue";
import AllEvaluationReport from "../views/evaluation/report.vue";
import ReportDetails from "../views/evaluation/detailsReport.vue";
import service4pDetailsPart2 from "../views/evaluation/Service4pDetailsPart2.vue";
import ReceiveReport from "../views/logistics/ReceiveReport.vue";
import ReceiveSummaryReport from "../views/logistics/ReceiveSummaryReport.vue";
import InvoiceScrapReturn from "../views/invoice/InvoiceScrapReturn.vue";
import InvoiceApproveScrapProduct from "../views/invoice/InvoiceApproveScrapProduct.vue";
import ReportScrapProducts from "../views/reports/reportScrapProducts.vue";

//logistics
import DealerDocumentAdd from "../views/logistics/DealerDocumentStore.vue";
import DealerDocumentReport from "../views/logistics/DealerDocumentIndex.vue";
import LostDocument from "../views/logistics/LostDocument.vue";
import LostDocumentHistory from "../views/logistics/LostDocumentHistory.vue";
import LostDocumentReport from "../views/logistics/LostDocumentReport.vue";
import PendingLostDocument from "../views/logistics/PendingLostDocument.vue";
import DocumentIndex from "../views/logistics/Document.vue";
import ChallanList from "../views/logistics/challan/ChallanList";

import FollowUpIndex from "../views/inquiry/FollowUpIndex.vue";

import inquiryConversionSummaryReport from "../views/inquiry/InquiryConversionSummaryReport.vue";

import TestRideAgentList from "../views/TestRide/AgentIndex";
import TestRiderList from "../views/TestRide/RiderIndex.vue";
import NotificationList from "../views/notification/NotificationIndex.vue";
import inquiryPrint from "../views/inquiry/InquiryPrint.vue";


//reportSalesSummary
import reportSalesSummary from "../views/reports/SalesSummaryReport.vue";
import reportBikeSales from "../views/reports/reportBikeSales.vue";
import reportSparePartsSales from "../views/reports/reportSparePartsSales.vue";
import reportBikeOrder from "../views/reports/reportBikeOrder.vue";
import reportSparePartsOrder from "../views/reports/reportSparePartsOrder.vue";
import reportBikeStock from "../views/reports/reportBikeStock.vue";
import reportSparePartsStock from "../views/reports/reportSparePartsStock.vue";
import reportSparePartsSalesAffiliator from "../views/reports/reportSparePartsSalesAffiliator.vue";
import reportEstimatedFreeService from "../views/reports/reportEstimatedFreeService.vue";
import reportEstimatedPaidService from "../views/reports/reportEstimatedPaidService.vue";
import reportClaimWarranty from "../views/reports/reportClaimWarranty.vue";
import reportServiceRatio from "../views/reports/reportServiceRatio.vue";
import reportInTimeServiceRatio from "../views/reports/reportInTimeServiceRatio.vue";
import reportCustomerCSISummary from "../views/reports/reportCustomerCSISummary.vue";
import reportOpeningCloseingStock from "../views/reports/reportOpeningCloseingStock.vue";
import reportCustomerBikeSalesFeedBack from "../views/reports/reportCustomerBikeSalesFeedBack.vue";
import reportServiceSummary from "../views/reports/reportServiceSummary.vue";

//Invoice
import InvoiceList from "../views/invoice/InvoiceList.vue";
import InvoiceCreate from "../views/invoice/InvoiceCreate.vue";
import InvoicePrint from "../views/invoice/InvoicePrint.vue";
import InvoiceShow from "../views/invoice/InvoiceShow.vue";
import InvoiceEdit from "../views/invoice/InvoiceEdit.vue";
import BRTAInvoicePrint from "../views/invoice/BRTAInvoicePrint.vue";
import InvoiceSpareParts from "../views/invoice/InvoiceSpareParts.vue";
import InvoiceSparePartsReturn from "../views/invoice/InvoiceSparePartsReturn.vue";
import InvoiceSparePartsPrint from "../views/invoice/InvoiceSparePartsPrint.vue";

//service | claim warranty
import ClaimWarrantyList from "../views/service/claim_warranty/List";
import ClaimWarrantyCreate from "../views/service/claim_warranty/Create";
import ClaimWarrantyShow from "../views/service/claim_warranty/Show";
import ClaimWarrantySummary from "../views/service/claim_warranty/ClaimWarrantySummary";

//SDMS WARRANTY CLAIM LIST
import SDMSWarrantyClaimList from "../views/reports/sdms/SDMSWarrantyClaimList";

import JobCardFreeServiceFollowUp from "../views/jobcard/FreeServiceFollowUp.vue"
//Approval | warranty
import PendingWarranty from "../views/service/approval/PendingWarranty";
import PendingWarrantyEditAndApproved from "../views/service/approval/PendingWarrantyEditAndApproved";
import ApprovedWarranty from "../views/service/approval/ApprovedWarranty";
import CancelWarranty from "../views/service/approval/CancelWarranty";
import ClaimWarrantyReport from "../views/service/approval/ClaimWarrantyReport";
import PrintWarranty from "../views/service/approval/PrintWarranty";

import OrderBike from "../views/orders/bike.vue";
import CreditPayment from "../views/payment/paymentList.vue";
import Stock from "../views/stock/Index.vue";
import StockAllocation from "../views/stock/RackAllocationIndex.vue";
import StockMSL from "../views/stock/MSLIndex.vue";
import JobCardAddEdit from '../components/jobCard/JobCardAddEdit.vue';
import RoleList from '../views/roles/Index.vue';
import RolePermission from '../views/roles/Permission.vue';

import PromotionList from '../views/promotion/List.vue'
import PromotionReport from '../views/promotion/Report.vue'
import PromotionTopSheet from "../views/promotion/TopSheet.vue"
import ChangePassword from "../views/settings/ChangePassword.vue"
import CustomerList from "../views/settings/Customer/List.vue"
import CustomerCreate from "../views/settings/Customer/Create.vue"
import CustomerEdit from "../views/settings/Customer/Edit.vue"

import NotFound from '../views/404/Index';
// import Profile from '../views/profile/Index';
import {baseurl} from '../base_url';
import JobCardPrint from "../views/jobcard/JobCardPrint.vue";
import InvoiceSparePartsList from "../views/invoice/InvoiceSparePartsList.vue";
import ProgressCard from "../components/inquiry/ProgressCard.vue";

// SDMS REPORT
import SdmsInvoiceList from "../views/sdms/SdmsInvoiceList.vue"
import SdmsCustomerLedger from "../views/sdms/SdmsCustomerLedger.vue"
import CustomerWiseProductSold from "../views/sdms/CustomerWiseProductSold.vue"
import CustomerWiseProductSoldPrint from "../views/sdms/CustomerWiseProductSoldPrint.vue"
import SdmsDayWiseSalesSummaryReport from "../views/sdms/SdmsDayWiseSalesSummaryReport.vue"
import SdmsCustomerLedgerPrint from "../views/sdms/SdmsCustomerLedgerPrint.vue"
import DealerUser from "../views/users/DealerUsers.vue"
import DealerOffer from "../views/sdms/DealerOffer.vue"
import DealerOfferList from "../views/sdms/DealerOfferList.vue"
import JobCardCSI from "../views/jobcard/JobCardCSI.vue"
import ReportPreBook from "../views/reports/reportPreBook.vue"

import PostDeliveryChecklist from "../views/post-delivery/Checklist.vue"
import CreatePostDeliveryChecklist from "../views/post-delivery/Create.vue"

import MoneyReceiptAdvance from "../views/money-receipt/advance.vue"
import AdvancePrint from "../views/money-receipt/AdvancePrint.vue"
import flagshipInvoicePrint from "../views/invoice/FlagshipInvoicePrint.vue";



Vue.use(VueRouter);

const config = () => {
    let token = localStorage.getItem('token');
    return {
        headers: {Authorization: `Bearer ${token}`}
    };
}
const checkToken = (to, from, next) => {
    let token = localStorage.getItem('token');
    if (token === 'undefined' || token === null || token === '') {
        next(baseurl + 'login');
    } else {
        next();
    }
};
const activeToken = (to, from, next) => {
    let token = localStorage.getItem('token');
    if (token === 'undefined' || token === null || token === '') {
        next();
    } else {
        next(baseurl);
    }
};

const routes = [
    {
        path: baseurl,
        component: Main,
        redirect: {name: 'Dashboard'},
        children: [
            //COMMON ROUTE | SHOW DASHBOARD DATA
            {
                path: baseurl + 'dashboard',
                name: 'Dashboard',
                component: Dashboard
            },


            //ADMIN ROUTE | SHOW USER LIST
            {
                path: baseurl + 'users/list',
                name: 'Users',
                component: Users
            },
            //GENERAL USER | UNADJUSTED REPORT
            {
                path: baseurl + 'adjustments/reports/unadjusted',
                name: 'AdjustmentsUnadjusted',
                component: AdjustmentsUnadjusted
            },
            //GENERAL USER | ADJUSTED REPORT
            {
                path: baseurl + 'adjustments/reports/adjusted',
                name: 'AdjustmentsAdjusted',
                component: AdjustmentsAdjusted
            },
            //JOB CARD
            {
                path: baseurl + 'jobcard/baylist',
                name: 'BayIndex',
                component: BayIndex
            },
            {
                path: baseurl + 'jobcard/worklist',
                name: 'WorkIndex',
                component: WorkIndex
            },
            {
                path: baseurl + 'jobcard/technician',
                name: 'TechnicianIndex',
                component: TechnicianIndex
            },
            {
                path: baseurl + 'jobcard',
                name: 'JobCardIndex',
                component: JobCardIndex
            },
            {
                path: baseurl + 'job-card-add-edit',
                name: 'JobCardAddEdit',
                component: JobCardAddEdit
            },
            {
                path: baseurl + 'job-card-print',
                name: 'JobCardPrint',
                component: JobCardPrint
            },
            {
                path: baseurl + 'jobcard/display',
                name: 'JobCardDisplay',
                component: JobCardDisplay,
                meta: { hideTopBar: true, hideSideMenu: true }
            },
            {
                path: baseurl + 'jobcard/report',
                name: 'JobCardReport',
                component: JobCardReport
            },
            {
                path: baseurl + 'jobcard/technician-wise-report',
                name: 'TechnicianWiseReport',
                component: TechnicianWiseReport
            },
            {
                path: baseurl + 'jobcard/booking-report',
                name: 'JobCardBookingReportt',
                component: JobCardBookingReport
            },
            {
                path: baseurl + 'jobcard/estimation',
                name: 'JobCardEstimationIndex',
                component: JobCardEstimationIndex
            },
            {
                path: baseurl + 'job-card-estimation',
                name: 'JobCardEstimationAddEdit',
                component: JobCardEstimationAddEdit
            },
            {
                path: baseurl + 'job-card-estimation-print',
                name: 'JobCardEstimationPrint',
                component: JobCardEstimationPrint
            },
            {
                path: baseurl + 'jobcard/service-history',
                name: 'ServiceHistory',
                component: ServiceHistory
            },
            {
                path: baseurl + 'jobcard/user-customer',
                name: 'UserCustomer',
                component: UserCustomer
            },
            {
                path: baseurl + 'jobcard/paid-service-schedule',
                name: 'PaidServiceSchedule',
                component: PaidServiceSchedule
            },
            {
                path: baseurl + 'jobcard/csiList',
                name: 'JobCardCSI',
                component: JobCardCSI
            },


            //ORDERS | SPARE PARTS
            {
                path: baseurl + 'orders/spareparts',
                name: 'OrderSpareParts',
                component: OrderSpareParts
            },
            //Invoice | SPARE PARTS
            { path: baseurl + 'invoice-list', name: 'InvoiceList', component: InvoiceList },
            { path: baseurl + 'invoice-create', name: 'InvoiceCreate', component: InvoiceCreate },
            { path: baseurl + 'invoice-print/:InvoiceId', name: 'InvoicePrint', component: InvoicePrint },
            { path: baseurl + 'flagship-invoice-print/:InvoiceNo', name: 'flagshipInvoicePrint', component: flagshipInvoicePrint },
            { path: baseurl + 'invoice-show/:InvoiceId', name: 'InvoiceShow', component: InvoiceShow },
            { path: baseurl + 'invoice-edit/:InvoiceNo', name: 'InvoiceEdit', component: InvoiceEdit },
            { path: baseurl + 'brta-invoice-print/:InvoiceId', name: 'BRTAInvoicePrint', component: BRTAInvoicePrint },
            //Service | CLAIM WARRANTY
            { path: baseurl + 'service/claim-warranty-list', name: 'ClaimWarrantyList', component: ClaimWarrantyList },
            { path: baseurl + 'service/claim-warranty-create', name: 'ClaimWarrantyCreate', component: ClaimWarrantyCreate },
            { path: baseurl + 'service/claim-warranty-show', name: 'ClaimWarrantyShow', component: ClaimWarrantyShow },
            //Service | approval
            { path: baseurl + 'approval/pending-warranty', name: 'PendingWarranty', component: PendingWarranty },
            { path: baseurl + 'approval/pending-warranty-edit-and-approved/:WarrantyId', name: 'PendingWarrantyEditAndApproved', component: PendingWarrantyEditAndApproved },
            { path: baseurl + 'approval/approved-warranty', name: 'ApprovedWarranty', component: ApprovedWarranty },
            { path: baseurl + 'approval/cancel-warranty', name: 'CancelWarranty', component: CancelWarranty },
            { path: baseurl + 'approval/claim-warranty-report', name: 'ClaimWarrantyReport', component: ClaimWarrantyReport },
            { path: baseurl + 'approval/warranty-print/:DCWarrantyId', name: 'PrintWarranty', component: PrintWarranty },
            {
                path: baseurl + 'invoice/spareparts',
                name: 'InvoiceSparePartsList',
                component: InvoiceSparePartsList
            },
            {
                path: baseurl + 'invoice/spareparts/create',
                name: 'InvoiceSpareParts',
                component: InvoiceSpareParts
            },
            {
                path: baseurl + 'invoice/spareparts/print/:invoiceId',
                name: 'InvoiceSparePartsPrint',
                component: InvoiceSparePartsPrint
            },
            {
                path: baseurl + 'invoice/return',
                name: 'InvoiceSparePartsReturn',
                component: InvoiceSparePartsReturn
            },
            {
                path: baseurl + 'invoice/return-scrap-products',
                name: 'InvoiceScrapReturn',
                component: InvoiceScrapReturn
            },
            {
                path: baseurl + 'invoice/approve-scrap-products',
                name: 'InvoiceApproveScrapProduct',
                component: InvoiceApproveScrapProduct
            },
            //ORDERS | BIKE
            {
                path: baseurl + 'orders/bike',
                name: 'OrderBike',
                component: OrderBike
            },
            //Payment | CreditPayment
            {
                path: baseurl + 'payment/credit-payment',
                name: 'CreditPayment',
                component: CreditPayment
            } ,
            //stock | list
            {
                path: baseurl + 'stock',
                name: 'Stock',
                component: Stock
            } ,
            //stock | RACK ALLOCATION
            {
                path: baseurl + 'stock/allocation',
                name: 'StockAllocation',
                component: StockAllocation
            } ,
            //stock | MSL
            {
                path: baseurl + 'stock/msl',
                name: 'StockMSL',
                component: StockMSL
            } ,

            { path: baseurl + 'invoice/adjustinvoice', name: 'adjustmentInvoiceIndex', component: adjustmentInvoiceIndex },
            { path: baseurl + 'invoice/adjustinvoice-add-edit', name: 'adjustmentInvoiceAddEdit', component: adjustmentInvoiceAddEdit },

            //logistics
            {
                path: baseurl + 'logistics/dealer-document',
                name: 'DealerDocumentAdd',
                component: DealerDocumentAdd
            },
            {
                path: baseurl + 'logistics/dealerdocument/report',
                name: 'DealerDocumentReport',
                component: DealerDocumentReport
            },
            {
                path: baseurl + 'logistics/lostdocument',
                name: 'LostDocument',
                component: LostDocument
            },
            {
                path: baseurl + 'logistics/lost-document-history',
                name: 'LostDocumentHistory',
                component: LostDocumentHistory
            },
            {
                path: baseurl + 'logistics/pending-lost-document',
                name: 'PendingLostDocument',
                component: PendingLostDocument
            },
            {
                path: baseurl + 'logistics/lost-document-report',
                name: 'LostDocumentReport',
                component: LostDocumentReport
            },
            {
                path: baseurl + 'logistics/document',
                name: 'DocumentIndex',
                component: DocumentIndex
            },
            {
                path: baseurl + 'logistics/challan-list',
                name: 'ChallanList',
                component: ChallanList
            },

            //warranty - freee service followup
            {
                path: baseurl + 'jobcard/free-service-followup',
                name: 'JobCardFreeServiceFollowUp',
                component: JobCardFreeServiceFollowUp
            } ,
            {
                path: baseurl + 'roles/list',
                name: 'RoleList',
                component: RoleList,
            },
            {
                path: baseurl + 'roles/permission',
                name: 'RolePermission',
                component: RolePermission,
            },
            {
                path: baseurl + 'settings/change-password',
                name: 'ChangePassword',
                component: ChangePassword,
            },
            {path: baseurl + 'settings/customer', name: 'CustomerList', component: CustomerList},
            {path: baseurl + 'settings/customer-create', name: 'CustomerCreate', component: CustomerCreate},
            {path: baseurl + 'settings/customer-edit/:CustomerCode', name: 'CustomerEdit', component: CustomerEdit},
            {
                path: baseurl + 'dealer/users',
                name: 'DealerUser',
                component: DealerUser
            },
            {
                path: baseurl + 'promotion/list',
                name: 'PromotionList',
                component: PromotionList
            },
            {
                path: baseurl + 'promotion/report',
                name: 'PromotionReport',
                component: PromotionReport
            },
            {
                path: baseurl + 'promotion/top-sheet',
                name: 'PromotionTopSheet',
                component: PromotionTopSheet
            },
            { path: baseurl + 'report/sales-summary', name: 'reportSalesSummary', component: reportSalesSummary },
            { path: baseurl + 'report/bike-sales', name: 'reportBikeSales', component: reportBikeSales },
            { path: baseurl + 'inquiry/followup', name: 'FollowUpIndex', component: FollowUpIndex },
            { path: baseurl + 'inquiry/conversion-summary', name: 'inquiryConversionSummaryReport', component: inquiryConversionSummaryReport },

            { path: baseurl + 'report/spare-parts-sales', name: 'reportSparePartsSales', component: reportSparePartsSales },
            { path: baseurl + 'report/bike-order', name: 'reportBikeOrder', component: reportBikeOrder },
            { path: baseurl + 'report/spare-parts-order', name: 'reportSparePartsOrder', component: reportSparePartsOrder },
            { path: baseurl + 'report/bike-stock', name: 'reportBikeStock', component: reportBikeStock },
            { path: baseurl + 'report/spare-parts-stock', name: 'reportSparePartsStock', component: reportSparePartsStock },
            { path: baseurl + 'report/spare-parts-sales-affiliator', name: 'reportSparePartsSalesAffiliator', component: reportSparePartsSalesAffiliator },
            { path: baseurl + 'report/estimated-free-service', name: 'reportEstimatedFreeService', component: reportEstimatedFreeService },
            { path: baseurl + 'report/estimated-paid-service', name: 'reportEstimatedPaidService', component: reportEstimatedPaidService },
            { path: baseurl + 'inquiry-progress-card', name: 'ProgressCard', component: ProgressCard },
            { path: baseurl + 'report/claim-warranty', name: 'reportClaimWarranty', component: reportClaimWarranty },
            { path: baseurl + 'report/service-ratio', name: 'reportServiceRatio', component: reportServiceRatio },
            { path: baseurl + 'report/in-time-service-ratio', name: 'reportInTimeServiceRatio', component: reportInTimeServiceRatio },
            { path: baseurl + 'report/customer-csi-summary', name: 'reportCustomerCSISummary', component: reportCustomerCSISummary },
            { path: baseurl + 'report/opening-closeing-stock', name: 'reportOpeningCloseingStock', component: reportOpeningCloseingStock },
            { path: baseurl + 'report/customer-bike-sales-feedBack', name: 'reportCustomerBikeSalesFeedBack', component: reportCustomerBikeSalesFeedBack },
            { path: baseurl + 'report/claim-warranty-summary', name: 'ClaimWarrantySummary', component: ClaimWarrantySummary },
            { path: baseurl + 'report/service-summary', name: 'reportServiceSummary', component: reportServiceSummary },
            { path: baseurl + 'report/scrap-product', name: 'ReportScrapProducts', component: ReportScrapProducts },
            { path: baseurl + 'prebook/list', name: 'ReportPreBook', component: ReportPreBook },

            // SDMS REPORT
            {
                path: baseurl + 'sdms-report/invoice-list',
                name: 'SdmsInvoiceList',
                component: SdmsInvoiceList
            },
            {
                path: baseurl + 'sdms-report/customer-ledger',
                name: 'SdmsCustomerLedger',
                component: SdmsCustomerLedger
            },
            {
                path: baseurl + 'sdms-report/customer-ledger-print/:customerCode/:startDate/:endDate/:business',
                name: 'SdmsCustomerLedgerPrint',
                component: SdmsCustomerLedgerPrint
            },
            {
                path: baseurl + 'sdms-report/customer-wise-product-sold',
                name: 'CustomerWiseProductSold',
                component: CustomerWiseProductSold
            },
            {
                path: baseurl + 'sdms-report/customer-wise-product-sold-print',
                name: 'CustomerWiseProductSoldPrint',
                component: CustomerWiseProductSoldPrint
            },
            {
                path: baseurl + 'sdms-report/day-wise-sales-summary-report',
                name: 'SdmsCustomerWiseProductSold',
                component: SdmsDayWiseSalesSummaryReport
            },
            {
                path: baseurl + 'sdms-report/dealer-offer',
                name: 'DealerOffer',
                component: DealerOffer
            },
            {
                path: baseurl + 'sdms-report/dealer-offer-list',
                name: 'DealerOfferList',
                component: DealerOfferList
            },
            //SDMS REPORT
            { path: baseurl + 'sdmsreport/warranty-claim-list', name: 'SDMSWarrantyClaimList', component: SDMSWarrantyClaimList },

            //test ride
            {
                path: baseurl + 'test-ride/agent',
                name: 'TestRideAgentList',
                component: TestRideAgentList
            },
            {
                path: baseurl + 'test-ride/create-new',
                name: 'TestRiderList',
                component: TestRiderList
            } ,
            //Notification
            {
                path: baseurl + 'transport/notification',
                name: 'NotificationList',
                component: NotificationList
            },

            //Report Invoice Survey
            {
                path: baseurl + 'report/invoiceSurveyReport',
                name: 'reportInvoiceReceiveSurvey',
                component: reportInvoiceReceiveSurvey
            },
            //Report Dealer  Survey
            {
                path: baseurl + 'report/dealer-survey-report',
                name: 'reportDealerInvoiceReceiveSurvey',
                component: reportDealerInvoiceReceiveSurvey
            },
            //Logistics Brta
            {
                path: baseurl + 'logistics/brta-registration-status-list',
                name: 'brtaRegistrationIndex',
                component: brtaRegistrationIndex
            },

            //Evaluation
            {
                path: baseurl + 'evaluation/service-sales',
                name: 'evaluationSalesIndex',
                component: evaluationSalesIndex
            },
            {
                path: baseurl + 'evaluation/service-sales-details',
                name: 'evaluationSalesDetails',
                component: evaluationSalesDetails
            },
            {
                path: baseurl + 'evalution4p/service4p',
                name: 'Service4pIndex',
                component: Service4pIndex
            },
            {
                path: baseurl + 'evalution4p/service-4p-details',
                name: 'Service4pDetails',
                component: Service4pDetails
            } ,
            {
                path: baseurl + 'evalution/service',
                name: 'AllEvaluationReport',
                component: AllEvaluationReport
            },
            {
                path: baseurl + 'evaluation/details-report',
                name: 'ReportDetails',
                component: ReportDetails
            },
            {
                path: baseurl + 'evalution4p/service-4p-details-part2',
                name: 'service4pDetailsPart2',
                component: service4pDetailsPart2
            },
            {
                path: baseurl + 'logistics/receivereport',
                name: 'ReceiveReport',
                component: ReceiveReport
            },
            {
                path: baseurl + 'logistics/receivereportsummery',
                name: 'ReceiveSummaryReport',
                component: ReceiveSummaryReport
            },
            //Product
            {
                path: baseurl + 'settings/product',
                name: 'ProductIndex',
                component: ProductIndex
            },
            //pending order
            {
                path: baseurl + 'dashboard/edit-approve',
                name: 'EditApproved',
                component: EditApproved
            },
            //Inquiry Print
            {
                path: baseurl + 'inquiry-print',
                name: 'inquiryPrint',
                component: inquiryPrint
            },
            {
                path: baseurl + 'post-delivery/checklist',
                name: 'PostDeliveryChecklist',
                component: PostDeliveryChecklist
            },
            {
                path: baseurl + 'post-delivery/checklist/create',
                name: 'CreatePostDeliveryChecklist',
                component: CreatePostDeliveryChecklist
            },
            {
                path: baseurl + 'post-delivery/checklist/print/:inquiryId',
                name: 'PostDeliveryPrint',
                component: PostDeliveryPrint
            },
            {
                path: baseurl + 'money-receipt/advance',
                name: 'MoneyReceiptAdvance',
                component: MoneyReceiptAdvance
            },
            {
                path: baseurl + 'money-receipt/:moneyRecNo',
                name: 'AdvancePrint',
                component: AdvancePrint
            },
            {
                path: baseurl + 'prebook/allocation',
                name: 'ReportPreBookAllocation',
                component: ReportPreBookAllocation
            },
            { path: baseurl + 'stock-allocation-flagship', name: 'FlagShipStockAddEdit', component: FlagShipStockAddEdit },
            { path: baseurl + 'prebook-allocation', name: 'PreBookAllocationAddEdit', component: PreBookAllocationAddEdit },
            { path: baseurl + 'report/flagship-bike-sales', name: 'ReportFlagshipBikeSales', component: ReportFlagshipBikeSales },
            {
                path: baseurl+'invoice/print-invoice',
                name:'PrintInvoice',
                component: PrintInvoice
            },

        ],
        beforeEnter(to, from, next) {
            checkToken(to, from, next);
        }
    },
    {
        path: baseurl + 'login',
        name: 'Login',
        component: Login,
        beforeEnter(to, from, next) {
            activeToken(to, from, next);
        }
    },
    {
        path: baseurl + '*',
        name: 'NotFound',
        component: NotFound,
    },
]

const router = new VueRouter({
    mode: 'history',
    base: process.env.baseurl,
    routes
});

router.afterEach(() => {
    $('#preloader').hide();
});

export default router
