<?php

namespace App\Controller;

use App\Entity\Persona;
use App\Entity\Usuario;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $persona = new Persona();
        $user = new Usuario();
        $form = $this->createForm(RegistrationFormType::class, $persona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $persona->setNombres($form->get('nombres')->getData());
            $persona->setApellidos($form->get('apellidos')->getData());
            $persona->setEdad($form->get('edad')->getData());
            $persona->setSexo($form->get('sexo')->getData());

            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $user->setEmail($form->get('email')->getData());
            $user->setPersona($persona);
            $entityManager->persist($user);

            try {
                $entityManager->flush();
            } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                $this->addFlash('error', 'Ya existe un usuario con ese email');
                return $this->redirectToRoute('app_register');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Ocurrió un error al guardar los datos');
                return $this->redirectToRoute('app_register');
            }

            // Log in the user
            $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
            $this->container->get('security.token_storage')->setToken($token);

            // Send the email
            $this->sendRegistrationEmail($mailer, $persona, $user);

            // Add flash message
            $this->addFlash('success', 'Usuario registrado correctamente, por favor inicie sesión');

            // Redirect to home
            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    private function sendRegistrationEmail(MailerInterface $mailer, Persona $persona, Usuario $user): void
    {
        $email = (new TemplatedEmail())
            ->from('kenbok2018@gmail.com')
            ->to('kenbok2014@hotmail.com')
            ->subject('Gracias por registrarte!')
            ->htmlTemplate('emails/test.html.twig')
            ->locale('es')
            ->context([
                'expiration_date' => new \DateTime('+7 days'),
                'persona' => [
                    'nombres' => $persona->getNombres(),
                    'apellidos' => $persona->getApellidos(),
                    'email' => $user->getEmail()
                ]
            ]);

        $mailer->send($email);
    }
}
