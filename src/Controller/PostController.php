<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();
        //dd($posts);

        return $this->render('post/index.html.twig', [
            'bg_image' => 'home-bg.jpg',
            'posts' => $posts,
        ]);
    }

    /**
     * ---Route("/post/{id}", name="post_view", methods={"GET"}, requirements={"id"="\d+"})
     * @Route("/post/{slug}", name="post_view", methods={"GET"})
     */
    public function view(Post $post): Response
    {
        //dd($post);
        return $this->render('post/view.html.twig', [
            'post' => $post,
            'bg_image' => $post->getImage()
        ]);
    }

    /**
     * @Route("/test", name="test")
     */
    public function test(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findLastPosts();
        dd($posts);
    }

}
