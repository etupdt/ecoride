<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use App\Repository\UserRepository;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @see https://symfony.com/doc/current/security/custom_authenticator.html
 */
 class CustomAuthenticator extends AbstractAuthenticator 
{

    use TargetPathTrait;

    public function __construct(
        private UserRepository $userRepository,
         private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    // abstract protected function getLoginUrl(Request $request): string;
     function getLoginUrl(Request $request): string {
        return '/login';
     }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool
    {
        error_log('==========================> supports'.$request->getBaseUrl().$request->getPathInfo());
        //return true; //$request->isMethod('POST') && $this->getLoginUrl($request) === $request->getBaseUrl().$request->getPathInfo();
        return $request->isMethod('POST') && $this->getLoginUrl($request) === $request->getBaseUrl().$request->getPathInfo();
    }

    public function authenticate(Request $request): Passport
    {

        error_log('=================================> authenticate '.$request->request->get('_username', '').'x'.$request->request->get('_password', ''));

        // $badges[] = new CsrfTokenBadge('authenticate', $request->request->get('passw_csrf_token'));
        $badges = [];

        if ($request->request->get('remenber_me', '')) {
            $badges[] = new RememberMeBadge();
        }

        return new Passport(
            new UserBadge($request->request->get('_username', ''), fn (string $identifier) => $this->userRepository->findUserByEmailOrUsername($identifier)),
            new PasswordCredentials($request->request->get('_password', '')),
            $badges
        );

    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {

        error_log('===========================> auth success');

        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('accueil'));

    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {

        error_log('=====================================> auth failure');

        if ($request->hasSession()) {
            $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $exception);
        }

        $url = $this->getLoginUrl($request);

        return new RedirectResponse($url);

    }

    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {

        error_log('=====================================> start');
        $url = $this->getLoginUrl($request);

        return new RedirectResponse($url);

    }

    public function isInteractive(): bool
    {
        return true;
    }

}
