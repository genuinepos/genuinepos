<?php

namespace App\Console\Commands;

use App\Enums\CategoryType;
use App\Models\Products\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\CodeGenerationService;

class AddCategoriesCommend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:cat';

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
        $codeGenerator = new CodeGenerationService;
        $categories = array(
            array('id' => '2', 'name' => 'winner', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-16 09:49:02', 'updated_at' => '2023-07-16 09:49:02'),
            array('id' => '3', 'name' => 'FLASK', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-16 09:58:53', 'updated_at' => '2023-07-16 09:58:53'),
            array('id' => '4', 'name' => 'OIL', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-16 11:55:39', 'updated_at' => '2023-07-16 11:55:39'),
            array('id' => '5', 'name' => 'biscuits', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 05:03:21', 'updated_at' => '2023-07-17 05:03:21'),
            array('id' => '6', 'name' => 'biscuits', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 05:30:46', 'updated_at' => '2023-07-17 05:30:46'),
            array('id' => '7', 'name' => 'chips', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 07:39:08', 'updated_at' => '2023-07-17 07:39:08'),
            array('id' => '8', 'name' => 'CHANACHUR', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 08:08:40', 'updated_at' => '2023-07-17 08:08:40'),
            array('id' => '9', 'name' => 'Ata', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 08:14:25', 'updated_at' => '2023-07-17 08:14:25'),
            array('id' => '10', 'name' => 'muri', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 08:25:57', 'updated_at' => '2023-07-17 08:25:57'),
            array('id' => '11', 'name' => 'tang', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 08:32:38', 'updated_at' => '2023-07-17 08:32:38'),
            array('id' => '12', 'name' => 'rice', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 08:38:39', 'updated_at' => '2023-07-17 08:38:39'),
            array('id' => '13', 'name' => 'juice', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 08:47:42', 'updated_at' => '2023-07-17 08:47:42'),
            array('id' => '14', 'name' => 'dal', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 09:31:04', 'updated_at' => '2023-07-17 09:31:04'),
            array('id' => '15', 'name' => 'suger', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 09:45:16', 'updated_at' => '2023-07-17 09:45:16'),
            array('id' => '16', 'name' => 'salt', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 09:52:35', 'updated_at' => '2023-07-17 09:52:35'),
            array('id' => '17', 'name' => 'semai', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 10:43:45', 'updated_at' => '2023-07-17 10:43:45'),
            array('id' => '18', 'name' => 'cake', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 10:59:31', 'updated_at' => '2023-07-17 10:59:31'),
            array('id' => '19', 'name' => 'egg', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-17 11:12:24', 'updated_at' => '2023-07-17 11:12:24'),
            array('id' => '20', 'name' => 'choclate', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 03:18:56', 'updated_at' => '2023-07-18 03:18:56'),
            array('id' => '21', 'name' => 'marsh mallow', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 03:50:42', 'updated_at' => '2023-07-18 03:50:42'),
            array('id' => '22', 'name' => 'Tissues', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 06:04:44', 'updated_at' => '2023-07-18 06:04:44'),
            array('id' => '23', 'name' => 'Powder milk', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 06:34:58', 'updated_at' => '2023-07-18 06:34:58'),
            array('id' => '24', 'name' => 'jelly', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 07:15:36', 'updated_at' => '2023-07-18 07:15:36'),
            array('id' => '25', 'name' => 'nut', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 07:18:06', 'updated_at' => '2023-07-18 07:18:06'),
            array('id' => '26', 'name' => 'nutella', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 07:26:09', 'updated_at' => '2023-07-18 07:26:09'),
            array('id' => '27', 'name' => 'Coffee', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 07:54:39', 'updated_at' => '2023-07-18 07:54:39'),
            array('id' => '28', 'name' => 'milk', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 08:17:16', 'updated_at' => '2023-07-18 08:17:16'),
            array('id' => '29', 'name' => 'Tea', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 09:04:05', 'updated_at' => '2023-07-18 09:04:05'),
            array('id' => '30', 'name' => 'tea', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-18 09:31:25', 'updated_at' => '2023-07-18 09:31:25'),
            array('id' => '31', 'name' => 'baby wipes', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 06:39:18', 'updated_at' => '2023-07-19 06:39:18'),
            array('id' => '32', 'name' => 'brush', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 06:41:03', 'updated_at' => '2023-07-19 06:41:03'),
            array('id' => '33', 'name' => 'masala', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 06:50:32', 'updated_at' => '2023-07-19 06:50:32'),
            array('id' => '34', 'name' => 'Toothpeste', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 06:52:39', 'updated_at' => '2023-07-19 06:52:39'),
            array('id' => '35', 'name' => 'Aerosol', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 06:58:33', 'updated_at' => '2023-07-19 06:58:33'),
            array('id' => '36', 'name' => 'coil', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 07:13:21', 'updated_at' => '2023-07-19 07:13:21'),
            array('id' => '37', 'name' => 'harpic', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 07:20:31', 'updated_at' => '2023-07-19 07:20:31'),
            array('id' => '38', 'name' => 'food all', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 07:24:46', 'updated_at' => '2023-07-19 07:24:46'),
            array('id' => '39', 'name' => 'powder', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 07:53:20', 'updated_at' => '2023-07-19 07:53:20'),
            array('id' => '40', 'name' => 'soap', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 07:56:00', 'updated_at' => '2023-07-19 07:56:00'),
            array('id' => '41', 'name' => 'food colour', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 07:59:31', 'updated_at' => '2023-07-19 07:59:31'),
            array('id' => '42', 'name' => 'Lotion', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 08:05:24', 'updated_at' => '2023-07-19 08:05:24'),
            array('id' => '43', 'name' => 'Face cream', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 08:12:43', 'updated_at' => '2023-07-19 08:12:43'),
            array('id' => '44', 'name' => 'oil', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 08:14:14', 'updated_at' => '2023-07-19 08:14:14'),
            array('id' => '45', 'name' => 'A 2 Z', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 08:52:50', 'updated_at' => '2023-07-19 08:52:50'),
            array('id' => '46', 'name' => 'HONEY', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 08:54:02', 'updated_at' => '2023-07-19 08:54:02'),
            array('id' => '47', 'name' => 'vinegar', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 08:56:26', 'updated_at' => '2023-07-19 08:56:26'),
            array('id' => '48', 'name' => 'Baby shampoo', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 08:57:51', 'updated_at' => '2023-07-19 08:57:51'),
            array('id' => '49', 'name' => 'sauce', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 09:02:52', 'updated_at' => '2023-07-19 09:02:52'),
            array('id' => '50', 'name' => 'mayonnaise', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 09:06:25', 'updated_at' => '2023-07-19 09:06:25'),
            array('id' => '51', 'name' => 'honey', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 09:08:28', 'updated_at' => '2023-07-19 09:08:28'),
            array('id' => '52', 'name' => 'ghee', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 09:17:38', 'updated_at' => '2023-07-19 09:17:38'),
            array('id' => '53', 'name' => 'Toothpaste', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 11:03:16', 'updated_at' => '2023-07-19 11:03:16'),
            array('id' => '54', 'name' => 'hand wash', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 11:17:05', 'updated_at' => '2023-07-19 11:17:05'),
            array('id' => '55', 'name' => 'fuska', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-19 11:56:27', 'updated_at' => '2023-07-19 11:56:27'),
            array('id' => '56', 'name' => 'Diaper', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-20 01:55:42', 'updated_at' => '2023-07-20 01:55:42'),
            array('id' => '57', 'name' => 'huggies', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-20 01:57:16', 'updated_at' => '2023-07-20 01:57:16'),
            array('id' => '58', 'name' => 'pampers', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-20 01:57:58', 'updated_at' => '2023-07-20 01:57:58'),
            array('id' => '59', 'name' => 'Acher', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-20 03:00:41', 'updated_at' => '2023-07-20 03:00:41'),
            array('id' => '60', 'name' => 'baby wipes', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-20 04:26:33', 'updated_at' => '2023-07-20 04:26:33'),
            array('id' => '61', 'name' => 'Noodles', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-20 06:42:31', 'updated_at' => '2023-07-20 06:42:31'),
            array('id' => '62', 'name' => 'pasta', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-20 07:55:29', 'updated_at' => '2023-07-20 07:55:29'),
            array('id' => '63', 'name' => 'masrum', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-20 08:28:51', 'updated_at' => '2023-07-20 08:28:51'),
            array('id' => '64', 'name' => 'Lifebuoy', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-21 03:12:15', 'updated_at' => '2023-07-21 03:12:15'),
            array('id' => '65', 'name' => 'Sanitary Napkin', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-21 04:49:06', 'updated_at' => '2023-07-21 04:49:06'),
            array('id' => '66', 'name' => 'shampoo', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-21 05:02:22', 'updated_at' => '2023-07-21 05:02:22'),
            array('id' => '67', 'name' => 'Air freshener', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-21 06:35:11', 'updated_at' => '2023-07-21 06:35:11'),
            array('id' => '68', 'name' => 'hair color cream', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-21 08:18:39', 'updated_at' => '2023-07-21 08:18:39'),
            array('id' => '69', 'name' => 'Face wash', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-22 05:05:08', 'updated_at' => '2023-07-22 05:05:08'),
            array('id' => '70', 'name' => 'NEUTROGENA', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-22 05:12:43', 'updated_at' => '2023-07-22 05:12:43'),
            array('id' => '71', 'name' => 'LOTUS', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-22 05:40:41', 'updated_at' => '2023-07-22 05:40:41'),
            array('id' => '72', 'name' => 'Body Spray', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-23 03:31:49', 'updated_at' => '2023-07-23 03:31:49'),
            array('id' => '73', 'name' => 'shaving cream', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-23 05:05:35', 'updated_at' => '2023-07-23 05:05:35'),
            array('id' => '74', 'name' => 'rezar', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-23 08:28:08', 'updated_at' => '2023-07-23 08:28:08'),
            array('id' => '75', 'name' => 'battary', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-23 08:54:33', 'updated_at' => '2023-07-23 08:54:33'),
            array('id' => '76', 'name' => 'umbrella', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-24 02:58:43', 'updated_at' => '2023-07-24 02:58:43'),
            array('id' => '77', 'name' => 'key ring', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-24 03:51:22', 'updated_at' => '2023-07-24 03:51:22'),
            array('id' => '78', 'name' => 'drink', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-24 04:10:43', 'updated_at' => '2023-07-24 04:10:43'),
            array('id' => '79', 'name' => 'ice cream', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-24 04:12:05', 'updated_at' => '2023-07-24 04:12:05'),
            array('id' => '80', 'name' => 'glass', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-24 05:20:32', 'updated_at' => '2023-07-24 05:20:32'),
            array('id' => '81', 'name' => 'bottle', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-24 05:34:33', 'updated_at' => '2023-07-24 05:34:33'),
            array('id' => '82', 'name' => 'Plastic', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-24 06:31:05', 'updated_at' => '2023-07-24 06:31:05'),
            array('id' => '83', 'name' => 'raksin', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-25 02:02:31', 'updated_at' => '2023-07-25 02:02:31'),
            array('id' => '84', 'name' => 'papos', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-25 02:13:37', 'updated_at' => '2023-07-25 02:13:37'),
            array('id' => '85', 'name' => 'Shoe', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-25 03:04:41', 'updated_at' => '2023-07-25 03:04:41'),
            array('id' => '86', 'name' => 'mug', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-25 04:38:40', 'updated_at' => '2023-07-25 04:38:40'),
            array('id' => '87', 'name' => 'frypan', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-25 07:24:11', 'updated_at' => '2023-07-25 07:24:11'),
            array('id' => '88', 'name' => 'Cereal', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-25 07:37:43', 'updated_at' => '2023-07-25 07:37:43'),
            array('id' => '89', 'name' => 'Ceramic', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-26 03:31:32', 'updated_at' => '2023-07-26 03:31:32'),
            array('id' => '90', 'name' => 'ITALIANO', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-26 04:43:04', 'updated_at' => '2023-07-26 04:43:04'),
            array('id' => '91', 'name' => 'board', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-26 05:20:57', 'updated_at' => '2023-07-26 05:20:57'),
            array('id' => '92', 'name' => 'kitchening', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-26 05:29:16', 'updated_at' => '2023-07-26 05:29:16'),
            array('id' => '93', 'name' => 'flask', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-26 08:46:09', 'updated_at' => '2023-07-26 08:46:09'),
            array('id' => '94', 'name' => 'wgs stove', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-27 03:24:09', 'updated_at' => '2023-07-27 03:24:09'),
            array('id' => '95', 'name' => 'doll', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-27 04:06:33', 'updated_at' => '2023-07-27 04:06:33'),
            array('id' => '96', 'name' => 'Toyes', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-27 04:52:44', 'updated_at' => '2023-07-27 04:52:44'),
            array('id' => '97', 'name' => 'mug', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-27 05:13:19', 'updated_at' => '2023-07-27 05:13:19'),
            array('id' => '98', 'name' => 'jug', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-27 06:10:53', 'updated_at' => '2023-07-27 06:10:53'),
            array('id' => '99', 'name' => 'spon', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-28 03:17:49', 'updated_at' => '2023-07-28 03:17:49'),
            array('id' => '100', 'name' => 'birthday  item', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-28 07:19:09', 'updated_at' => '2023-07-28 07:19:09'),
            array('id' => '101', 'name' => 'Baby item', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-29 02:58:14', 'updated_at' => '2023-07-29 02:58:14'),
            array('id' => '102', 'name' => 'book', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-29 05:41:59', 'updated_at' => '2023-07-29 05:41:59'),
            array('id' => '103', 'name' => 'hit', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-31 19:28:36', 'updated_at' => '2023-07-31 19:28:36'),
            array('id' => '104', 'name' => 'harpic', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-07-31 21:00:44', 'updated_at' => '2023-07-31 21:00:44'),
            array('id' => '105', 'name' => 'hot pot', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-08-08 05:33:29', 'updated_at' => '2023-08-08 05:33:29'),
            array('id' => '106', 'name' => 'perfume', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-08-08 09:05:29', 'updated_at' => '2023-08-08 09:05:29'),
            array('id' => '107', 'name' => 'Nail katar', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-08-11 09:06:19', 'updated_at' => '2023-08-11 09:06:19'),
            array('id' => '108', 'name' => 'mop', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-08-14 11:43:30', 'updated_at' => '2023-08-14 11:43:30'),
            array('id' => '109', 'name' => 'rack', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-08-14 12:26:05', 'updated_at' => '2023-08-14 12:26:05'),
            array('id' => '110', 'name' => 'stool', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-08-17 09:16:29', 'updated_at' => '2023-08-17 09:16:29'),
            array('id' => '111', 'name' => 'hanger', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-08-17 09:30:44', 'updated_at' => '2023-08-17 09:30:44'),
            array('id' => '112', 'name' => 'plate', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-08-18 06:48:10', 'updated_at' => '2023-08-18 06:48:10'),
            array('id' => '113', 'name' => 'clock', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-08-18 07:20:00', 'updated_at' => '2023-08-18 07:20:00'),
            array('id' => '114', 'name' => 'cup', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-08-18 08:03:39', 'updated_at' => '2023-08-18 08:03:39'),
            array('id' => '115', 'name' => 'chair , table, ordoop', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2023-09-13 09:05:28', 'updated_at' => '2023-09-13 09:05:28'),
            array('id' => '116', 'name' => 'toys', 'description' => NULL, 'parent_category_id' => NULL, 'photo' => 'default.png', 'status' => '1', 'created_at' => '2024-01-18 20:38:45', 'updated_at' => '2024-01-18 20:38:45')
        );

        foreach ($categories as $category) {

            // echo $category['id'].PHP_EOL;
            $exists = DB::table('categories')->where('id', $category['id'])->first();
            if (!isset($exists)) {

                $code = $codeGenerator->categoryCode(type: CategoryType::MainCategory->value);

                Category::insert([
                    'id' => $category['id'],
                    'code' => $code,
                    'name' => $category['name'],
                ]);
            }
        }
    }
}
