<?php

namespace App\Controller;

use App\Form\CsvFileType;
use App\Form\EmployeeType;
use App\Handler\EmployeeCreate;
use App\Handler\EmployeeList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    #[Route('/', name: 'index', methods: 'GET')]
    public function home(): Response
    {
        return $this->render('base.twig');
    }

    #[Route('/list', name: 'list')]
    public function list(Request $request, EmployeeList $handler): Response
    {
        $form = $this->createForm(EmployeeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->get('name')->getData();

            $employees = $handler->handle($name);

            return $this->render('list.twig', [
                'form' => $form->createView(),
                'employees' => $employees,
            ]);
        }

        $employees = $handler->handle();

        return $this->render('list.twig', [
            'form' => $form->createView(),
            'employees' => $employees,
        ]);
    }

    #[Route('/form', name: 'form')]
    public function form(Request $request, EmployeeCreate $handler): Response
    {
        $form = $this->createForm(CsvFileType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $csv */
            $csv = $form->get('csv')->getData();
            $handler->handle($csv);
        }

        return $this->render('form.twig', [
            'form' => $form->createView(),
        ]);
    }
}