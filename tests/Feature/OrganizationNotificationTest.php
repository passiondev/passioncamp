<?php

namespace Tests\Feature;

use App\Http\Controllers\OrganizationNotificationController;
use App\Notifications\OrganizationNotification;
use App\Organization;
use App\OrgItem;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use JMac\Testing\Traits\HttpTestAssertions;
use Tests\TestCase;

class OrganizationNotificationTest extends TestCase
{
    use HttpTestAssertions;
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        factory(Organization::class, 10)->create()->each(function ($organization) {
            $organization->tickets()->saveMany(factory(OrgItem::class, 3)->make(['org_type' => 'ticket']));
        });
    }

    public function test_a_notification_can_be_sent_to_active_organizations()
    {
        Notification::fake();
        $this->assertActionUsesMiddleware(OrganizationNotificationController::class, 'store', 'auth');
        $this->assertActionUsesMiddleware(OrganizationNotificationController::class, 'store', 'can:super');
        $this->actingAs(factory(User::class)->state('superAdmin')->create());

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

    public function test_a_church_admin_can_see_notifications()
    {
        /** @var \App\Organization $organization */
        $organization = Organization::first();

        $organization->notify(new OrganizationNotification('this is a test'));

        /** @var \App\User $churchAdmin */
        $churchAdmin = $organization->users()->save(factory(User::class)->state('churchAdmin')->make());

        $response = $this->actingAs($churchAdmin)->get('/account/dashboard');

        $response->assertOk()->assertSeeText('this is a test');
    }
}
