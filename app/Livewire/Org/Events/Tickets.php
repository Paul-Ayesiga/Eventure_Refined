<?php

namespace App\Livewire\Org\Events;

use App\Models\Event;
use App\Models\Ticket;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Tickets extends Component
{
    use WithPagination;

    public $eventId;
    public $event;

    // Form fields for creating/editing tickets
    public $ticketId;
    public $name;
    public $description;
    public $price;
    public $quantity_available;
    public $sale_start_date;
    public $sale_end_date;
    public $max_tickets_per_booking = 1;
    public $status = 'active';
    public $repeat_ticket = false;

    // For modal control
    public $isModalOpen = false;
    public $isEditing = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'quantity_available' => 'required|integer|min:1',
        'sale_start_date' => 'required|date',
        'sale_end_date' => 'required|date|after:sale_start_date',
        'max_tickets_per_booking' => 'required|integer|min:1',
        'status' => 'required|in:active,inactive',
        'repeat_ticket' => 'boolean'
    ];

    public function mount($id)
    {
        $this->eventId = $id;
        $this->event = Event::findOrFail($id);

        // Check if user is authorized to manage this event
        // if ($this->event->organiser->organiser_id !== Auth::id()) {
        //     abort(403, 'Unauthorized action.');
        // }
    }

    public function openCreateModal()
    {
        // Prevent creating tickets if the event is archived
        if ($this->event->isArchived()) {
            $this->dispatch('toast', 'Cannot create tickets for archived events.', 'error', 'top-right');
            return;
        }

        $this->resetForm();
        $this->isEditing = false;
        $this->isModalOpen = true;
    }

    public function openEditModal($ticketId)
    {
        // Prevent editing tickets if the event is archived
        if ($this->event->isArchived()) {
            $this->dispatch('toast', 'Cannot edit tickets for archived events.', 'error', 'top-right');
            return;
        }

        $ticket = Ticket::findOrFail($ticketId);

        $this->ticketId = $ticket->id;
        $this->name = $ticket->name;
        $this->description = $ticket->description;
        $this->price = $ticket->price;
        $this->quantity_available = $ticket->quantity_available;
        $this->sale_start_date = $ticket->sale_start_date->format('Y-m-d');
        $this->sale_end_date = $ticket->sale_end_date->format('Y-m-d');
        $this->max_tickets_per_booking = $ticket->max_tickets_per_booking;
        $this->status = $ticket->status;
        $this->repeat_ticket = $ticket->repeat_ticket;

        $this->isEditing = true;
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'ticketId',
            'name',
            'description',
            'price',
            'quantity_available',
            'sale_start_date',
            'sale_end_date',
            'max_tickets_per_booking',
            'status',
            'repeat_ticket'
        ]);
    }

    public function saveTicket()
    {
        // Prevent saving tickets if the event is archived
        if ($this->event->isArchived()) {
            $this->dispatch('toast', 'Cannot modify tickets for archived events.', 'error', 'top-right');
            return;
        }

        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'quantity_available' => $this->quantity_available,
            'sale_start_date' => $this->sale_start_date,
            'sale_end_date' => $this->sale_end_date,
            'max_tickets_per_booking' => $this->max_tickets_per_booking,
            'status' => $this->status,
            'repeat_ticket' => $this->repeat_ticket,
        ];

        if ($this->isEditing) {
            $ticket = Ticket::findOrFail($this->ticketId);
            $ticket->update($data);
            $message = 'Ticket updated successfully.';
        } else {
            $data['event_id'] = $this->eventId;
            $data['quantity_sold'] = 0;
            Ticket::create($data);
            $message = 'Ticket created successfully.';
        }

        $this->closeModal();
        $this->dispatch('toast', $message, 'success', 'top-right');
    }

    public function deleteTicket($ticketId)
    {
        // Prevent deleting tickets if the event is archived
        if ($this->event->isArchived()) {
            $this->dispatch('toast', 'Cannot delete tickets for archived events.', 'error', 'top-right');
            return;
        }

        $ticket = Ticket::findOrFail($ticketId);

        // Check if ticket has any bookings
        if ($ticket->bookingItems()->count() > 0) {
            $this->dispatch('toast', 'Cannot delete ticket with existing bookings.', 'error', 'top-right');
            return;
        }

        $ticket->delete();
        $this->dispatch('toast', 'Ticket deleted successfully.', 'success', 'top-right');
    }

    public function toggleTicketStatus($ticketId)
    {
        // Prevent toggling ticket status if the event is archived
        if ($this->event->isArchived()) {
            $this->dispatch('toast', 'Cannot modify tickets for archived events.', 'error', 'top-right');
            return;
        }

        $ticket = Ticket::findOrFail($ticketId);
        $ticket->status = $ticket->status === 'active' ? 'inactive' : 'active';
        $ticket->save();

        $status = $ticket->status === 'active' ? 'activated' : 'deactivated';
        $this->dispatch('toast', "Ticket {$status} successfully.", 'success', 'top-right');
    }

    public function render()
    {
        $tickets = $this->event->tickets()->paginate(10);

        return view('livewire.org.events.tickets', [
            'tickets' => $tickets
        ])->layout('components.layouts.event-detail', [
            'eventId' => $this->eventId,
            'event' => $this->event
        ]);
    }
}
