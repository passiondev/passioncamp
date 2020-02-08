<?php

namespace Tests\Feature;

use App\User;
use App\OrgItem;
use Tests\TestCase;
use App\Organization;
use JMac\Testing\Traits\HttpTestAssertions;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrganizationNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\OrganizationNotificationController;

class OrganizationNotificationTest extends TestCase
{
    use HttpTestAssertions;
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Notification::fake();

        factory(Organization::class, 10)->create()->each(function ($organization) {
            $organization->tickets()->saveMany(factory(OrgItem::class, 3)->make(['org_type' => 'ticket']));
        });

        $this->actingAs(factory(User::class)->state('superAdmin')->create());
    }

    public function test_middleware_applied()
    {
        $this->assertActionUsesMiddleware(OrganizationNotificationController::class, 'store', 'auth');
        $this->assertActionUsesMiddleware(OrganizationNotificationController::class, 'store', 'can:super');
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
