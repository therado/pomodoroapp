<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\DiscordApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DiscordController extends AbstractController
{
    public function __construct(private readonly DiscordApiService $discordApiService)
    {
    }
    
    #[Route('/discord/connect', name: 'app_discord_connect')]
    public function connect(Request $request): Response
    {
        $token = $request->request->get('token');

        if($this->isCsrfTokenValid('discord-auth', $token)){
            $request->getSession()->set('discord-auth', true);
            $scope = ['identify', 'email'];
            return $this->redirect($this->discordApiService->getAuthorizationUrl($scope));
        }
        return $this->redirectToRoute('app_home');
    }

    #[Route('/discord/auth', name: 'app_discord_auth')]
    public function auth(): Response
    {
        return $this->redirectToRoute('app_home');
    }

    #[Route('/discord/check', name: 'app_discord_check')]
    public function check(Request $request, EntityManagerInterface $em, UserRepository $userRepository)
    {
        $accessToken = $request->get('access_token');
        if (!$accessToken) {
            return $this->render('discord/check.html.twig');
        }
    
        $discordUser = $this->discordApiService->fetchUser($accessToken);
        $user = $userRepository->findOneBy(['discordId' => $discordUser->id]);
    
        if ($user) {
            if ($user->getAccessToken() !== $accessToken || $user->getAvatar() !== $discordUser->avatar) {
                $user->setAccessToken($accessToken);
                $user->setAvatar($discordUser->avatar);
                $em->persist($user);
                $em->flush();
            }
    
            return $this->redirectToRoute('app_discord_auth', [
                'accessToken' => $accessToken
            ]);
        }
    
        $user = new User();
    
        $user->setAccessToken($accessToken);
        $user->setUsername($discordUser->username);
        $user->setEmail($discordUser->email);
        $user->setAvatar($discordUser->avatar);
        $user->setDiscordId($discordUser->id);
    
        $em->persist($user);
        $em->flush();
    
        return $this->redirectToRoute('app_discord_auth', [
            'accessToken' => $accessToken
        ]);
    }
}
