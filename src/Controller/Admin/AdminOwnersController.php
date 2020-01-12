<?php


namespace App\Controller\Admin;

use App\Entity\Owner;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminOwnersController
 * @package App\Controller\Admin
 * @Security("is_granted('ROLE_SUPER_ADMIN')")
 */
class AdminOwnersController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * PlayersController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/admin/owners", methods={"GET"}, name="adminOwnersPage")
     *
     */
    public function adminOwnersPage()
    {
        /** @var Owner[] $owners */
        $owners = $this->em->getRepository(Owner::class)->findAll();
        return $this->render('admin/owners.html.twig', [
            'owners' => $owners,
        ]);
    }
}