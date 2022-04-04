<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\Mapping\Cache;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends AbstractController
{

    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function renderMenuList()
    {
        $categories = $this->categoryRepository->findAll();

        return $this->render('category/_menu.html.twig', [
            'categories' => $categories
        ]);
    }
    /**
     * @Route("/admin/category/create", name="category_create")
     */
    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $category = new Category;
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug(strtolower($slugger->slug($category->getName())));
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }

        $formView = $form->createView();
        return $this->render(
            'category/create.html.twig',
            [
                'formView' => $formView
            ]
        );
    }


    /**
     * @Route("/admin/category/{id}/edit", name="category_edit")
    
     */


    public function edit($id, CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $em, SluggerInterface $slugger, Security $security)
    {   //     * @IsGranted("ROLE_ADMIN", message="sorry") OR  * @IsGranted("CAN_EDIT",subject="id", message="in annotation")

        $category = $categoryRepository->find($id);

        if (!$category) {
            throw new NotFoundHttpException("Cant find the category");
        }

        // $security->isGranted("CAN_EDIT", $category); OR BELOW METHOD OR WITH isGranted in annotaion
        //$this->denyAccessUnlessGranted("CAN_EDIT", $category, "Ohh no u dont have access to this category ");

        // $user = $this->getUser();

        // if (!$user) {
        //     return $this->redirectToRoute("security_login");
        // }

        // if ($user !== $category->getOwner()) {
        //     throw new AccessDeniedHttpException("U r not the owner of this category");
        // }


        // $this->denyAccessUnlessGranted("ROLE_ADMIN", null, "Sorry you dont have right to access this page");

        // with out the helper method of abstract controller
        // $user = $this->getUser();
        // if ($user === null) {
        //     return $this->redirectToRoute('security_login');
        // }
        // // if (!in_array("ROLE_ADMIN", $user->getRoles())) {
        // //     throw new AccessDeniedHttpException("You dont have access to this page sorry");
        // // }
        // if ($this->isGranted("ROLE_ADMIN") === false) {
        //     throw new AccessDeniedHttpException("You dont have access to this page sorry");
        // }

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug(strtolower($slugger->slug($category->getName())));
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        $formView = $form->createView();

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'formView' => $formView
        ]);
    }
}
