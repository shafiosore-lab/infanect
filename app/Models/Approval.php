<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Approval extends Model
{
    protected $fillable = [
        'type',
        'action',
        'requestor_id',
        'approver_id',
        'entity_type',
        'entity_id',
        'status',
        'request_data',
        'approved_data',
        'comments',
        'approved_at',
    ];

    protected $casts = [
        'request_data' => 'array',
        'approved_data' => 'array',
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function requestor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requestor_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function entity()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Helper methods
    public function approve(User $approver, $comments = null)
    {
        $this->update([
            'status' => 'approved',
            'approver_id' => $approver->id,
            'comments' => $comments,
            'approved_at' => now(),
        ]);

        // Apply the approved changes
        $this->applyChanges();
    }

    public function reject(User $approver, $comments = null)
    {
        $this->update([
            'status' => 'rejected',
            'approver_id' => $approver->id,
            'comments' => $comments,
            'approved_at' => now(),
        ]);
    }

    protected function applyChanges()
    {
        if (!$this->approved_data) {
            return;
        }

        // Apply changes based on entity type and action
        switch ($this->entity_type) {
            case 'App\Models\Activity':
                $this->applyActivityChanges();
                break;
            case 'App\Models\Service':
                $this->applyServiceChanges();
                break;
        }
    }

    protected function applyActivityChanges()
    {
        $activity = Activity::find($this->entity_id);
        if (!$activity) return;

        if ($this->action === 'create') {
            $activity->update($this->approved_data);
            $activity->update(['is_approved' => true]);
        } elseif ($this->action === 'update') {
            $activity->update($this->approved_data);
        } elseif ($this->action === 'delete') {
            $activity->delete();
        }
    }

    protected function applyServiceChanges()
    {
        $service = Service::find($this->entity_id);
        if (!$service) return;

        if ($this->action === 'create') {
            $service->update($this->approved_data);
            $service->update(['is_approved' => true]);
        } elseif ($this->action === 'update') {
            $service->update($this->approved_data);
        } elseif ($this->action === 'delete') {
            $service->delete();
        }
    }
}
