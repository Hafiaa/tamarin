<?php

namespace App\Filament\Admin\Resources\TestimonialResource\Pages;

use App\Filament\Admin\Resources\TestimonialResource;
use App\Models\Testimonial;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CreateTestimonial extends CreateRecord
{
    protected static string $resource = TestimonialResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure user_id is set from the authenticated admin
        if (empty($data['user_id']) && Auth::check()) {
            $data['user_id'] = Auth::id();
        }

        // Ensure status is set
        if (empty($data['status'])) {
            $data['status'] = 'pending_admin_approval';
        }

        // Ensure is_featured is set
        if (!isset($data['is_featured'])) {
            $data['is_featured'] = false;
        }

        // Ensure rating is set
        if (empty($data['rating'])) {
            $data['rating'] = 5;
        }

        // Ensure content is set (required field)
        if (empty($data['content'])) {
            $data['content'] = 'No content provided';
        }

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Create the testimonial with all the data
        return Testimonial::create([
            'user_id' => $data['user_id'] ?? null,
            'reservation_id' => $data['reservation_id'] ?? null,
            'content' => $data['content'] ?? 'No content provided',
            'rating' => (int)($data['rating'] ?? 5),
            'status' => $data['status'] ?? 'pending_admin_approval',
            'is_featured' => (bool)($data['is_featured'] ?? false),
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        // You can add any post-creation logic here if needed
    }
}
