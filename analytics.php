<?php
/*
Plugin Name: Wordpress Google Analytics Reports
Plugin URI: http://www.imthi.com/wp-analytics/
Description: This plugin helps to get Google Analytics reports overview to your dashboard using Google Analytics Data API.
Author: Imthiaz Rafiq
Version: 1.0.7
Author URI: http://www.imthi.com/
*/
//SVN: $Id: analytics.php 902561 2014-04-25 10:44:30Z imthiaz $


class googleAnalyticsPlugin {
	
	var $username;
	
	var $password;
	
	var $profile;
	
	var $authToken;
	
	function googleAnalyticsPlugin() {
		$this->enabled = false;
		$this->username = get_option ( 'wp-analytics-login-email' );
		$this->password = get_option ( 'wp-analytics-login-password' );
		$this->profile = get_option ( 'wp-analytics-profile' );
		add_action ( 'admin_menu', array ( 
			&$this, 
			'addAdminMenu' 
		) );
	}
	
	function addAdminMenu() {
		if (function_exists ( 'add_submenu_page' )) {
			add_submenu_page ( 'plugins.php', __ ( 'Analytics Configuration' ), __ ( 'Analytics Configuration' ), 'manage_options', 'wp-analytics-options', array ( 
				&$this, 
				'pluginOption' 
			) );
			if (! empty ( $this->profile )) {
				add_submenu_page ( 'index.php', __ ( 'Analytics Report' ), __ ( 'Analytics Report' ), 'manage_options', 'wp-analytics-reports', array ( 
					&$this, 
					'reportsPage' 
				) );
			}
		}
	
	}
	
	function reportsPage() {
		include_once ('analytics-report.php');
	}
	
	function pluginOption() {
		if (!$this->getRequirements ()) {
			print "This plugin needs curl and xml modules to be enabled in PHP. Please check if both are enabled.";
			return;
		}
		if (isset ( $_POST ['submit'] )) {
			if (function_exists ( 'current_user_can' ) && ! current_user_can ( 'manage_options' )) {
				die ( __ ( 'Cheatin&#8217; uh?' ) );
			}
			update_option ( 'wp-analytics-login-email', $_POST ['wp-analytics-login-email'] );
			update_option ( 'wp-analytics-login-password', $_POST ['wp-analytics-login-password'] );
			update_option ( 'wp-analytics-profile', $_POST ['wp-analytics-profile'] );
			$this->username = get_option ( 'wp-analytics-login-email' );
			$this->password = get_option ( 'wp-analytics-login-password' );
			$this->profile = get_option ( 'wp-analytics-profile' );
		}
		include_once ('analytics-option.php');
	}
	
	function getRequirements() {
		if (! defined ( 'XML_ERROR_NONE' )) {
			return FALSE;
		}
		if (! function_exists ( 'curl_init' )) {
			return FALSE;
		}
		return TRUE;
	}
	
	function getAuthToken() {
		if (empty ( $this->username ) || empty ( $this->password )) {
			return FALSE;
		}
		if (! empty ( $this->authToken )) {
			return $this->authToken;
		}
		$data = array ( 
			'accountType' => 'GOOGLE', 
			'Email' => $this->username, 
			'Passwd' => $this->password, 
			'source' => 'wp-analytics 1.0', 
			'service' => 'analytics' 
		);
		
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, "https://www.google.com/accounts/ClientLogin" );
		//curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, TRUE );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt ( $ch, CURLOPT_POST, TRUE );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
		
		$output = curl_exec ( $ch );
		$info = curl_getinfo ( $ch );
		if ($info ['http_code'] == 200) {
			preg_match ( '/Auth=(.*)/', $output, $matches );
			if (isset ( $matches [1] )) {
				$this->authToken = $matches [1];
			}
		}
		curl_close ( $ch );
		return $this->authToken;
	}
	
	function fetchFeed($url) {
		
		if (empty ( $this->authToken )) {
			$this->getAuthToken ();
			if (empty ( $this->authToken )) {
				return FALSE;
			}
		}
		
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		//curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		$headers = array ( 
			"Authorization: GoogleLogin auth={$this->authToken}" 
		);
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
		
		$output = curl_exec ( $ch );
		$info = curl_getinfo ( $ch );
		if ($info ['http_code'] == 200) {
			return $output;
		} else {
			return FALSE;
		}
	}
	
	function getProfiles() {
		$feedUrl = "https://www.google.com/analytics/feeds/accounts/default";
		if (($feedData = $this->fetchFeed ( $feedUrl )) === FALSE) {
			return array ();
		}
		$doc = new DOMDocument ( );
		$doc->loadXML ( $feedData );
		$entries = $doc->getElementsByTagName ( 'entry' );
		$profiles = array ();
		foreach ( $entries as $entry ) {
			$tableId = $entry->getElementsByTagName ( 'tableId' )->item ( 0 )->nodeValue;
			$profiles [$tableId] = array ();
			$profiles [$tableId] ["tableId"] = $tableId;
			$profiles [$tableId] ["title"] = $entry->getElementsByTagName ( 'title' )->item ( 0 )->nodeValue;
			$profiles [$tableId] ["entryid"] = $entry->getElementsByTagName ( 'id' )->item ( 0 )->nodeValue;
			$properties = $entry->getElementsByTagName ( 'property' );
			foreach ( $properties as $property ) {
				$profiles [$tableId] ['property'] [$property->getAttribute ( 'name' )] = $property->getAttribute ( 'value' );
			}
		}
		return $profiles;
	}
	
	function getAnalyticRecords($startDate, $endDate, $dimensions, $metrics, $sort = '', $maxResults = '') {
		
		$url = 'https://www.google.com/analytics/feeds/data';
		$url .= "?ids=" . $this->profile;
		$url .= "&start-date=" . $startDate;
		$url .= "&end-date=" . $endDate;
		$url .= "&dimensions=" . $dimensions;
		$url .= "&metrics=" . $metrics;
		if (! empty ( $sort )) {
			$url .= "&sort=" . $sort;
		}
		if (! empty ( $maxResults )) {
			$url .= "&max-results=" . $maxResults;
		}
		if (($feedData = $this->fetchFeed ( $url )) === FALSE) {
			return array ();
		}
		$doc = new DOMDocument ( );
		$doc->loadXML ( $feedData );
		$results = array ();
		
		$aggregates = $doc->getElementsByTagName ( 'aggregates' );
		foreach ( $aggregates as $aggregate ) {
			$metrics = $aggregate->getElementsByTagName ( 'metric' );
			foreach ( $metrics as $metric ) {
				$results ['aggregates'] ['metric'] [$metric->getAttribute ( 'name' )] = $metric->getAttribute ( 'value' );
			}
		}
		
		$entries = $doc->getElementsByTagName ( 'entry' );
		foreach ( $entries as $entry ) {
			$record = array ();
			$record ["title"] = $entry->getElementsByTagName ( 'title' )->item ( 0 )->nodeValue;
			$dimensions = $entry->getElementsByTagName ( 'dimension' );
			foreach ( $dimensions as $dimension ) {
				$record ['dimension'] [$dimension->getAttribute ( 'name' )] = $dimension->getAttribute ( 'value' );
			}
			$metrics = $entry->getElementsByTagName ( 'metric' );
			foreach ( $metrics as $metric ) {
				$record ['metric'] [$metric->getAttribute ( 'name' )] = $metric->getAttribute ( 'value' );
			}
			$results ['entry'] [] = $record;
		}
		return $results;
	}

}
$wpGoogleAnalytics = new googleAnalyticsPlugin ( );
