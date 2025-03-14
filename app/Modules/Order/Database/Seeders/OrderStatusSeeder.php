<?php

namespace Modules\Order\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Order\Entities\OrderStatus;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {
            $all = [
                [
                    'title' => 'pending',
                    'color_label' => json_encode(["text" => "danger", "value" => "#f8d7da"]),
                    'success_status' => 0,
                    'failed_status'=>1,
                ],
                [
                    'title' => 'success',
                    'color_label' => json_encode(["text" => "success", "value" => "#D4EDDA"]),
                    'success_status' => 1,
                    'failed_status'=>0,
                ],
                [
                    'title' => 'failed',
                    'color_label' => json_encode(["text" => "danger", "value" => "#F8D7DA"]),
                    'success_status' => 0,
                    'failed_status'=>1,
                ],
                [
                    'title' => 'cancelled',
                    'color_label' => json_encode(["text" => "danger", "value" => "#F8D7DA"]),
                    'success_status' => 0,
                    'failed_status'=>1,
                ],
                [
                    'title' => 'confirmed',
                    'color_label' => json_encode(["text" => "success", "value" => "#D4EDDA"]),
                    'success_status' => 1,
                    'failed_status'=>0,
                ],
            ];

            foreach ($all as $k => $status) {
                $s = OrderStatus::create($status);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
