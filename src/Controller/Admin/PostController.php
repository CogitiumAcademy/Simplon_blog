<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use App\Form\CategoryType;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{

    /**
     * @Route("/post/add", name="post_add")
     */
    public function addPost(Request $request): Response
    {
        $post = new Post();
        //dd($category);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Initialisation des valeurs par défaut
            // Le User connecté
            // Active à false
            $post->setUser($this->getUser());
            //dd($this->getUser());
            $post->setActive(false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('admin/post/add.html.twig', [
            'form' => $form->createView(),
            'bg_image' => 'home-bg.jpg'
        ]);
    }

    /**
     * @Route("/admin/post", name="admin_post_index")
     */
    public function indexPost(PostRepository $postRepository): Response
    {
        return $this->render('admin/post/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/post/activate/{id}", name="admin_post_activate", requirements={"id"="\d+"})
     */
    public function activatePost(Post $post): Response
    {
        //dd($post);
        $post->setActive( ($post->getActive()) ? false : true );
        
        /*
        if ($post->getActive() == true) {
            $post->setActive(false);
        } else {
            $post->setActive(true);
        }
        */

        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();
        return new Response("true");
    }

    /**
     * @Route("/admin/post/delete/{id}", name="admin_post_delete", requirements={"id"="\d+"})
     */
    public function deletePost(Post $post): Response
    {
        //dd($post);
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();
        $this->addFlash('success', 'Votre article a été supprimé avec succès !');
        return $this->redirectToRoute('admin_post_index');
    }

    /**
     * @Route("/admin/post/update/{id}", name="admin_post_update", requirements={"id"="\d+"})
     */
    public function updatePost(Post $post, Request $request): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            $this->addFlash('success', 'Votre article a été modifié avec succès !');
            return $this->redirectToRoute('admin_post_index');
        }

        return $this->render('admin/post/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
