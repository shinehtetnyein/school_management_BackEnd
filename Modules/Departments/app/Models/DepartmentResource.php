<?php

namespace Modules\Departments\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class DepartmentResource extends Model
{
    protected $fillable = [
        'department_id',
        'name',
        'type',
        'quantity',
        'status',
        'last_maintenance',
        'notes'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'status' => 'boolean',
        'last_maintenance' => 'datetime'
    ];

    // Relationships
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function maintenanceLogs(): MorphMany
    {
        return $this->morphMany(Event::class, 'eventable')->where('type', 'maintenance');
    }

    // Scopes
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    public function scopeInUse(Builder $query): Builder
    {
        return $query->where('status', false);
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeNeedsMaintenance(Builder $query, int $daysThreshold = 30): Builder
    {
        return $query->where(function ($q) use ($daysThreshold) {
            $q->whereNull('last_maintenance')
              ->orWhere('last_maintenance', '<=', now()->subDays($daysThreshold));
        });
    }

    public function scopeLowQuantity(Builder $query, int $threshold = 5): Builder
    {
        return $query->where('quantity', '<=', $threshold);
    }

    // Helper Methods
    public function isAvailable(): bool
    {
        return $this->status && $this->quantity > 0;
    }

    public function needsMaintenance(int $daysThreshold = 30): bool
    {
        return $this->last_maintenance === null ||
               $this->last_maintenance->diffInDays(now()) >= $daysThreshold;
    }

    public function getMaintenanceStatus(): string
    {
        if ($this->last_maintenance === null) {
            return 'Never maintained';
        }

        return 'Last maintained ' . $this->last_maintenance->diffForHumans();
    }

    public function markAsMaintained(): bool
    {
        $this->last_maintenance = now();
        return $this->save();
    }

    public function setInUse(int $quantity = 1): bool
    {
        if ($this->quantity < $quantity) {
            return false;
        }

        $this->quantity -= $quantity;
        $this->status = $this->quantity > 0;
        return $this->save();
    }

    public function returnToInventory(int $quantity = 1): bool
    {
        $this->quantity += $quantity;
        $this->status = true;
        return $this->save();
    }

    public function addMaintenanceLog(string $description): void
    {
        $this->maintenanceLogs()->create([
            'title' => 'Maintenance - ' . $this->name,
            'description' => $description,
            'start_date' => now(),
            'status' => 'completed',
            'type' => 'maintenance'
        ]);

        $this->markAsMaintained();
    }

    public function getResourceSummary(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'quantity' => $this->quantity,
            'status' => $this->isAvailable() ? 'Available' : 'In Use',
            'maintenance_status' => $this->getMaintenanceStatus(),
            'needs_maintenance' => $this->needsMaintenance(),
            'last_maintenance' => $this->last_maintenance?->format('Y-m-d H:i:s')
        ];
    }
}
