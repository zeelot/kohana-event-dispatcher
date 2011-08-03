# Kohana Event Dispatcher

A simple events-system for Kohana 3.x.

- **Author**: Jeremy Lindblom ([jeremeamia](https://github.com/jeremeamia))
- **Version**: 0.1
- **Compatible Kohana Version(s):** 3.1.x

## Disclaimer

Currently untested. Not for production use.

## Sample Usage

In this example, the event dispatcher will be used to implement part of a user
rewards system. In this case, a Rewarder object listens for an event signifying
the someone has accepted a Referral to the application. The rewarder then
rewards the referrer a specific amount of points.

### Instantiation

	$dispatcher = Event_Dispatcher::factory()
		->load_subscribers();

### Triggering an Event

	class Referral {
		protected $_dispatcher;
		/* ... */

		public function __construct(/* ... */, Interface_Dispatcher $dispatcher)
		{
			/* ... */
			$this->_dispatcher = $dispatcher;
		}

		/* ... */

		public function accept()
		{
			/* ... */
			$this->_dispatcher->trigger('referral.accepted', new Event($this));
			/* ... */
		}
	}

### Handling the Event

	class Subscriber_Rewarder implements Interface_Subscriber {
		public function get_listeners()
		{
			return array(
				'referral.accepted' => array($this, 'referral'),
			);
		}

		public function referral(Interface_Event $event)
		{
			$referral = $event->get_subject();
			if ($referral instanceof Referral)
			{
				$points = Kohana::config('rewards.points.referral');
				$referral->referrer->reward_points($points);
			}
		}
	}
