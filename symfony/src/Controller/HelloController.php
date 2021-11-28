<?php

namespace App\Controller;

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

class HelloController extends AbstractController
{
    /**
     * @Route("/hello", name="hello")
     */
    public function index(Request $request, LoggerInterface $logger)
    {
        $person = new Person();
        $person->setName('Taro')
            ->setAge(36)
            ->setEmail('taro@gmail.com');

        $form = $this->createFormBuilder($person)
            ->add('name', TextType::class)
            ->add('age', IntegerType::class)
            ->add('email', EmailType::class)
            ->add('save', SubmitType::class, ['label' => 'Click'])
            ->getForm();

        if($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            $obj = $form->getData();
            $msg = 'Name:'. $obj->getName(). '<br>'
                . 'Age:'. $obj->getAge(). '<br>'
                . 'Email:'. $obj->getEmail();
        }else{
            $msg = 'お名前どうぞ';
        }
        return $this->render('hello/index.html.twig', [
            'title' => 'Hello',
            'message' => $msg,
            'form' => $form->createView()
        ]);
    }
}

//データクラス
class Person
{
    protected $name;
    protected $age;
    protected $email;
    protected $data = "";

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function setAge($age)
    {
        $this->age = $age;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getSession()
    {
        return $this->session;
    }

    public function setSession($session)
    {
        $this->session = $session;
        return $this;
    }

}

