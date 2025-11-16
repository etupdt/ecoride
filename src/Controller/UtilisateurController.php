<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UtilisateurFormType;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class UtilisateurController extends AbstractController
{

    #[Route('/utilisateur/d/{id}', name: 'app_delete_utilisateur')]
    public function delete(int $id, Request $request, UserRepository $utilisateurRepository, EntityManagerInterface $entityManager): Response
    {

        $utilisateur = $utilisateurRepository->find($id);
        
        $entityManager->remove($utilisateur);
        $entityManager->flush();

        return $this->redirectToRoute('app_utilisateurs');

    }

    #[Route('/utilisateurs', name: 'app_utilisateurs', methods: ['GET'])]
    public function utilisateurs(Request $request, UserRepository $utilisateurRepository): Response
    {

        $utilisateurs = $utilisateurRepository->findAll();

        /** @var User $user */
        $user = $this->getUser();

        return $this->render('utilisateur/list.html.twig', [
            'controller_name' => 'UserController',
            'user' => $user,
            'utilisateurs' => $utilisateurs
        ]);

    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/utilisateur/u/{id}', name: 'app_update_utilisateur')]
    public function edit(int $id, Request $request, UserRepository $utilisateurRepository, EntityManagerInterface $entityManager): Response
    {

        $utilisateur = $utilisateurRepository->find($id);
        
        $form = $this->createForm(UtilisateurFormType::class, $utilisateur, [
            'suspendu' => in_array('ROLE_SUSPENDU', $utilisateur->getRoles())
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $suspendu = $form->get('suspendu')->getData();

            $roles = $utilisateur->getRoles();

            if ($suspendu) {
                $roles[] = 'ROLE_SUSPENDU';
            } else {
                $roles = array_filter($roles, function ($r) {
                    return $r !== "ROLE_SUSPENDU";
                });
            }

            $utilisateur->setRoles($roles);

            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateurs');

        }

        /** @var User $user */
        $user = $this->getUser();

        return $this->render('utilisateur/index.html.twig', [
            'controller_name' => 'UserController',
            'userForm' => $form,
            'user' => $user,
            'action' => 'Modifier',
        ]);

    }
    
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/utilisateur/c', name: 'app_create_utilisateur')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $utilisateur = new User();
        
        $form = $this->createForm(UtilisateurFormType::class, $utilisateur, [
            'suspendu' => false
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $utilisateur->setRoles(['ROLE_EMPLOYEE']);

            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateurs');

        }

        /** @var User $user */
        $user = $this->getUser();

        return $this->render('utilisateur/index.html.twig', [
            'controller_name' => 'UserController',
            'userForm' => $form,
            'user' => $user,
            'action' => 'Créer',
        ]);
        
    }

}
