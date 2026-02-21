<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Seed the products table with medicine data.
     *
     * @return void
     */
    public function run(): void
    {
        $userIds = DB::table('users')->pluck('id')->toArray();

        if (empty($userIds)) {
            return; // Prevent foreign key issues
        }

        $manufacturers = [
            'Square Pharmaceuticals Ltd.',
            'Beximco Pharmaceuticals Ltd.',
            'Incepta Pharmaceuticals Ltd.',
            'Renata Limited',
            'ACI Limited',
            'Eskayef Pharmaceuticals Ltd.',
            'Opsonin Pharma Ltd.',
            'Aristopharma Ltd.'
        ];

        $medicines = [
            ['name' => 'Napa 500mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 20.00, 'rx' => false],
            ['name' => 'Seclo 20mg', 'type' => 'Capsule', 'unit' => 'strip', 'price' => 70.00, 'rx' => true],
            ['name' => 'Ace 500mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 18.00, 'rx' => false],
            ['name' => 'Maxpro 20mg', 'type' => 'Capsule', 'unit' => 'strip', 'price' => 85.00, 'rx' => true],
            ['name' => 'Monas 10mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 120.00, 'rx' => true],
            ['name' => 'Filmet 500mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 45.00, 'rx' => true],
            ['name' => 'Tusca Syrup', 'type' => 'Syrup', 'unit' => 'bottle', 'price' => 110.00, 'rx' => false],
            ['name' => 'Orsaline-N', 'type' => 'Saline', 'unit' => 'box', 'price' => 60.00, 'rx' => false],
            ['name' => 'Ceevit 250mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 25.00, 'rx' => false],
            ['name' => 'Alatrol 10mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 30.00, 'rx' => false],
            ['name' => 'Rivotril 0.5mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 150.00, 'rx' => true],
            ['name' => 'Neotack Cream', 'type' => 'Cream', 'unit' => 'tube', 'price' => 95.00, 'rx' => false],
            ['name' => 'Losectil 20mg', 'type' => 'Capsule', 'unit' => 'strip', 'price' => 90.00, 'rx' => true],
            ['name' => 'Ciprocin 500mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 140.00, 'rx' => true],
            ['name' => 'Amodis 400mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 100.00, 'rx' => true],

            // --- 50 Additional Medicines ---
            ['name' => 'Paracetamol 500mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 15.00, 'rx' => false],
            ['name' => 'Amoxicillin 500mg', 'type' => 'Capsule', 'unit' => 'strip', 'price' => 80.00, 'rx' => true],
            ['name' => 'Omeprazole 20mg', 'type' => 'Capsule', 'unit' => 'strip', 'price' => 60.00, 'rx' => true],
            ['name' => 'Ibuprofen 400mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 35.00, 'rx' => false],
            ['name' => 'Cetirizine 10mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 25.00, 'rx' => false],
            ['name' => 'Azithromycin 250mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 120.00, 'rx' => true],
            ['name' => 'Metformin 500mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 50.00, 'rx' => true],
            ['name' => 'Losartan 50mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 70.00, 'rx' => true],
            ['name' => 'Salbutamol Inhaler', 'type' => 'Inhaler', 'unit' => 'pcs', 'price' => 350.00, 'rx' => true],
            ['name' => 'Doxycycline 100mg', 'type' => 'Capsule', 'unit' => 'strip', 'price' => 90.00, 'rx' => true],
            ['name' => 'Furosemide 40mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 40.00, 'rx' => true],
            ['name' => 'Ranitidine 150mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 30.00, 'rx' => false],
            ['name' => 'Vitamin D3 2000IU', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 50.00, 'rx' => false],
            ['name' => 'Clindamycin 300mg', 'type' => 'Capsule', 'unit' => 'strip', 'price' => 100.00, 'rx' => true],
            ['name' => 'Amlodipine 5mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 45.00, 'rx' => true],
            ['name' => 'Hydrochlorothiazide 25mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 35.00, 'rx' => true],
            ['name' => 'Lorazepam 1mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 150.00, 'rx' => true],
            ['name' => 'Cefixime 200mg', 'type' => 'Capsule', 'unit' => 'strip', 'price' => 120.00, 'rx' => true],
            ['name' => 'Vitamin C 500mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 20.00, 'rx' => false],
            ['name' => 'Prednisolone 5mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 100.00, 'rx' => true],
            ['name' => 'Diclofenac 50mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 40.00, 'rx' => false],
            ['name' => 'Gaviscon', 'type' => 'Syrup', 'unit' => 'bottle', 'price' => 130.00, 'rx' => false],
            ['name' => 'Multivitamin Syrup', 'type' => 'Syrup', 'unit' => 'bottle', 'price' => 150.00, 'rx' => false],
            ['name' => 'Ketorolac 10mg', 'type' => 'Injection', 'unit' => 'pcs', 'price' => 180.00, 'rx' => true],
            ['name' => 'Pantoprazole 40mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 90.00, 'rx' => true],
            ['name' => 'Levofloxacin 500mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 200.00, 'rx' => true],
            ['name' => 'Amaryl 2mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 180.00, 'rx' => true],
            ['name' => 'Ceftriaxone 1g', 'type' => 'Injection', 'unit' => 'pcs', 'price' => 350.00, 'rx' => true],
            ['name' => 'Saline 0.9%', 'type' => 'Saline', 'unit' => 'box', 'price' => 70.00, 'rx' => false],
            ['name' => 'Topical Antiseptic Cream', 'type' => 'Cream', 'unit' => 'tube', 'price' => 80.00, 'rx' => false],
            ['name' => 'Miconazole 2%', 'type' => 'Cream', 'unit' => 'tube', 'price' => 90.00, 'rx' => false],
            ['name' => 'Fluconazole 150mg', 'type' => 'Capsule', 'unit' => 'strip', 'price' => 120.00, 'rx' => true],
            ['name' => 'Levothyroxine 50mcg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 75.00, 'rx' => true],
            ['name' => 'Cough Suppressant Syrup', 'type' => 'Syrup', 'unit' => 'bottle', 'price' => 100.00, 'rx' => false],
            ['name' => 'Vitamin B Complex', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 60.00, 'rx' => false],
            ['name' => 'Magnesium + Calcium', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 70.00, 'rx' => false],
            ['name' => 'Salbutamol Syrup', 'type' => 'Syrup', 'unit' => 'bottle', 'price' => 120.00, 'rx' => true],
            ['name' => 'Diclofenac Gel', 'type' => 'Gel', 'unit' => 'tube', 'price' => 90.00, 'rx' => false],
            ['name' => 'Clarithromycin 500mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 150.00, 'rx' => true],
            ['name' => 'Risperidone 2mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 120.00, 'rx' => true],
            ['name' => 'Losartan-H 50mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 85.00, 'rx' => true],
            ['name' => 'Metoprolol 50mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 70.00, 'rx' => true],
            ['name' => 'Omeprazole + Sodium Bicarbonate', 'type' => 'Capsule', 'unit' => 'strip', 'price' => 95.00, 'rx' => true],
            ['name' => 'Azithral 500mg', 'type' => 'Tablet', 'unit' => 'strip', 'price' => 110.00, 'rx' => true],
            ['name' => 'Fluticasone Nasal Spray', 'type' => 'Spray', 'unit' => 'pcs', 'price' => 200.00, 'rx' => true],
            ['name' => 'Hydrocortisone 1%', 'type' => 'Cream', 'unit' => 'tube', 'price' => 80.00, 'rx' => false],
            ['name' => 'Saline Nasal Drops', 'type' => 'Drops', 'unit' => 'bottle', 'price' => 60.00, 'rx' => false],
        ];

        $products = [];

        foreach ($medicines as $medicine) {

            $entryBy = collect($userIds)->random();
            $approvedBy = collect($userIds)->random();

            $products[] = [
                'name'                       => $medicine['name'],
                'type'                       => $medicine['type'],
                'unit'                       => $medicine['unit'],
                'manufacturer'               => $manufacturers[array_rand($manufacturers)],
                'product_code'               => 'MED-' . strtoupper(Str::random(8)),
                'description'                => 'Medicine commonly used in Bangladesh for general treatment purposes.',
                'purchase_price'             => number_format($medicine['price'] * 0.8, 2, '.', ''), // example: 80% of selling
                'selling_price'              => number_format($medicine['price'], 2, '.', ''),
                'stock_quantity'             => rand(50, 500),
                'stock_value'                => 0, // can calculate after seeding: stock_quantity * purchase_price
                'is_prescription_required'   => $medicine['rx'],
                'status'                     => 'approved',
                'entry_by'                   => $entryBy,
                'approved_by'                => $approvedBy,
                'created_at'                 => now(),
                'updated_at'                 => now(),
            ];
        }

        DB::table('products')->insert($products);
    }
}
