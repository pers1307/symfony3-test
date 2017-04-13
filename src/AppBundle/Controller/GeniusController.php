<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.04.2017
 * Time: 9:48
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Genus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GeniusController extends Controller
{
    /**
     * @Route("/genus/new/")
     */
    public function newAction()
    {
        $genus = new Genus();

        $genus->setName('Oct');

        $em = $this->getDoctrine()->getManager();

        $em->persist($genus);
        $em->flush();

        return new Response('Genus created!');
    }

    /**
     * @Route("/genus/{genusName}/")
     */
    public function showAction($genusName)
    {
//        $templating = $this->container->get('templating');
//
//        $html = $templating->render('genus/show.html.twig', [
//            'name' => $genusName
//        ]);
//
//        return new Response($html);

        $funFact = 'Octopuses can change the color of their body in just *three-tenths* of a second!';

        $cache = $this->get('doctrine_cache.providers.my_markdown_cache');
        $key = md5($funFact);

        if ($cache->contains($key)) {

            $funFact = $cache->fetch($key);
        } else {

            $funFact = $this->container->get('markdown.parser')
                ->transform($funFact);

            $cache->save($key, $funFact);
        }

        return $this->render('genus/show.html.twig', [
            'name'    => $genusName,
            'funFact' => $funFact,
            'notes' => [
                0 => '1',
                1 => '2',
                2 => '3',
            ]
        ]);
    }

    /**
     * @Route("/genus/{genusName}/notes/", name="genus_show_notes")
     * @Method("GET")
     */
    public function getNotesAction()
    {
        $notes = [
            ['id' => 1, 'username' => 'AquaPelham', 'avatarUri' => '/images/leanna.jpeg', 'note' => 'Octopus asked me a riddle, outsmarted me', 'date' => 'Dec. 10, 2015'],
            ['id' => 2, 'username' => 'AquaWeaver', 'avatarUri' => '/images/ryan.jpeg', 'note' => 'I counted 8 legs... as they wrapped around me', 'date' => 'Dec. 1, 2015'],
            ['id' => 3, 'username' => 'AquaPelham', 'avatarUri' => '/images/leanna.jpeg', 'note' => 'Inked!', 'date' => 'Aug. 20, 2015'],
        ];
        $data = [
            'notes' => $notes
        ];

        return new JsonResponse($data);
    }
}