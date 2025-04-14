<?php

namespace App\Livewire\Org\Events;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\WaitingList as ModelsWaitingList;
use Livewire\Component;
use Livewire\WithPagination;

class WaitingList extends Component
{
    use WithPagination;

    public $eventId;
    public $event;
    public $selectedTicketId = null;

    // Search and filter properties
    public $search = '';
    public $status = 'pending';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $selectedEntries = [];

    public function mount($id)
    {
        $this->eventId = $id;
        $this->event = Event::with(['tickets.waitingList.user'])->findOrFail($id);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function notifySelected()
    {
        if (empty($this->selectedEntries)) {
            $this->dispatch('toast', 'Please select entries to notify.', 'error', 'top-right');
            return;
        }

        foreach ($this->selectedEntries as $entryId) {
            $entry = ModelsWaitingList::find($entryId);
            if ($entry && $entry->status === 'pending') {
                $entry->markAsNotified();
            }
        }

        $this->selectedEntries = [];
        $this->dispatch('toast', 'Selected entries have been notified.', 'success', 'top-right');
    }

    public function removeSelected()
    {
        if (empty($this->selectedEntries)) {
            $this->dispatch('toast', 'Please select entries to remove.', 'error', 'top-right');
            return;
        }

        ModelsWaitingList::whereIn('id', $this->selectedEntries)->delete();
        $this->selectedEntries = [];
        $this->dispatch('toast', 'Selected entries have been removed.', 'success', 'top-right');
    }

    public function render()
    {
        $tickets = $this->event->tickets()
            ->whereHas('waitingList', function ($query) {
                $query->where('status', $this->status);

                if ($this->search) {
                    $query->whereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%')
                            ->orWhereHas('userDetail', function ($q) {
                                $q->where('phone_number', 'like', '%' . $this->search . '%');
                            });
                    });
                }
            })
            ->with(['waitingList' => function ($query) {
                $query->where('status', $this->status)
                    ->when($this->search, function ($q) {
                        $q->whereHas('user', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%')
                                ->orWhereHas('userDetail', function ($q) {
                                    $q->where('phone_number', 'like', '%' . $this->search . '%');
                                });
                        });
                    })
                    ->with('user.userDetail')
                    ->orderBy($this->sortField, $this->sortDirection);
            }])
            ->paginate(10);

        return view('livewire.org.events.waiting-list', [
            'tickets' => $tickets
        ])->layout('components.layouts.event-detail', [
            'eventId' => $this->eventId,
            'event' => $this->event
        ]);
    }
}
