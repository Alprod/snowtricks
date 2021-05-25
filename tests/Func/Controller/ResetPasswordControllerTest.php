<?php

namespace App\Tests\Func\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ResetPasswordControllerTest extends WebTestCase
{
	private const EMPTY_VALUE_EMAIL = '';
	private const MESSAGE_VALUE_EMAIL_EMPTY = 'Veuillez entrer votre email';

	private $client;

	public function setUp() : void
	{
		$this->client = static::createClient();
	}


	public function testForgotPasswordUriIfExist(): void
    {
        $this->client->request('GET', '/reset-password/forgot-password');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testIsValueEmailIsEmpty()
    {
    	$crawler = $this->client->request('POST', '/reset-password/forgot-password');
    	$form = $crawler->selectButton('Envoyer un e-mail de réinitialisation du mot de passe');
    	$value  = $form->form([
    		'reset_password_request_form' => [
    			'email'=> self::EMPTY_VALUE_EMAIL
		        ]
            ]);
    	$this->client->submit($value);
    	$this->client->reload();
    	self::assertSelectorTextContains('li.text-danger', self::MESSAGE_VALUE_EMAIL_EMPTY);
    }

	public function testRestPasswordUriIfExist()
	{
		$csrfToken = $this->client->getContainer()->get('security.csrf.token_manager')->getToken('change_password_form__token');
		$this->client->request('GET', "/reset-password/reset/$csrfToken");
		self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

}
