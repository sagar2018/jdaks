<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Project;
use App\Models\BillingConfig;
use App\Models\RaBill;
use App\Models\PvIndex;
use App\Models\Expense;

class SampleProjectSeeder extends Seeder
{
    public function run(): void
    {
        $now     = Carbon::now();
        $admin   = User::where('email', 'admin@jdaksinfra.com')->first();
        $adminId = $admin?->id ?? 1;

        // ── 1. Project ───────────────────────────────────────────────────────
        $project = Project::create([
            'name'           => 'NH-44 Four-Lane Improvement: Km 142 to Km 178',
            'package'        => 'PKG-NH44-2024-07',
            'location'       => 'Madhya Pradesh – Gwalior to Shivpuri',
            'contractor'     => 'JDAKS Infrastructure Pvt. Ltd.',
            'chainage_start' => 142.000,
            'chainage_end'   => 178.000,
            'start_date'     => '2024-04-01',
            'end_date'       => '2026-03-31',
            'deadline'       => '2026-01-31',
            'bid_date'       => '2024-01-15',
            'bid_type'       => 'below',
            'bid_pct'        => 4.75,
            'bit_grade'      => 'VG-40',
            'dual_lane'      => true,
            'notes'          => 'World Bank funded NH improvement project. Includes widening, realignment at 3 curves, 2 minor bridges, and 4 underpasses.',
            'created_by'     => $adminId,
        ]);

        // Assign admin to project
        DB::table('project_user')->insert([
            'project_id'  => $project->id,
            'user_id'     => $adminId,
            'assigned_by' => $adminId,
            'assigned_at' => $now,
        ]);

        // ── 2. BOQ Items ─────────────────────────────────────────────────────
        $boqData = [
            // Earthwork
            ['category' => 'Earthwork',   'description' => 'Clearing & Grubbing',                          'unit' => 'Ha',     'quantity' => 24.000, 'rate' => 12500.00,  'sort_order' => 10],
            ['category' => 'Earthwork',   'description' => 'Excavation in cutting including disposal',      'unit' => 'cum',    'quantity' => 45820.000, 'rate' => 185.00,'sort_order' => 20],
            ['category' => 'Earthwork',   'description' => 'Embankment with borrow material',               'unit' => 'cum',    'quantity' => 128450.000,'rate' => 210.00,'sort_order' => 30],
            ['category' => 'Earthwork',   'description' => 'Sub-grade preparation (Proof rolling)',         'unit' => 'sqm',    'quantity' => 216000.000,'rate' => 22.50,'sort_order' => 40],

            // Pavement
            ['category' => 'Pavement',    'description' => 'Granular Sub Base (GSB) – 200mm',              'unit' => 'cum',    'quantity' => 43200.000, 'rate' => 1250.00, 'sort_order' => 50],
            ['category' => 'Pavement',    'description' => 'Wet Mix Macadam (WMM) – 250mm',                'unit' => 'cum',    'quantity' => 54000.000, 'rate' => 1875.00, 'sort_order' => 60],
            ['category' => 'Pavement',    'description' => 'Dense Bituminous Macadam (DBM) – 50mm',        'unit' => 'sqm',    'quantity' => 216000.000,'rate' => 425.00,  'sort_order' => 70],
            ['category' => 'Pavement',    'description' => 'Bituminous Concrete (BC) – 40mm (VG-40)',      'unit' => 'sqm',    'quantity' => 216000.000,'rate' => 560.00,  'sort_order' => 80],
            ['category' => 'Pavement',    'description' => 'Prime Coat (SS-1)',                             'unit' => 'sqm',    'quantity' => 216000.000,'rate' => 18.50,   'sort_order' => 90],
            ['category' => 'Pavement',    'description' => 'Tack Coat (RS-1)',                              'unit' => 'sqm',    'quantity' => 216000.000,'rate' => 12.00,   'sort_order' => 100],

            // Drainage
            ['category' => 'Drainage',    'description' => 'Longitudinal rubble drain (0.6m × 0.6m)',      'unit' => 'rmt',    'quantity' => 36000.000, 'rate' => 1450.00, 'sort_order' => 110],
            ['category' => 'Drainage',    'description' => 'Catch water drain – 0.9m × 0.6m',              'unit' => 'rmt',    'quantity' => 14400.000, 'rate' => 1820.00, 'sort_order' => 120],
            ['category' => 'Drainage',    'description' => 'HP Pipe Culvert NP3 – 900mm dia',              'unit' => 'rmt',    'quantity' => 480.000,   'rate' => 8500.00, 'sort_order' => 130],
            ['category' => 'Drainage',    'description' => 'Slab culvert 2×2m, single cell',               'unit' => 'sqm',    'quantity' => 960.000,   'rate' => 28500.00,'sort_order' => 140],

            // Structures
            ['category' => 'Structures',  'description' => 'RCC M30 – Minor bridge superstructure',        'unit' => 'cum',    'quantity' => 820.000,   'rate' => 12500.00,'sort_order' => 150],
            ['category' => 'Structures',  'description' => 'HYSD Steel Fe500 – bridges',                   'unit' => 'MT',     'quantity' => 124.000,   'rate' => 85000.00,'sort_order' => 160],
            ['category' => 'Structures',  'description' => 'Elastomeric bearings',                         'unit' => 'Nos',    'quantity' => 48.000,    'rate' => 14500.00,'sort_order' => 170],

            // Traffic & Safety
            ['category' => 'Traffic',     'description' => 'Road marking – thermoplastic (white)',          'unit' => 'sqm',    'quantity' => 18000.000, 'rate' => 185.00,  'sort_order' => 180],
            ['category' => 'Traffic',     'description' => 'Road marking – thermoplastic (yellow)',         'unit' => 'sqm',    'quantity' => 4200.000,  'rate' => 195.00,  'sort_order' => 190],
            ['category' => 'Traffic',     'description' => 'Overhead sign gantry',                         'unit' => 'Nos',    'quantity' => 6.000,     'rate' => 380000.00,'sort_order'=> 200],
            ['category' => 'Traffic',     'description' => 'Reflective delineators',                       'unit' => 'Nos',    'quantity' => 1440.000,  'rate' => 650.00,  'sort_order' => 210],
            ['category' => 'Traffic',     'description' => 'W-Beam crash barrier (single sided)',           'unit' => 'rmt',    'quantity' => 7200.000,  'rate' => 2850.00, 'sort_order' => 220],
        ];

        $boqIds = [];
        foreach ($boqData as $row) {
            $id = DB::table('boq_items')->insertGetId([
                'project_id'  => $project->id,
                'category'    => $row['category'],
                'description' => $row['description'],
                'unit'        => $row['unit'],
                'quantity'    => $row['quantity'],
                'rate'        => $row['rate'],
                'sort_order'  => $row['sort_order'],
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
            $boqIds[$row['description']] = $id;
        }

        // ── 3. Progress Entries ──────────────────────────────────────────────
        $progressData = [
            // Earthwork progress – mostly done
            ['boq' => 'Clearing & Grubbing',            'date' => '2024-04-20', 'qty' => 24.000,     'from' => 142.000, 'to' => 166.000, 'status' => 'completed'],
            ['boq' => 'Excavation in cutting including disposal', 'date' => '2024-06-15', 'qty' => 28500.000, 'from' => 142.000, 'to' => 158.000, 'status' => 'completed'],
            ['boq' => 'Excavation in cutting including disposal', 'date' => '2024-08-10', 'qty' => 17320.000, 'from' => 158.000, 'to' => 178.000, 'status' => 'partial'],
            ['boq' => 'Embankment with borrow material', 'date' => '2024-07-30', 'qty' => 85000.000, 'from' => 142.000, 'to' => 162.000, 'status' => 'completed'],
            ['boq' => 'Embankment with borrow material', 'date' => '2024-10-15', 'qty' => 38500.000, 'from' => 162.000, 'to' => 175.000, 'status' => 'partial'],

            // Sub-base / base
            ['boq' => 'Granular Sub Base (GSB) – 200mm',  'date' => '2024-09-10', 'qty' => 25200.000, 'from' => 142.000, 'to' => 158.000, 'status' => 'completed'],
            ['boq' => 'Granular Sub Base (GSB) – 200mm',  'date' => '2024-11-20', 'qty' => 12600.000, 'from' => 158.000, 'to' => 166.000, 'status' => 'partial'],
            ['boq' => 'Wet Mix Macadam (WMM) – 250mm',    'date' => '2024-10-25', 'qty' => 27000.000, 'from' => 142.000, 'to' => 154.000, 'status' => 'completed'],

            // Pavement
            ['boq' => 'Dense Bituminous Macadam (DBM) – 50mm',  'date' => '2025-01-12', 'qty' => 97200.000, 'from' => 142.000, 'to' => 151.500, 'status' => 'completed'],
            ['boq' => 'Bituminous Concrete (BC) – 40mm (VG-40)','date' => '2025-02-18', 'qty' => 64800.000, 'from' => 142.000, 'to' => 148.000, 'status' => 'completed'],

            // Drainage
            ['boq' => 'Longitudinal rubble drain (0.6m × 0.6m)', 'date' => '2024-12-05', 'qty' => 18000.000, 'from' => 142.000, 'to' => 160.000, 'status' => 'completed'],
            ['boq' => 'HP Pipe Culvert NP3 – 900mm dia',         'date' => '2025-01-22', 'qty' => 240.000,   'from' => null,    'to' => null,    'status' => 'completed'],

            // Structures
            ['boq' => 'RCC M30 – Minor bridge superstructure',   'date' => '2025-03-10', 'qty' => 410.000,   'from' => null,    'to' => null,    'status' => 'partial'],
        ];

        foreach ($progressData as $row) {
            $boqId = $boqIds[$row['boq']] ?? null;
            if (!$boqId) continue;
            DB::table('progress_entries')->insert([
                'project_id'   => $project->id,
                'boq_item_id'  => $boqId,
                'date'         => $row['date'],
                'quantity'     => $row['qty'],
                'chainage_from'=> $row['from'],
                'chainage_to'  => $row['to'],
                'status'       => $row['status'],
                'notes'        => null,
                'created_by'   => $adminId,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }

        // ── 4. Billing Config ────────────────────────────────────────────────
        BillingConfig::create([
            'project_id'     => $project->id,
            'loa_number'     => 'NHAI/MP/2024/NH44/LOA-0072',
            'tendered_value' => 285_64_00_000.00,   // ₹285.64 Cr
            'bid_type'       => 'below',
            'bid_pct'        => 4.75,
            'sd_pct'         => 5.00,
            'it_tds_pct'     => 2.00,
            'labour_cess_pct'=> 1.00,
            'gst_tds_pct'    => 2.00,
            'configured_at'  => '2024-04-01 10:00:00',
        ]);

        // ── 5. RA Bills ──────────────────────────────────────────────────────
        $deductions1 = [
            ['label' => 'Security Deposit (5%)',         'pct' => 5.00,  'amount' => 421250.00],
            ['label' => 'Income Tax TDS (2%)',           'pct' => 2.00,  'amount' => 168500.00],
            ['label' => 'Labour Cess (1%)',              'pct' => 1.00,  'amount' => 84250.00],
            ['label' => 'GST TDS (2%)',                  'pct' => 2.00,  'amount' => 168500.00],
            ['label' => 'Advance Recovery',              'pct' => null,  'amount' => 500000.00],
        ];

        $ra1 = RaBill::create([
            'project_id'     => $project->id,
            'bill_number'    => 'RA-2024-001',
            'bill_date'      => '2024-07-31',
            'gross_amount'   => 84_25_000.00,
            'deductions_json'=> $deductions1,
            'net_payable'    => 74_82_500.00,
            'status'         => 'paid',
            'notes'          => '1st Running Account Bill – Earthwork & Clearing',
            'submitted_at'   => '2024-08-05 09:00:00',
            'certified_at'   => '2024-08-18 14:30:00',
            'approved_at'    => '2024-08-25 11:00:00',
            'paid_at'        => '2024-09-10 16:00:00',
            'paid_amount'    => 74_82_500.00,
            'created_by'     => $adminId,
        ]);

        $deductions2 = [
            ['label' => 'Security Deposit (5%)',         'pct' => 5.00,  'amount' => 612500.00],
            ['label' => 'Income Tax TDS (2%)',           'pct' => 2.00,  'amount' => 245000.00],
            ['label' => 'Labour Cess (1%)',              'pct' => 1.00,  'amount' => 122500.00],
            ['label' => 'GST TDS (2%)',                  'pct' => 2.00,  'amount' => 245000.00],
        ];

        $ra2 = RaBill::create([
            'project_id'     => $project->id,
            'bill_number'    => 'RA-2024-002',
            'bill_date'      => '2024-10-31',
            'gross_amount'   => 1_22_50_000.00,
            'deductions_json'=> $deductions2,
            'net_payable'    => 1_10_25_000.00,
            'status'         => 'approved',
            'notes'          => '2nd Running Account Bill – Embankment & Sub-base',
            'submitted_at'   => '2024-11-05 10:00:00',
            'certified_at'   => '2024-11-20 15:00:00',
            'approved_at'    => '2024-11-28 12:00:00',
            'paid_at'        => null,
            'paid_amount'    => null,
            'created_by'     => $adminId,
        ]);

        $ra3 = RaBill::create([
            'project_id'     => $project->id,
            'bill_number'    => 'RA-2025-003',
            'bill_date'      => '2025-01-31',
            'gross_amount'   => 1_85_75_000.00,
            'deductions_json'=> [
                ['label' => 'Security Deposit (5%)',     'pct' => 5.00,  'amount' => 928750.00],
                ['label' => 'Income Tax TDS (2%)',       'pct' => 2.00,  'amount' => 371500.00],
                ['label' => 'Labour Cess (1%)',          'pct' => 1.00,  'amount' => 185750.00],
                ['label' => 'GST TDS (2%)',              'pct' => 2.00,  'amount' => 371500.00],
            ],
            'net_payable'    => 1_69_17_500.00,
            'status'         => 'submitted',
            'notes'          => '3rd RA Bill – WMM, DBM & Drainage works',
            'submitted_at'   => '2025-02-04 09:30:00',
            'certified_at'   => null,
            'approved_at'    => null,
            'paid_at'        => null,
            'paid_amount'    => null,
            'created_by'     => $adminId,
        ]);

        // Draft bill
        RaBill::create([
            'project_id'     => $project->id,
            'bill_number'    => 'RA-2025-004',
            'bill_date'      => '2025-03-31',
            'gross_amount'   => 0,
            'deductions_json'=> [],
            'net_payable'    => 0,
            'status'         => 'draft',
            'notes'          => '4th RA Bill – Bituminous layers & Bridge works (in preparation)',
            'submitted_at'   => null,
            'certified_at'   => null,
            'approved_at'    => null,
            'paid_at'        => null,
            'paid_amount'    => null,
            'created_by'     => $adminId,
        ]);

        // ── 6. DSR Items ─────────────────────────────────────────────────────
        $dsrList = [
            [
                'description' => 'Granular Sub Base (GSB) – 200mm',
                'material' => [
                    ['name' => 'Crushed stone aggregate 40mm', 'unit' => 'cum', 'qty' => 1.35, 'rate' => 650.00],
                    ['name' => 'Crushed stone aggregate 20mm', 'unit' => 'cum', 'qty' => 0.45, 'rate' => 720.00],
                    ['name' => 'Fine aggregate (M-sand)',      'unit' => 'cum', 'qty' => 0.20, 'rate' => 850.00],
                ],
                'labour' => [
                    ['name' => 'Skilled Labour',   'unit' => 'day', 'qty' => 0.08, 'rate' => 650.00],
                    ['name' => 'Unskilled Labour', 'unit' => 'day', 'qty' => 0.25, 'rate' => 450.00],
                ],
                'machinery' => [
                    ['name' => 'Vibratory Roller 10T',  'unit' => 'hr', 'qty' => 0.04, 'rate' => 2800.00],
                    ['name' => 'Motor Grader 180HP',     'unit' => 'hr', 'qty' => 0.03, 'rate' => 3200.00],
                    ['name' => 'Water Tanker 12KL',      'unit' => 'hr', 'qty' => 0.05, 'rate' => 1200.00],
                ],
                'deduction_pct' => ['wastage' => 2.5, 'contractor_profit' => 10.0],
            ],
            [
                'description' => 'Dense Bituminous Macadam (DBM) – 50mm',
                'material' => [
                    ['name' => 'Bitumen VG-40',             'unit' => 'kg',  'qty' => 4.20,  'rate' => 58.00],
                    ['name' => 'Stone aggregate 20mm',       'unit' => 'cum', 'qty' => 0.045, 'rate' => 850.00],
                    ['name' => 'Stone aggregate 12mm',       'unit' => 'cum', 'qty' => 0.020, 'rate' => 900.00],
                    ['name' => 'Stone dust (Filler)',        'unit' => 'kg',  'qty' => 3.50,  'rate' => 2.50],
                ],
                'labour' => [
                    ['name' => 'Skilled Labour',             'unit' => 'day', 'qty' => 0.06, 'rate' => 650.00],
                    ['name' => 'Unskilled Labour',           'unit' => 'day', 'qty' => 0.15, 'rate' => 450.00],
                ],
                'machinery' => [
                    ['name' => 'Hot Mix Plant 120TPH',       'unit' => 'hr', 'qty' => 0.02,  'rate' => 12000.00],
                    ['name' => 'Paver Finisher',             'unit' => 'hr', 'qty' => 0.025, 'rate' => 5500.00],
                    ['name' => 'Tandem Vibratory Roller',    'unit' => 'hr', 'qty' => 0.03,  'rate' => 3500.00],
                    ['name' => 'Pneumatic Tyre Roller',      'unit' => 'hr', 'qty' => 0.025, 'rate' => 2800.00],
                ],
                'deduction_pct' => ['wastage' => 1.5, 'contractor_profit' => 10.0],
            ],
            [
                'description' => 'RCC M30 – Minor bridge superstructure',
                'material' => [
                    ['name' => 'Cement OPC 53',              'unit' => 'bag', 'qty' => 8.50,  'rate' => 380.00],
                    ['name' => 'Sand (Zone II)',              'unit' => 'cum', 'qty' => 0.42,  'rate' => 1200.00],
                    ['name' => 'Aggregate 20mm',             'unit' => 'cum', 'qty' => 0.85,  'rate' => 850.00],
                    ['name' => 'Aggregate 10mm',             'unit' => 'cum', 'qty' => 0.35,  'rate' => 900.00],
                    ['name' => 'Water',                      'unit' => 'lit', 'qty' => 165.00,'rate' => 0.05],
                    ['name' => 'Admixture (Superplasticizer)','unit'=> 'kg',  'qty' => 2.50,  'rate' => 85.00],
                ],
                'labour' => [
                    ['name' => 'Mason (Skilled)',             'unit' => 'day', 'qty' => 0.50, 'rate' => 750.00],
                    ['name' => 'Helper (Unskilled)',          'unit' => 'day', 'qty' => 1.20, 'rate' => 450.00],
                    ['name' => 'Carpenter',                   'unit' => 'day', 'qty' => 0.40, 'rate' => 700.00],
                    ['name' => 'Steel binder',                'unit' => 'day', 'qty' => 0.30, 'rate' => 700.00],
                ],
                'machinery' => [
                    ['name' => 'Transit Mixer 6cum',         'unit' => 'hr', 'qty' => 0.15, 'rate' => 2500.00],
                    ['name' => 'Concrete Pump',              'unit' => 'hr', 'qty' => 0.12, 'rate' => 3500.00],
                    ['name' => 'Vibrator (needle)',          'unit' => 'hr', 'qty' => 0.50, 'rate' => 350.00],
                ],
                'deduction_pct' => ['wastage' => 3.0, 'contractor_profit' => 10.0],
            ],
        ];

        foreach ($dsrList as $dsr) {
            $boqId = $boqIds[$dsr['description']] ?? null;
            if (!$boqId) continue;
            DB::table('dsr_items')->insert([
                'project_id'        => $project->id,
                'boq_item_id'       => $boqId,
                'material_json'     => json_encode($dsr['material']),
                'labour_json'       => json_encode($dsr['labour']),
                'machinery_json'    => json_encode($dsr['machinery']),
                'deduction_pct_json'=> json_encode($dsr['deduction_pct']),
                'updated_by'        => $adminId,
                'created_at'        => $now,
                'updated_at'        => $now,
            ]);
        }

        // ── 7. Inventory Materials ───────────────────────────────────────────
        $materials = [
            ['code' => 'MAT-BIT-VG40',  'name' => 'Bitumen VG-40',             'unit' => 'MT',   'reorder' => 50.000,  'opening' => 120.000],
            ['code' => 'MAT-CEM-OPC53', 'name' => 'Cement OPC 53 Grade',        'unit' => 'bag',  'reorder' => 500.000, 'opening' => 2000.000],
            ['code' => 'MAT-AGG-20MM',  'name' => 'Stone Aggregate 20mm',       'unit' => 'cum',  'reorder' => 200.000, 'opening' => 850.000],
            ['code' => 'MAT-AGG-40MM',  'name' => 'Stone Aggregate 40mm',       'unit' => 'cum',  'reorder' => 300.000, 'opening' => 1200.000],
            ['code' => 'MAT-SAND-M',    'name' => 'M-Sand (Manufactured Sand)', 'unit' => 'cum',  'reorder' => 150.000, 'opening' => 600.000],
            ['code' => 'MAT-STEEL-FE5', 'name' => 'HYSD Steel Fe500',           'unit' => 'MT',   'reorder' => 20.000,  'opening' => 85.000],
            ['code' => 'MAT-EMUL-RS1',  'name' => 'Bitumen Emulsion RS-1',      'unit' => 'litre','reorder' => 500.000, 'opening' => 2400.000],
            ['code' => 'MAT-SHUTPL',    'name' => 'Shuttering Plates (12mm)',    'unit' => 'sqm',  'reorder' => 100.000, 'opening' => 450.000],
        ];

        $materialIds = [];
        foreach ($materials as $mat) {
            $id = DB::table('inventory_materials')->insertGetId([
                'project_id'   => $project->id,
                'material_code'=> $mat['code'],
                'name'         => $mat['name'],
                'unit'         => $mat['unit'],
                'reorder_qty'  => $mat['reorder'],
                'opening_stock'=> $mat['opening'],
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
            $materialIds[$mat['code']] = $id;
        }

        // Inventory Transactions
        $txns = [
            // Purchases
            ['code' => 'MAT-BIT-VG40',  'type' => 'purchase',    'date' => '2024-09-05', 'qty' => 200.000, 'rate' => 56500.00, 'notes' => 'PO-2024-BIT-001 – HPCL supply'],
            ['code' => 'MAT-BIT-VG40',  'type' => 'purchase',    'date' => '2024-12-10', 'qty' => 150.000, 'rate' => 57200.00, 'notes' => 'PO-2024-BIT-002 – HPCL supply'],
            ['code' => 'MAT-CEM-OPC53', 'type' => 'purchase',    'date' => '2024-08-20', 'qty' => 5000.000,'rate' => 365.00,   'notes' => 'PO-2024-CEM-001 – ACC Cement'],
            ['code' => 'MAT-CEM-OPC53', 'type' => 'purchase',    'date' => '2025-01-15', 'qty' => 3000.000,'rate' => 375.00,   'notes' => 'PO-2025-CEM-001 – Ultratech'],
            ['code' => 'MAT-AGG-20MM',  'type' => 'purchase',    'date' => '2024-07-12', 'qty' => 1500.000,'rate' => 820.00,   'notes' => 'Quarry supply – local'],
            ['code' => 'MAT-STEEL-FE5', 'type' => 'purchase',    'date' => '2024-11-25', 'qty' => 80.000,  'rate' => 82500.00, 'notes' => 'TATA Steel – Invoice ST2024-882'],
            // Consumption
            ['code' => 'MAT-BIT-VG40',  'type' => 'consumption', 'date' => '2024-11-18', 'qty' => 180.000, 'rate' => 56500.00, 'notes' => 'DBM layer Km 142-148'],
            ['code' => 'MAT-BIT-VG40',  'type' => 'consumption', 'date' => '2025-01-22', 'qty' => 210.000, 'rate' => 57200.00, 'notes' => 'DBM+BC layer Km 148-151.5'],
            ['code' => 'MAT-CEM-OPC53', 'type' => 'consumption', 'date' => '2025-01-10', 'qty' => 2800.000,'rate' => 365.00,   'notes' => 'Minor bridge – footing & substructure'],
            ['code' => 'MAT-CEM-OPC53', 'type' => 'consumption', 'date' => '2025-03-05', 'qty' => 1850.000,'rate' => 375.00,   'notes' => 'Minor bridge – superstructure pour 1'],
            ['code' => 'MAT-AGG-20MM',  'type' => 'consumption', 'date' => '2024-12-18', 'qty' => 680.000, 'rate' => 820.00,   'notes' => 'Culverts & minor bridge works'],
            ['code' => 'MAT-STEEL-FE5', 'type' => 'consumption', 'date' => '2025-02-28', 'qty' => 62.000,  'rate' => 82500.00, 'notes' => 'Minor bridge reinforcement'],
            // Wastage
            ['code' => 'MAT-BIT-VG40',  'type' => 'wastage',     'date' => '2024-12-01', 'qty' => 2.500,   'rate' => 56500.00, 'notes' => 'Spillage during tanker transfer'],
            ['code' => 'MAT-CEM-OPC53', 'type' => 'wastage',     'date' => '2025-01-28', 'qty' => 85.000,  'rate' => 365.00,   'notes' => 'Damaged bags – moisture exposure'],
        ];

        foreach ($txns as $txn) {
            $matId = $materialIds[$txn['code']] ?? null;
            if (!$matId) continue;
            DB::table('inventory_transactions')->insert([
                'project_id'  => $project->id,
                'material_id' => $matId,
                'txn_type'    => $txn['type'],
                'date'        => $txn['date'],
                'quantity'    => $txn['qty'],
                'rate'        => $txn['rate'],
                'amount'      => round($txn['qty'] * $txn['rate'], 2),
                'notes'       => $txn['notes'],
                'created_by'  => $adminId,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }

        // ── 8. Price Variation Indices ───────────────────────────────────────
        $pvMonths = [
            ['month' => '2024-04', 'base_month' => '2024-04', 'labour' => 1.0000, 'cement' => 1.0000, 'steel' => 1.0000, 'bitumen' => 1.0000, 'pol' => 1.0000, 'other' => 1.0000, 'plant' => 1.0000],
            ['month' => '2024-07', 'base_month' => '2024-04', 'labour' => 1.0250, 'cement' => 0.9820, 'steel' => 1.0380, 'bitumen' => 1.0650, 'pol' => 1.0420, 'other' => 1.0150, 'plant' => 1.0180],
            ['month' => '2024-10', 'base_month' => '2024-04', 'labour' => 1.0520, 'cement' => 0.9650, 'steel' => 1.0820, 'bitumen' => 1.0980, 'pol' => 1.0750, 'other' => 1.0280, 'plant' => 1.0350],
            ['month' => '2025-01', 'base_month' => '2024-04', 'labour' => 1.0780, 'cement' => 0.9920, 'steel' => 1.1150, 'bitumen' => 1.1240, 'pol' => 1.0920, 'other' => 1.0450, 'plant' => 1.0520],
            ['month' => '2025-04', 'base_month' => '2024-04', 'labour' => 1.1050, 'cement' => 1.0180, 'steel' => 1.1480, 'bitumen' => 1.0850, 'pol' => 1.0680, 'other' => 1.0620, 'plant' => 1.0690],
        ];

        foreach ($pvMonths as $pv) {
            PvIndex::create([
                'project_id'  => $project->id,
                'month'       => $pv['month'],
                'base_month'  => $pv['base_month'],
                'labour_idx'  => $pv['labour'],
                'cement_idx'  => $pv['cement'],
                'steel_idx'   => $pv['steel'],
                'bitumen_idx' => $pv['bitumen'],
                'pol_idx'     => $pv['pol'],
                'other_idx'   => $pv['other'],
                'plant_idx'   => $pv['plant'],
                'created_by'  => $adminId,
            ]);
        }

        // ── 9. Expenses ──────────────────────────────────────────────────────
        $expenses = [
            // Labour
            ['date' => '2024-04-30', 'category' => 'labour',   'desc' => 'Site clearing & grubbing – daily wage labourers (120 nos × 26 days)',   'amount' => 14_04_000.00],
            ['date' => '2024-05-31', 'category' => 'labour',   'desc' => 'Earthwork gang – daily wages for May 2024 (85 nos)',                     'amount' => 9_92_250.00],
            ['date' => '2024-08-31', 'category' => 'labour',   'desc' => 'Embankment & sub-grade team wages – Aug 2024 (110 nos)',                 'amount' => 12_87_000.00],
            ['date' => '2024-11-30', 'category' => 'labour',   'desc' => 'Sub-base & base course crew wages – Nov 2024 (95 nos)',                  'amount' => 11_11_500.00],
            ['date' => '2025-01-31', 'category' => 'labour',   'desc' => 'Bituminous works & bridge team – Jan 2025 (140 nos)',                    'amount' => 16_38_000.00],
            ['date' => '2025-02-28', 'category' => 'labour',   'desc' => 'Bridge reinforcement & shuttering – Feb 2025 (65 nos)',                  'amount' => 7_60_500.00],

            // Machinery
            ['date' => '2024-06-15', 'category' => 'machinery','desc' => 'Tipper hire charges – earthwork (12 nos × 25 days)',                    'amount' => 10_50_000.00],
            ['date' => '2024-07-31', 'category' => 'machinery','desc' => 'Excavator & Dozer hire – Jul 2024',                                     'amount' => 8_40_000.00],
            ['date' => '2024-09-30', 'category' => 'machinery','desc' => 'Vibratory roller + Motor grader + Water tanker hire – Sep 2024',         'amount' => 6_72_000.00],
            ['date' => '2024-11-15', 'category' => 'machinery','desc' => 'Hot Mix Plant 120TPH – mobilisation and installation',                  'amount' => 18_50_000.00],
            ['date' => '2025-01-10', 'category' => 'machinery','desc' => 'Paver Finisher + Tandem roller – Jan 2025 hire',                        'amount' => 9_60_000.00],
            ['date' => '2025-02-10', 'category' => 'machinery','desc' => 'Transit mixer + Concrete pump – bridge works Feb 2025',                 'amount' => 7_20_000.00],

            // Other
            ['date' => '2024-04-05', 'category' => 'other',    'desc' => 'Site establishment – temporary office, stores & labour camp setup',      'amount' => 22_50_000.00],
            ['date' => '2024-04-10', 'category' => 'other',    'desc' => 'Survey & layout – total station, GPS & marking',                         'amount' => 3_80_000.00],
            ['date' => '2024-05-20', 'category' => 'other',    'desc' => 'Quality control lab equipment & consumables',                            'amount' => 8_75_000.00],
            ['date' => '2024-08-01', 'category' => 'other',    'desc' => 'Safety & PPE – helmets, jackets, boots (all staff)',                     'amount' => 2_25_000.00],
            ['date' => '2024-10-12', 'category' => 'other',    'desc' => 'Diesel – HSD for generators & equipment (Oct 2024)',                     'amount' => 4_85_000.00],
            ['date' => '2025-01-05', 'category' => 'other',    'desc' => 'Performance bank guarantee renewal charges',                             'amount' => 1_42_500.00],
            ['date' => '2025-02-01', 'category' => 'other',    'desc' => 'Diesel – HSD for HMP & site equipment (Feb 2025)',                       'amount' => 6_20_000.00],
            ['date' => '2025-03-15', 'category' => 'other',    'desc' => 'Traffic management & signage – diversions for bridge works',             'amount' => 3_15_000.00],
        ];

        foreach ($expenses as $exp) {
            Expense::create([
                'project_id'  => $project->id,
                'date'        => $exp['date'],
                'category'    => $exp['category'],
                'description' => $exp['desc'],
                'amount'      => $exp['amount'],
                'created_by'  => $adminId,
            ]);
        }

        $this->command->info("✔  Sample project created: {$project->name} (ID: {$project->id})");
        $this->command->info("   BOQ items : " . count($boqIds));
        $this->command->info("   RA Bills  : 4 (1 paid, 1 approved, 1 submitted, 1 draft)");
        $this->command->info("   Materials : " . count($materialIds));
        $this->command->info("   Expenses  : " . count($expenses));
    }
}
