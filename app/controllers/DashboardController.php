<?php

use FI\Quotes\QuoteStatuses;
use FI\Invoices\InvoiceStatuses;
use FI\Storage\Interfaces\QuoteRepositoryInterface;
use FI\Storage\Interfaces\InvoiceRepositoryInterface;
use FI\Storage\Interfaces\QuoteAmountRepositoryInterface;
use FI\Storage\Interfaces\InvoiceAmountRepositoryInterface;

class DashboardController extends BaseController {
	
	protected $invoice;
	protected $quote;
	protected $invoiceAmount;
	protected $quoteAmount;

	public function __construct(
		InvoiceRepositoryInterface $invoice, 
		QuoteRepositoryInterface $quote,
		InvoiceAmountRepositoryInterface $invoiceAmount,
		QuoteAmountRepositoryInterface $quoteAmount)
	{
		$this->invoice       = $invoice;
		$this->quote         = $quote;
		$this->invoiceAmount = $invoiceAmount;
		$this->quoteAmount   = $quoteAmount;
	}

	public function index()
	{
		$invoiceStatuses = InvoiceStatuses::statuses();
		$quoteStatuses   = QuoteStatuses::statuses();

		unset($invoiceStatuses[0], $quoteStatuses[0]);

		return View::make('dashboard.index')
		->with('quoteStatuses', $quoteStatuses)
		->with('invoiceStatuses', $invoiceStatuses)
		->with('overdueInvoices', $this->invoice->getRecentOverdue(15))
		->with('recentInvoices', $this->invoice->getRecent(15))
		->with('recentQuotes', $this->quote->getRecent(15))
		->with('invoiceStatusAmounts', $this->invoiceAmount->getTotalsByStatus($invoiceStatuses))
		->with('quoteStatusAmounts', $this->quoteAmount->getTotalsByStatus($quoteStatuses));
	}

}