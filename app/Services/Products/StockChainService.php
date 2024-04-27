<?php

namespace App\Services\Products;

use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;
use App\Models\Products\StockChain;
use App\Enums\StockAccountingMethod;
use App\Models\Purchases\PurchaseProduct;

class StockChainService
{
    public function addStockChain(?object $sale = null, ?object $stockIssue = null, ?object $stockAdjustment = null, int $stockAccountingMethod = 1)
    {
        if (isset($sale)) {

            foreach ($sale->saleProducts as $saleProduct) {

                if ($saleProduct->product->is_manage_stock == BooleanType::True->value) {

                    $variantId = $saleProduct->variant_id ? $saleProduct->variant_id : null;

                    $soldQty = $saleProduct->quantity;

                    $sortedBy = $stockAccountingMethod == StockAccountingMethod::FIFO->value ? 'asc' : 'desc';

                    $purchaseProducts = PurchaseProduct::where('left_qty', '>', '0')
                        ->where('product_id', $saleProduct->product_id)
                        ->where('variant_id', $variantId)
                        ->where('branch_id', auth()->user()->branch_id)
                        ->orderBy('created_at', $sortedBy)->get();

                    if (count($purchaseProducts) > 0) {

                        foreach ($purchaseProducts as $purchaseProduct) {

                            if ($soldQty > $purchaseProduct->left_qty) {

                                if ($soldQty > 0) {

                                    $this->stockChainInsert(branchId: $sale->branch_id, productId: $saleProduct->product_id, variantId: $saleProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'sale_product_id', transId: $saleProduct->id, outQty: $purchaseProduct->left_qty, createdAt: $sale->sale_date_ts);

                                    $soldQty = $soldQty - $purchaseProduct->left_qty;
                                    $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                } else {

                                    break;
                                }
                            } elseif ($soldQty == $purchaseProduct->left_qty) {

                                if ($soldQty > 0) {

                                    $this->stockChainInsert(branchId: $sale->branch_id, productId: $saleProduct->product_id, variantId: $saleProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'sale_product_id', transId: $saleProduct->id, outQty: $soldQty, createdAt: $sale->sale_date_ts);

                                    $soldQty = $soldQty - $soldQty;
                                    $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                } else {

                                    break;
                                }
                            } elseif ($soldQty < $purchaseProduct->left_qty) {

                                if ($soldQty > 0) {


                                    $this->stockChainInsert(branchId: $sale->branch_id, productId: $saleProduct->product_id, variantId: $saleProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'sale_product_id', transId: $saleProduct->id, outQty: $soldQty, createdAt: $sale->sale_date_ts);

                                    $soldQty = $soldQty - $soldQty;
                                    $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                } else {

                                    break;
                                }
                            }
                        }
                    }

                    if ($soldQty > 0) {

                        $purchaseProductsInGlobalWarehouse = DB::table('purchase_products')->where('purchase_products.left_qty', '>', '0')
                            ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
                            ->leftJoin('warehouses', 'purchases.warehouse_id', 'warehouses.id')
                            ->where('purchase_products.product_id', $saleProduct->product_id)
                            ->where('purchase_products.variant_id', $variantId)
                            ->where('warehouses.is_global', BooleanType::True->value)
                            ->orderBy('purchase_products.created_at', $sortedBy)->get();

                        if (count($purchaseProductsInGlobalWarehouse) > 0) {

                            foreach ($purchaseProductsInGlobalWarehouse as $purchaseProduct) {

                                if ($soldQty > $purchaseProduct->left_qty) {

                                    if ($soldQty > 0) {

                                        $this->stockChainInsert(branchId: $sale->branch_id, productId: $saleProduct->product_id, variantId: $saleProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'sale_product_id', transId: $saleProduct->id, outQty: $purchaseProduct->left_qty, createdAt: $sale->sale_date_ts);

                                        $soldQty = $soldQty - $purchaseProduct->left_qty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                } elseif ($soldQty == $purchaseProduct->left_qty) {

                                    if ($soldQty > 0) {

                                        $this->stockChainInsert(branchId: $sale->branch_id, productId: $saleProduct->product_id, variantId: $saleProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'sale_product_id', transId: $saleProduct->id, outQty: $soldQty, createdAt: $sale->sale_date_ts);

                                        $soldQty = $soldQty - $soldQty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                } elseif ($soldQty < $purchaseProduct->left_qty) {

                                    if ($soldQty > 0) {

                                        $this->stockChainInsert(branchId: $sale->branch_id, productId: $saleProduct->product_id, variantId: $saleProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'sale_product_id', transId: $saleProduct->id, outQty: $soldQty, createdAt: $sale->sale_date_ts);

                                        $soldQty = $soldQty - $soldQty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                }
                            }
                        }
                    }
                } else {

                    $this->stockChainInsert(branchId: $sale->branch_id, productId: $saleProduct->product_id, variantId: $saleProduct->variant_id, purchaseProductId: null, transColName: 'sale_product_id', transId: $saleProduct->id, outQty: $saleProduct->quantity, createdAt: $sale->sale_date_ts);
                }
            }
        }

        if (isset($stockIssue)) {

            foreach ($stockIssue->stockIssuedProducts as $issuedProduct) {

                if ($issuedProduct->product->is_manage_stock == BooleanType::True->value) {

                    $variantId = $issuedProduct->variant_id ? $issuedProduct->variant_id : null;

                    $issuedQty = $issuedProduct->quantity;

                    $sortedBy = $stockAccountingMethod == StockAccountingMethod::FIFO->value ? 'asc' : 'desc';

                    $purchaseProducts = PurchaseProduct::where('left_qty', '>', '0')
                        ->where('product_id', $issuedProduct->product_id)
                        ->where('variant_id', $variantId)
                        ->where('branch_id', auth()->user()->branch_id)
                        ->orderBy('created_at', $sortedBy)->get();

                    if (count($purchaseProducts) > 0) {

                        foreach ($purchaseProducts as $purchaseProduct) {

                            if ($issuedQty > $purchaseProduct->left_qty) {

                                if ($issuedQty > 0) {

                                    $this->stockChainInsert(branchId: $stockIssue->branch_id, productId: $issuedProduct->product_id, variantId: $issuedProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'stock_issue_product_id', transId: $issuedProduct->id, outQty: $purchaseProduct->left_qty, createdAt: $stockIssue->date_ts);

                                    $issuedQty = $issuedQty - $purchaseProduct->left_qty;
                                    $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                } else {

                                    break;
                                }
                            } elseif ($issuedQty == $purchaseProduct->left_qty) {

                                if ($issuedQty > 0) {

                                    $this->stockChainInsert(branchId: $stockIssue->branch_id, productId: $issuedProduct->product_id, variantId: $issuedProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'stock_issue_product_id', transId: $issuedProduct->id, outQty: $issuedQty, createdAt: $stockIssue->date_ts);

                                    $issuedQty = $issuedQty - $issuedQty;
                                    $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                } else {

                                    break;
                                }
                            } elseif ($issuedQty < $purchaseProduct->left_qty) {

                                if ($issuedQty > 0) {

                                    $this->stockChainInsert(branchId: $stockIssue->branch_id, productId: $issuedProduct->product_id, variantId: $issuedProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'stock_issue_product_id', transId: $issuedProduct->id, outQty: $issuedQty, createdAt: $stockIssue->date_ts);

                                    $issuedQty = $issuedQty - $issuedQty;
                                    $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                } else {

                                    break;
                                }
                            }
                        }
                    }

                    if ($issuedQty > 0) {

                        $purchaseProductsInGlobalWarehouse = DB::table('purchase_products')->where('purchase_products.left_qty', '>', '0')
                            ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
                            ->leftJoin('warehouses', 'purchases.warehouse_id', 'warehouses.id')
                            ->where('purchase_products.product_id', $issuedProduct->product_id)
                            ->where('purchase_products.variant_id', $variantId)
                            ->where('warehouses.is_global', BooleanType::True->value)
                            ->orderBy('purchase_products.created_at', $sortedBy)->get();

                        if (count($purchaseProductsInGlobalWarehouse) > 0) {

                            foreach ($purchaseProductsInGlobalWarehouse as $purchaseProduct) {

                                if ($issuedQty > $purchaseProduct->left_qty) {

                                    if ($issuedQty > 0) {

                                        $this->stockChainInsert(branchId: $stockIssue->branch_id, productId: $issuedProduct->product_id, variantId: $issuedProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'stock_issue_product_id', transId: $issuedProduct->id, outQty: $purchaseProduct->left_qty, createdAt: $stockIssue->date_ts);

                                        $issuedQty = $issuedQty - $purchaseProduct->left_qty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                } elseif ($issuedQty == $purchaseProduct->left_qty) {

                                    if ($issuedQty > 0) {

                                        $this->stockChainInsert(branchId: $stockIssue->branch_id, productId: $issuedProduct->product_id, variantId: $issuedProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'stock_issue_product_id', transId: $issuedProduct->id, outQty: $issuedQty, createdAt: $stockIssue->date_ts);

                                        $issuedQty = $issuedQty - $issuedQty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                } elseif ($issuedQty < $purchaseProduct->left_qty) {

                                    if ($issuedQty > 0) {

                                        $this->stockChainInsert(branchId: $stockIssue->branch_id, productId: $issuedProduct->product_id, variantId: $issuedProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'stock_issue_product_id', transId: $issuedProduct->id, outQty: $issuedQty, createdAt: $stockIssue->date_ts);

                                        $issuedQty = $issuedQty - $issuedQty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                }
                            }
                        }
                    }
                } else {

                    $this->stockChainInsert(branchId: $stockIssue->branch_id, productId: $issuedProduct->product_id, variantId: $issuedProduct->variant_id, purchaseProductId: null, transColName: 'stock_issue_product_id', transId: $issuedProduct->id, outQty: $issuedProduct->quantity, createdAt: $stockIssue->date_ts);
                }
            }
        }

        if (isset($stockAdjustment)) {

            foreach ($stockAdjustment->adjustmentProducts as $adjustmentProduct) {

                if ($adjustmentProduct->product->is_manage_stock == BooleanType::True->value) {

                    $variantId = $adjustmentProduct->variant_id ? $adjustmentProduct->variant_id : null;

                    $adjustedQty = $adjustmentProduct->quantity;

                    $sortedBy = $stockAccountingMethod == StockAccountingMethod::FIFO->value ? 'asc' : 'desc';

                    $purchaseProducts = PurchaseProduct::where('left_qty', '>', '0')
                        ->where('product_id', $adjustmentProduct->product_id)
                        ->where('variant_id', $variantId)
                        ->where('branch_id', auth()->user()->branch_id)
                        ->orderBy('created_at', $sortedBy)->get();

                    if (count($purchaseProducts) > 0) {

                        foreach ($purchaseProducts as $purchaseProduct) {

                            if ($adjustedQty > $purchaseProduct->left_qty) {

                                if ($adjustedQty > 0) {

                                    $this->stockChainInsert(branchId: $stockAdjustment->branch_id, productId: $adjustmentProduct->product_id, variantId: $adjustmentProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'stock_adjustment_product_id', transId: $adjustmentProduct->id, outQty: $purchaseProduct->left_qty, createdAt: $stockAdjustment->date_ts);

                                    $adjustedQty = $adjustedQty - $purchaseProduct->left_qty;
                                    $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                } else {

                                    break;
                                }
                            } elseif ($adjustedQty == $purchaseProduct->left_qty) {

                                if ($adjustedQty > 0) {

                                    $this->stockChainInsert(branchId: $stockAdjustment->branch_id, productId: $adjustmentProduct->product_id, variantId: $adjustmentProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'stock_adjustment_product_id', transId: $adjustmentProduct->id, outQty: $adjustedQty, createdAt: $stockAdjustment->date_ts);

                                    $adjustedQty = $adjustedQty - $adjustedQty;
                                    $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                } else {

                                    break;
                                }
                            } elseif ($adjustedQty < $purchaseProduct->left_qty) {

                                if ($adjustedQty > 0) {

                                    $this->stockChainInsert(branchId: $stockAdjustment->branch_id, productId: $adjustmentProduct->product_id, variantId: $adjustmentProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'stock_adjustment_product_id', transId: $adjustmentProduct->id, outQty: $adjustedQty, createdAt: $stockAdjustment->date_ts);

                                    $adjustedQty = $adjustedQty - $adjustedQty;
                                    $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                } else {

                                    break;
                                }
                            }
                        }
                    }

                    if ($adjustedQty > 0) {

                        $purchaseProductsInGlobalWarehouse = DB::table('purchase_products')->where('purchase_products.left_qty', '>', '0')
                            ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
                            ->leftJoin('warehouses', 'purchases.warehouse_id', 'warehouses.id')
                            ->where('purchase_products.product_id', $adjustmentProduct->product_id)
                            ->where('purchase_products.variant_id', $variantId)
                            ->where('warehouses.is_global', BooleanType::True->value)
                            ->orderBy('purchase_products.created_at', $sortedBy)->get();

                        if (count($purchaseProductsInGlobalWarehouse) > 0) {

                            foreach ($purchaseProductsInGlobalWarehouse as $purchaseProduct) {

                                if ($adjustedQty > $purchaseProduct->left_qty) {

                                    if ($adjustedQty > 0) {

                                        $this->stockChainInsert(branchId: $stockAdjustment->branch_id, productId: $adjustmentProduct->product_id, variantId: $adjustmentProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'stock_adjustment_product_id', transId: $adjustmentProduct->id, outQty: $purchaseProduct->left_qty, createdAt: $stockAdjustment->date_ts);

                                        $adjustedQty = $adjustedQty - $purchaseProduct->left_qty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                } elseif ($adjustedQty == $purchaseProduct->left_qty) {

                                    if ($adjustedQty > 0) {

                                        $this->stockChainInsert(branchId: $stockAdjustment->branch_id, productId: $adjustmentProduct->product_id, variantId: $adjustmentProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'stock_adjustment_product_id', transId: $adjustmentProduct->id, outQty: $adjustedQty, createdAt: $stockAdjustment->date_ts);

                                        $adjustedQty = $adjustedQty - $adjustedQty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                } elseif ($adjustedQty < $purchaseProduct->left_qty) {

                                    if ($adjustedQty > 0) {

                                        $this->stockChainInsert(branchId: $stockAdjustment->branch_id, productId: $adjustmentProduct->product_id, variantId: $adjustmentProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'stock_adjustment_product_id', transId: $adjustmentProduct->id, outQty: $adjustedQty, createdAt: $stockAdjustment->date_ts);

                                        $adjustedQty = $adjustedQty - $adjustedQty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                }
                            }
                        }
                    }
                } else {

                    $this->stockChainInsert(branchId: $stockAdjustment->branch_id, productId: $adjustmentProduct->product_id, variantId: $adjustmentProduct->variant_id, purchaseProductId: null, transColName: 'stock_adjustment_product_id', transId: $adjustmentProduct->id, outQty: $issuedProduct->quantity, createdAt: $stockAdjustment->date_ts);
                }
            }
        }
    }

    public function updateStockChain(?object $sale = null, ?object $stockIssue = null, ?object $stockAdjustment = null, int $stockAccountingMethod = 1)
    {
        if (isset($sale)) {

            foreach ($sale->saleProducts as $saleProduct) {

                if ($saleProduct->product->is_manage_stock == 1) {

                    $variantId = $saleProduct->variant_id ? $saleProduct->variant_id : null;

                    $soldQty = $saleProduct->quantity;

                    $stockChains = StockChain::with('purchaseProduct')->where('sale_product_id', $saleProduct->id)->get();

                    foreach ($stockChains as $stockChain) {

                        $stockChain->purchaseProduct->left_qty += $stockChain->out_qty;
                        $stockChain->purchaseProduct->save();

                        if ($soldQty > $stockChain->purchaseProduct->left_qty) {

                            $stockChain->created_at = $sale->sale_date_ts;
                            $stockChain->out_qty = $stockChain->purchaseProduct->left_qty;
                            $stockChain->save();
                            $soldQty = $soldQty - $stockChain->purchaseProduct->left_qty;
                            $this->adjustPurchaseProductOutLeftQty($stockChain->purchaseProduct);
                        } elseif ($soldQty == $stockChain->purchaseProduct->left_qty) {

                            $stockChain->created_at = $sale->sale_date_ts;
                            $stockChain->out_qty = $soldQty;
                            $stockChain->save();
                            $soldQty = $soldQty - $soldQty;
                            $this->adjustPurchaseProductOutLeftQty($stockChain->purchaseProduct);
                        } elseif ($soldQty < $stockChain->purchaseProduct->left_qty) {

                            $stockChain->created_at = $sale->sale_date_ts;
                            $stockChain->out_qty = $soldQty;
                            $stockChain->save();
                            $soldQty = $soldQty - $soldQty;
                            $this->adjustPurchaseProductOutLeftQty($stockChain->purchaseProduct);
                        }
                    }

                    if ($soldQty > 0) {

                        $sortedBy = $stockAccountingMethod == StockAccountingMethod::FIFO->value ? 'asc' : 'desc';
                        $purchaseProducts = PurchaseProduct::where('left_qty', '>', '0')
                            ->where('product_id', $saleProduct->product_id)
                            ->where('variant_id', $variantId)
                            ->where('branch_id', auth()->user()->branch_id)
                            ->orderBy('created_at',  $sortedBy)->get();

                        if (count($purchaseProducts) > 0) {

                            foreach ($purchaseProducts as $purchaseProduct) {

                                if ($soldQty > $purchaseProduct->left_qty) {

                                    if ($soldQty > 0) {

                                        $this->stockChainInsert(branchId: $sale->branch_id, productId: $saleProduct->product_id, variantId: $saleProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'sale_product_id', transId: $saleProduct->id, outQty: $purchaseProduct->left_qty, createdAt: $sale->sale_date_ts);

                                        $soldQty = $soldQty - $purchaseProduct->left_qty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                } elseif ($soldQty == $purchaseProduct->left_qty) {

                                    if ($soldQty > 0) {

                                        $this->stockChainInsert(branchId: $sale->branch_id, productId: $saleProduct->product_id, variantId: $saleProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'sale_product_id', transId: $saleProduct->id, outQty: $soldQty, createdAt: $sale->sale_date_ts);

                                        $soldQty = $soldQty - $soldQty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                } elseif ($soldQty < $purchaseProduct->left_qty) {

                                    if ($soldQty > 0) {

                                        $this->stockChainInsert(branchId: $sale->branch_id, productId: $saleProduct->product_id, variantId: $saleProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'sale_product_id', transId: $saleProduct->id, outQty: $soldQty, createdAt: $sale->sale_date_ts);

                                        $soldQty = $soldQty - $soldQty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                }
                            }
                        }
                    }

                    if ($soldQty > 0) {

                        $purchaseProductsInGlobalWarehouse = DB::table('purchase_products')->where('purchase_products.left_qty', '>', '0')
                            ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
                            ->leftJoin('warehouses', 'purchases.warehouse_id', 'warehouses.id')
                            ->where('purchase_products.product_id', $saleProduct->product_id)
                            ->where('purchase_products.variant_id', $variantId)
                            ->where('warehouses.is_global', BooleanType::True->value)
                            ->orderBy('purchase_products.created_at', $sortedBy)->get();

                        if (count($purchaseProductsInGlobalWarehouse) > 0) {

                            foreach ($purchaseProductsInGlobalWarehouse as $purchaseProduct) {

                                if ($soldQty > $purchaseProduct->left_qty) {

                                    if ($soldQty > 0) {

                                        $this->stockChainInsert(branchId: $sale->branch_id, productId: $saleProduct->product_id, variantId: $saleProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'sale_product_id', transId: $saleProduct->id, outQty: $purchaseProduct->left_qty, createdAt: $sale->sale_date_ts);

                                        $soldQty = $soldQty - $purchaseProduct->left_qty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                } elseif ($soldQty == $purchaseProduct->left_qty) {

                                    if ($soldQty > 0) {

                                        $this->stockChainInsert(branchId: $sale->branch_id, productId: $saleProduct->product_id, variantId: $saleProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'sale_product_id', transId: $saleProduct->id, outQty: $soldQty, createdAt: $sale->sale_date_ts);

                                        $soldQty = $soldQty - $soldQty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                } elseif ($soldQty < $purchaseProduct->left_qty) {

                                    if ($soldQty > 0) {

                                        $this->stockChainInsert(branchId: $sale->branch_id, productId: $saleProduct->product_id, variantId: $saleProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'sale_product_id', transId: $saleProduct->id, outQty: $soldQty, createdAt: $sale->sale_date_ts);

                                        $soldQty = $soldQty - $soldQty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if (isset($stockIssue)) {

            foreach ($stockIssue->stockIssuedProducts as $issuedProduct) {

                if ($issuedProduct->product->is_manage_stock == BooleanType::True->value) {

                    $variantId = $issuedProduct->variant_id ? $issuedProduct->variant_id : null;

                    $issuedQty = $issuedProduct->quantity;

                    $stockChains = StockChain::with('purchaseProduct')
                        ->where('stock_issue_product_id', $issuedProduct->id)->get();

                    foreach ($stockChains as $stockChain) {

                        $stockChain->purchaseProduct->left_qty += $stockChain->out_qty;
                        $stockChain->purchaseProduct->save();

                        if ($issuedQty > $stockChain->purchaseProduct->left_qty) {

                            $stockChain->created_at = $stockIssue->date_ts;
                            $stockChain->out_qty = $stockChain->purchaseProduct->left_qty;
                            $stockChain->save();
                            $issuedQty = $issuedQty - $stockChain->purchaseProduct->left_qty;
                            $this->adjustPurchaseProductOutLeftQty($stockChain->purchaseProduct);
                        } elseif ($issuedQty == $stockChain->purchaseProduct->left_qty) {

                            $stockChain->created_at = $stockIssue->date_ts;
                            $stockChain->out_qty = $issuedQty;
                            $stockChain->save();
                            $issuedQty = $issuedQty - $issuedQty;
                            $this->adjustPurchaseProductOutLeftQty($stockChain->purchaseProduct);
                        } elseif ($issuedQty < $stockChain->purchaseProduct->left_qty) {

                            $stockChain->created_at = $stockIssue->date_ts;
                            $stockChain->out_qty = $issuedQty;
                            $stockChain->save();
                            $issuedQty = $issuedQty - $issuedQty;
                            $this->adjustPurchaseProductOutLeftQty($stockChain->purchaseProduct);
                        }
                    }

                    if ($issuedQty > 0) {

                        $sortedBy = $stockAccountingMethod == StockAccountingMethod::FIFO->value ? 'asc' : 'desc';
                        $purchaseProducts = PurchaseProduct::where('left_qty', '>', '0')
                            ->where('product_id', $issuedProduct->product_id)
                            ->where('variant_id', $variantId)
                            ->where('branch_id', auth()->user()->branch_id)
                            ->orderBy('created_at',  $sortedBy)->get();

                        if (count($purchaseProducts) > 0) {

                            foreach ($purchaseProducts as $purchaseProduct) {

                                if ($issuedQty > $purchaseProduct->left_qty) {

                                    if ($issuedQty > 0) {

                                        $this->stockChainInsert(branchId: $stockIssue->branch_id, productId: $issuedProduct->product_id, variantId: $issuedProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'stock_issue_product_id', transId: $issuedProduct->id, outQty: $purchaseProduct->left_qty, createdAt: $stockIssue->date_ts);

                                        $issuedQty = $issuedQty - $purchaseProduct->left_qty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                } elseif ($issuedQty == $purchaseProduct->left_qty) {

                                    if ($issuedQty > 0) {

                                        $this->stockChainInsert(branchId: $stockIssue->branch_id, productId: $issuedProduct->product_id, variantId: $issuedProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'stock_issue_product_id', transId: $issuedProduct->id, outQty: $issuedQty, createdAt: $stockIssue->date_ts);

                                        $issuedQty = $issuedQty - $issuedQty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                } elseif ($issuedQty < $purchaseProduct->left_qty) {

                                    if ($issuedQty > 0) {

                                        $this->stockChainInsert(branchId: $stockIssue->branch_id, productId: $issuedProduct->product_id, variantId: $issuedProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'stock_issue_product_id', transId: $issuedProduct->id, outQty: $issuedQty, createdAt: $stockIssue->date_ts);

                                        $issuedQty = $issuedQty - $issuedQty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                }
                            }
                        }
                    }

                    if ($issuedQty > 0) {

                        $purchaseProductsInGlobalWarehouse = DB::table('purchase_products')->where('purchase_products.left_qty', '>', '0')
                            ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
                            ->leftJoin('warehouses', 'purchases.warehouse_id', 'warehouses.id')
                            ->where('purchase_products.product_id', $issuedProduct->product_id)
                            ->where('purchase_products.variant_id', $variantId)
                            ->where('warehouses.is_global', BooleanType::True->value)
                            ->orderBy('purchase_products.created_at', $sortedBy)->get();

                        if (count($purchaseProductsInGlobalWarehouse) > 0) {

                            foreach ($purchaseProductsInGlobalWarehouse as $purchaseProduct) {

                                if ($issuedQty > $purchaseProduct->left_qty) {

                                    if ($issuedQty > 0) {

                                        $this->stockChainInsert(branchId: $stockIssue->branch_id, productId: $issuedProduct->product_id, variantId: $issuedProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'stock_issue_product_id', transId: $issuedProduct->id, outQty: $purchaseProduct->left_qty, createdAt: $stockIssue->date_ts);

                                        $issuedQty = $issuedQty - $purchaseProduct->left_qty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                } elseif ($issuedQty == $purchaseProduct->left_qty) {

                                    if ($issuedQty > 0) {

                                        $this->stockChainInsert(branchId: $stockIssue->branch_id, productId: $issuedProduct->product_id, variantId: $issuedProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'stock_issue_product_id', transId: $issuedProduct->id, outQty: $issuedQty, createdAt: $stockIssue->date_ts);

                                        $issuedQty = $issuedQty - $issuedQty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                } elseif ($issuedQty < $purchaseProduct->left_qty) {

                                    if ($issuedQty > 0) {

                                        $this->stockChainInsert(branchId: $stockIssue->branch_id, productId: $issuedProduct->product_id, variantId: $issuedProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'stock_issue_product_id', transId: $issuedProduct->id, outQty: $issuedQty, createdAt: $stockIssue->date_ts);

                                        $issuedQty = $issuedQty - $issuedQty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if (isset($stockAdjustment)) {

            foreach ($stockAdjustment->adjustmentProducts as $adjustmentProduct) {

                if ($adjustmentProduct->product->is_manage_stock == BooleanType::True->value) {

                    $variantId = $adjustmentProduct->variant_id ? $adjustmentProduct->variant_id : null;

                    $adjustedQty = $adjustmentProduct->quantity;

                    $stockChains = StockChain::with('purchaseProduct')->where('stock_adjustment_product_id', $adjustmentProduct->id)->get();

                    foreach ($stockChains as $stockChain) {

                        $stockChain->purchaseProduct->left_qty += $stockChain->out_qty;
                        $stockChain->purchaseProduct->save();

                        if ($adjustedQty > $stockChain->purchaseProduct->left_qty) {

                            $stockChain->created_at = $stockAdjustment->date_ts;
                            $stockChain->out_qty = $stockChain->purchaseProduct->left_qty;
                            $stockChain->save();
                            $adjustedQty = $adjustedQty - $stockChain->purchaseProduct->left_qty;
                            $this->adjustPurchaseProductOutLeftQty($stockChain->purchaseProduct);
                        } elseif ($adjustedQty == $stockChain->purchaseProduct->left_qty) {

                            $stockChain->created_at = $stockAdjustment->date_ts;
                            $stockChain->out_qty = $adjustedQty;
                            $stockChain->save();
                            $adjustedQty = $adjustedQty - $adjustedQty;
                            $this->adjustPurchaseProductOutLeftQty($stockChain->purchaseProduct);
                        } elseif ($adjustedQty < $stockChain->purchaseProduct->left_qty) {

                            $stockChain->created_at = $stockAdjustment->date_ts;
                            $stockChain->out_qty = $adjustedQty;
                            $stockChain->save();
                            $adjustedQty = $adjustedQty - $adjustedQty;
                            $this->adjustPurchaseProductOutLeftQty($stockChain->purchaseProduct);
                        }
                    }

                    if ($adjustedQty > 0) {

                        $sortedBy = $stockAccountingMethod == StockAccountingMethod::FIFO->value ? 'asc' : 'desc';
                        $purchaseProducts = PurchaseProduct::where('left_qty', '>', '0')
                            ->where('product_id', $adjustmentProduct->product_id)
                            ->where('variant_id', $variantId)
                            ->where('branch_id', auth()->user()->branch_id)
                            ->orderBy('created_at',  $sortedBy)->get();

                        if (count($purchaseProducts) > 0) {

                            foreach ($purchaseProducts as $purchaseProduct) {

                                if ($adjustedQty > $purchaseProduct->left_qty) {

                                    if ($adjustedQty > 0) {

                                        $this->stockChainInsert(branchId: $stockIssue->branch_id, productId: $issuedProduct->product_id, variantId: $issuedProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'stock_issue_product_id', transId: $issuedProduct->id, outQty: $purchaseProduct->left_qty, createdAt: $stockAdjustment->date_ts);

                                        $adjustedQty = $adjustedQty - $purchaseProduct->left_qty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                } elseif ($adjustedQty == $purchaseProduct->left_qty) {

                                    if ($adjustedQty > 0) {

                                        $this->stockChainInsert(branchId: $stockIssue->branch_id, productId: $issuedProduct->product_id, variantId: $issuedProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'stock_issue_product_id', transId: $issuedProduct->id, outQty: $adjustedQty, createdAt: $stockAdjustment->date_ts);

                                        $issuedQty = $adjustedQty - $adjustedQty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                } elseif ($adjustedQty < $purchaseProduct->left_qty) {

                                    if ($adjustedQty > 0) {

                                        $this->stockChainInsert(branchId: $stockIssue->branch_id, productId: $issuedProduct->product_id, variantId: $issuedProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'stock_issue_product_id', transId: $issuedProduct->id, outQty: $adjustedQty, createdAt: $stockAdjustment->date_ts);

                                        $adjustedQty = $adjustedQty - $adjustedQty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                }
                            }
                        }
                    }

                    if ($adjustedQty > 0) {

                        $purchaseProductsInGlobalWarehouse = DB::table('purchase_products')->where('purchase_products.left_qty', '>', 0)
                            ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
                            ->leftJoin('warehouses', 'purchase_products.warehouse_id', 'warehouses.id')
                            ->where('purchase_products.product_id', $adjustmentProduct->product_id)
                            ->where('purchase_products.variant_id', $variantId)
                            ->where('warehouses.is_global', BooleanType::True->value)
                            ->orderBy('purchase_products.created_at', $sortedBy)->get();

                        if (count($purchaseProductsInGlobalWarehouse) > 0) {

                            foreach ($purchaseProductsInGlobalWarehouse as $purchaseProduct) {

                                if ($issuedQty > $purchaseProduct->left_qty) {

                                    if ($adjustedQty > 0) {

                                        $this->stockChainInsert(branchId: $stockIssue->branch_id, productId: $issuedProduct->product_id, variantId: $issuedProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'stock_issue_product_id', transId: $issuedProduct->id, outQty: $purchaseProduct->left_qty, createdAt: $stockAdjustment->date_ts);

                                        $adjustedQty = $adjustedQty - $purchaseProduct->left_qty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                } elseif ($adjustedQty == $purchaseProduct->left_qty) {

                                    if ($adjustedQty > 0) {

                                        $this->stockChainInsert(branchId: $stockIssue->branch_id, productId: $issuedProduct->product_id, variantId: $issuedProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'stock_issue_product_id', transId: $issuedProduct->id, outQty: $adjustedQty, createdAt: $stockAdjustment->date_ts);

                                        $adjustedQty = $adjustedQty - $adjustedQty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                } elseif ($adjustedQty < $purchaseProduct->left_qty) {

                                    if ($adjustedQty > 0) {

                                        $this->stockChainInsert(branchId: $stockIssue->branch_id, productId: $issuedProduct->product_id, variantId: $issuedProduct->variant_id, purchaseProductId: $purchaseProduct->id, transColName: 'stock_issue_product_id', transId: $issuedProduct->id, outQty: $adjustedQty, createdAt: $stockAdjustment->date_ts);

                                        $adjustedQty = $adjustedQty - $adjustedQty;
                                        $this->adjustPurchaseProductOutLeftQty($purchaseProduct);
                                    } else {

                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function adjustPurchaseProductOutLeftQty($purchaseProduct)
    {
        $totalOut = DB::table('stock_chains')
            ->where('purchase_product_id', $purchaseProduct->id)
            ->select(DB::raw('SUM(out_qty) as total_out'))
            ->groupBy('purchase_product_id')->get();

        $leftQty = $purchaseProduct->quantity - $totalOut->sum('total_out');
        $purchaseProduct->left_qty = $leftQty;
        $purchaseProduct->save();
    }

    private function stockChainInsert(?int $branchId, int $productId, ?int $variantId, ?int $purchaseProductId, string $transColName, int $transId, float $outQty, string $createdAt): void
    {
        $addStockChain = new StockChain();
        $addStockChain->branch_id = $branchId;
        $addStockChain->product_id = $productId;
        $addStockChain->variant_id = $variantId;
        $addStockChain->purchase_product_id = $purchaseProductId;
        $addStockChain->{$transColName} = $transId;
        $addStockChain->out_qty = $outQty;
        $addStockChain->created_at = $createdAt;
        $addStockChain->save();
    }
}
