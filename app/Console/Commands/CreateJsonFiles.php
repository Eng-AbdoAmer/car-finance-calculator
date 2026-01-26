<?php

// namespace App\Console\Commands;

// use Illuminate\Console\Command;
// use Illuminate\Support\Facades\File;

// class CreateJsonFiles extends Command
// {
//     protected $signature = 'app:create-json-files';
//     protected $description = 'Create JSON files for insurance rates and car categories';

//     public function handle()
//     {
//         $this->info('Creating JSON files...');
        
       
//         $folders = [
//             storage_path('app/json'),
//             public_path('storage/json'),
//             database_path('data'),
//             resource_path('data')
//         ];
        
//         foreach ($folders as $folder) {
//             if (!File::exists($folder)) {
//                 File::makeDirectory($folder, 0755, true);
//                 $this->info("Created directory: {$folder}");
//             }
//         }
        
       
//         $insuranceRates = [
//             'male' => [
//                 '18 to 24' => ['A' => 0.083, 'B' => 0.072, 'C' => 0.065, 'D' => 0.08, 'E' => 0.05, 'F' => 0.06, 'G' => 0.065],
//                 '25 to 30' => ['A' => 0.058, 'B' => 0.055, 'C' => 0.049, 'D' => 0.066, 'E' => 0.026, 'F' => 0.05, 'G' => 0.048],
//                 '31 to 35' => ['A' => 0.036, 'B' => 0.035, 'C' => 0.038, 'D' => 0.045, 'E' => 0.031, 'F' => 0.031, 'G' => 0.03],
//                 '36 to 40' => ['A' => 0.028, 'B' => 0.028, 'C' => 0.033, 'D' => 0.038, 'E' => 0.034, 'F' => 0.029, 'G' => 0.023],
//                 '41 to 50' => ['A' => 0.026, 'B' => 0.026, 'C' => 0.027, 'D' => 0.036, 'E' => 0.025, 'F' => 0.03, 'G' => 0.024],
//                 '51 to 60' => ['A' => 0.028, 'B' => 0.028, 'C' => 0.031, 'D' => 0.036, 'E' => 0.022, 'F' => 0.03, 'G' => 0.024],
//                 '61 & above' => ['A' => 0.031, 'B' => 0.028, 'C' => 0.029, 'D' => 0.037, 'E' => 0.03, 'F' => 0.035, 'G' => 0.035]
//             ],
//             'female' => [
//                 '18 to 24' => ['A' => 0.063, 'B' => 0.067, 'C' => 0.084, 'D' => 0.07, 'E' => 0.055, 'F' => 0.065, 'G' => 0.07],
//                 '25 to 30' => ['A' => 0.056, 'B' => 0.062, 'C' => 0.054, 'D' => 0.058, 'E' => 0.066, 'F' => 0.055, 'G' => 0.051],
//                 '31 to 35' => ['A' => 0.04, 'B' => 0.042, 'C' => 0.048, 'D' => 0.059, 'E' => 0.05, 'F' => 0.038, 'G' => 0.032],
//                 '36 to 40' => ['A' => 0.035, 'B' => 0.033, 'C' => 0.039, 'D' => 0.052, 'E' => 0.035, 'F' => 0.031, 'G' => 0.03],
//                 '41 to 50' => ['A' => 0.036, 'B' => 0.036, 'C' => 0.041, 'D' => 0.048, 'E' => 0.048, 'F' => 0.03, 'G' => 0.027],
//                 '51 to 60' => ['A' => 0.04, 'B' => 0.045, 'C' => 0.045, 'D' => 0.054, 'E' => 0.025, 'F' => 0.03, 'G' => 0.026],
//                 '61 & above' => ['A' => 0.048, 'B' => 0.047, 'C' => 0.044, 'D' => 0.056, 'E' => 0.06, 'F' => 0.035, 'G' => 0.035]
//             ]
//         ];
        
        
//         $carCategories = [
//             'Toyota' => 'A',
//             'Lexus' => 'A',
//             'Mazda' => 'A',
//             'Honda' => 'A',
//             'Nissan' => 'A',
//             'Ford' => 'B',
//             'GM' => 'B',
//             'Chevrolet' => 'B',
//             'Isuzu' => 'B',
//             'Hyundai' => 'B',
//             'Kia' => 'B',
//             'Mitsubishi' => 'B',
//             'Chrysler' => 'C',
//             'Dodge' => 'C',
//             'Jeep' => 'C',
//             'Suzuki' => 'C',
//             'Volkswagen' => 'C',
//             'Audi' => 'C',
//             'Mercedes' => 'C',
//             'BMW' => 'C',
//             'Porsche' => 'C',
//             'Range Rover' => 'C',
//             'Infiniti' => 'C',
//             'Fiat' => 'C',
//             'Citroen' => 'D',
//             'MG' => 'D',
//             'Renault' => 'D',
//             'Seat' => 'D',
//             'Skoda' => 'D',
//             'Feat' => 'D',
//             'Subaru' => 'D',
//             'Opel' => 'D',
//             'Mini Cooper' => 'D',
//             'Ssang Yong' => 'D',
//             'TATA' => 'D',
//             'Volvo' => 'D',
//             'Cadillac' => 'D',
//             'Havel' => 'D',
//             'Lincoln' => 'D',
//             'Land Rover' => 'D',
//             'Peugeot' => 'D',
//             'Geely' => 'D',
//             'Havel & all other Chinese cars' => 'D',
//             'Bentley' => 'E',
//             'Rolls-Royce' => 'E',
//             'Ferrari' => 'E',
//             'Bugatti' => 'E',
//             'Alfa Romeo' => 'E',
//             'Aston Martin' => 'E',
//             'Lamborghini' => 'E',
//             'Maserati' => 'E',
//             'Maybach' => 'E',
//             'Motorbike' => 'F',
//             'Lucid' => 'G'
//         ];
        
//         $files = [
//             'insurance_rates.json' => json_encode($insuranceRates, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
//             'car_categories.json' => json_encode($carCategories, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
//         ];
        
//         foreach ($files as $filename => $content) {
//             foreach ($folders as $folder) {
//                 $path = $folder . '/' . $filename;
//                 File::put($path, $content);
//                 $this->info("Created: {$path}");
//             }
//         }
        
//         $this->info('JSON files created successfully!');
//         return Command::SUCCESS;
//     }
// }