<?php

namespace App\Console\Commands;

use App\Helpers\ListHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class SyncUserTables extends Command
{
    protected $signature = 'sync:user-tables';
    protected $description = 'Sync all user tables with master structure dynamically';

    public function handle()
    {
        // Ambil semua user yang punya prefix tabel
        $users = User::whereNotNull('tbl_prefix')->get();
        $listTables = ListHelper::getTables();

        foreach ($users as $user) {
            foreach ($listTables as $table) {
                $this->syncTable($user->tbl_prefix, $table);
            }
        }

        $this->info('User tables synced successfully!');
    }

    private function syncTable($prefix, $tableName)
    {
        $masterTable = $tableName;
        $userTable = $prefix . '_' . $tableName;

        // Cek apakah tabel user ada
        if (!DB::select("SHOW TABLES LIKE '$userTable'")) {
            $this->info("Skipping $userTable (table not found)");
            return;
        }

        // Ambil daftar kolom tabel master
        $masterColumns = $this->getTableColumns($masterTable);
        // Ambil daftar kolom tabel user
        $userColumns = $this->getTableColumns($userTable);

        // Cari kolom yang perlu ditambahkan
        $columnsToAdd = array_diff_key($masterColumns, $userColumns);
        // Cari kolom yang ada di user tapi tidak ada di master (Opsional)
        $columnsToRemove = array_diff_key($userColumns, $masterColumns);

        // Tambahkan kolom yang belum ada di tabel user
        foreach ($columnsToAdd as $column => $definition) {
            DB::statement("ALTER TABLE `$userTable` ADD COLUMN `$column` $definition;");
            $this->info("Added column `$column` to `$userTable`");
        }

        // Hapus kolom yang tidak ada di master (Opsional)
        foreach ($columnsToRemove as $column => $definition) {
            DB::statement("ALTER TABLE `$userTable` DROP COLUMN `$column`;");
            $this->info("Removed column `$column` from `$userTable`");
        }
    }

    private function getTableColumns($table)
    {
        $columns = DB::select("SHOW COLUMNS FROM `$table`");
        $columnDefinitions = [];

        foreach ($columns as $column) {
            $columnDefinitions[$column->Field] = $column->Type .
                ($column->Null == 'NO' ? ' NOT NULL' : '') .
                ($column->Default !== null ? " DEFAULT '{$column->Default}'" : '');
        }

        return $columnDefinitions;
    }
}
