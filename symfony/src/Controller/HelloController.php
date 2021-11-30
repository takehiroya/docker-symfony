<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use Symfony\Componet\Security\Core\Encoder\UserPasswordEncoderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HelloController extends AbstractController
{
    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request, ValidatorInterface $validator)
    {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if($request->getMethod() == 'POST'){
            $person = $form->getData();
            $errors = $validator->validate($person);
            if (count($errors) == 0){
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($person);
                $manager->flush();
                return $this->redirect('/hello');
            } else {
                return $this->render('hello/create.html.twig', [
                    'title' => 'Hello',
                    'message' => 'Error',
                    'form' => $form->createView(),
                ]);
            }
        } else {
            return $this->render('hello/create.html.twig', [
                'title' => 'Hello',
                'message' => 'Create Entity',
                'form' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/update/{id}", name="update")
     */
    public function update(Request $request, Person $person)
    {
        $form = $this->createFormBuilder($person)
            ->add('name', TextType::class)
            ->add('email', TextType::class)
            ->add('age', IntegerType::class)
            ->add('save', SubmitType::class, array('label' => 'Click'))
            ->getForm();
        if($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            $person = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();
            return $this->redirect('/hello');
        }else{
            return $this->render('hello/create.html.twig', [
                'title' => 'Hello',
                'message' => 'update Entity id='. $person->getId(),
                'form' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Request $request, Person $person)
    {
        $form = $this->createFormBuilder($person)
            ->add('name', TextType::class)
            ->add('email', TextType::class)
            ->add('age', IntegerType::class)
            ->add('save', SubmitType::class, array('label' => 'Click'))
            ->getForm();
        if($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            $person = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($person);
            $manager->flush();
            return $this->redirect('/hello');
        }else{
            return $this->render('hello/create.html.twig', [
                'title' => 'Hello',
                'message' => 'update Entity id='. $person->getId(),
                'form' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/hello", name="hello")
     */
    public function index(Request $request, LoggerInterface $logger)
    {
        $repository = $this->getDoctrine()
            ->getRepository(Person::class);
            $data = $repository->findall();
        return $this->render('hello/index.html.twig', [
            'title' => 'Hello',
            'data' => $data,
        ]);
    }
    /**
     * @Route("/find", name="find")
     */
    public function find(Request $request)
    {
        $formobj = new FindForm();
        $form = $this->createFormBuilder($formobj)
            ->add('find', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Click'))
            ->getForm();
        $repository = $this->getDoctrine()->getRepository(Person::class);

        if($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            $findstr = $form->getData()->getFind();
            $result = $repository->findByNameOrEmail($findstr);
        } else {
            $result = $repository->findAllWithSort();
        }

        return $this->render('hello/find.html.twig', [
            'title' => 'Hello',
            'form' => $form->createView(),
            'data' => $result,
        ]);
    }
}

class findForm
{
    private $find;
    public function getFind()
    {
        return $this->find;
    }
    public function setFind($find)
    {
        $this->find = $find;
    }
}