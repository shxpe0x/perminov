<?php

namespace App\Console\Commands;

use App\Models\Cart;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanOldCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'carts:clean {--days=30 : Number of days of inactivity}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Очистить корзины, неактивные более N дней';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $date = now()->subDays($days);

        $this->info("Поиск корзин, неактивных с {$date->format('Y-m-d H:i:s')}...");

        DB::beginTransaction();

        try {
            // Находим старые корзины
            $oldCarts = Cart::where('updated_at', '<', $date)
                ->with('items')
                ->get();

            $count = $oldCarts->count();
            $itemsCount = $oldCarts->sum(function ($cart) {
                return $cart->items->count();
            });

            if ($count === 0) {
                $this->info('Не найдено старых корзин.');
                DB::commit();
                return Command::SUCCESS;
            }

            if (!$this->confirm("Найдено {$count} корзин ({$itemsCount} товаров). Удалить?", true)) {
                $this->info('Операция отменена.');
                DB::rollBack();
                return Command::SUCCESS;
            }

            // Удаляем товары из корзин
            foreach ($oldCarts as $cart) {
                $cart->items()->delete();
            }

            // Удаляем корзины
            Cart::where('updated_at', '<', $date)->delete();

            DB::commit();

            $this->info("Удалено {$count} корзин и {$itemsCount} товаров.");
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Ошибка: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
