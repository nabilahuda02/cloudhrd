<?php

class RegistrationTest extends TestCase {

    /**
     * To check during registration
     * 
     * USER TABLE
     * - user is inserted
     * - confirmed = 0
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

	public function testWithoutResellerCode()
	{
		$this->resetDb();
		$crawler = $this->action('POST', 'AuthController@store', [
			'name' => 'test',
			'domain' => 'test',
			'username' => 'testing',
			'email' => 'testing@example.com',
			'password' => 'test123',
			'password_confirmation' => 'test123'
		]);

		// user assertions
		$this->assertRedirectedToAction('AuthController@login');
		$this->assertTrue(($user = User::where('name', 'test')->first()) !== null);
		$this->assertTrue($user->confirmed === '0');
		$this->assertTrue(Hash::check('test123', $user->password));
		$this->assertTrue($user->database !== null);
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

	public function testWithResellerCode()
	{
		$this->assertTrue(($sales = User::where('name', 'Sales')->first()) !== null);

		$crawler = $this->action('POST', 'AuthController@store', [
			'name' => 'test2',
			'domain' => 'test2',
			'username' => 'testing2',
			'email' => 'testing2@example.com',
			'password' => 'test123',
			'password_confirmation' => 'test123',
			'reseller_code' => $sales->reseller_code
		]);

		// user assertions
		$this->assertRedirectedToAction('AuthController@login');
		$this->assertTrue(($user = User::where('name', 'test2')->first()) !== null);
		$this->assertTrue($user->confirmed === '0');
		$this->assertTrue(Hash::check('test123', $user->password));
		$this->assertTrue($user->database !== null);
		$this->assertTrue($user->organization_unit_id === '1');
		$this->assertTrue($user->roles->lists('id') === ['7']);

		// // subscription assertions
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
