<?php

namespace App\Livewire\Org\Events;

use Livewire\Component;
use App\Models\Event;
use Carbon\Carbon;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventDetails extends Component
{
    use WithFileUploads;

    public int $eventId;
    public $event;
    public $isEditing = false;

    // Form fields
    public $eventType;
    public $name;
    public $venue;
    public $event_repeat;
    public $repeat_days = 1;
    public $start_date;
    public $end_date;
    public $start_time;
    public $end_time;
    public $timezone;
    public $currency;
    public $category;
    public $convert_timezone;
    public $event_visibility;

    // Add these new public properties
    public $description;
    public $tags = [];
    public $banners = [];

    // Add this new property for temporary tag input
    public $newTag = '';

    // Add these new properties
    public $tempBanners = [];

    // Add venueData property
    public $venueData;

    // Change from protected to public so it's accessible in the view
    public $allowedTags = [
        'music',
        'sports',
        'education',
        'technology',
        'business',
        'networking',
        'conference',
        'workshop',
        'seminar',
        'concert',
        'festival',
        'charity',
        'food',
        'art',
        'culture'
    ];


    public function getAllowedTags()
    {
        return $this->allowedTags;
    }

    // Add validation rules for images
    protected $rules = [
        'eventType' => 'required|string|max:50',
        'name' => 'required|string|max:255',
        'venue' => 'nullable|string|max:255',
        'event_repeat' => 'nullable|string|max:50',
        'repeat_days' => 'nullable|integer|min:1',
        'start_date' => 'required|date',
        'start_time' => 'required',
        'end_time' => 'required',
        'timezone' => 'required|string|max:100',
        'currency' => 'required|string|max:10',
        'category' => 'nullable|string|max:100',
        'description' => 'nullable|string',
        'tags' => 'array|max:5', // Maximum 10 tags
        'tags.*' => 'string|max:30', // Each tag max 30 characters
        'tempBanners.*' => 'image|max:5120', // 5MB max per image
        'banners' => 'nullable|array|max:3',
        // Add venueData validation if needed
        'venueData' => 'nullable|array',
    ];

    protected $messages = [
        'tempBanners.*.image' => 'Each banner must be an image file.',
        'tempBanners.*.max' => 'Banner images must not exceed 5MB.',
        'tags.max' => 'You cannot add more than 10 tags.',
        'tags.*.max' => 'Each tag must not exceed 30 characters.',
    ];

    public function mount($id)
    {
        $this->eventId = $id;
        $this->loadEvent();
    }

    public function loadEvent()
    {
        $this->event = Event::findOrFail($this->eventId);
        if (!$this->isEditing) {
            $this->fillFormFields();
        }
    }

    public function fillFormFields()
    {
        $this->eventType = $this->event->event_type;
        $this->name = $this->event->name;
        $this->venue = $this->event->venue;
        $this->event_repeat = $this->event->event_repeat;
        $this->repeat_days = $this->event->repeat_days ?? 1;
        $this->category = $this->event->category;

        $startDateTime = $this->event->start_datetime instanceof Carbon
            ? $this->event->start_datetime
            : Carbon::parse($this->event->start_datetime);

        $endDateTime = $this->event->end_datetime instanceof Carbon
            ? $this->event->end_datetime
            : Carbon::parse($this->event->end_datetime);

        $this->start_date = $startDateTime->toDateString();
        $this->start_time = $startDateTime->format('H:i');
        $this->end_time = $endDateTime->format('H:i');

        $this->timezone = $this->event->timezone;
        $this->currency = $this->event->currency;
        $this->convert_timezone = $this->event->auto_convert_timezone;
        $this->event_visibility = $this->event->status === 'Published';

        // Add new field assignments
        $this->description = $this->event->description;
        $this->tags = json_decode($this->event->tags ?? '[]', true);
        $this->banners = json_decode($this->event->banners ?? '[]', true) ?: [];

        // Add venue data assignment
        if ($this->event->location) {
            $this->venueData = [
                'place_id' => $this->event->location->place_id,
                'osm_id' => $this->event->location->osm_id,
                'osm_type' => $this->event->location->osm_type,
                'lat' => $this->event->location->latitude,
                'lon' => $this->event->location->longitude,
                'display_name' => $this->event->location->display_name,
                'display_place' => $this->event->location->display_place,
                'display_address' => $this->event->location->display_address,
                'address' => [
                    'country' => $this->event->location->country,
                    'country_code' => $this->event->location->country_code,
                ],
                'type' => $this->event->location->type,
                'class' => $this->event->location->class,
                'boundingbox' => $this->event->location->bounds,
            ];
        }
    }

    public function toggleEdit()
    {
        // Prevent editing if the event is archived
        if ($this->event->isArchived()) {
            $this->dispatch('toast', 'Archived events cannot be edited. They are read-only for insights.', 'error', 'top-right');
            return;
        }

        $this->isEditing = !$this->isEditing;
        if ($this->isEditing) {
            $this->fillFormFields();
        }

        // Dispatch an event to notify JavaScript that edit mode has been toggled
        $this->dispatch('toggleEdit');
    }

    public function update()
    {
        // Prevent updating if the event is archived
        if ($this->event->isArchived()) {
            $this->dispatch('toast', 'Archived events cannot be updated. They are read-only for insights.', 'error', 'top-right');
            return;
        }

        $this->validate();

        $start_datetime = Carbon::parse("{$this->start_date} {$this->start_time}");
        $end_datetime = Carbon::parse("{$this->start_date} {$this->end_time}");

        // If event type is online, clear venue and venueData
        if ($this->eventType === 'online') {
            $this->venue = null;
            $this->venueData = null;
        }

        // Create event data array
        $eventData = [
            'event_type' => $this->eventType,
            'name' => $this->name,
            'venue' => $this->venue,
            'event_repeat' => $this->event_repeat,
            'start_date' => $this->start_date,
            'start_datetime' => $start_datetime,
            'end_datetime' => $end_datetime,
            'timezone' => $this->timezone,
            'currency' => $this->currency,
            'status' => $this->event_visibility ? 'Published' : 'Draft',
            'category' => $this->category,
            'auto_convert_timezone' => $this->convert_timezone,
            'description' => $this->description,
            'tags' => json_encode(array_values($this->tags)),
            'banners' => json_encode($this->banners),
        ];

        // Add repeat_days to event data if event repeats
        if ($this->event_repeat !== 'Does not repeat') {
            $eventData['repeat_days'] = $this->repeat_days;
        }

        $this->event->update($eventData);

        // Update or create location data if venueData is available
        if ($this->venueData) {
            if ($this->event->location) {
                $this->event->location->update([
                    'place_id' => $this->venueData['place_id'],
                    'osm_id' => $this->venueData['osm_id'],
                    'osm_type' => $this->venueData['osm_type'],
                    'latitude' => $this->venueData['lat'],
                    'longitude' => $this->venueData['lon'],
                    'display_name' => $this->venueData['display_name'],
                    'display_place' => $this->venueData['display_place'],
                    'display_address' => $this->venueData['display_address'],
                    'country' => $this->venueData['address']['country'] ?? null,
                    'country_code' => $this->venueData['address']['country_code'] ?? null,
                    'type' => $this->venueData['type'],
                    'class' => $this->venueData['class'],
                    'bounds' => $this->venueData['boundingbox'] ?? null,
                ]);
            } else {
                $this->event->location()->create([
                    'place_id' => $this->venueData['place_id'],
                    'osm_id' => $this->venueData['osm_id'],
                    'osm_type' => $this->venueData['osm_type'],
                    'latitude' => $this->venueData['lat'],
                    'longitude' => $this->venueData['lon'],
                    'display_name' => $this->venueData['display_name'],
                    'display_place' => $this->venueData['display_place'],
                    'display_address' => $this->venueData['display_address'],
                    'country' => $this->venueData['address']['country'] ?? null,
                    'country_code' => $this->venueData['address']['country_code'] ?? null,
                    'type' => $this->venueData['type'],
                    'class' => $this->venueData['class'],
                    'bounds' => $this->venueData['boundingbox'] ?? null,
                ]);
            }
        }

        $this->isEditing = false;
        $this->dispatch('toast', 'Event updated successfully.', 'success', 'top-right');
    }

    public function addBanner()
    {
        $this->banners[] = '';
    }

    public function removeBanner($index)
    {
        if (isset($this->banners[$index])) {
            // Remove file from storage
            $path = str_replace('/storage/', '', $this->banners[$index]);
            Storage::disk('public')->delete($path);

            // Remove from array
            unset($this->banners[$index]);
            $this->banners = array_values($this->banners);
        }
    }

    public function addTag()
    {
        $tag = Str::lower(trim($this->newTag));

        if (empty($tag)) {
            return;
        }

        // Check if tag is in allowed list
        if (!in_array($tag, $this->allowedTags)) {
            $this->addError('newTag', 'This tag is not allowed. Please choose from the suggested tags.');
            return;
        }

        if (!in_array($tag, $this->tags)) {
            $this->tags[] = $tag;
        }

        $this->newTag = '';
    }

    public function removeTag($index)
    {
        unset($this->tags[$index]);
        $this->tags = array_values($this->tags);
    }

    public function updatedTempBanners()
    {
        $this->validate([
            'tempBanners.*' => 'image|max:5120'
        ]);

        foreach ($this->tempBanners as $banner) {
            $path = $banner->store('event-banners', 'public');
            $this->banners[] = Storage::url($path);  // Store just the URL string
        }

        $this->tempBanners = []; // Reset temporary uploads
    }

    /**
     * Check if the event is archived
     */
    public function isArchived()
    {
        return $this->event->isArchived();
    }

    /**
     * Get the archived status message
     */
    public function getArchivedMessage()
    {
        if (!$this->event->isArchived()) {
            return null;
        }

        $archivedAt = $this->event->archived_at->format('M d, Y');
        $deletionDate = $this->event->archived_at->addDays(30)->format('M d, Y');

        return "This event was archived on {$archivedAt} and is now read-only. It will be permanently deleted on {$deletionDate}.";
    }

    public function render()
    {
        return view('livewire.org.events.event-details')->layout('components.layouts.event-detail', [
            'eventId' => $this->eventId,
            'event' => $this->event
        ]);
    }
}
