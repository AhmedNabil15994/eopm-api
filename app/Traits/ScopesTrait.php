<?php

namespace App\Traits;

trait ScopesTrait
{
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeUnActive($query)
    {
        return $query->where('status', false);
    }

    public function scopeOnlyDeleted($query)
    {
        return $query->onlyTrashed();
    }

    public function scopeWithDeleted($query)
    {
        return $query->withTrashed();
    }

    public function scopeSuccessPayment($query)
    {
        return $query->where('success_status', 1);
    }

    public function scopeFailedOrderStatus($query)
    {
        return $query->where('failed_status', 1);
    }

    public function scopePendingOrderStatus($query)
    {
        return $query->where([
            ['failed_status', null],
            ['success_status', null]
        ]);
    }

    public function scopeInReviewStatus($query)
    {
        return $query->where([
            ['failed_status', 0],
            ['success_status', 0]
        ]);
    }

}
