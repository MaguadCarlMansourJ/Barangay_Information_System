<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PaymentsExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Payment::with(['documentRequest.resident', 'receivedBy'])->get()->map(function ($payment) {
            return [
                'OR Number' => $payment->or_number,
                'Resident' => $payment->documentRequest->resident->full_name ?? 'N/A',
                'Document Type' => $payment->documentRequest->documentType->name ?? 'N/A',
                'Amount' => '₱' . number_format($payment->amount, 2),
                'Payment Method' => $payment->payment_method,
                'Payment Date' => $payment->payment_date->format('M d, Y h:i A'),
                'Received By' => $payment->receivedBy->name ?? 'N/A',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'OR Number',
            'Resident Name',
            'Document Type',
            'Amount',
            'Payment Method',
            'Payment Date',
            'Received By',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}

