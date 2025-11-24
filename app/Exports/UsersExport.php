<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class UsersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting
{
    protected $role;

    public function __construct($role)
    {
        $this->role = $role;
    }

    public function collection()
    {
        if ($this->role === 'customer') {
            return User::whereHas('customer')->with('customer')->get();
        }

        if ($this->role === 'distributor') {
            return User::whereHas('distributor')->with('distributor')->get();
        }

        return collect([]);
    }

    public function map($user): array
    {
        if ($this->role === 'customer') {
            return [
                $user->id,
                $user->customer->first_name . ' ' . $user->customer->last_name,
                $user->email,
                $user->customer->contact_number,
                $user->customer->referral_code,
            ];
        }

        if ($this->role === 'distributor') {
            return [
                $user->id,
                $user->distributor->name,
                $user->email,
                $user->distributor->contact_number,
                $user->distributor->distributor_type,
                $user->distributor->region,
                $user->distributor->province,
                $user->distributor->city,
            ];
        }

        return [];
    }

    public function headings(): array
    {
        if ($this->role === 'customer') {
            return [
                'ID',
                'Name',
                'Email',
                'Contact Number',
                'Referral Code'
            ];
        }

        if ($this->role === 'distributor') {
            return [
                'ID',
                'Name',
                'Email',
                'Contact Number',
                'Distributor Type',
                'Region',
                'Province',
                'City'
            ];
        }

        return [];
    }

    public function columnFormats(): array
    {
        if ($this->role === 'customer') {
            return [
                'D' => NumberFormat::FORMAT_TEXT, // Contact Number as text
            ];
        }

        if ($this->role === 'distributor') {
            return [
                'D' => NumberFormat::FORMAT_TEXT, // Contact Number as text
            ];
        }

        return [];
    }
}
