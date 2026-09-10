<?php

/**
 * Security controller.
 */

namespace App\Controller;

use App\Form\Type\ChangePasswordFormType;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Security controller.
 */
class SecurityController extends AbstractController
{
    /**
     * SecurityController constructor.
     *
     * @param UserServiceInterface $userService User service instance
     */
    public function __construct(private readonly UserServiceInterface $userService)
    {
    }

    /**
     * Login action.
     *
     * @param AuthenticationUtils $authenticationUtils Authentication utilities
     *
     * @return Response HTTP response
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser() instanceof UserInterface) {
            return $this->redirectToRoute('url_index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * Logout action.
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * Change password action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/profile/change_password', name: 'app_change_password')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function changePassword(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();

            $this->userService->changePassword($user, $plainPassword);

            $this->addFlash('success', 'flash.password_changed');

            return $this->redirectToRoute('url_index');
        }

        return $this->render('security/change_password.html.twig', [
            'changePasswordForm' => $form->createView(),
        ]);
    }
}
