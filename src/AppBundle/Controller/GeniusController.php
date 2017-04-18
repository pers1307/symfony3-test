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
        // создать базу с названием из конфига (перед этим должен быть создан конфиг)
        // php bin/console doctrine:database:create

        // Кинуть в бд обновления, которые произошли в entities
        // php bin/console doctrine:database:update [--dump-sql]
        // php bin/console doctrine:database:update --force

        // Удалить весь кэш
        // php bin/console cache:clear --env=prod

        // php bin/console doctrine:query:sql 'SELECT * FROM genus'

        //
        // php bin/console doctrine:schema:update [--dump-sql]

        // Не выполнять на продакшене, удаляет старые поля и вставляет их заново
        // php bin/console doctrine:schema:update --force

        // Создать миграцию, которую потом успешно можно редактировать
        // php bin/console doctrine:migrations:diff

        // Накатить миграции
        // php bin/console doctrine:migrations:migrate

        // Вгрузить в базу фикстуры
        // php bin/console doctrine:fixtures:load

        $genus = new Genus();

        $genus->setName('Oct');
        $genus->setSubFamily('Octopodinae');
        $genus->setSpeciesCount(56);

        $em = $this->getDoctrine()->getManager();

        $em->persist($genus);
        $em->flush();

        return new Response('<html><body>Genus created!</body></html>');
    }

    /**
     * @Route("/genus/")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

//        $genuses = $em->getRepository('AppBundle\Entity\Genus')
        $genuses = $em->getRepository('AppBundle:Genus')
            ->findAll();

//        dump($genuses); die;

        return $this->render('genus/list.html.twig', [
            'genuses' => $genuses
        ]);
    }

    /**
     * @Route("/genus/{genusName}/", name="genus_show")
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

        $em = $this->getDoctrine()->getManager();

        $genus = $em->getRepository('AppBundle:Genus')
            ->findOneBy(['name' => $genusName]);

        /*
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
        */

        if (is_null($genus)) {

            throw $this->createNotFoundException('No genus found');
        }

        return $this->render('genus/show.html.twig', [
            'genus' => $genus
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