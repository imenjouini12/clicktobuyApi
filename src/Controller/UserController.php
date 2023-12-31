<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Usr\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    private $manager;
    private $user;
    private $userService;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $manager, UserRepository $user,UserServiceInterface $userService,UserPasswordHasherInterface $passwordHasher)
    {
         $this->manager = $manager;
         $this->user = $user;
         $this->userService = $userService;
         $this->passwordHasher = $passwordHasher ;

    }
    
    
    //Création d'un utilisateur
    /**
     * @Route("api/users/create", name="user_create", methods={"POST"})
     */
    public function create(Request $request): Response
    {
           $data = json_decode($request->getContent(), true);
           $email=$data['email'];
           $password=$data['password'];
           $name=$data['name'];
           $email_exist = $this->user->findOneByEmail($email);     
           // Validez les données JSON selon vos besoins
           if (!isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
               return new JsonResponse(['error' => 'Données JSON invalides'], Response::HTTP_BAD_REQUEST);
           }elseif($email_exist)
           {
              return new JsonResponse
              (
                  [
                    'status'=>false,
                    'message'=>'Cet email existe déjà, veuillez le changer'
                  ]
    
                  );
           }else{
            $user = new User();
            $user->setEmail($email);
            $user->setName($name);
            
            // Utilisez UserPasswordHasherInterface pour hacher le mot de passe
            $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
    
            // Utilisez le service UserService pour ajouter l'utilisateur
            $this->userService->addUser($user);
    
        
            return new JsonResponse(['message' => 'Utilisateur créé avec succès'], Response::HTTP_CREATED);

           }       
    }



    //Liste des utilisateurs
     /**
     * @Route("/api/Users", name="get_allusers", methods={"GET"})
     */
    public function getAllUsers(): Response
    {
        $users=$this->userService->getAllUsers();
        

        return $this->json($users,200);
    }

    //Modifier compte utilisateur
    /**
     * @Route("/api/users/update/{id}", name="user_update", methods={"PUT"})
     * @ParamConverter("user", class="App\Entity\User")
     */
    public function updateUser(Request $request,User $user): JsonResponse
    {
                if (!$user) {
                    return new JsonResponse(['error' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
                }
        $data = json_decode($request->getContent(), true);
        $email = isset($data['email']) ? $data['email'] : null;
        $password = isset($data['password']) ? $data['password'] : null;
        $name = isset($data['name']) ? $data['name'] : null; 
        if (!isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
            return new JsonResponse(['error' => 'Données JSON invalides'], Response::HTTP_BAD_REQUEST);
        }else{
            if ($email) {
                $user->setEmail($email);
            }
            
            if ($name) {
                $user->setName($name);
            }
                 // Vérifiez si un nouveau mot de passe a été fourni
            if ($password) {
                // Utilisez UserPasswordHasherInterface pour hacher le mot de passe
                $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
                $user->setPassword($hashedPassword);
            }
         // Utilisez le service UserService pour ajouter l'utilisateur
         $this->userService->updateUser($user);
     
         return new JsonResponse(['message' => 'Utilisateur modifier avec succès'], Response::HTTP_CREATED);

        }       





    }
     /**
     * @Route("/api/users/{id}", name="user_get", methods={"GET"})
     * @ParamConverter("user", class="App\Entity\User")
     */
    public function getOneUser(User $user): JsonResponse
    {
        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
        }else{
           $plainPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
           $oneUser=$this->userService->getUserById($user->getId());
           $userData = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            //'password' => $plainPassword,
        ];
        return $this->json($userData, 200);
        }


    }

    //Supprimer un utilisateur
    /**
    * @Route("/api/users/delete/{id}", name="user_delete", methods={"DELETE"})
    * @ParamConverter("user", class="App\Entity\User")
    */
    public function delete(User $user): JsonResponse
    {
        $this->userService->deleteUser($user);
            return new JsonResponse
            (
                [
                'status'=>true,
                'message'=>'utilisateur suprimer avec succèes'
                ]

            );

    }


        //just un test
    /**
    * @Route("/users/test", name="test", methods={"GET"})
    */
    public function test():JsonResponse
    {
     
            return new JsonResponse
            (
                [
                'status'=>true,
                'message'=>'c\'est un test'
                ]

            );

    }

}