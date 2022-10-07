<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\MediaBundle\Provider\Pool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    private $entityManagerInterface;
    private $mediaPool;

    public function __construct(EntityManagerInterface $entityManagerInterface, Pool $mediaPool)
    {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->mediaPool = $mediaPool;
    }

    #[Route('/api/getCategory/{slug}', name: 'getCategory', methods: ['GET'])]
    public function getCategory($slug): Response
    {
        // Get category repository
        $categoryRepository = $this->entityManagerInterface->getRepository(Category::class);
        // Get current category from repository
        $category = $categoryRepository->findOneBy(['slug' => $slug]);

        // Return project's data
        if ($category) {

            // Get projects of current category
            $docProjects = $category->getProjects();
            $projects = [];
            foreach($docProjects as $dp) {
                $currentProject = [];
                $currentProject['id'] = $dp->getId();
                $currentProject['title'] = $dp->getTitle();

                $picture = $dp->getPicture();
                $provider = $this->mediaPool->getProvider($picture->getProviderName());
                $publicUrl = $provider->generatePublicUrl($picture, 'default_small');
                $currentProject['picture'] = $publicUrl;

                $currentProject['content'] = $dp->getContent();
                $projects[] = $currentProject;
            }

            return $this->json([
                'id' => $category->getID(),
                'title' => $category->getTitle(),
                'slug' => $category->getSlug(),
                'projects' => $projects,
                'projects_count' => count($docProjects),
            ]);
        } else {
            return $this->json([]);
        }
    }

    #[Route('/api/getCategories', name: 'getCategories', methods: ['GET'])]
    public function getCategories(): Response
    {
        // Get category repository
        $categoryRepository = $this->entityManagerInterface->getRepository(Category::class);
        // Get all categories from repository
        $categories = $categoryRepository->findAll();

        $finalArrayOfCategories = [];

        foreach ($categories as $category) {
            $finalArrayOfCategories[] = [
                'id' => $category->getID(),
                'title' => $category->getTitle(),
                'slug' => $category->getSlug(),
                'projects_count' => count($category->getProjects()),
            ];
        }

        // Return project's data
        return $this->json($finalArrayOfCategories);
    }
}
