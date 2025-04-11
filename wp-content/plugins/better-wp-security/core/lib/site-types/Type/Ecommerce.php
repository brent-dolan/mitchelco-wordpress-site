<?php

namespace iThemesSecurity\Lib\Site_Types\Type;

use iThemesSecurity\Exception\Invalid_Argument_Exception;
use iThemesSecurity\Lib\Site_Types\Question\Client_Question_Pack;
use iThemesSecurity\Lib\Site_Types\Question;
use iThemesSecurity\Lib\Site_Types\Question\Global_Question_Pack;
use iThemesSecurity\Lib\Site_Types\Question\Login_Security_Question_Pack;
use iThemesSecurity\Lib\Site_Types\Question\Site_Scan_Question;
use iThemesSecurity\Lib\Site_Types\Templating_Site_Type;

final class Ecommerce implements Templating_Site_Type {
	const TEMPLATES = [
		Question::SCAN_SITE,
		Question::SELECT_END_USERS,
		Question::END_USERS_TWO_FACTOR,
		Question::END_USERS_PASSWORD_POLICY,
	];

	public function get_slug(): string {
		return self::ECOMMERCE;
	}

	public function get_title(): string {
		return __( 'eCommerce', 'better-wp-security' );
	}

	public function get_description(): string {
		return __( 'A website to sell products or services.', 'better-wp-security' );
	}

	public function get_icon(): string {
		return 'money';
	}

	public function get_questions(): array {
		return array_merge(
			[ new Site_Scan_Question( $this ) ],
			( new Login_Security_Question_Pack() )->get_questions(),
			( new Client_Question_Pack() )->get_questions(),
			( new Global_Question_Pack() )->get_questions(),
		);
	}

	public function is_supported_question( string $question_id ): bool {
		return in_array( $question_id, self::TEMPLATES, true );
	}

	public function make_prompt( string $question_id ): string {
		switch ( $question_id ) {
			case Question::SCAN_SITE:
				return __( 'Before we configure Solid Security, let’s scan your store for vulnerabilities…', 'better-wp-security' );
			case Question::SELECT_END_USERS:
				return __( 'Select your customers', 'better-wp-security' );
			case Question::END_USERS_TWO_FACTOR:
				return __( 'Do you want to secure your customer accounts with two-factor authentication?', 'better-wp-security' );
			case Question::END_USERS_PASSWORD_POLICY:
				return __( 'Do you want to secure your customer accounts with a password policy?', 'better-wp-security' );
			default:
				throw new Invalid_Argument_Exception( sprintf( 'The eCommerce site type does not support the %s question.', $question_id ) );
		}
	}

	public function make_description( string $question_id ): string {
		switch ( $question_id ) {
			case Question::SELECT_END_USERS:
			case Question::END_USERS_TWO_FACTOR:
			case Question::END_USERS_PASSWORD_POLICY:
				return '';
			default:
				throw new Invalid_Argument_Exception( sprintf( 'The eCommerce site type does not support the %s question.', $question_id ) );
		}
	}
}
