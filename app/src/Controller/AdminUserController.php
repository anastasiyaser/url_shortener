<?php

/**
 * Admin User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\ChangePasswordFormType;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AdminUserController.
 */
#[Route('/admin/user')]
#[IsGranted('ROLE_ADMIN')]
class AdminUserController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param UserServiceInterface $userService User service
     * @param TranslatorInterface  $translator  Translator
     */
    public function __construct(private readonly UserServiceInterface $userService, private readonly TranslatorInterface $translator)
    {
    }

    /**
     * Index action for listing users.
     *
     * @return Response HTTP response
     */
    #[Route(name: 'admin_user_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $this->userService->findAll(),
        ]);
    }

    /**
     * Change password for another user by admin.
     *
     * @param Request $request HTTP request
     * @param User    $user    User entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/change-password', name: 'admin_user_change_password', methods: ['GET', 'POST'])]
    public function changePassword(Request $request, User $user): Response
    {
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            $this->userService->changePassword($user, $plainPassword);

            $this->addFlash(
                'success',
                $this->translator->trans('flash.password_changed')
            );

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user/change_password.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
