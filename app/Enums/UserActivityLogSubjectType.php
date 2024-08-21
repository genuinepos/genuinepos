<?php

namespace App\Enums;

enum UserActivityLogSubjectType: int
{
    case Product = 26;
    case Customers = 1;
    case Suppliers = 2;
    case Users = 3;
    case UserLogin = 18;
    case UserLogout = 19;
    case Receipt = 27;
    case Payment = 28;
    case Contra = 31;
    case Purchase = 4;
    case PurchaseOrder = 5;
    case PurchaseReturn = 6;
    case Sales = 7;
    case Draft = 29;
    case Quotation = 30;
    case ChangeQuotationStatus = 43;
    case HoldInvoice = 32;
    case SuspendInvoice = 33;
    case SalesOrder = 8;
    case SaleReturn = 9;
    case ExchangeInvoice = 34;
    case UpdateShipmentDetails = 44;
    case TransferStock = 10;
    case StockAdjustment = 13;
    case Expense = 15;
    case Bank = 16;
    case Accounts = 17;
    case Categories = 20;
    case SubCategories = 21;
    case Brands = 22;
    case Units = 23;
    case Variants = 24;
    case Warranties = 25;
    case SellingPriceGroups = 35;
    case LocationSwitch = 36;
    case StockIssue = 37;
    case BuyPlan = 38;
    case BuyPlanDueRepayment = 39;
    case BuyShop = 40;
    case RenewStore = 41;
    case BuyBusiness = 42;

}
