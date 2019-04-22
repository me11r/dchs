<?php

namespace App\Observers;

use App\Models\QueuedReport;

class QueuedReportObserver
{
    public function creating(QueuedReport $item)
    {
        $item->cache_hash_key = $this->getCacheHashKey($item);
    }

    private function getCacheHashKey(QueuedReport $item)
    {
        return  hash('sha512', json_encode($item->report_data) . '_' . $item->user_id . '_' . $item->created_at) . microtime();
    }
}
