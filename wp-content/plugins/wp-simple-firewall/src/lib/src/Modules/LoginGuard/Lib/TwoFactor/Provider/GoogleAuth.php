<?php declare( strict_types=1 );

namespace FernleafSystems\Wordpress\Plugin\Shield\Modules\LoginGuard\Lib\TwoFactor\Provider;

use Dolondro\GoogleAuthenticator\{
	GoogleAuthenticator,
	Secret,
	SecretFactory
};
use FernleafSystems\Utilities\Data\Response\StdResponse;
use FernleafSystems\Wordpress\Plugin\Shield\ActionRouter\ActionData;
use FernleafSystems\Wordpress\Plugin\Shield\ActionRouter\Actions\MfaGoogleAuthToggle;
use FernleafSystems\Wordpress\Services\Services;
use FernleafSystems\Wordpress\Services\Utilities\URL;

class GoogleAuth extends AbstractShieldProvider {

	protected const SLUG = 'ga';

	/**
	 * @var Secret
	 */
	private $workingSecret;

	public function isProfileActive() :bool {
		return $this->hasValidSecret() && $this->hasValidatedProfile();
	}

	public function getJavascriptVars() :array {
		return [
			'ajax' => [
				'profile_ga_toggle' => ActionData::Build( MfaGoogleAuthToggle::class ),
			],
		];
	}

	protected function getUserProfileFormRenderData() :array {
		$con = self::con();
		$validatedProfile = $this->hasValidatedProfile();

		return Services::DataManipulation()->mergeArraysRecursive(
			parent::getUserProfileFormRenderData(),
			[
				'hrefs'   => [
					'qr_code_auth' => $validatedProfile ? '' : $this->getQrUrl(),
					//				'src_chart_url' => $validatedProfile ? '' : $this->getQrImage(), // opt now for JS-based render
				],
				'vars'    => [
					'ga_secret' => $validatedProfile ? $this->getSecret() : $this->resetSecret(),
				],
				'strings' => [
					'enter_auth_app_code'   => __( 'Enter 6-digit Code from App', 'wp-simple-firewall' ),
					'description_otp_code'  => __( 'Provide the current code generated by your Google Authenticator app.', 'wp-simple-firewall' ),
					'description_chart_url' => __( 'Use your Google Authenticator app to scan this QR code and enter the 6-digit one time password.', 'wp-simple-firewall' ),
					'description_ga_secret' => __( 'If you have a problem with scanning the QR code enter the long code manually into the app.', 'wp-simple-firewall' ),
					'desc_remove'           => __( 'Click to immediately remove Google Authenticator login authentication.', 'wp-simple-firewall' ),
					'label_check_to_remove' => sprintf( __( 'Remove %s', 'wp-simple-firewall' ), __( 'Google Authenticator', 'wp-simple-firewall' ) ),
					'label_enter_code'      => __( 'Google Authenticator Code', 'wp-simple-firewall' ),
					'label_ga_secret'       => __( 'Manual Code', 'wp-simple-firewall' ),
					'label_scan_qr_code'    => __( 'Scan This QR Code', 'wp-simple-firewall' ),
					'title'                 => __( 'Google Authenticator', 'wp-simple-firewall' ),
					'cant_add_other_user'   => sprintf( __( "Sorry, %s may not be added to another user's account.", 'wp-simple-firewall' ), 'Google Authenticator' ),
					'cant_remove_admins'    => sprintf( __( "Sorry, %s may only be removed from another user's account by a Security Administrator.", 'wp-simple-firewall' ), __( 'Google Authenticator', 'wp-simple-firewall' ) ),
					'provided_by'           => sprintf( __( 'Provided by %s', 'wp-simple-firewall' ), $con->getHumanName() ),
					'remove_more_info'      => __( 'Understand how to remove Google Authenticator', 'wp-simple-firewall' ),
					'remove_google_auth'    => __( 'Remove Google Authenticator', 'wp-simple-firewall' )
				],
			]
		);
	}

	private function getQrUrl() :string {
		$sec = $this->getGaSecret();
		return URL::Build( sprintf( 'otpauth://totp/%s', urlencode( $sec->getIssuer().':'.$sec->getAccountName() ) ), [
			'secret' => $sec->getSecretKey(),
			'issuer' => $sec->getIssuer(),
			'label'  => $sec->getLabel(),
		] );
	}

	public function removeGA() :StdResponse {
		$this->setProfileValidated( false )
			 ->resetSecret();

		$r = new StdResponse();
		$r->success = true;
		$r->msg_text = __( 'Google Authenticator was successfully removed from the account.', 'wp-simple-firewall' );
		return $r;
	}

	public function activateGA( string $otp ) :StdResponse {
		$r = new StdResponse();
		$r->success = $this->processOtp( $otp );
		if ( $r->success ) {
			$this->setProfileValidated( true );
			$r->msg_text = sprintf(
				__( '%s was successfully added to your account.', 'wp-simple-firewall' ),
				__( 'Google Authenticator', 'wp-simple-firewall' )
			);
		}
		else {
			$this->resetSecret();
			$r->error_text = __( 'One Time Password (OTP) was not valid.', 'wp-simple-firewall' )
							 .' '.__( 'Please try again.', 'wp-simple-firewall' );
		}
		return $r;
	}

	public function getFormField() :array {
		return [
			'slug'        => static::ProviderSlug(),
			'name'        => $this->getLoginIntentFormParameter(),
			'type'        => 'text',
			'value'       => '',
			'placeholder' => __( '123456', 'wp-simple-firewall' ),
			'text'        => __( 'Authenticator OTP', 'wp-simple-firewall' ),
			'description' => __( 'Enter 6-digit code from your authenticator app', 'wp-simple-firewall' ),
			'help_link'   => 'https://shsec.io/wpsf42',
			'extras'      => [
				'onkeyup' => "this.value=this.value.replace(/[^\d]/g,'')"
			]
		];
	}

	protected function processOtp( string $otp ) :bool {
		$valid = false;
		try {
			$valid = \preg_match( '#^\d{6}$#', $otp )
					 && ( new GoogleAuthenticator() )->authenticate( $this->getSecret(), $otp );
		}
		catch ( \Exception|\Psr\Cache\CacheException $e ) {
		}
		return $valid;
	}

	/**
	 * @return string
	 */
	protected function genNewSecret() {
		try {
			return $this->getGaSecret()->getSecretKey();
		}
		catch ( \InvalidArgumentException $e ) {
			return '';
		}
	}

	private function getGaSecret() :Secret {
		if ( !isset( $this->workingSecret ) ) {
			$this->workingSecret = ( new SecretFactory() )->create(
				preg_replace( '#[^\da-z]#i', '', Services::WpGeneral()->getSiteName() ),
				sanitize_user( $this->getUser()->user_login )
			);
		}
		return $this->workingSecret;
	}

	protected function getSecret() {
		$secret = parent::getSecret();
		return empty( $secret ) ? $this->resetSecret() : $secret;
	}

	protected function hasValidSecret() :bool {
		$secret = $this->getSecret();
		return \is_string( $secret ) && \strlen( $secret ) === 16;
	}

	public function isProviderEnabled() :bool {
		return $this->opts()->isEnabledGoogleAuthenticator();
	}

	public function getProviderName() :string {
		return 'Google Authenticator';
	}
}