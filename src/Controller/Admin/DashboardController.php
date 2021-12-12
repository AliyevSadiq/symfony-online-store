<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin',name: 'admin_')]
class DashboardController extends AbstractController
{
    #[Route('/dashboard',name: 'dashboard_show')]
    public function dashboard(): Response
    {
     return $this->render('admin/page/dashboard.html.twig');
    }
}