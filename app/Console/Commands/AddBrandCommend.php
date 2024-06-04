<?php

namespace App\Console\Commands;

use App\Models\Products\Brand;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\CodeGenerationService;

class AddBrandCommend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:brand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $brands = array(
            array('id' => '1', 'name' => 'RFL', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-16 09:49:24', 'updated_at' => '2023-07-16 09:49:24'),
            array('id' => '2', 'name' => 'RFL', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-16 09:59:14', 'updated_at' => '2023-07-16 09:59:14'),
            array('id' => '3', 'name' => 'olympic', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 05:04:04', 'updated_at' => '2023-07-17 05:04:04'),
            array('id' => '4', 'name' => 'metfort', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 05:10:41', 'updated_at' => '2023-07-17 05:10:41'),
            array('id' => '5', 'name' => 'B-CITY GROUP', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 05:27:10', 'updated_at' => '2023-07-17 05:27:10'),
            array('id' => '6', 'name' => 'kishwan', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 05:31:15', 'updated_at' => '2023-07-17 05:31:15'),
            array('id' => '7', 'name' => 'B-HAQUE', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 05:34:50', 'updated_at' => '2023-07-17 05:34:50'),
            array('id' => '8', 'name' => 'pran', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 05:36:51', 'updated_at' => '2023-07-17 05:36:51'),
            array('id' => '9', 'name' => 'B-KISHWAN', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 05:39:14', 'updated_at' => '2023-07-17 05:39:14'),
            array('id' => '10', 'name' => 'B-PRAN', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 05:40:41', 'updated_at' => '2023-07-17 05:40:41'),
            array('id' => '11', 'name' => 'kazifarms kitchen', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 05:40:43', 'updated_at' => '2023-07-17 05:40:43'),
            array('id' => '12', 'name' => 'B-danish', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 05:47:40', 'updated_at' => '2023-07-17 05:47:40'),
            array('id' => '13', 'name' => 'B- bengal', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 05:52:26', 'updated_at' => '2023-07-17 05:52:26'),
            array('id' => '14', 'name' => 'fulkoli', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 06:48:42', 'updated_at' => '2023-07-17 06:48:42'),
            array('id' => '15', 'name' => 'bakemans,s', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 07:12:12', 'updated_at' => '2023-07-17 07:12:12'),
            array('id' => '16', 'name' => 'b-zafran', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 07:20:04', 'updated_at' => '2023-07-17 07:20:04'),
            array('id' => '17', 'name' => 'b-banoful', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 07:32:57', 'updated_at' => '2023-07-17 07:32:57'),
            array('id' => '18', 'name' => 'goldmark', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 07:33:53', 'updated_at' => '2023-07-17 07:33:53'),
            array('id' => '19', 'name' => 'C-BOMBAY SWEETS', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 07:40:12', 'updated_at' => '2023-07-17 07:40:12'),
            array('id' => '20', 'name' => 'C-GQ FOODS', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 07:41:59', 'updated_at' => '2023-07-17 07:41:59'),
            array('id' => '21', 'name' => 'C-sun', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 07:44:13', 'updated_at' => '2023-07-17 07:44:13'),
            array('id' => '22', 'name' => 'C-NEW ZEALAND DAIRY', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 07:44:16', 'updated_at' => '2023-07-17 07:44:16'),
            array('id' => '23', 'name' => 'C-kurkure', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 07:51:23', 'updated_at' => '2023-07-17 07:51:23'),
            array('id' => '24', 'name' => 'C-LAYS', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 07:55:21', 'updated_at' => '2023-07-17 07:55:21'),
            array('id' => '25', 'name' => 'C-PRINGLES', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 08:00:20', 'updated_at' => '2023-07-17 08:00:20'),
            array('id' => '26', 'name' => 'C-BD Food', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 08:13:31', 'updated_at' => '2023-07-17 08:13:31'),
            array('id' => '27', 'name' => 'B - Bashundhara', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 08:21:31', 'updated_at' => '2023-07-17 08:21:31'),
            array('id' => '28', 'name' => 'C-Ruchi', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 08:22:03', 'updated_at' => '2023-07-17 08:22:03'),
            array('id' => '29', 'name' => 'tang', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 08:33:00', 'updated_at' => '2023-07-17 08:33:00'),
            array('id' => '30', 'name' => 'aci', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 08:39:53', 'updated_at' => '2023-07-17 08:39:53'),
            array('id' => '31', 'name' => 'dal', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 09:31:27', 'updated_at' => '2023-07-17 09:31:27'),
            array('id' => '32', 'name' => 'walton', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 09:39:54', 'updated_at' => '2023-07-17 09:39:54'),
            array('id' => '33', 'name' => 'Arfan Rice', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 09:42:12', 'updated_at' => '2023-07-17 09:42:12'),
            array('id' => '34', 'name' => 'Square Food', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 09:47:09', 'updated_at' => '2023-07-17 09:47:09'),
            array('id' => '35', 'name' => 'Rupchanda', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 09:51:12', 'updated_at' => '2023-07-17 09:51:12'),
            array('id' => '36', 'name' => 'Molla Salt', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 09:55:39', 'updated_at' => '2023-07-17 09:55:39'),
            array('id' => '37', 'name' => 'Bonoful', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 10:43:59', 'updated_at' => '2023-07-17 10:43:59'),
            array('id' => '38', 'name' => 'Dairy Milk', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 03:22:37', 'updated_at' => '2023-07-18 03:22:37'),
            array('id' => '39', 'name' => 'dan cake', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 05:25:25', 'updated_at' => '2023-07-18 05:25:25'),
            array('id' => '40', 'name' => 'cbl', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 05:30:25', 'updated_at' => '2023-07-18 05:30:25'),
            array('id' => '41', 'name' => 'Fay', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 06:05:08', 'updated_at' => '2023-07-18 06:05:08'),
            array('id' => '42', 'name' => 'baby nutrition', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 06:37:51', 'updated_at' => '2023-07-18 06:37:51'),
            array('id' => '43', 'name' => 'Nestle', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 06:44:11', 'updated_at' => '2023-07-18 06:44:11'),
            array('id' => '44', 'name' => 'Unilever', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 06:50:53', 'updated_at' => '2023-07-18 06:50:53'),
            array('id' => '45', 'name' => 'Ect', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 07:29:12', 'updated_at' => '2023-07-18 07:29:12'),
            array('id' => '46', 'name' => 'Ahmed Food', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 08:12:43', 'updated_at' => '2023-07-18 08:12:43'),
            array('id' => '47', 'name' => 'Star Ship', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 08:17:49', 'updated_at' => '2023-07-18 08:17:49'),
            array('id' => '48', 'name' => 'Arong', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 08:25:17', 'updated_at' => '2023-07-18 08:25:17'),
            array('id' => '49', 'name' => 'Ligion', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 09:04:27', 'updated_at' => '2023-07-18 09:04:27'),
            array('id' => '50', 'name' => 'ispahani', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 09:05:35', 'updated_at' => '2023-07-18 09:05:35'),
            array('id' => '51', 'name' => 'Marks', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 09:29:40', 'updated_at' => '2023-07-18 09:29:40'),
            array('id' => '52', 'name' => 'Ispahani', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 09:40:22', 'updated_at' => '2023-07-18 09:40:22'),
            array('id' => '53', 'name' => 'Fresh', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 09:40:53', 'updated_at' => '2023-07-18 09:40:53'),
            array('id' => '54', 'name' => 'bashundhar', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 09:44:35', 'updated_at' => '2023-07-18 09:44:35'),
            array('id' => '55', 'name' => 'Dano', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 10:06:59', 'updated_at' => '2023-07-18 10:06:59'),
            array('id' => '56', 'name' => 'Colgate', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 06:41:22', 'updated_at' => '2023-07-19 06:41:22'),
            array('id' => '57', 'name' => 'Radhuni', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 06:47:14', 'updated_at' => '2023-07-19 06:47:14'),
            array('id' => '58', 'name' => 'Sajeeb', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 06:51:05', 'updated_at' => '2023-07-19 06:51:05'),
            array('id' => '59', 'name' => 'Dabur', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 06:53:46', 'updated_at' => '2023-07-19 06:53:46'),
            array('id' => '60', 'name' => 'preoty', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 07:23:19', 'updated_at' => '2023-07-19 07:23:19'),
            array('id' => '61', 'name' => 'Johnsons', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 07:53:36', 'updated_at' => '2023-07-19 07:53:36'),
            array('id' => '62', 'name' => 'One Super', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 08:48:56', 'updated_at' => '2023-07-19 08:48:56'),
            array('id' => '63', 'name' => 'magic', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 08:50:03', 'updated_at' => '2023-07-19 08:50:03'),
            array('id' => '64', 'name' => 'just for baby', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 08:58:13', 'updated_at' => '2023-07-19 08:58:13'),
            array('id' => '65', 'name' => 'meril', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 09:15:52', 'updated_at' => '2023-07-19 09:15:52'),
            array('id' => '66', 'name' => 'YC', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 09:20:34', 'updated_at' => '2023-07-19 09:20:34'),
            array('id' => '67', 'name' => 'Lihama', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 09:30:24', 'updated_at' => '2023-07-19 09:30:24'),
            array('id' => '68', 'name' => 'Nutrilife', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 10:47:30', 'updated_at' => '2023-07-19 10:47:30'),
            array('id' => '69', 'name' => 'Sensodyne', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 11:16:59', 'updated_at' => '2023-07-19 11:16:59'),
            array('id' => '70', 'name' => 'reckitt', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 11:28:19', 'updated_at' => '2023-07-19 11:28:19'),
            array('id' => '71', 'name' => 'dettol', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 11:44:27', 'updated_at' => '2023-07-19 11:44:27'),
            array('id' => '72', 'name' => 'milk', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 12:19:11', 'updated_at' => '2023-07-19 12:19:11'),
            array('id' => '73', 'name' => 'huggies', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-20 01:58:29', 'updated_at' => '2023-07-20 01:58:29'),
            array('id' => '74', 'name' => 'kodomo', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-20 02:26:23', 'updated_at' => '2023-07-20 02:26:23'),
            array('id' => '75', 'name' => 'vitacare', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-20 02:51:33', 'updated_at' => '2023-07-20 02:51:33'),
            array('id' => '76', 'name' => 'savlon', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-20 04:32:10', 'updated_at' => '2023-07-20 04:32:10'),
            array('id' => '77', 'name' => 'Maggi', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-20 06:43:47', 'updated_at' => '2023-07-20 06:43:47'),
            array('id' => '78', 'name' => 'shan', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-20 07:50:35', 'updated_at' => '2023-07-20 07:50:35'),
            array('id' => '79', 'name' => 'Emami', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-20 08:05:05', 'updated_at' => '2023-07-20 08:05:05'),
            array('id' => '80', 'name' => 'doodles', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-20 08:17:29', 'updated_at' => '2023-07-20 08:17:29'),
            array('id' => '81', 'name' => 'Lifebuoy', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-21 03:12:44', 'updated_at' => '2023-07-21 03:12:44'),
            array('id' => '82', 'name' => 'vivel', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-21 03:59:02', 'updated_at' => '2023-07-21 03:59:02'),
            array('id' => '83', 'name' => 'Dove', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-21 04:01:56', 'updated_at' => '2023-07-21 04:01:56'),
            array('id' => '84', 'name' => 'Godrej', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-21 04:11:55', 'updated_at' => '2023-07-21 04:11:55'),
            array('id' => '85', 'name' => 'Tibbet', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-21 04:27:53', 'updated_at' => '2023-07-21 04:27:53'),
            array('id' => '86', 'name' => 'Mumtaz', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-21 07:33:16', 'updated_at' => '2023-07-21 07:33:16'),
            array('id' => '87', 'name' => 'olive oil', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-21 07:42:58', 'updated_at' => '2023-07-21 07:42:58'),
            array('id' => '88', 'name' => 'Garnier', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-21 08:18:56', 'updated_at' => '2023-07-21 08:18:56'),
            array('id' => '89', 'name' => 'jui', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-21 08:36:55', 'updated_at' => '2023-07-21 08:36:55'),
            array('id' => '90', 'name' => 'parachut', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-21 09:02:39', 'updated_at' => '2023-07-21 09:02:39'),
            array('id' => '91', 'name' => 'cute', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-21 10:01:17', 'updated_at' => '2023-07-21 10:01:17'),
            array('id' => '92', 'name' => 'Clean&clear', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-22 05:05:56', 'updated_at' => '2023-07-22 05:05:56'),
            array('id' => '93', 'name' => 'LOTUS', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-22 05:38:51', 'updated_at' => '2023-07-22 05:38:51'),
            array('id' => '94', 'name' => 'CLARISS', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-22 05:44:35', 'updated_at' => '2023-07-22 05:44:35'),
            array('id' => '95', 'name' => 'Nivea', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-23 03:35:13', 'updated_at' => '2023-07-23 03:35:13'),
            array('id' => '96', 'name' => 'fogg', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-23 03:37:39', 'updated_at' => '2023-07-23 03:37:39'),
            array('id' => '97', 'name' => 'Nivea', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-23 03:42:01', 'updated_at' => '2023-07-23 03:42:01'),
            array('id' => '98', 'name' => 'Gillette', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-23 05:05:51', 'updated_at' => '2023-07-23 05:05:51'),
            array('id' => '99', 'name' => 'igloo', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-24 04:12:23', 'updated_at' => '2023-07-24 04:12:23'),
            array('id' => '100', 'name' => '7 up', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-24 04:15:50', 'updated_at' => '2023-07-24 04:15:50'),
            array('id' => '101', 'name' => 'juice', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-24 04:26:33', 'updated_at' => '2023-07-24 04:26:33'),
            array('id' => '102', 'name' => 'Golden Harvest', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-24 04:39:37', 'updated_at' => '2023-07-24 04:39:37'),
            array('id' => '103', 'name' => 'country natural', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-24 05:08:39', 'updated_at' => '2023-07-24 05:08:39'),
            array('id' => '104', 'name' => 'walkar', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-25 03:05:11', 'updated_at' => '2023-07-25 03:05:11'),
            array('id' => '105', 'name' => 'Coca Cola', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-25 05:01:59', 'updated_at' => '2023-07-25 05:01:59'),
            array('id' => '106', 'name' => 'akij Group', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-30 03:56:52', 'updated_at' => '2023-07-30 03:56:52'),
            array('id' => '107', 'name' => 'discovery', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-08-15 04:21:12', 'updated_at' => '2023-08-15 04:21:12'),
            array('id' => '108', 'name' => 'indian', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-08-19 04:40:55', 'updated_at' => '2023-08-19 04:40:55'),
            array('id' => '109', 'name' => 'rajkonna', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-08-19 07:55:32', 'updated_at' => '2023-08-19 07:55:32'),
            array('id' => '110', 'name' => 'Biomil', 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-09-13 03:40:55', 'updated_at' => '2023-09-13 03:40:55')
        );

        $codeGenerator = new CodeGenerationService;

        foreach ($brands as $brand) {

            // echo $category['id'].PHP_EOL;
            $exists = DB::table('brands')->where('id', $brand['id'])->first();
            if (!isset($exists)) {

                $code = $codeGenerator->brandCode();

                Brand::insert([
                    'id' => $brand['id'],
                    'code' => $code,
                    'name' => $brand['name'],
                ]);

                echo 'Created : ' . $brand['name'] . PHP_EOL;
            }
        }
    }
}
