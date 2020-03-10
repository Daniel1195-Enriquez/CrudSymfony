<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PostController extends Controller
{
    /**
     * @Route("/post", name="view_posts_route")
     */
    public function showAllPostsAction(Request $request)
    {
        // replace this example code with whatever you need
        $posts = $this->getDoctrine()->getRepository('AppBundle:Post')->findAll();
        return $this->render('pages/index.html.twig',['posts'=>$posts]);
    }

     /**
     * @Route("/create", name="create_post_route")
     */
    public function createPostsAction(Request $request)
    {
        $post = new Post;
        $form = $this->createFormBuilder($post)
        ->add('nombre',TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('apePaterno',TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('apeMaterno',TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('edad', IntegerType::class, array('attr' => array('class' => 'form-control')))
        ->add('save', SubmitType::class, array('label' => 'Crear Post', 'attr' => array('class' => 'btn 
        btn-primary', 'style' => 'margin-top:10px')))
        ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $nombre = $form['nombre']->getData();
            $apePaterno = $form['apePaterno']->getData();
            $apeMaterno = $form['apeMaterno']->getData();
            $edad = $form['edad']->getData();

            $post->setNombre($nombre);
            $post->setApePaterno($apePaterno);
            $post->setApeMaterno($apeMaterno);
            $post->setEdad($edad);

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            $this->addFlash('message', '¡Post creado con éxito!');
            return $this->redirectToRoute('view_posts_route');
        }
        return $this->render('pages/create.html.twig',['form'=> $form->createView()]);
    }
    
    /**
     * @Route("/view/{id}", name="view_post_route")
     */
    public function viewPostsAction($id)
    {
        $post = $this->getDoctrine()->getRepository('AppBundle:Post')->find($id);
        return $this->render('pages/view.html.twig',['post' => $post]);
    }


     /**
     * @Route("/edit/{id}", name="edit_posts_route")
     */
    public function editPostsAction(Request $request,$id)
    {
        $post = $this->getDoctrine()->getRepository('AppBundle:Post')->find($id);
        $post->setNombre($post->getNombre());
        $post->setApePaterno($post->getApePaterno());
        $post->setApeMaterno($post->getApeMaterno());
        $post->setEdad($post->getEdad());
        $form = $this->createFormBuilder($post);
        $form = $this->createFormBuilder($post)
            ->add('nombre',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('apePaterno',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('apeMaterno',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('edad', IntegerType::class, array('attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array('label' => 'Guardar cambios del Post', 'attr' => array('class' => 'btn 
            btn-primary', 'style' => 'margin-top:10px')))
            ->getForm();
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $nombre = $form['nombre']->getData();
                $apePaterno = $form['apePaterno']->getData();
                $apeMaterno = $form['apeMaterno']->getData();
                $edad = $form['edad']->getData();
                $em = $this->getDoctrine()->getManager();
                $post = $em->getRepository('AppBundle:Post')->find($id);
                
                $post->setNombre($nombre);
                $post->setApePaterno($apePaterno);
                $post->setApeMaterno($apeMaterno);
                $post->setEdad($edad);

                $em->flush();
                $this->addFlash('message', '¡Post editado con éxito!');
                return $this->redirectToRoute('view_posts_route');
            }
        return $this->render('pages/edit.html.twig',['form'=> $form->createView()]);
        
    }
     
     /**
     * @Route("/delete/{id}", name="View_all_posts")
     */
    public function deletePostsAction($id)
    {
        $em = $this-> getDoctrine()->getManager();
        $post= $em->getRepository('AppBundle:Post')->find($id);
        $em->remove($post);
        $em->flush();
        $this->addFlash('message', 'Se elimino el registro');
        return $this->redirectToRoute('view_posts_route');
    }
}
