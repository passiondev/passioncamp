<?php

namespace Tests\Feature;

use App\OrgItem;
use Tests\TestCase;
use App\Organization;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrganizationNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrganizationNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Notification::fake();

        factory(Organization::class, 10)->create()->each(function ($organization) {
            $organization->tickets()->saveMany(factory(OrgItem::class, 3)->make(['org_type' => 'ticket']));
        });
    }

    public function test_a_notification_can_be_sent_to_active_organizations()
    {
        $inactiveOrganization = factory(Organization::class)->create();

        $this->post('/admin/notifications', [
            'subject' => 'This is my message',
        ]);

        Notification::assertSentTo(Organization::active()->get(), OrganizationNotification::class, function ($notification) {
            return $notification->toArray(null) === [
                'subject' => 'This is my message',
            ];
        });

        Notification::assertNotSentTo($inactiveOrganization, OrganizationNotification::class);
    }
}
