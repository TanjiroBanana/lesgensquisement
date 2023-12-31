<?php

/**
 * Handles server side tasks during PPCP onboarding.
 */
class SWPM_PayPal_PPCP_Onboarding_Serverside {

	public function __construct() {

		//Setup AJAX request handler for the onboarding process.
		add_action( 'wp_ajax_swpm_handle_onboarded_callback_data', array(&$this, 'handle_onboarded_callback_data' ) );
		add_action( 'wp_ajax_nopriv_handle_onboarded_callback_data', array(&$this, 'handle_onboarded_callback_data' ) );

	}

	public function handle_onboarded_callback_data(){
		//Handle the data sent by PayPal after the onboarding process.
		//The get_option('swpm_ppcp_sandbox_connect_query_args') will give you the query args that you sent to the PayPal onboarding page

		SwpmLog::log_simple_debug( 'Onboarding step: handle_onboarded_callback_data.', true );

		//Get the data from the request
		$data = isset( $_POST['data'] ) ? stripslashes_deep( $_POST['data'] ) : array();
		if ( empty( $data ) ) {
			wp_send_json(
				array(
					'success' => false,
					'msg'  => __( 'Empty data received.', 'simple-membership' ),
				)
			);
		}

        $data_array = json_decode($data, true);
        SwpmLog::log_array_data_to_debug( $data_array, true );//Debugging purpose

		//Check nonce.
        $nonce_string = SWPM_PayPal_PPCP_Onboarding::$account_connect_string;
		if ( ! check_ajax_referer( $nonce_string, '_wpnonce', false ) ) {
			wp_send_json(
				array(
					'success' => false,
					'msg'  => __( 'Nonce check failed. The page was most likely cached. Please reload the page and try again.', 'simple-membership' ),
				)
			);
			exit;
		}

		//Get the environment mode.
		$environment_mode = isset( $data_array['environment'] ) ? $data_array['environment'] : 'production';

		//=== Generate the access token using the shared id and auth code. ===
        $access_token = $this->generate_token_using_shared_id( $data_array['sharedId'], $data_array['authCode'], $environment_mode);
		if ( ! $access_token ) {
			//Failed to generate token.
			wp_send_json(
				array(
					'success' => false,
					'msg'  => __( 'Failed to generate access token. check debug log file for any error message.', 'simple-membership' ),
				)
			);
			exit;
		}

		//=== Get the seller API credentials using the access token. ===
		//SwpmLog::log_simple_debug( 'Onboarding step: access token generated successfully. Token: ' . $access_token, true );//Debug purpose only
		$seller_api_credentials = $this->get_seller_api_credentials_using_token( $access_token, $environment_mode );
		if ( ! $seller_api_credentials ) {
			//Failed to get seller API credentials.
			wp_send_json(
				array(
					'success' => false,
					'msg'  => __( 'Failed to get seller API credentials. check debug log file for any error message.', 'simple-membership' ),
				)
			);
		}
		//SwpmLog::log_array_data_to_debug( $seller_api_credentials, true );//TODO - Debugging purpose

		//Save the credentials to the database.
		$this->save_seller_api_credentials( $seller_api_credentials, $environment_mode);

		//=== Bearer token ===
		//Let's use the already generated access token throughout the onboarding process.
		$bearer_token = $access_token;
		//SwpmLog::log_simple_debug( 'Onboarding step: using access token from the previous step. Token: ' . $bearer_token, true );//Debug purpose only

		//=== Seller account status ===
		$seller_account_status = $this->get_seller_account_status_data_using_bearer_token($bearer_token, $seller_api_credentials, $environment_mode );
		SwpmLog::log_array_data_to_debug( $seller_account_status, true );//TODO - Debugging purpose
		if( ! $seller_account_status ){
			//Failed to get seller account status.
			wp_send_json(
				array(
					'success' => false,
					'msg'  => __( 'Failed to get seller account status. check debug log file for any error message.', 'simple-membership' ),
				)
			);			
		}

		//Save the seller paypal email to the database.
		//The paypal email address of the seller will be available in the 'tracking_id' field of the seller_account_status array.
		$seller_paypal_email = isset( $seller_account_status['tracking_id'] )? $seller_account_status['tracking_id'] : '';
		$this->save_seller_paypal_email( $seller_paypal_email, $environment_mode );

		//Check if the seller account is limited or not.
		if( ! $seller_account_status['payments_receivable'] ){
			//Seller account is limited. Show a message to the seller.
			wp_send_json(
				array(
					'success' => false,
					'msg'  => __( 'Your PayPal account is limited so you cannot accept payment. Contact PaPal support or check your PayPal account inbox for an email from PayPal for the next steps to remove the account limit.', 'simple-membership' ),
				)
			);			
		}
		if( ! $seller_account_status['primary_email_confirmed'] ){
			//Seller account is limited. Show a message to the seller.
			wp_send_json(
				array(
					'success' => false,
					'msg'  => __( 'Your PayPal account email is not confirmed. Check your PayPal account inbox for an email from PayPal to confirm your PayPal email address.', 'simple-membership' ),
				)
			);			
		}

		//Webhooks will be created (if not already created) when the admin creates subsription payment buttons

		//Save the onboarding complete flag to the database.
		$settings = SwpmSettings::get_instance();
		$settings->set_value('paypal-ppcp-onboarding-'.$environment_mode, 'completed');
		$settings->save();

		//Delete any cached token using the old credentials (so it is forced to generate and cache a new one after onboarding (when new API call is made)))
		SWPM_PayPal_Bearer::delete_cached_token();
				
        SwpmLog::log_simple_debug( 'Successfully processed the handle_onboarded_callback_data. Environment mode: '.$environment_mode, true );

		//If everything is processed successfully, send the success response.
		wp_send_json( array( 'success' => true, 'msg' => 'Succedssfully processed the handle_onboarded_callback_data.' ) );
		exit;

	}


	/*
	 * Gets the seller's account status data. So we can check if payments_receivable flag is true and primary_email_confirmed flag is true
	 * Returns an array with client_id and client_secret or false otherwise.
	 */
	public function get_seller_account_status_data_using_bearer_token($bearer_token, $seller_api_credentials, $environment_mode = 'production'){
		SwpmLog::log_simple_debug( 'Onboarding step: get_seller_account_status_data. Environment mode: ' . $environment_mode, true );

		$api_base_url = $this->get_api_base_url_by_environment_mode( $environment_mode );
		$partner_id = $this->get_partner_id_by_environment_mode( $environment_mode );

		$url = trailingslashit( $api_base_url ) . 'v1/customer/partners/' . $partner_id . '/merchant-integrations/' . $seller_api_credentials['payer_id'];	
		$args = array(
			'method'  => 'GET',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bearer ' . $bearer_token,
				'PayPal-Partner-Attribution-Id' => 'TipsandTricks_SP_PPCP',
			),
		);
		//Debug purpose only
		//SwpmLog::log_simple_debug( 'PayPal API request headers for getting seller account status: ', true );
		//SwpmLog::log_array_data_to_debug( $args, true);		

		$response = $this->send_request_by_url_and_args( $url, $args );

		if ( is_wp_error( $response ) ) {
			//WP could not post the request.
			$error_msg = $response->get_error_message();//Get the error from the WP_Error object.
			SwpmLog::log_simple_debug( 'Failed to post the request to the PayPal API. Error: ' . $error_msg, false );
			return false;
		}

		$json = json_decode( wp_remote_retrieve_body( $response ) );
		$status_code = (int) wp_remote_retrieve_response_code( $response );
		if ( $status_code !== 200 ) {
			//PayPal API returned an error.
			$response_body = wp_remote_retrieve_body( $response );
			SwpmLog::log_simple_debug( 'PayPal API returned an error. Status Code: ' . $status_code . ' Response Body: ' . $response_body, false );
			return false;
		}

		if ( ! isset( $json->payments_receivable ) || ! isset( $json->primary_email_confirmed ) ) {
			//Seller status not found. Log error.
			if (isset( $json->error )) {
				//Try to get the error descrption (if present)
				$error_msg = isset($json->error_description)? $json->error_description : $json->error;
			} else {
				$error_msg = 'The payments_receivable and primary_email_confirmed flags are not set.';
			}
			SwpmLog::log_simple_debug( 'Failed to get seller PayPal account status. Status code: '.$status_code.', Error msg: ' . $error_msg, false );
			return false;
		}

		//Success. return the credentials.
		return array(
			'merchant_id' => $json->merchant_id,
			'tracking_id' => $json->tracking_id,/* This will be the paypal account email address */
			'payments_receivable' => $json->payments_receivable,
			'primary_email_confirmed' => $json->primary_email_confirmed,
		);

	}

	public function save_seller_paypal_email( $seller_paypal_email, $environment_mode = 'production' ) {
		//This is saved as a separate method because the seller paypal email is not available in the get seller api credentials call.
		//The seller paypal email is available in the get seller account status call.
		$settings = SwpmSettings::get_instance();

		if( $environment_mode == 'sandbox' ){
			$settings->set_value('paypal-sandbox-seller-paypal-email', $seller_paypal_email);
		} else {
			$settings->set_value('paypal-live-seller-paypal-email', $seller_paypal_email);
		}

		$settings->save();
		SwpmLog::log_simple_debug( 'Seller PayPal email address ('.$seller_paypal_email.') saved successfully (environment mode: '.$environment_mode.').', true );
	}

	public function save_seller_api_credentials( $seller_api_credentials, $environment_mode = 'production' ) {
		// Save the API credentials to the database.
		$settings = SwpmSettings::get_instance();

		if( $environment_mode == 'sandbox' ){
			//Sandobx mode
			$settings->set_value('paypal-sandbox-client-id', $seller_api_credentials['client_id']);
			$settings->set_value('paypal-sandbox-secret-key', $seller_api_credentials['client_secret']);
			$settings->set_value('paypal-sandbox-seller-merchant-id', $seller_api_credentials['payer_id']);//Seller Merchant ID
		} else {
			//Production mode
			$settings->set_value('paypal-live-client-id', $seller_api_credentials['client_id']);
			$settings->set_value('paypal-live-secret-key', $seller_api_credentials['client_secret']);
			$settings->set_value('paypal-live-seller-merchant-id', $seller_api_credentials['payer_id']);//Seller Merchant ID
		}

		$settings->save();
		SwpmLog::log_simple_debug( 'Seller API credentials (environment mode: '.$environment_mode.') saved successfully.', true );
	}

	public static function reset_seller_api_credentials( $environment_mode = 'production' ) {
		// Save the API credentials to the database.
		$settings = SwpmSettings::get_instance();

		if( $environment_mode == 'sandbox' ){
			//Sandobx mode
			$settings->set_value('paypal-sandbox-client-id', '');
			$settings->set_value('paypal-sandbox-secret-key', '');
			$settings->set_value('paypal-sandbox-seller-merchant-id', '');//Seller Merchant ID
			$settings->set_value('paypal-sandbox-seller-paypal-email', '');//Seller PayPal Email
		} else {
			//Production mode
			$settings->set_value('paypal-live-client-id', '');
			$settings->set_value('paypal-live-secret-key', '');
			$settings->set_value('paypal-live-seller-merchant-id', '');//Seller Merchant ID
			$settings->set_value('paypal-live-seller-paypal-email', '');//Seller PayPal Email
		}

		//Reset the onboarding complete flag (for the corresponding mode) to the database.
		$settings->set_value('paypal-ppcp-onboarding-'.$environment_mode, '');

		//Save the settings
		$settings->save();
		SwpmLog::log_simple_debug( 'Seller API credentials (environment mode: '.$environment_mode.') reset/removed successfully.', true );
	}

	/**
	 * Generates a token using the shared_id and auth_token and seller_nonce. Used during the onboarding process.
	 *
	 * @param string $shared_id The shared id.
	 * @param string $auth_code The auth code.
	 * @param string $environment_mode The environment mode. sandbox or production.
	 * 
	 * Returns the token or false otherwise.
	 */
	public function generate_token_using_shared_id( $shared_id, $auth_code, $environment_mode = 'production' ) {
		SwpmLog::log_simple_debug( 'Onboarding step: generate_token_using_shared_id. Environment mode: ' . $environment_mode, true );

		if( isset($environment_mode) && $environment_mode == 'sandbox' ){
			$query_args = get_option('swpm_ppcp_sandbox_connect_query_args');
			$seller_nonce = isset($query_args['sellerNonce']) ? $query_args['sellerNonce'] : '';
		} else {
			//TODO - test after production account is created.
			$query_args = get_option('swpm_ppcp_production_connect_query_args');
			$seller_nonce = isset($query_args['sellerNonce']) ? $query_args['sellerNonce'] : '';
		}
		SwpmLog::log_simple_debug( 'Seller nonce value: ' . $seller_nonce, true );

		$api_base_url = $this->get_api_base_url_by_environment_mode( $environment_mode );

		$url = trailingslashit( $api_base_url ) . 'v1/oauth2/token/';

		//Note: we don't have the seller merchant ID yet. So cannot use the auth assertion header.
		$args = array(
			'method'  => 'POST',
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( $shared_id . ':' ),
			),
			'body' => array(
				'grant_type' => 'authorization_code',
				'code' => $auth_code,
				'code_verifier' => $seller_nonce,
			),
		);

		//SwpmLog::log_array_data_to_debug( $args, true);//Debugging purpose
		$response = $this->send_request_by_url_and_args( $url, $args );
		//SwpmLog::log_array_data_to_debug( $response, true);//Debugging purpose

		if ( is_wp_error( $response ) ) {
			//WP could not post the request.
			$error_msg = $response->get_error_message();//Get the error from the WP_Error object.
			SwpmLog::log_simple_debug( 'Failed to post the request to the PayPal API. Error: ' . $error_msg, false );
			return false;
		}

		$json = json_decode( $response['body'] );
		$status_code = (int) wp_remote_retrieve_response_code( $response );//HTTP response code (ex: 400)
		if ( ! isset( $json->access_token ) ) {
			//No token found. Log error.
			if (isset( $json->error )) {
				//Try to get the error descrption (if present)
				$error_msg = isset($json->error_description) ? $json->error_description : $json->error;
			} else {
				$error_msg = 'No token found.';
			}
			SwpmLog::log_simple_debug( 'Failed to generate token. Status code: '.$status_code.', Error msg: ' . $error_msg, false );
			return false;
		}

		//Success. return the token.
		return (string) $json->access_token;
	}

	/*
	 * Gets the seller's API credentials using the access token.
	 * Returns an array with client_id and client_secret or false otherwise.
	 */
	public function get_seller_api_credentials_using_token($access_token, $environment_mode = 'production'){
		SwpmLog::log_simple_debug( 'Onboarding step: get_seller_api_credentials_using_token. Environment mode: ' . $environment_mode, true );

		$api_base_url = $this->get_api_base_url_by_environment_mode( $environment_mode );
		$partner_merchant_id = $this->get_partner_id_by_environment_mode( $environment_mode );

		$url = trailingslashit( $api_base_url ) . 'v1/customer/partners/' . $partner_merchant_id . '/merchant-integrations/credentials/';
		
		//Note: we don't have the seller merchant ID yet. So cannot use the auth assertion header.
		$args = array(
			'method'  => 'GET',
			'headers' => array(
				'Authorization' => 'Bearer ' . $access_token,
				'Content-Type'  => 'application/json',
			),
		);

		$response = $this->send_request_by_url_and_args( $url, $args );

		if ( is_wp_error( $response ) ) {
			//WP could not post the request.
			$error_msg = $response->get_error_message();//Get the error from the WP_Error object.
			SwpmLog::log_simple_debug( 'Failed to post the request to the PayPal API. Error: ' . $error_msg, false );
			return false;
		}

		$json = json_decode( $response['body'] );
		$status_code = (int) wp_remote_retrieve_response_code( $response );

		if ( ! isset( $json->client_id ) || ! isset( $json->client_secret ) ) {
			//Seller API credentials not found. Log error.
			if (isset( $json->error )) {
				//Try to get the error descrption (if present)
				$error_msg = isset($json->error_description)? $json->error_description : $json->error;
			} else {
				$error_msg = 'No client_id or client_secret found.';
			}
			SwpmLog::log_simple_debug( 'Failed to get seller API credentials. Status code: '.$status_code.', Error msg: ' . $error_msg, false );
			return false;
		}

		//Success. return the credentials.
		return array(
			'client_id' => $json->client_id,
			'client_secret' => $json->client_secret,
			'payer_id' => $json->payer_id,
		);

	}

	/**
	 * Performs a request to the PayPal API using URL and arguments.
	 */
	public function send_request_by_url_and_args( $url, $args ) {

		$args['timeout'] = 30;

		$args = apply_filters( 'swpm_ppcp_onboarding_request_args', $args, $url );
		if ( ! isset( $args['headers']['PayPal-Partner-Attribution-Id'] ) ) {
			$args['headers']['PayPal-Partner-Attribution-Id'] = 'TipsandTricks_SP_PPCP';
		}

		//=== Debug purposes ===
		//SwpmLog::log_simple_debug( '----- PayPal API request header -----', true );
		//SwpmLog::log_array_data_to_debug( $args, true );
		//=== End of debug purposes ===

		$response = wp_remote_get( $url, $args );

		//=== Debug purposes ===
		//PayPal debug id
		$paypal_debug_id = wp_remote_retrieve_header( $response, 'paypal-debug-id' );
		SwpmLog::log_simple_debug( 'PayPal Debug ID from the REST API response: ' . $paypal_debug_id, true );
		//Debug the request body
		//$response_body = wp_remote_retrieve_body( $response );
		//$response_body_json_decoded = json_decode( $response_body );
		//SwpmLog::log_array_data_to_debug( $response_body_json_decoded, true );
		//Debug the full response (header and body)
		//$response_full_var_exported = var_export( $response, true );
		//SwpmLog::log_simple_debug( 'PayPal API response body: ' . $debug_api_response, true );
		//=== End of debug purposes ===

		return $response;
	}

	public function get_api_base_url_by_environment_mode( $environment_mode = 'production' ) {
		if ($environment_mode == 'production') {
			return SWPM_PayPal_Main::$api_base_url_production;
		} else {
			return SWPM_PayPal_Main::$api_base_url_sandbox;
		}
	}

	public function get_partner_id_by_environment_mode( $environment_mode = 'production' ) {
		if ($environment_mode == 'production') {
			return SWPM_PayPal_Main::$partner_id_production;
		} else {
			return SWPM_PayPal_Main::$partner_id_sandbox;
		}
	}

}