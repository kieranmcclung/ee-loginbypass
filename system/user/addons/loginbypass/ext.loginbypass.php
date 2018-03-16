<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Loginbypass_ext
{
	var $name = 'Login Bypass';
	var $version = '1.0.0';
	var $description = 'Bypasses default ExpressionEngine login/logout messages';

	public function activate_extension()
	{
		/**
		 * As far as I understand, these two hooks take place after authentication
		 * so you don't need to worry about any of that
		 **/
		$this->add_hook('member_member_login_single');
		$this->add_hook('member_member_logout');
	}

	public function disable_extension()
	{
		ee()->db->where('class', __CLASS__);
		ee()->db->delete('extensions');
	}

	public function update_extension($current = '')
	{
		if ($current == '' || (version_compare($current, $this->version) === 0))
		{
			return false;
		}
	}

	public function member_member_login_single()
	{
		ee()->load->helper('url');
		
		// Change $url to wherever you need to redirect on login
		$url = site_url() . 'account';
		// Side note: this should be site_url('account') but it isn't working for my install

		redirect($url);
		return FALSE;
	}

	public function member_member_logout()
	{
		ee()->load->helper('url');

		// Change $url to wherever you need to redirect on logout
		$url = site_url();
		redirect($url);
		return FALSE;
	}

	private function add_hook($name, $priority = 10)
	{
		ee()->db->insert('extensions', 
			array(
				'class'    => __CLASS__,
				'method'   => $name,
				'hook'     => $name,
				'settings' => '',
				'priority' => $priority,
				'version'  => $this->version,
				'enabled'  => 'y'
			)
		);
	}
}