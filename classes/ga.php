<?php
/**
 *
 *
 * @author Ethan Liu <ethan@creativecrap.com>
 * @copyright Creativecrap.com, 24 May, 2013
 **/

require_once dirname(__FILE__) . '/../libs/php-ga/src/autoload.php';

use UnitedPrototype\GoogleAnalytics;

Class GoogleAnalyticsTracker {
	public $account = '';
	public $domain = '';

	private $tracker;
	private $visitor;
	private $session;
	private $page;
	private $event;

	public function __construct($account, $domain) {
		if ($account) {
			$this->account = $account;
		}
		if ($domain) {
			$this->domain = $domain;
		}

		if ($this->account && $this->domain) {
			$this->tracker = new GoogleAnalytics\Tracker($this->account, $this->domain);
		}
	}

	public function addPageView($path, $title = '') {
		if ($this->page || empty($path)) {
			return;
		}

		$this->page = new GoogleAnalytics\Page($path);
		if ($title) {
			$this->page->setTitle(title);
		}

		$this->tracker->trackPageview($this->page, $this->getSession(), $this->getVisitor());
	}

	public function addEvent($category = '', $action = '', $label = '', $value = '', $noninteraction = null) {
		if (!$this->event) {
			$this->event = new GoogleAnalytics\Event();
		}

		if ($category) {
			$this->event->setCategory($category);
		}
		if ($action) {
			$this->event->setAction($action);
		}
		if ($label) {
			$this->event->setLabel($label);
		}
		if ($value) {
			$this->event->setValue($value);
		}
		if ($noninteraction !== null) {
			$this->event->setNoninteraction($noninteraction);
		}

		$this->tracker->trackEvent($this->event, $this->getSession(), $this->getVisitor());
	}

	private function getVisitor() {
		if (!$this->visitor) {
			$this->visitor = new GoogleAnalytics\Visitor();
			$this->visitor->setIpAddress($_SERVER['REMOTE_ADDR']);
			$this->visitor->setUserAgent($_SERVER['HTTP_USER_AGENT']);
			// $this->visitor->setScreenResolution('1024x768');
		}

		return $this->visitor;
	}

	private function getSession() {
		if (!$this->session) {
			$this->session = new GoogleAnalytics\Session();
		}
		return $this->session;
	}

}
