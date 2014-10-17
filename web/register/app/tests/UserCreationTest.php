<?php

class UserCreationTest extends TestCase {

    /**
     * To check during registration
     * 
     * USER TABLE
     * - user is inserted
     * - confirmed = 1
     * - password is set correctly
     * - database is set correctly
     * - organization_unit is set
     * - roles set to [7]
     *
     * SUBSCRIPTION TABLE
     * - entry into table
     * - is_trial = true
     * - end_date
     */

    public function testCreateAdmin()
    {
        $this->be(User::find(1));
        $crawler = $this->action('POST', 'UsersController@store', [
            'name' => 'testadmin',
            'username' => 'testadmin',
            'email' => 'testadmin@example.com',
            'organization_unit_id' => 1,
            'roles[]' => 1,
            'password' => 'testadmin',
            'password_confirmation' => 'testadmin'
        ]);

        // user assertions
        $this->assertRedirectedToAction('UsersController@index');
        $this->assertTrue(($user = User::where('name', 'testadmin')->first()) !== null);
        $this->assertTrue($user->confirmed === '1');
        $this->assertTrue(Hash::check('testadmin', $user->password));
        $this->assertTrue($user->organization_unit_id === '1');
        $this->assertTrue($user->roles->lists('id') === ['1']);
    }

    public function testCreateReseller()
    {
        $this->be(User::find(1));
        $crawler = $this->action('POST', 'UsersController@store', [
            'name' => 'testreseller',
            'username' => 'testreseller',
            'email' => 'testreseller@example.com',
            'organization_unit_id' => 1,
            'roles[]' => 6,
            'password' => 'testreseller',
            'password_confirmation' => 'testreseller'
        ]);

        // user assertions
        $this->assertRedirectedToAction('UsersController@index');
        $this->assertTrue(($user = User::where('name', 'testreseller')->first()) !== null);
        $this->assertTrue($user->confirmed === '1');
        $this->assertTrue(Hash::check('testreseller', $user->password));
        $this->assertTrue($user->organization_unit_id === '1');
        $this->assertTrue($user->roles->lists('id') === ['6']);
        $this->assertTrue($user->reseller_code !== null);
    }

    public function testCreateUserWithoutResellerId()
    {
        $this->be(User::find(1));
        $crawler = $this->action('POST', 'UsersController@store', [
            'name' => 'testuser3',
            'username' => 'testuser3',
            'email' => 'testuser3@example.com',
            'organization_unit_id' => 1,
            'roles[]' => 7,
            'reseller_id' => '',
            'password' => 'testuser3',
            'password_confirmation' => 'testuser3'
        ]);

        // user assertions
        $this->assertRedirectedToAction('UsersController@index');
        $this->assertTrue(($user = User::where('name', 'testuser3')->first()) !== null);
        $this->assertTrue($user->confirmed === '1');
        $this->assertTrue(Hash::check('testuser3', $user->password));
        $this->assertTrue($user->organization_unit_id === '1');
        $this->assertTrue($user->roles->lists('id') === ['7']);

         // subscription assertions
         $subscriptionConfig = Config::get('subscriptions');
         $this->assertTrue($subscriptionConfig !== []);
         $this->assertTrue(($subscription = Subscription::where('user_id', $user->id)->first()) !== null);
         $this->assertTrue($subscription->is_trial === '1');
         $this->assertTrue(Carbon\Carbon::createFromTimeStamp(strtotime($subscription->start_date))->toDateString() === date('Y-m-d'));
         $this->assertTrue(Carbon\Carbon::createFromTimeStamp(strtotime($subscription->end_date))->
             diffInMonths(Carbon\Carbon::createFromTimeStamp(strtotime($subscription->start_date)))
                 === $subscriptionConfig['trial_without_reseller_code']);
    }

    public function testCreateUserWithResellerId()
    {
        $this->be(User::find(1));
        $crawler = $this->action('POST', 'UsersController@store', [
            'name' => 'testuser4',
            'username' => 'testuser4',
            'email' => 'testuser4@example.com',
            'organization_unit_id' => 1,
            'roles[]' => 7,
            'reseller_id' => 2,
            'password' => 'testuser4',
            'password_confirmation' => 'testuser4'
        ]);

        // user assertions
        $this->assertRedirectedToAction('UsersController@index');
        $this->assertTrue(($user = User::where('name', 'testuser4')->first()) !== null);
        $this->assertTrue($user->confirmed === '1');
        $this->assertTrue(Hash::check('testuser4', $user->password));
        $this->assertTrue($user->organization_unit_id === '1');
        $this->assertTrue($user->roles->lists('id') === ['7']);
        $this->assertTrue($user->reseller_id === '2');

         // subscription assertions
         $subscriptionConfig = Config::get('subscriptions');
         $this->assertTrue($subscriptionConfig !== []);
         $this->assertTrue(($subscription = Subscription::where('user_id', $user->id)->first()) !== null);
         $this->assertTrue($subscription->is_trial === '1');
         $this->assertTrue(Carbon\Carbon::createFromTimeStamp(strtotime($subscription->start_date))->toDateString() === date('Y-m-d'));
         $this->assertTrue(Carbon\Carbon::createFromTimeStamp(strtotime($subscription->end_date))->
             diffInMonths(Carbon\Carbon::createFromTimeStamp(strtotime($subscription->start_date)))
                 === $subscriptionConfig['trial_with_reseller_code']);
    }

}
