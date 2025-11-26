<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use App\Models\Payslip;

class PayslipEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $payslip;
    private $pdfContent;

    /**
     * Create a new message instance.
     */
    public function __construct(Payslip $payslip, $pdfContent)
    {
        $this->payslip = $payslip;
        $this->pdfContent = $pdfContent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Payslip for ' . $this->payslip->getFormattedPeriod(),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payslip',
            with: [
                'payslip' => $this->payslip,
                'employeeName' => $this->payslip->employee_name,
                'period' => $this->payslip->getFormattedPeriod(),
                'netPay' => number_format($this->payslip->net_pay, 2)
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $filename = 'Payslip_' . $this->payslip->employee_name . '_' . $this->payslip->pay_period_year . '_' . str_pad($this->payslip->pay_period_month, 2, '0', STR_PAD_LEFT) . '.pdf';
        
        return [
            Attachment::fromData(fn () => $this->pdfContent, $filename)
                ->withMime('application/pdf')
        ];
    }
}