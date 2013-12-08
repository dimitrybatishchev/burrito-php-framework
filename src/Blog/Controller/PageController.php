<?php
namespace Blog\Controller;

use Blog\Form\BlogPostForm;
use Blog\Form\PostForm;
use Burrito\Framework\Form\FormFactory;
use Burrito\Framework\Http\Request;
use Burrito\Framework\EventDispatcher\EventDispatcher as EventDispatcher;
use Burrito\Framework\Http\Response;
use Burrito\Framework\Model\PdoAdapter;

use Blog\Entity\Post;
use Burrito\Framework\Model\EntityManager;

class PageController extends \Burrito\Framework\Controller {

    function mainPageAction(){
        $context = array();

        //$posts = Post::all();
        //$context['posts'] = $posts;

        $context['form'] = new BlogPostForm();

        $response = new Response();
        $response->addHeader('Content-Type', 'text/html');
        $response->setBody($this->view->render('main', $context));
        return $response;
    }

    function listAction(){
        $schema = include(ROOT.'\app\config\schema.php');
        $entityManager = new EntityManager(new \PDO("mysql:dbname=framework", "admin", "admin"), $schema);

        $posts = $entityManager->findAll('\\Blog\\Entity\\Post');
        foreach($posts as $post){
            //var_dump($post);
            foreach($post->getComments() as $comment){
                //var_dump($comment);
            }
        }

        $context['posts'] = $posts;

        $response = new Response();
        $response->setBody($this->view->render('list', $context));
        return $response;
    }

    function detailAction($id){
        $schema = include(ROOT.'\app\config\schema.php');
        $entityManager = new EntityManager(new \PDO("mysql:dbname=framework", "admin", "admin"), $schema);

        $post = $entityManager->findOne('\\Blog\\Entity\\Post', array('id' => $id));
        $context['post'] = $post;

        $response = new Response();
        $response->addHeader('Content-Type', 'text/html');
        $response->setBody($this->view->render('detail', $context));
        return $response;
    }

    function createAction(){

        $container = $this->getContainer();
        $request = $container->get('request');

        $postForm = new PostForm();
        $postForm->bindRequest($request);

        if ($request->getMethod() == 'POST'){
            if($postForm->isValid()){
                $data = $postForm->getValues();

                $post = new Post();
                $post->setTitle($data['title']);
                $post->setContent($data['content']);

                $entityManager = $container->get('entityManager');
                $entityManager->save($post);

                $this->redirect('list');
            }
        }

        $context = array('form' => $postForm,);

        $response = new Response();
        $response->addHeader('Content-Type', 'text/html');
        $response->setBody($this->view->render('create', $context));
        return $response;
    }

    function editAction(){
        $response = new Response();
        $response->addHeader('Content-Type', 'text/html');
        $response->setBody($this->view->render('edit'));
        return $response;
    }

    function aboutAction(){

        /**

        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addListener('test', function(){
            print 'ok';
        });
        $eventDispatcher->addListener('test', function(){
            print 'ok';
        });
        $eventDispatcher->dispatch('test');

         *

        $postMapper->insert(
            new Post(
                "Welcome to SitePoint",
                "To become yourself a true PHP master, you must first master PHP."));

        $postMapper->insert(
            new Post(
                "Welcome to SitePoint (Reprise)",
                "To become yourself a PHP Master, you must first master... Wait! Did I post that already?"));

        $user = new User("Everchanging Joe", "joe@example.com");
        $userMapper->insert($user);

        // Joe's comments for the first post (post ID = 1, user ID = 1)
        $commentMapper->insert(
            new Comment(
                "I just love this post! Looking forward to seeing more of this stuff.",
                $user),
            1, $user->id);

        $commentMapper->insert(
            new Comment(
                "I just changed my mind and dislike this post! Hope not seeing more of this stuff.",
                $user),
            1, $user->id);

        // Joe's comment for the second post (post ID = 2, user ID = 1)
        $commentMapper->insert(
            new Comment(
                "Not quite sure if I like this post or not, so I cannot say anything for now.",
                $user),
            2, $user->id);

        **/

        /**
        $adapter = new PdoAdapter("mysql:dbname=framework", "admin",
            "admin");

        $userMapper = new UserMapper($adapter);
        $commentMapper = new CommentMapper($adapter, $userMapper);
        $postMapper = new PostMapper($adapter, $commentMapper);

        $posts = $postMapper->findAll();
         **/


        /**
        $posts = Post::objects()->all();
         *
         */

        $schema = include(ROOT.'\app\config\schema.php');
        $entityManager = new EntityManager(new \PDO("mysql:dbname=framework", "admin", "admin"), $schema);

        $posts = $entityManager->findAll('\\Blog\\Entity\\Post');
        foreach($posts as $post){
            //var_dump($post);
            foreach($post->getComments() as $comment){
                //var_dump($comment);
            }
        }

        $context['posts'] = $posts;


        $response = new Response();
        $response->setBody($this->view->render('page', $context));
        return $response;
    }
}